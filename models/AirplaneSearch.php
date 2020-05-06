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

class AirplaneSearch extends Airplane {

  public $organisation;
  public $airport;

  public function rules() {
    return [
        [['code_name', 'name', 'description', 'organisation', 'airport', 'price', 'min_price', 'seats_num'], 'safe'],
    ];
  }

  public function scenarios() {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  public function search($params) {
    $query = Airplane::find()->alias("t")->joinWith(['organisation o', 'airport a']);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
            'attributes' => [
                'organisation' => [
                    'asc' => ['o.name' => SORT_ASC],
                    'desc' => ['o.name' => SORT_DESC]
                ],
                'airport' => [
                    'asc' => ['a.name' => SORT_ASC],
                    'desc' => ['a.name' => SORT_DESC]
                ],
                'id' => [
                    'asc' => ['t.id' => SORT_ASC],
                    'desc' => ['t.id' => SORT_DESC]
                ],
                'code_name' => [
                    'asc' => ['t.code_name' => SORT_ASC],
                    'desc' => ['t.code_name' => SORT_DESC]
                ],
                'name' => [
                    'asc' => ['t.name' => SORT_ASC],
                    'desc' => ['t.name' => SORT_DESC]
                ],
                'price' => [
                    'asc' => ['t.price' => SORT_ASC],
                    'desc' => ['t.price' => SORT_DESC]
                ],
                'min_price' => [
                    'asc' => ['t.min_price' => SORT_ASC],
                    'desc' => ['t.min_price' => SORT_DESC]
                ],
                'seats_num' => [
                    'asc' => ['t.seats_num' => SORT_ASC],
                    'desc' => ['t.seats_num' => SORT_DESC]
                ]
            ]
        ],
    ]);

    $query->andFilterWhere([
        't.airport_id' => $this->airport_id,
        't.created_user_id' => $this->created_user_id,
    ]);
    
//    $dataProvider->sort->attributes['organisation'] = [
//        'asc' => ['o.name' => SORT_ASC],
//        'desc' => ['o.name' => SORT_DESC],
//    ];
//
//    $dataProvider->sort->defaultOrder = ['id' => SORT_ASC]
//    ;

    if (!($this->load($params) && $this->validate())) {
      return $dataProvider;
    }
    
    $query->andFilterWhere ( [ 'OR' ,
            [ 'like' , 'LOWER(a.code_name)' , BBHelper::toLower($this->airport) ],
            [ 'like' , 'LOWER(a.name)' , BBHelper::toLower($this->airport) ],
        ] );

    $query->andFilterWhere(['like', 'LOWER(t.code_name)', BBHelper::toLower($this->code_name)])
            ->andFilterWhere(['like', 'LOWER(t.name)', BBHelper::toLower($this->name)])
            ->andFilterWhere(['like', 'LOWER(t.description)', BBHelper::toLower($this->description)])
            ->andFilterWhere(['like', 'LOWER(o.name)', BBHelper::toLower($this->organisation)]);

    return $dataProvider;
  }

}
