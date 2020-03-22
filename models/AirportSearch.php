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
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 *
 * @author baso10
 */
class AirportSearch extends Airport {

  public function rules() {
    return [
        [['code_name', 'name'], 'safe'],
    ];
  }

  public function scenarios() {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  public function search($params) {
    $query = Airport::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    if (!($this->load($params) && $this->validate())) {
      return $dataProvider;
    }

    $query->andFilterWhere([
        'id' => $this->id,
        'code_name' => $this->code_name,
    ]);

    $query->andFilterWhere(['like', 'name', $this->name]);

    return $dataProvider;
  }

}
