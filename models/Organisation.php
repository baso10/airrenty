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

/**
 *
 * @author baso10
 */
class Organisation extends BBActiveRecord {

  public static function tableName() {
    return '{{%organisation}}';
  }

  public function rules() {
    return [
        [['name', 'web_page', 'airport_id'], 'required'],
        [['description'], 'safe'],
        [['created_time'], 'default', 'value' => new BBNowExpression()],
    ];
  }

  public function attributeLabels() {
    return [
        'id' => 'ID',
        'name' => Yii::t("app", "Name"),
        'web_page' => Yii::t("app", "Website"),
        'description' => Yii::t("app", "Description"),
        'airport_id' => Yii::t("app", "Airport")
    ];
  }

  /**
   * @return BBActiveRecord
   */
  public function getAirport() {
    return $this->hasOne(Airport::className(), ['id' => 'airport_id']);
  }

  public function beforeSave($insert) {
    if (!$insert) {
      $this->modified_time = new BBNowExpression();
      $this->modified_user_id = 1;
    }

    $this->order_num = 1;
    $this->created_user_id = 1;

//    $lastModel = Organisation::find()->orderBy("id desc")->one();
//    if ($lastModel != null) {
//      $this->code_name = "" . (($lastModel->id) + 1);
//    }

    return parent::beforeSave($insert);
  }

}
