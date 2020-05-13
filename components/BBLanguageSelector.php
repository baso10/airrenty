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

use yii\base\Behavior;
use app\components\BBHelper;
use Yii;

/**
 *
 * @author baso10
 */
class BBLanguageSelector extends Behavior {

  public function setLanguage() {

    $countryGET = Yii::$app->request->get("country");
    if (!empty($countryGET)) {
      if ($countryGET == "CH") {
        Yii::$app->language = "de-CH";
      } else if ($countryGET == "SI") {
        Yii::$app->language = "sl";
      }
      // add a new cookie to the response to be sent
      Yii::$app->response->cookies->add(new \yii\web\Cookie([
          'name' => 'language',
          'value' => Yii::$app->language,
      ]));
      
      return;
    }
    
    //check cookies
    $cookies = Yii::$app->request->cookies;
    $language = $cookies->getValue('language');
    
    if(!empty($language)) {
      Yii::$app->language = $language;
      return;
    }

    //default
    Yii::$app->language = "de-CH";//default
    
    //set from IP
    $url = "http://www.geoplugin.net/php.gp?ip={IP}";
    $url = str_replace('{IP}', BBHelper::getIP(), $url);

    $response = file_get_contents($url, 'r');
    if ($response) {
      $data = unserialize($response);
      if (!empty($data['geoplugin_countryCode'])) {
        $countryCode = $data['geoplugin_countryCode'];
        if (!empty($countryCode) && in_array($countryCode, ["CH", "SI"])) {
          if ($countryCode == "CH") {
            Yii::$app->language = "de-CH";
          } else if ($countryCode == "SI") {
            Yii::$app->language = "sl";
          }

        }
      }
    }
  }

}
