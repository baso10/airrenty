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

namespace app\components;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use const YII_ENV_TEST;
use app\models\BBApiModel;

class BBController extends Controller {

  public $layout = 'column1';
  protected $_model = null;

  /**
   * @inheritdoc
   */
  public function actions() {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction',
        ],
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
    ];
  }

  protected function performAjaxValidation($model) {
    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      echo json_encode(ActiveForm::validate($model));
      Yii::$app->end();
    }
  }

  protected function redirectAfterLogin() {
    if (Yii::$app->getUser()->getReturnUrl() == Yii::$app->getHomeUrl()) {
      return $this->redirect(["/"]);
    } else {
      return $this->goBack();
    }
  }

  public function getGoBackUrl($defaultUrl = null) {
    $previous = $defaultUrl === null ? "javascript:history.go(-1)" : $defaultUrl;
    if (isset($_SERVER['HTTP_REFERER'])) {
      $previous = $_SERVER['HTTP_REFERER'];
    }
    return $previous;
  }
  
  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return BBApiModel the loaded model
   * @throws CHttpException
   */
  protected function loadModel($id) {
    $clazz = $this->getModelClass();
    $model = $clazz::findOne($id);
    if ($model === null){
      throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
    return $model;
  }
  
  protected function getModelClass() {
    return new $this->_model;
  }

}
