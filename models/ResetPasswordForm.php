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
use yii\base\InvalidArgumentException;
use yii\base\Model;
use app\models\User;

class ResetPasswordForm extends Model {

  public $password;
  private $_user;

  /**
   * Creates a form model given a token.
   *
   * @param string $token
   * @param array $config name-value pairs that will be used to initialize the object properties
   * @throws InvalidArgumentException if token is empty or not valid
   */
  public function __construct($token, $config = []) {
    if (empty($token) || !is_string($token)) {
      throw new InvalidArgumentException('Password reset token cannot be blank.');
    }
    $this->_user = User::findByRecoveryToken($token);
    if (!$this->_user) {
      throw new InvalidArgumentException('Wrong password reset token.');
    }
    parent::__construct($config);
  }
  
  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
        ['password', 'required'],
        ['password', 'string', 'min' => 4],
    ];
  }
  
  public function attributeLabels() {
    return [
        'password' => Yii::t("app", "New password"),
    ];
  }

  /**
   * Resets password.
   *
   * @return bool if password was reset.
   */
  public function resetPassword() {
    $user = $this->_user;
    $user->setPassword($this->password);
    $user->removePasswordResetToken();

    $saved = $user->save(false);
    return $saved ? $user : false;
  }

}
