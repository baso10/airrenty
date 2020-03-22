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
use app\models\Organisation;
use app\models\OrganisationSearch;

class OrganisationController extends BBController {

  public function init() {
    parent::init();

    $this->_model = Organisation::class;
  }

  public function behaviors() {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'allow' => false,
                ],
            ],
        ],
    ];
  }

  public function actionIndex() {

    $searchModel = new OrganisationSearch();
    $searchDataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
                'searchModel' => $searchModel,
                'searchDataProvider' => $searchDataProvider,
    ]);
  }

  /**
   * @return string
   */
  public function actionView($id) {

    $model = $this->loadModel($id);

    return $this->render('view', [
                'model' => $model
    ]);
  }

  public function actionCreate() {
    $model = new Organisation();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['index']);
    } else {
      return $this->render('create', [
                  'model' => $model,
      ]);
    }
  }

  public function actionUpdate($id) {
    $model = $this->loadModel($id);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['index']);
    } else {
      return $this->render('update', [
                  'model' => $model,
      ]);
    }
  }

  public function actionDelete($id) {
    $model = $this->loadModel($id);

    $model->delete();

    return $this->redirect(['index']);
  }

}
