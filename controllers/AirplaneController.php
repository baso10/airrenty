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
use app\models\Airplane;
use app\models\AirplaneSearch;
use app\models\Organisation;

class AirplaneController extends BBController {

  public function init() {
    parent::init();

    $this->_model = Airplane::class;
  }

  public function behaviors() {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['index', 'view', 'cancel'],
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
  
  protected function checkUpdate($model) {
    if(!Yii::$app->user->isSuperAdmin() && $model->created_user_id != Yii::$app->user->getId()) {
      throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
  }

  public function actionIndex() {

    $airplaneSearchModel = new AirplaneSearch();
    $airplaneDataProvider = $airplaneSearchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
                'airplaneSearchModel' => $airplaneSearchModel,
                'airplaneDataProvider' => $airplaneDataProvider,
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
    $model = new Airplane();
    
    if(!Yii::$app->user->isSuperAdmin()) {
      $organisationModel = Organisation::findOne(["created_user_id" => Yii::$app->user->getId()]);
      if(!empty($organisationModel)) {
        $model->airport_id = $organisationModel->airport_id;
        $model->organisation_id = $organisationModel->id;
      }
    }

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->code_name]);
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
      return $this->redirect(['view', 'id' => $model->code_name]);
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

    return $this->redirect(['index']);
  }

  protected function loadModel($id) {
    $model = Airplane::findByCodeName($id);
    if ($model === null) {
      throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
    return $model;
  }
  
  public function actionCancel() {
    if (!Yii::$app->user->isSuperAdmin()) {
      return $this->redirect(['site/account']);
    }
    
    return parent::actionCancel();
  }

}
