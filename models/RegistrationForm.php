<?php

/*
 * Copyright 2020 baso10.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Organisation;
use app\components\BBHelper;
use app\components\BBEmail;

/**
 *
 * @author baso10
 */
class RegistrationForm extends Model {

  public $email;
  public $password;
  public $name;
  public $description;
  public $web_page;
  public $airport_id;

  public function rules() {
    return [
        ['email', 'trim'],
        ['email', 'required'],
        ['email', 'email'],
        ['email', 'string', 'max' => 255],
        ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
        ['password', 'required'],
        ['password', 'string', 'min' => 4],
        [['name', 'web_page', 'airport_id', 'description'], 'required'],
        [['name', 'web_page'], 'string', 'max' => 1000],
        ['airport_id', 'integer'],
        [['description'], 'safe'],
    ];
  }

  public function attributeLabels() {
    return [
        'email' => Yii::t("app", "Email"),
        'password' => Yii::t("app", "Password"),
        'name' => Yii::t("app", "Name"),
        'description' => Yii::t("app", "Description"),
        'web_page' => Yii::t("app", "Website"),
        'airport_id' => Yii::t("app", "Airport"),
    ];
  }

  public function register() {
    //check resend email
    $existingUser = User::findByUsername($this->email);
    if (isset($existingUser)) {
      if ($existingUser->status == User::STATUS_WAITING_CONFIRMATION) {
        //allow it
        $existingUser->setPassword($this->password);
        if (empty($existingUser->confirmation_token)) {
          $existingUser->generateEmailVerificationToken();
        }

        return $existingUser->save() && BBEmail::sendRegistrationEmail($existingUser);
      }
    }

    if (!$this->validate()) {
      return null;
    }

    $saved = true;

    $transaction = User::getDb()->beginTransaction();
    try {

      $user = new User();
      $user->username = $this->email;
      $user->email = $this->email;
      $user->setPassword($this->password);
      $user->generateAuthKey();
      $user->status = User::STATUS_ACTIVE;

      if (Yii::$app->params["user_enable_email_confirmation"]) {
        $user->status = User::STATUS_WAITING_CONFIRMATION;
        $user->generateEmailVerificationToken();
      }

      $user->registered_ip = BBHelper::getIP();

      $saved = $saved && $user->save();

      if (!$saved) {
        Yii::error("User not saved. " . json_encode($user->getErrors()));
      }

      //save organisation
      $organisation = new Organisation();
      $organisation->name = $this->name;
      $organisation->description = $this->description;
      $organisation->web_page = $this->web_page;
      $organisation->airport_id = $this->airport_id;
      $organisation->created_user_id = $user->id;

      $saved = $saved && $organisation->save();

      if (!$saved) {
        Yii::error("Organisation not saved. " . json_encode($organisation->getErrors()));
      }

      if ($saved) {
        $transaction->commit();
      } else {
        $transaction->rollBack();
      }
    } catch (\Throwable $e) {
      $transaction->rollBack();
      Yii::error($e->getMessage() . $e->getTraceAsString());
      throw $e;
    }

    if (Yii::$app->params["user_enable_email_confirmation"]) {
      BBEmail::sendRegistrationEmail($user);
    }

    return $saved;
  }

}
