<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = Yii::t("app", "New password");
?>
<div class="site-login">


  <div class="row">
    <div class="col-lg-4 offset-lg-4">
      <h1><?= Html::encode($this->title) ?></h1>
      <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>
      <?= $form->field($model, 'oldPassword')->passwordInput() ?>
      <?= $form->field($model, 'password')->passwordInput() ?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t("app", "Change password"), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
      </div>
      <?php ActiveForm::end(); ?>
      
    </div>
  </div>
</div>