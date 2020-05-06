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

use yii\db\ActiveRecord;
use app\components\BBNowExpression;
use Yii;

/**
 *
 * @author basic
 */
class BBActiveRecord extends ActiveRecord {

  const SCENARIO_INSERT = 'insert';
  const SCENARIO_UPDATE = 'update';
  const SCENARIO_SEARCH = 'search';

  public function lockTable() {
    $this->getDb()->createCommand("LOCK TABLE " . $this->tableName() . " IN EXCLUSIVE MODE")->execute();
  }
  
  public function beforeSave($insert) {
    if(!$insert) {
      if($this->hasAttribute("modified_time")) {
        $this->modified_time = new BBNowExpression(); 
      }
      if($this->hasAttribute("modified_user_id")) {
        $this->modified_user_id = Yii::$app->user->getId(); 
      }
    } else {
      if($this->hasAttribute("created_time")) {
        $this->created_time = new BBNowExpression(); 
      }
      if($this->hasAttribute("created_user_id") && empty($this->created_user_id) && !empty(Yii::$app->user->getId())) {
        $this->created_user_id = Yii::$app->user->getId(); 
      }
    }
    
    return parent::beforeSave($insert);
  }

}
