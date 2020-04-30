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
        
        [['name', 'web_page', 'airport_id'], 'required'],
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
    if(isset($existingUser)) {
      if($existingUser->status == User::STATUS_WAITING_CONFIRMATION) {
        //allow it
        $existingUser->setPassword($this->password);
        if(empty($existingUser->confirmation_token)) {
          $existingUser->generateEmailVerificationToken();
        }
        
        return $existingUser->save() && $this->sendEmail($existingUser);
      }
    }
    
    if (!$this->validate()) {
      return null;
    }
    
    $saved = true;

    $user = new User();
    $user->username = $this->email;
    $user->email = $this->email;
    $user->setPassword($this->password);
    $user->generateAuthKey();
    $user->generateEmailVerificationToken();
    
    $saved &= $user->save();
    
    //save organisation
    $organisation = new Organisation();
    $organisation->name = $this->name;
    $organisation->description = $this->description;
    $organisation->web_page = $this->web_page;
    $organisation->airport_id = $this->airport_id;
    $organisation->created_user_id = $user->id;
    
    $saved &= $organisation->save();
    
    
    $saved &= $this->sendEmail($user);
    return $saved; 
  }

  /**
   * Sends confirmation email to user
   * @param User $user user model to with email should be send
   * @return bool whether the email was sent
   */
  protected function sendEmail($user) {
    try {
      Yii::$app
              ->mailer
              ->compose('emailRegistration', ['user' => $user])
              ->setFrom(Yii::$app->params['email_from'])
              ->setReplyTo(Yii::$app->params['email_replyTo'])
              ->setTo($this->email)
              ->setSubject(Yii::t("app", "Welcome"))
              ->send();
    } catch (\Exception $ex) {
      Yii::error($ex->getMessage());
    }

    return true;
  }

}
