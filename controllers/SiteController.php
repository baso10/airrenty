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
use app\models\VerifyEmailForm;
use app\models\RegistrationForm;
use app\models\AirplaneSearch;
use app\models\OrganisationSearch;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\ChangePasswordForm;
use app\models\User;

class SiteController extends BBController {

  /**
   * @inheritdoc
   */
  public function behaviors() {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'only' => ['logout', 'register', 'account'],
            'rules' => [
                [
                    'actions' => ['register'],
                    'allow' => true,
                ],
                [
                    'actions' => ['logout', 'account', 'changePassword'],
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

    $country = "CH"; //default
    $lang = Yii::$app->language;

    if ($lang == "sl") {
      $country = "SI";
    }

    /* @var $airportModels Airport[] */
    $airportModels = Airport::findAll(["country" => $country]);

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
      return $this->redirect(["site/account"]);
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

  /**
   * Signs user up.
   *
   * @return mixed
   */
  public function actionRegister($success = null) {
    $model = new RegistrationForm();
    if (Yii::$app->params["user_register_disabled"]) {
      Yii::$app->session->setFlash('danger', Yii::t("app", 'Registration is disabled'));
      return $this->render('registration', [
                  'model' => $model,
                  'success' => $success
      ]);
    }
    if ($model->load(Yii::$app->request->post()) && $model->register()) {
      Yii::$app->session->setFlash('success', Yii::t("app", 'Success'));
      if (Yii::$app->params["user_enable_email_confirmation"]) {
        return $this->redirect(['register', 'success' => 1]);
      } else {
        $userModel = User::findByUsername($model->email);
        Yii::$app->user->login($userModel);
        return $this->redirect(['account']);
      }
    }

    return $this->render('registration', [
                'model' => $model,
                'success' => $success
    ]);
  }

  /**
   * Requests password reset.
   *
   * @return mixed
   */
  public function actionRequestPasswordReset() {
    $model = new PasswordResetRequestForm();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->sendEmail()) {
        Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

        return $this->goHome();
      } else {
        Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
      }
    }

    return $this->render('requestPasswordResetToken', [
                'model' => $model,
    ]);
  }

  /**
   * Resets password.
   *
   * @param string $token
   * @return mixed
   * @throws BadRequestHttpException
   */
  public function actionResetPassword($token) {
    try {
      $model = new ResetPasswordForm($token);
    } catch (InvalidArgumentException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($userModel = $model->resetPassword()) {
        Yii::$app->session->setFlash('success', Yii::t("app", 'New password saved'));

        Yii::$app->user->login($userModel);

        return $this->redirect(["site/account"]);
      }
    }

    return $this->render('resetPassword', [
                'model' => $model,
    ]);
  }

  public function actionChangePassword() {
    $model = new ChangePasswordForm();

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($userModel = $model->resetPassword()) {
        Yii::$app->session->setFlash('success', Yii::t("app", 'New password saved'));

        Yii::$app->user->login($userModel);

        return $this->redirect(["site/account"]);
      }
    }

    return $this->render('changePassword', [
                'model' => $model,
    ]);
  }

  /**
   * Verify email address
   *
   * @param string $token
   * @throws BadRequestHttpException
   * @return yii\web\Response
   */
  public function actionVerifyEmail($token) {
    try {
      $model = new VerifyEmailForm($token);
    } catch (InvalidArgumentException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }
    if ($user = $model->activateUser()) {
      if (Yii::$app->user->login($user)) {
        Yii::$app->session->setFlash('success', Yii::t("app", 'Your email has been confirmed!'));
        return $this->goHome();
      }
    }

    Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
    return $this->goHome();
  }

  public function actionNewAccount() {

    return $this->render('newAccount', [
    ]);
  }

  public function actionAccount() {

    $userModel = Yii::$app->user->getModel();


    //airplane
    $airplaneSearchModel = new AirplaneSearch();
    $airplaneSearchModel->created_user_id = $userModel->id;

    $airplaneDataProvider = $airplaneSearchModel->search(Yii::$app->request->queryParams);
    $airplaneDataProvider->pagination = false;
    $airplaneDataProvider->sort = false;

    //orgarnisation
    $orgarnisationSearchModel = new OrganisationSearch();
    $orgarnisationSearchModel->created_user_id = $userModel->id;

    $orgarnisationDataProvider = $orgarnisationSearchModel->search(Yii::$app->request->queryParams);
    $orgarnisationDataProvider->pagination = false;
    $orgarnisationDataProvider->sort = false;


    return $this->render('account', [
                'userModel' => $userModel,
                'airplaneSearchModel' => $airplaneSearchModel,
                'airplaneDataProvider' => $airplaneDataProvider,
                'orgarnisationSearchModel' => $orgarnisationSearchModel,
                'orgarnisationDataProvider' => $orgarnisationDataProvider,
    ]);
  }

}
