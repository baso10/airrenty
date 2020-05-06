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

namespace app\controllers;

use Yii;
use app\components\BBController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Airport;
use app\models\AirplaneSearch;

class AirportController extends BBController {

  public function init() {
    parent::init();

    $this->_model = Airport::class;
  }

  public function behaviors() {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view'],
                    'allow' => true,
                ],
                [
                    'allow' => false,
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ],
    ];
  }
  
  /**
   * @return string
   */
  public function actionView($id) {

    $model = Airport::findByCodeName($id);
    if ($model === null) {
      throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }

    $airplaneSearchModel = new AirplaneSearch();
    $airplaneSearchModel->airport_id = $model->id;
    $airplaneDataProvider = $airplaneSearchModel->search(Yii::$app->request->queryParams);
    $airplaneDataProvider->pagination = false;

    return $this->render('view', [
                'model' => $model,
                'airplaneSearchModel' => $airplaneSearchModel,
                'airplaneDataProvider' => $airplaneDataProvider,
    ]);
  }

}
