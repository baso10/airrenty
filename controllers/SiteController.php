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
use app\models\LoginForm;

class SiteController extends BBController {

  /**
   * @inheritdoc
   */
  public function behaviors() {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'login', 'error', 'about', 'legal'],
                    'allow' => true,
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function actions() {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction',
        ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex() {

    /* @var $airportModels Airport[] */
    $airportModels = Airport::findAll(["country" => "CH"]);

    return $this->render('index', [
                "airportModels" => $airportModels,
    ]);
  }

  public function actionLogin() {
    if (!\Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
      return $this->goBack();
    } else {
      return $this->render('login', [
                  'model' => $model,
      ]);
    }
  }

  public function actionLogout() {
    Yii::$app->user->logout();

    return $this->goHome();
  }
  
  public function actionAbout() {

    return $this->render('about', [
    ]);
  }
  
  public function actionLegal() {

    return $this->render('legal', [
    ]);
  }

}
