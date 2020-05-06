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

use yii\base\Model;
use Yii;

class ChangePasswordForm extends Model {

  public $password;
  public $oldPassword;

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
        ['oldPassword', 'required'],
        ['password', 'required'],
        ['password', 'string', 'min' => 4],
    ];
  }

  public function attributeLabels() {
    return [
        'oldPassword' => Yii::t("app", "Old password"),
        'password' => Yii::t("app", "New password"),
    ];
  }
    
  /**
   * Resets password.
   *
   * @return bool if password was reset.
   */
  public function resetPassword() {
    $user = Yii::$app->user->getModel();
    
    if(!$user->validatePassword($this->oldPassword)) {
      $this->addError("oldPassword", "Wrong password");
      return false;
    }
    
    $user->setPassword($this->password);
    $user->removePasswordResetToken();

    $saved = $user->save(false);
    return $saved ? $user : false;
  }

}
