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
use NumberFormatter;


class BBAmount {

  public static function amountToString($amount, $currency, $options = []) {
    if ($currency == "BTC") {
      $btcAmountString = \bcdiv(doubleval($amount), 100000000, 8);

      return $btcAmountString . (" " . $currency);
    } else {
      $formatter = new NumberFormatter(Yii::$app->language, NumberFormatter::DECIMAL);
      $decimalPoint = $formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
      $decPlaces = 2; //get from currency
      $decFactor = pow(10, $decPlaces);

      if (isset($options["numberOfDecimals"])) {
        $decPlaces = $options["numberOfDecimals"];
      }

      /* convert amount into formatted string */
      $majorInt = (int) ($amount / $decFactor);
      $major = number_format($majorInt);

      $minorInt = $amount % $decFactor;
      if ($minorInt < 0) {
        $minorInt *= -1;
        if ($majorInt == 0) {
          $major = "-" . $major;
        }
      }
      $minor = "" . $minorInt;
      $fillPlaces = $decPlaces - strlen($minor);
      if ($fillPlaces > 0) {
        $minor = str_pad($minor, $fillPlaces + strlen($minor), '0', STR_PAD_LEFT);
      }
      if ($decPlaces == 0) {
        return $major . (" " . $currency);
      }
      
      return $major . $decimalPoint . $minor . (" " . $currency);
      
    }

    //else
    return $amount . (" " . $currency);
  }

}
