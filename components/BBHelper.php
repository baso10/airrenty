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

class BBHelper {

  /**
   * @var boolean whether 'mbstring' PHP extension available. This static property introduced for
   * the better overall performance of the class functionality. Checking 'mbstring' availability
   * through static property with predefined status value is much faster than direct calling
   * of function_exists('...').
   * Intended for internal use only.
   * @since 1.1.13
   */
  private static $_mbstringAvailable;

  /**
   *
   * @param string $string
   * @return string lower case string
   */
  public static function toLower($string) {
    if (self::$_mbstringAvailable === null)
      self::$_mbstringAvailable = extension_loaded('mbstring');

    $result = self::$_mbstringAvailable ? mb_strtolower($string, Yii::$app->charset) : strtolower($string);

    $low = array("Č" => "č", "Ž" => "ž", "Š" => "š");
    $result = strtr($result, $low);

    return $result;
  }

  public static function limitWords($string, $wordLimit) {
    $words = explode(" ", $string);
    return count($words) > $wordLimit ? implode(" ", array_splice($words, 0, $wordLimit)) : $string;
  }

  
  public static function getIP()
  {
      // Get real visitor IP behind CloudFlare network
      if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
      }
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP))
      {
          $ip = $client;
      }
      elseif(filter_var($forward, FILTER_VALIDATE_IP))
      {
          $ip = $forward;
      }
      else
      {
          $ip = $remote;
      }

      return $ip;
  }

  public static function extractLink($string) {
    $rexProtocol = '(https?://)?';
    $rexDomain = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
    $rexPort = '(:[0-9]{1,5})?';
    $rexPath = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
    $rexQuery = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
    $rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';

    return preg_replace_callback("&\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$))&", function($match) {
      $completeUrl = $match[1] ? $match[0] : "http://{$match[0]}";
      return '<a href="' . htmlspecialchars_decode(CHtml::encode($completeUrl)) . '">' . $match[2] . $match[3] . $match[4] . $match[5] . '</a>';
    }, htmlspecialchars($string));
  }

  public static function getFormatedText($string) {
    $result = $string;
    $result = str_replace("\t", "<span class=\"text-tab\">\t</span>", $result);
    $result = nl2br($result);

    return $result;
  }

  /**
   * 
   * @param string $value
   * @return string
   */
  public static function searchReplace($value) {
    if ($value === null || $value === "" || $value === "null") {
      return $value;
    }
    $op = "";
    if (preg_match('/^(?:\s*(<>>|<><|<>|<=|>=|<|>|=))?(.*)$/', $value, $matches)) {
      $value = $matches[2];
      $op = $matches[1];
    }
    $value = strtr($value, array('*' => '%', '?' => '_', '%' => '\%', '_' => '\_', '\\' => '\\\\'));

    if ($op === '' || $op === '<>') {
      $value = $op . '%' . $value . '%';
    } else if ($op === '>') {
      $value = $value . '%';
    } else if ($op === '<') {
      $value = '%' . $value;
    } else if ($op === '<><') {
      $value = '<>%' . $value;
    } else if ($op === '<>>') {
      $value = '<>' . $value . '%';
    } else {
      $value = $op . $value;
    }

    return $value;
  }

  public static function stripInvisibleMenu(&$menuItems, $recursion = false) {
    foreach ($menuItems as $key => $meniItem) {
      if (isset($meniItem["items"])) {
        self::stripInvisibleMenu($meniItem["items"], true);
      }
      if (isset($meniItem["visible"]) && !$meniItem["visible"]) {
        unset($menuItems[$key]);
      }

      //remove also empty menu items, when you remove all children
      if (isset($meniItem["items"]) && empty($meniItem["items"])) {
        unset($menuItems[$key]);
      }
    }

    if (!$recursion) {
      $menuItems = array_values($menuItems);
    }
  }

  /**
   * Sanitizes a filename replacing whitespace with dashes
   *
   * Removes special characters that are illegal in filenames on certain
   * operating systems and special characters requiring special escaping
   * to manipulate at the command line. Replaces spaces and consecutive
   * dashes with a single dash. Trim period, dash and underscore from beginning
   * and end of filename.
   *
   * @since 2.1.0
   *
   * @param string $filename The filename to be sanitized
   * @return string The sanitized filename
   */
  public static function sanitize_file_name($filename) {
    $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
    $filename = str_replace($special_chars, '_', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    return $filename;
  }

  public static function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
      $url = "http://" . $url;
    }
    return $url;
  }

  public static function getCommandParameters($paramArray) {
    $result = "";
    foreach ($paramArray as $key => $value) {
      $result .= " --$key=$value";
    }

    return $result;
  }
  public static function replaceDecimalSymbol($value, $forSave = false) {

    $decimalSymbol = Yii::$app->locale->getNumberSymbol("decimal");
    if ($forSave) {
      if ($decimalSymbol == ',') {
        $value = self::stripAllButLastChar($value, ".");
        $value = str_replace($decimalSymbol, ".", $value); //replace with comma
      } else {
        $value = str_replace(",", "", $value); //remove thousand
      }
    } else {
      if ($decimalSymbol == ',') {
        $value = str_replace(".", $decimalSymbol, $value); //replace with comma
      }
    }
    if ($value === "") {
      $value = null;
    }

    return $value;
  }

  public static function getCurrencyArray() {
    return array(
        'EUR' => 'EUR',
        'USD' => 'USD',
        'RSD' => 'RSD',
        'GBP' => 'GBP',
        'AUD' => 'AUD',
        'CAD' => 'CAD',
        'CHF' => 'CHF',
        'AED' => 'AED',
        'AFN' => 'AFN',
        'ALL' => 'ALL',
        'AMD' => 'AMD',
        'ANG' => 'ANG',
        'AOA' => 'AOA',
        'ARS' => 'ARS',
        'AWG' => 'AWG',
        'AZN' => 'AZN',
        'BAM' => 'BAM',
        'BBD' => 'BBD',
        'BDT' => 'BDT',
        'BGN' => 'BGN',
        'BHD' => 'BHD',
        'BIF' => 'BIF',
        'BMD' => 'BMD',
        'BND' => 'BND',
        'BOB' => 'BOB',
        'BRL' => 'BRL',
        'BSD' => 'BSD',
        'BTN' => 'BTN',
        'BWP' => 'BWP',
        'BYR' => 'BYR',
        'BZD' => 'BZD',
        'CDF' => 'CDF',
        'CLP' => 'CLP',
        'CNY' => 'CNY',
        'COP' => 'COP',
        'CRC' => 'CRC',
        'CUP' => 'CUP',
        'CVE' => 'CVE',
        'CZK' => 'CZK',
        'DJF' => 'DJF',
        'DKK' => 'DKK',
        'DOP' => 'DOP',
        'DZD' => 'DZD',
        'EGP' => 'EGP',
        'ERN' => 'ERN',
        'ETB' => 'ETB',
        'FJD' => 'FJD',
        'FKP' => 'FKP',
        'GEL' => 'GEL',
        'GHS' => 'GHS',
        'GIP' => 'GIP',
        'GMD' => 'GMD',
        'GNF' => 'GNF',
        'GTQ' => 'GTQ',
        'GYD' => 'GYD',
        'HKD' => 'HKD',
        'HNL' => 'HNL',
        'HRK' => 'HRK',
        'HTG' => 'HTG',
        'HUF' => 'HUF',
        'IDR' => 'IDR',
        'ILS' => 'ILS',
        'INR' => 'INR',
        'IQD' => 'IQD',
        'IRR' => 'IRR',
        'ISK' => 'ISK',
        'JMD' => 'JMD',
        'JOD' => 'JOD',
        'JPY' => 'JPY',
        'KES' => 'KES',
        'KGS' => 'KGS',
        'KHR' => 'KHR',
        'KMF' => 'KMF',
        'KPW' => 'KPW',
        'KRW' => 'KRW',
        'KWD' => 'KWD',
        'KYD' => 'KYD',
        'KZT' => 'KZT',
        'LAK' => 'LAK',
        'LBP' => 'LBP',
        'LKR' => 'LKR',
        'LRD' => 'LRD',
        'LSL' => 'LSL',
        'LTL' => 'LTL',
        'LVL' => 'LVL',
        'LYD' => 'LYD',
        'MAD' => 'MAD',
        'MDL' => 'MDL',
        'MGA' => 'MGA',
        'MKD' => 'MKD',
        'MMK' => 'MMK',
        'MNT' => 'MNT',
        'MOP' => 'MOP',
        'MRO' => 'MRO',
        'MUR' => 'MUR',
        'MVR' => 'MVR',
        'MWK' => 'MWK',
        'MXN' => 'MXN',
        'MYR' => 'MYR',
        'MZN' => 'MZN',
        'NAD' => 'NAD',
        'NGN' => 'NGN',
        'NIO' => 'NIO',
        'NOK' => 'NOK',
        'NPR' => 'NPR',
        'NZD' => 'NZD',
        'OMR' => 'OMR',
        'PAB' => 'PAB',
        'PEN' => 'PEN',
        'PGK' => 'PGK',
        'PHP' => 'PHP',
        'PKR' => 'PKR',
        'PLN' => 'PLN',
        'PYG' => 'PYG',
        'QAR' => 'QAR',
        'RON' => 'RON',
        'RUB' => 'RUB',
        'RUR' => 'RUR',
        'RWF' => 'RWF',
        'SAR' => 'SAR',
        'SBD' => 'SBD',
        'SCR' => 'SCR',
        'SDG' => 'SDG',
        'SEK' => 'SEK',
        'SGD' => 'SGD',
        'SHP' => 'SHP',
        'SLL' => 'SLL',
        'SOS' => 'SOS',
        'SRD' => 'SRD',
        'STD' => 'STD',
        'SYP' => 'SYP',
        'SZL' => 'SZL',
        'THB' => 'THB',
        'TJS' => 'TJS',
        'TMT' => 'TMT',
        'TND' => 'TND',
        'TOP' => 'TOP',
        'TRY' => 'TRY',
        'TTD' => 'TTD',
        'TWD' => 'TWD',
        'TZS' => 'TZS',
        'UAH' => 'UAH',
        'UGX' => 'UGX',
        'UYU' => 'UYU',
        'UZS' => 'UZS',
        'VEF' => 'VEF',
        'VND' => 'VND',
        'VUV' => 'VUV',
        'WST' => 'WST',
        'XAF' => 'XAF',
        'XAG' => 'XAG',
        'XAU' => 'XAU',
        'XBA' => 'XBA',
        'XBB' => 'XBB',
        'XBC' => 'XBC',
        'XBD' => 'XBD',
        'XCD' => 'XCD',
        'XDR' => 'XDR',
        'XFU' => 'XFU',
        'XOF' => 'XOF',
        'XPD' => 'XPD',
        'XPF' => 'XPF',
        'XPT' => 'XPT',
        'XTS' => 'XTS',
        'YER' => 'YER',
        'ZAR' => 'ZAR',
        'ZMW' => 'ZMW',
        'ZWL' => 'ZWL',
    );
  }

  public static function getEncrypted($plainText, $key) {
    $key = substr($key, 0, 16);
    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    # creates a cipher text compatible with AES (Rijndael block size = 128)
    # to keep the text confidential
    # only suitable for encoded input that never ends with value 00h
    # (because of default zero padding)
    $ciphertext = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plainText, MCRYPT_MODE_CBC, $iv);

    # prepend the IV for it to be available for decryption
    $ciphertext = $iv . $ciphertext;

    # encode the resulting cipher text so it can be represented by a string
    $ciphertext_base64 = base64_encode($ciphertext);

    return $ciphertext_base64;
  }

  public static function getDecrypted($ciphertext_base64, $key) {
    $key = substr($key, 0, 16);

    # --- DECRYPTION ---

    $ciphertext_dec = base64_decode($ciphertext_base64);
//    echo $ciphertext_dec;die;
    # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);

    # retrieves the cipher text (everything except the $iv_size in the front)
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    # may remove 00h valued characters from end of plain text
    $plaintext_dec = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    //have to remove last null if exists
    $plaintext_dec = rtrim($plaintext_dec, "\0");

    return $plaintext_dec;
  }

  public static function splitEmail($email) {
    //find @
    $atPos = strrpos($email, "@");
    //extract example.com and remove ">" if found
    $host = rtrim(substr($email, $atPos + 1), ">");
    //find first "<" or space left of @
    $leftPart = substr($email, 0, $atPos);
    $spacePos = strrpos($leftPart, " ");
    if ($spacePos == 0) {
      $spacePos = -1; //for +1 to work
    }
    $emailPart = ltrim(substr($leftPart, $spacePos + 1), "<");
    $name = "";
    if ($spacePos != -1) {
      $name = trim(trim(substr($email, 0, $spacePos)), '"');
    }

    return array("name" => $name, "email" => $emailPart . "@" . $host);
  }

  public static function getImageExtensionFromType($contentType) {
    $map = array(
        'application/pdf' => 'pdf',
        'application/zip' => 'zip',
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/bmp' => 'bmp',
        'text/css' => 'css',
        'text/html' => 'html',
        'text/javascript' => 'js',
        'text/plain' => 'txt',
        'text/xml' => 'xml',
    );

    return isset($map[$contentType]) ? $map[$contentType] : null;
  }

  /**
   *
   * @param string $imageData
   * @return array
   */
  public static function getImageDecodedFromData($imageData) {
    $imageDecodedData = null;
    $new_data = explode(":", $imageData);
    if (isset($new_data[0]) && isset($new_data[1])) {
      $new_data = explode(";", $new_data[1]);
      if (isset($new_data[0]) && isset($new_data[1])) {
        $type = $new_data[0];
        $data = explode(",", $new_data[1]);
        if (isset($data[1])) {
          $imageDecodedData = array("type" => $type, "data" => base64_decode($data[1]));
        }
      }
    }

    return $imageDecodedData;
  }

  public static function stripAllButLastChar($string, $charToRemove) {
    //locales can make data as 123.123.5 - remove all but last dot
    $dotCount = substr_count($string, $charToRemove);
    if ($dotCount > 1) {
      $lastDotPosition = strrpos($string, $charToRemove); //find last position
      $dataWithHash = substr_replace($string, "###", $lastDotPosition, 1); //do temp replace with unique chars
      $dataWithHashNoDots = str_replace($charToRemove, "", $dataWithHash); //remove all remaining dots
      $string = str_replace("###", $charToRemove, $dataWithHashNoDots); //put back last dot
    }
    return $string;
  }

  public static function toHHMMSS($seconds) {

    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds - ($hours * 3600)) / 60);
    $seconds2 = round($seconds - ($hours * 3600) - ($minutes * 60));
    if ($seconds2 == 60) {
      $seconds2 = 0;
      $minutes++;
      if ($minutes == 60) {
        $minutes = 0;
        $hours++;
      }
    }
    if ($hours < 10) {
      $hours = "0" . $hours;
    }
    if ($minutes < 10) {
      $minutes = "0" . $minutes;
    }
    if ($seconds2 < 10) {
      $seconds2 = "0" . $seconds2;
    }
    $timeString = ($hours > 0 ? $hours . ':' : '') . $minutes . ':' . $seconds2;
    return $timeString;
  }

  public static function HHMMSStoSeconds($str_time) {
    $hours = 0;
    $minutes = 0;
    $seconds = 0;
    $hoursStr = "";
    $minutesStr = "";
    $secondsStr = "";
    if (strlen($str_time) <= 2) {
      $seconds = $str_time;
    } else {
      $reversed = strrev($str_time);
      $strLen = strlen($reversed);
      $position = 1;
      for ($i = 0; $i < $strLen; $i++) {
        $char = $reversed[$i];
        if (in_array($char, array(":", ",", ".", ";"))) {
          $position++;
          continue;
        }
        if ($position == 1) {
          if (strlen($secondsStr) == 2) {
            $position++;
          } else {
            $secondsStr = $char . $secondsStr;
          }
        }
        if ($position == 2) {
          if (strlen($minutesStr) == 2) {
            $position++;
          } else {
            $minutesStr = $char . $minutesStr;
          }
        }
        if ($position == 3) {
          $hoursStr = $char . $hoursStr;
        }
      }
      $hours = (int) $hoursStr;
      $minutes = (int) $minutesStr;
      $seconds = (int) $secondsStr;
    }

    return $hours * 3600 + $minutes * 60 + $seconds;
  }

  public static function getFormatedValue($dataValueOriginal, $optionsOrnumberOfDecimals = -1, $unitName = "", $shortValue = false, $minDecimalSpaces = 0) {
    if ($dataValueOriginal === null) {
      return null;
    }
    $shortValue = $shortValue && Yii::$app->params["helper_disable_short_value"] != 1;
    $numberOfDecimals = -1;
    $addDecimalZeros = true;
    if (is_array($optionsOrnumberOfDecimals)) {
      $numberOfDecimals = isset($optionsOrnumberOfDecimals["numberOfDecimals"]) ? $optionsOrnumberOfDecimals["numberOfDecimals"] : -1;
      $addDecimalZeros = isset($optionsOrnumberOfDecimals["addDecimalZeros"]) ? $optionsOrnumberOfDecimals["addDecimalZeros"] : true;
    } else {
      $numberOfDecimals = $optionsOrnumberOfDecimals;
    }
    $dataValue = doubleval($dataValueOriginal);
//      echo $dataValue . "<br/>";
    //find decimal symbol
    $decimalSymbol = Yii::$app->locale->getNumberSymbol("decimal");
    //prepare thousand seperator
    $thousands_sep = ",";
    if ($decimalSymbol == ',') {
      $thousands_sep = ".";
    }

    if ($numberOfDecimals === null || $numberOfDecimals == -1) {
      //find from original value
      if (strpos($dataValueOriginal, ".") === false) {
        $numberOfDecimals = 0;
      } else {
        $numberOfDecimals = strlen(substr($dataValueOriginal, strpos($dataValueOriginal, ".") + 1));
      }
    } else {
      $numberOfDecimals = $numberOfDecimals > 0 ? $numberOfDecimals : 0;
    }
    if ($dataValue !== 0.0 && $dataValue > -1 && $dataValue < 1 && $numberOfDecimals == 0) {
      $numberOfDecimals = 1;
    }
    if ($numberOfDecimals > 5) {
      $numberOfDecimals = 5;
    }

    $broken_number = explode(".", $dataValue);
    //number_format gives +0 if -0 or +0
    $add_negative = $broken_number[0] === '-0';

    $leftValue = $broken_number[0];
    $rightValue = null;
    if (isset($broken_number[1])) {
      $rightValue = $broken_number[1];
      if ($numberOfDecimals == 0) {
        $len = strlen($rightValue);
        for ($i = 0; $i < $len; $i++) {
          $lastDigit = (int) substr($rightValue, -1, 1);
          if (strlen($rightValue) == 1) {
            $rightValue = $lastDigit;
          } else {
            $rightValue = substr($rightValue, 0, strlen($rightValue) - 1);
          }
          if ($lastDigit >= 5) {
            $rightValue++;
          }
        }
        if ($rightValue >= 5) {
          if ($leftValue < 0) {
            $leftValue--;
          } else {
            $leftValue++;
          }
        }
      } else {
        $extraDecimals = substr($rightValue, $numberOfDecimals);
        $rightValue = substr($rightValue, 0, $numberOfDecimals);
        //do rounding
        $len = strlen($extraDecimals);
        for ($i = 0; $i < $len; $i++) {
          $lastDigit = (int) substr($extraDecimals, -1, 1);
          if (strlen($extraDecimals) == 1) {
            $extraDecimals = $lastDigit;
          } else {
            $extraDecimals = substr($extraDecimals, 0, strlen($extraDecimals) - 1);
          }
          if ($lastDigit >= 5) {
            $extraDecimals++;
          }
        }
        if ($extraDecimals >= 5) {
          $rightValue++;
        }
      }
    }
    if ($shortValue) {
      if ($leftValue > 1000000000000) {
        $value = round($leftValue / 1000000000000, 2) . 'T';
      } else if ($leftValue > 1000000000) {
        $value = round($leftValue / 1000000000, 2) . 'B';
      } else if ($leftValue > 1000000) {
        $value = round($leftValue / 1000000, 2) . 'M';
      } else {
        $shortValue = false;
      }
    }
    if (!$shortValue) {
      //number format is user for thousand seperator. Decimal is added manualy
      if ($numberOfDecimals > 0) {
        $rightLen = strlen($rightValue);
        if ($addDecimalZeros && $rightLen < $numberOfDecimals) {
          $rightValue = str_pad($rightValue, $rightLen + $numberOfDecimals - $rightLen, "0");
        }
        $addSymbol = $rightValue !== "";
        if ($minDecimalSpaces > 0) {
          while ($rightLen < $minDecimalSpaces + 1) {
            $rightValue .= "&nbsp;";
            $rightLen++;
          }
        }
        $value = number_format($leftValue, 0, $decimalSymbol, $thousands_sep);
        if ($addSymbol) {
          $value .= $decimalSymbol;
        }
        //rightValue can have &nbsp;
        $value .= $rightValue;
      } else {
        $rightValue = "";
        if ($minDecimalSpaces > 0) {
          $rightLen = 0;
          while ($rightLen < $minDecimalSpaces + 3) {
            $rightValue .= "&nbsp;";
            $rightLen++;
          }
        }
        $value = number_format($leftValue, 0, $decimalSymbol, $thousands_sep) . $rightValue;
      }
    }

    if ($add_negative) {
      $value = "-$value";
    }

    return $value . (!empty($unitName) ? (" " . CHtml::encode($unitName)) : "");
  }

  public static function splitJavaExceptionStack($string) {
    $result = "";
    $lines = explode("\n", $string);
    foreach ($lines as $line) {
      if (strpos($line, "Caused by") !== false) {
        $result .= $line . "\n";
      }
    }
    return $result;
  }

  public static function hasDecimalSymbol($formatedValue) {
    //find decimal symbol
    $decimalSymbol = Yii::$app->locale->getNumberSymbol("decimal");

    return strpos($formatedValue, $decimalSymbol) !== false;
  }

  public static function getSearchOrOperator($searchValue) {
    return strpos($searchValue, "<>") === 0 ? "AND" : "OR";
  }

  public static function setMinExecutionTime($seconds) {
    $newExecutionTime = max($seconds, intval(ini_get("max_execution_time")));
    ini_set("max_execution_time", $newExecutionTime);
  }

  public static function getArrayLeaves($array, &$result = null) {
    if ($result === null) {
      $result = array();
    }

    foreach ($array as $key => $value) {
      if (is_array($value)) {
        self::getArrayLeaves($value, $result);
      } else {
        $result[$key] = $value;
      }
    }
    return $result;
  }

  public static function getDaysFromDates($begin, $end) {
    $days = array();
    $dayInterval = new DateInterval('P1D');
    $begin = new DateTime($begin);
    $end = new DateTime($end);
    $_end = clone $end;
    $_end->modify('+1 day');
    foreach ((new DatePeriod($begin, $dayInterval, $_end)) as $i => $period) {
      $_begin = $period;
      if ($i)
        $_begin->setTime(0, 0, 0);
      if ($_begin > $end)
        break;
      $_end = clone $_begin;
      $_end->setTime(23, 59, 59);
      if ($end < $_end)
        $_end = $end;
      $days[] = array(
          'begin' => $_begin,
          'end' => $_end,
      );
    }
    return $days;
  }

  
  public static function execCommandInBackground($cmd, $logFile = "background") {
    try {
      $command = Yii::$app->basePath . "/yii $cmd >> " . Yii::$app->basePath . "/runtime/logs/$logFile.log 2>&1 &";
      if (substr(php_uname(), 0, 7) == "Windows") {
        $fullPhpPath = Yii::$app->params["php_path"];
        if (!empty($fullPhpPath)) {
          $command = "$fullPhpPath " . $command;
        } else {
          $command = "php " . $command;
        }
        pclose(popen("start /B cmd /C " . $command, "r"));
      } else {
        $command = "php " . $command;
//        $scriptsDir = Yii::$app->params["export_YII_CONSOLE_COMMANDS"];
//        if ($scriptsDir && file_exists($scriptsDir)) {
//          $command = 'export YII_CONSOLE_COMMANDS="' . $scriptsDir . '" ; ' . $command;
//        }
        exec($command);
      }
    } catch (Exception $e) {
      Yii::error($e->getMessage() . " - " . $e->getTraceAsString(), "error");
    }
  }

}
