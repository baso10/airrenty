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
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use app\components\BBNowExpression;

/**
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property int|null $is_super_admin
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_enabled_time
 * @property string|null $confirmation_token
 * @property string|null $confirmation_sent_time
 * @property string|null $confirmed_time
 * @property string|null $recovery_token
 * @property string|null $recovery_sent_time
 * @property string|null $blocked_time
 * @property string|null $registered_ip
 * @property int|null $require_password_change
 * @property string|null $password_last_change
 * @property int|null $password_expire_days
 * @property int|null $created_user_id
 * @property string $created_time
 * @property string|null $modified_time
 * @property string|null $updated_time
 * @property int $status
 */
class User extends BBActiveRecord implements IdentityInterface {

  const STATUS_WAITING_CONFIRMATION = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_DELETED = 5;

  public static function tableName() {
    return '{{%user}}';
  }

  public function rules() {
    return [
    ];
  }

  /**
   * @inheritdoc
   */
  public static function findIdentity($id) {
    return static::findOne($id);
  }

  /**
   * @inheritdoc
   */
  public static function findIdentityByAccessToken($token, $type = null) {
    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username) {
    return static::findOne(['username' => $username]);
  }

  /**
   * Finds user by password reset token
   *
   * @param string $token password reset token
   * @return static|null
   */
  public static function findByRecoveryToken($token) {
    return static::findOne([
                'recovery_token' => $token
    ]);
  }

  public static function findByConfirmationToken($token) {
    return static::findOne([
                'confirmation_token' => $token
    ]);
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->getPrimaryKey();
  }

  /**
   * @inheritdoc
   */
  public function getAuthKey() {
    return $this->auth_key;
  }

  /**
   * @inheritdoc
   */
  public function validateAuthKey($authKey) {
    return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return boolean if password provided is valid for current user
   */
  public function validatePassword($password) {
    return Yii::$app->security->validatePassword($password, $this->password_hash);
  }

  /**
   * Generates password hash from password and sets it to the model
   *
   * @param string $password
   */
  public function setPassword($password) {
    $this->password_hash = Yii::$app->security->generatePasswordHash($password);
  }

  /**
   * Generates "remember me" authentication key
   */
  public function generateAuthKey() {
    $this->auth_key = Yii::$app->security->generateRandomString();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken() {
    $this->recovery_token = Yii::$app->security->generateRandomString() . '_' . time();
    $this->recovery_sent_time = new BBNowExpression();
  }

  public static function isPasswordResetTokenValid($token) {
    if (empty($token)) {
      return false;
    }

    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire = Yii::$app->params['user_recover_token_valid_time'];
    return $timestamp + $expire >= time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken() {
    $this->recovery_token = null;
  }

  public function generateEmailVerificationToken() {
    $this->confirmation_token = Yii::$app->security->generateRandomString() . '_' . time();
    $this->confirmation_sent_time = new BBNowExpression();
  }

  public function removeConfirmationToken() {
    $this->confirmation_token = null;
    $this->confirmed_time = new BBNowExpression();
  }
}
