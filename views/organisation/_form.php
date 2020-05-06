<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Airport;

/* @var $this yii\web\View */
/* @var $model app\models\Airplane */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="create-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="row">
    <div class="col col-xl-6">
      <?= $form->field($model, 'name')->textInput() ?>
    </div>
    <div class="col col-xl-6">
      <?= $form->field($model, 'web_page')->textInput() ?>
    </div>
  </div>


  <div class="row">
    <div class="col col-xl-6">
      <?= $form->field($model, 'description')->textArea(['rows' => 6]) ?>
    </div>
    <div class="col col-xl-6">
      <?=
      $form->field($model, 'airport_id')->widget(Select2::class, [
          'data' => ArrayHelper::map(Airport::find()->all(), 'id', 'codeNameWithName'),
          'options' => ['placeholder' => 'Select airport ...'],
          'pluginOptions' => [
              'allowClear' => false
  ]])
      ?>

    </div>
  </div>



  <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t("app", "Add") : Yii::t("app", "Update"), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?= Html::a(Yii::t("app", "Back"), ["cancel"], ["class" => "form-back-button"]); ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>

