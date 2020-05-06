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
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ],
    ];
  }

  protected function checkIndex() {
    if (!Yii::$app->user->isSuperAdmin()) {
      throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
  }
  
  protected function checkUpdate($model) {
    if(!Yii::$app->user->isSuperAdmin() && $model->created_user_id != Yii::$app->user->getId()) {
      throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
  }

  public function actionIndex() {

    $this->checkIndex();

    $searchModel = new OrganisationSearch();
    $searchDataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
                'searchModel' => $searchModel,
                'searchDataProvider' => $searchDataProvider,
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
    
    $this->checkUpdate($model);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      if (!Yii::$app->user->isSuperAdmin()) {
        return $this->redirect(['site/account']);
      }
      return $this->redirect(['index']);
    } else {
      return $this->render('update', [
                  'model' => $model,
      ]);
    }
  }

  public function actionDelete($id) {
    $model = $this->loadModel($id);
    
    $this->checkUpdate($model);

    $model->delete();

    if (!Yii::$app->user->isSuperAdmin()) {
      return $this->redirect(['site/account']);
    }

    return $this->redirect(['index']);
  }
  
  public function actionCancel() {
    if (!Yii::$app->user->isSuperAdmin()) {
      return $this->redirect(['site/account']);
    }
    
    return parent::actionCancel();
  }
}
