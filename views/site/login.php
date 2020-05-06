<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = Yii::t("app", "Login");
?>
<div class="site-login">


  <div class="row">
    <div class="col-lg-4 offset-lg-4">
      <h1><?= Html::encode($this->title) ?></h1>
      <hr>
      <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
      <?= $form->field($model, 'username') ?>
      <?= $form->field($model, 'password')->passwordInput() ?>
      
      <div class="login-forgot-password">
       <?= Html::a(Yii::t("app", "Forgot your password?"), ['site/request-password-reset']) ?>
      </div>
      
      <?= $form->field($model, 'rememberMe')->checkbox() ?>

      
      <div class="form-group">
        <?= Html::submitButton(Yii::t("app", "Login"), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
      </div>
      <?php ActiveForm::end(); ?>

      <p class="login-no-account">
        <?= Yii::t("app", "No account?") ?>
        <?= Html::a(Yii::t("app", "Create new account"), ["site/register"]) ?>
      </p>
    </div>
  </div>
</div>