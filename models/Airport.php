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
use app\models\Airplane;

/**
 *
 * @property int $id
 * @property string $code_name
 * @property string $name
 * @property float|null $lat
 * @property float|null $lon
 * @property string|null $web_page
 * @property string|null $country
 * @property int|null $order_num
 * @property int $disabled
 * @property int|null $created_user_id
 * @property string|null $created_time
 * @property string|null $modified_time
 * @property int|null $modified_user_id
 * @property int|null $landing_fee
 * @property string|null $currency
 * @property int|null $price_fuel100
 * @property int|null $price_fuel91
 * @property string|null $fuel_date
 * @property string|null $codeNameWithName
 *
 * @property Airplane[] $airplanes
 * @property Organisation[] $organisations
 *
 * @author baso10
 */
class Airport extends BBActiveRecord {

  public $codeNameWithName;
  
  
  public static function tableName() {
    return '{{%airport}}';
  }

  public function attributeLabels() {
    return [
        'id' => 'ID',
        'code_name' => Yii::t("app", "Code"),
        'name' => Yii::t("app", "Name"),
        'web_page' => Yii::t("app", "Web page"),
    ];
  }

  public function rules() {
    return [
        [['order_num'], 'default', 'value' => 1],
    ];
  }
  
  public function getAirplanes() {
    return $this->hasMany(Airplane::className(), ['airport_id' => 'id']);
  }
  
  public function getAirplanesCount() {
    return $this->getAirplanes()->count();
  }
  
  /**
   * @param string $code_name
   * @return Airplane|null
   */
  public static function findByCodeName($code_name) {
    return static::findOne(['code_name' => $code_name]);
  }
  
  public function afterFind() {

    $this->codeNameWithName = $this->code_name . " - " . $this->name;

    parent::afterFind();
  }

}
