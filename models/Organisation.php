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
 * @property string $name
 * @property string $web_page
 * @property int $airport_id
 * @property string|null $description
 * @property int|null $order_num
 * @property int $disabled
 * @property int|null $created_user_id
 * @property string|null $created_time
 * @property string|null $modified_time
 * @property int|null $modified_user_id
 *
 * @property Airport $airport
 *
 * @author baso10
 */
class Organisation extends BBActiveRecord {

  public static function tableName() {
    return '{{%organisation}}';
  }

  public function rules() {
    return [
        [['name', 'web_page', 'airport_id', 'description'], 'required'],
        [['description'], 'safe'],
        [['order_num'], 'default', 'value' => 1],
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
    if (!empty($this->web_page) && strpos($this->web_page, 'http') !== 0) {
      $this->web_page = 'http://' . $this->web_page;
    }

    return parent::beforeSave($insert);
  }

}
