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
use app\components\BBHelper;

/**
 *
 * @author baso10
 */
class OrganisationSearch extends Organisation {

  public $airport;
  
  public function rules() {
    return [
        [['name', 'airport'], 'safe'],
    ];
  }

  public function scenarios() {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  public function search($params) {
    $query = Organisation::find()->alias("t")->joinWith(['airport a']);;

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
            'attributes' => [
                'airport' => [
                    'asc' => ['a.name' => SORT_ASC],
                    'desc' => ['a.name' => SORT_DESC]
                ],
                'id' => [
                    'asc' => ['t.id' => SORT_ASC],
                    'desc' => ['t.id' => SORT_DESC]
                ],
                'name' => [
                    'asc' => ['t.name' => SORT_ASC],
                    'desc' => ['t.name' => SORT_DESC]
                ],
            ]
        ],
    ]);
    
    $query->andFilterWhere([
        't.created_user_id' => $this->created_user_id,
    ]);

    if (!($this->load($params) && $this->validate())) {
      return $dataProvider;
    }

    $query->andFilterWhere(['like', 'LOWER(t.name)', BBHelper::toLower($this->name)])
            ->andFilterWhere(['like', 'LOWER(a.name)', BBHelper::toLower($this->airport)]);
    
    return $dataProvider;
  }

}
