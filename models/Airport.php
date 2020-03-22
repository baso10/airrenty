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
use app\components\BBNowExpression;
use app\models\FuelPrice;
use app\models\Airplane;

/**
 *
 * @property integer $id
 * @property integer $code_name
 * @property integer $name
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
        [['created_time'], 'default', 'value'=> new BBNowExpression()],
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
