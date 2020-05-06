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
use app\components\BBEmail;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

  public $email;

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
        ['email', 'trim'],
        ['email', 'required'],
        ['email', 'email'],
        ['email', 'exist',
            'targetClass' => '\app\models\User',
            'filter' => ['status' => User::STATUS_ACTIVE],
            'message' => 'Error.'
        ],
    ];
  }
  
  public function attributeLabels() {
    return [
        'email' => Yii::t("app", "Email"),
    ];
  }

  /**
   * Sends an email with a link, for resetting the password.
   *
   * @return bool whether the email was send
   */
  public function sendEmail() {
    /* @var $user User */
    $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
    ]);

    if (!$user) {
      return false;
    }

    //if token exists, leave it as valid
    if (!User::isPasswordResetTokenValid($user->recovery_token)) {
      $user->generatePasswordResetToken();
      if (!$user->save()) {
        return false;
      }
    }

    return BBEmail::sendResetPasswordEmail($user);
  }

}
