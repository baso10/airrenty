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

/**
 *
 * @property int $id
 * @property int $airport_id
 * @property int $organisation_id
 * @property string $code_name
 * @property string $name
 * @property int|null $price
 * @property string|null $currency
 * @property int|null $min_price
 * @property string|null $description
 * @property int|null $order_num
 * @property int $disabled
 * @property int|null $created_user_id
 * @property string|null $created_time
 * @property string|null $modified_time
 * @property int|null $modified_user_id
 * @property string|null $wiki_url
 * @property string|null $image_url
 * @property int|null $seats_num
 *
 * @property Airport $airport
 * @property Organisation $organisation
 *
 * @author baso10
 */
class Airplane extends BBActiveRecord {

  public $codeNameWithName;
  public $price_original;
  public $min_price_original;

  public static function tableName() {
    return '{{%airplane}}';
  }

  public function rules() {
    return [
        [['code_name', 'name', 'seats_num', 'price', 'organisation_id', 'airport_id'], 'required'],
        [['seats_num', 'airport_id', 'organisation_id'], 'integer'],
        [['wiki_url', 'image_url', 'description', 'min_price'], 'safe'],
        [['wiki_url', 'image_url', 'code_name', 'name'], 'trim'],
        [['order_num'], 'default', 'value' => 1],
        [['price'], function ($attribute, $params, $validator) {
                $val1 = (int) str_replace(",", ".", $this->$attribute);
                if ($val1 < 50) {
                    $this->addError($attribute, Yii::t("app", "Invalid price"));
                }
            }],
    ];
  }

  public function attributeLabels() {
    return [
        'id' => 'ID',
        'code_name' => Yii::t("app", "Registration"),
        'name' => Yii::t("app", "Name"),
        'description' => Yii::t("app", "Note"),
        'price' => Yii::t("app", "Price"),
        'min_price' => Yii::t("app", "Price with discount"),
        'seats_num' => Yii::t("app", "Seats"),
        'organisation' => Yii::t("app", "Organisation"),
        'organisation_id' => Yii::t("app", "Organisation"),
        'airport_id' => Yii::t("app", "Airport"),
        'airport' => Yii::t("app", "Airport"),
        'wiki_url' => Yii::t("app", "Wikipedia URL"),
        'image_url' => Yii::t("app", "Image URL"),
    ];
  }

  public function attributeHints() {
    return [
        'price' => Yii::t("app", "per hour") . ". " . Yii::t("app", "With VAT"),
        'min_price' => Yii::t("app", "per hour") . ". " . Yii::t("app", "With VAT") . ". " . Yii::t("app", "The lowest price with membership discount or package discount"),
        'name' => 'eg. Cessna 172 Skyhawk II, Aero AT-3, Piper PA28-181 Archer 2, ...',
        'wiki_url' => Yii::t("app", "Link where users can find more details about the airplane"),
        'image_url' => Yii::t("app", "Link to image. Please use external services to store an image."),
    ];
  }

  /**
   * @return BBActiveRecord
   */
  public function getOrganisation() {
    return $this->hasOne(Organisation::className(), ['id' => 'organisation_id']);
  }

  /**
   * @return BBActiveRecord
   */
  public function getAirport() {
    return $this->hasOne(Airport::className(), ['id' => 'airport_id']);
  }

  /**
   * @param string $code_name
   * @return Airplane|null
   */
  public static function findByCodeName($code_name) {
    return static::findOne(['code_name' => $code_name]);
  }

  public function afterFind() {

    $this->price_original = $this->price;
    $this->min_price_original = $this->min_price;
    
    if (!empty($this->price)) {
      $decPlaces = 2; //get from currency
      $decFactor = pow(10, $decPlaces);
      $majorInt = (int) ($this->price / $decFactor);
      $major = number_format($majorInt);

      $minorInt = $this->price % $decFactor;
      if ($minorInt < 0) {
        $minorInt *= -1;
      }
      $this->price = floatval("" . $major . "." . $minorInt);
    }

    if (!empty($this->min_price)) {
      $decPlaces = 2; //get from currency
      $decFactor = pow(10, $decPlaces);
      $majorInt = (int) ($this->min_price / $decFactor);
      $major = number_format($majorInt);

      $minorInt = $this->min_price % $decFactor;
      if ($minorInt < 0) {
        $minorInt *= -1;
      }
      $this->min_price = floatval("" . $major . "." . $minorInt);
    }

    $this->codeNameWithName = $this->code_name . " - " . $this->name;


    parent::afterFind();
  }

  public function beforeSave($insert) {
    if (!empty($this->price)) {
      $this->price = (int) (floatval(str_replace(",", ".", $this->price)) * 100);
      $this->currency = "CHF";
    }

    if (!empty($this->min_price)) {
      $this->min_price = (int) (floatval(str_replace(",", ".", $this->min_price)) * 100);
      $this->currency = "CHF";
    }
    
    if(!empty($this->code_name)) {
      $this->code_name = strtoupper($this->code_name);
    }
    
    if(!empty($this->wiki_url)) {
      if (strpos($this->wiki_url, 'http') !== 0) {
        $this->wiki_url = 'http://' . $this->wiki_url;
      }
    }
    
    if(!empty($this->image_url)) {
      if (strpos($this->image_url, 'http') !== 0) {
        $this->image_url = 'http://' . $this->image_url;
      }
    }
    
    return parent::beforeSave($insert);
  }

}
