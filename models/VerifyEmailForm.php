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

use app\models\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Description of VerifyEmailForm
 *
 * @author baso10
 */
class VerifyEmailForm extends Model {

  /**
   * @var string
   */
  public $token;

  /**
   * @var User
   */
  private $_user;

  /**
   * Creates a form model with given token.
   *
   * @param string $token
   * @param array $config name-value pairs that will be used to initialize the object properties
   * @throws InvalidArgumentException if token is empty or not valid
   */
  public function __construct($token, array $config = []) {
    if (empty($token) || !is_string($token)) {
      throw new InvalidArgumentException('Verify email token cannot be blank.');
    }
    $this->_user = User::findByConfirmationToken($token);
    if (!$this->_user) {
      throw new InvalidArgumentException('Wrong verify email token.');
    }
    parent::__construct($config);
  }
  
  
  /**
     * Verify email
     *
     * @return User|null the saved model or null if saving fails
     */
    public function activateUser()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        return $user->save(false) ? $user : null;
    }

}
