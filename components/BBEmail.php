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

namespace app\components;

use Yii;

/**
 *
 * @author baso10
 */
class BBEmail {

  public static function sendRegistrationEmail($user) {
    try {
      $mail = Yii::$app
              ->mailer
              ->compose('emailRegistration', ['user' => $user])
              ->setFrom(Yii::$app->params['email_from'])
              ->setReplyTo(Yii::$app->params['email_replyTo'])
              ->setTo($user->email)
              ->setSubject(Yii::t("app", "Welcome"));
      if (Yii::$app->params["email_disabled"]) {
        Yii::error($mail->toString());
      } else {
        $mail->send();
      }
      return true;
    } catch (\Exception $ex) {
      Yii::error($ex->getMessage());
    }
    
    return false;
  }

  public static function sendResetPasswordEmail($user) {

    try {
      $mail = Yii::$app
              ->mailer
              ->compose('resetPassword', ['user' => $user])
              ->setFrom(Yii::$app->params['email_from'])
              ->setReplyTo(Yii::$app->params['email_replyTo'])
              ->setTo($user->email)
              ->setSubject(Yii::t("app", 'Password reset'));
      if (Yii::$app->params["email_disabled"]) {
        Yii::error($mail->toString());
      } else {
        $mail->send();
      }
      return true;
    } catch (\Exception $ex) {
      Yii::error($ex->getMessage());
    }

    return false;
  }

}
