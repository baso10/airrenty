<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Airport;
use app\models\Organisation;

/* @var $this yii\web\View */
/* @var $model app\models\Airplane */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="create-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="row">
    <div class="col col-xl-3">
      <?= $form->field($model, 'code_name')->textInput() ?>
    </div>
    <div class="col col-xl-6">
      <?= $form->field($model, 'name')->textInput() ?>
    </div>
    <div class="col col-xl-3">
      <?= $form->field($model, 'seats_num')->dropDownList([1 => "1", 2 => "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6"], ['prompt' => 'Select number of seats']) ?>
    </div>
  </div>

  <div class="row">
    <div class="col col-xl-6">
      <?=
      $form->field($model, 'price', [
          'addon' => ['append' => ['content' => 'CHF']]
      ])->textInput()
      ?>
    </div>
    <div class="col col-xl-6">
      <?=
      $form->field($model, 'min_price', [
          'addon' => ['append' => ['content' => 'CHF']]
      ])->textInput()
      ?>
    </div>
  </div>

  <div class="row">
    <div class="col col-xl-6">
      <?=
      $form->field($model, 'airport_id')->widget(Select2::class, [
          'data' => ArrayHelper::map(Airport::find()->all(), 'id', 'codeNameWithName'),
          'options' => ['placeholder' => 'Select airport ...'],
          'pluginOptions' => [
              'allowClear' => true
  ]])
      ?>

    </div>
    <?php if (Yii::$app->user->isSuperAdmin()) : ?>
      <div class="col col-xl-6">
        <?=
        $form->field($model, 'organisation_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(Organisation::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Select organisation ...'],
            'pluginOptions' => [
                'allowClear' => true
    ]])
        ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="row">
    <div class="col col-xl-6">
      <?= $form->field($model, 'wiki_url')->textInput() ?>
    </div>
    <div class="col col-xl-6">
      <?= $form->field($model, 'image_url')->textInput() ?>
    </div>
  </div>

  <div class="row">
    <div class="col col-xl-12">
      <?=
              $form->field($model, 'description', [
                  'template' => "{label}\n{hint}\n{input}\n{error}",
                  'labelOptions' => [
                      'class' => 'control-label control-label-with-hint'
                  ],
                  'hintOptions' => [
                      'class' => 'form-text text-muted hint-block-above'
                  ],
              ])
              ->hint(Yii::t("app", "Any extra details about the plane or rental conditions"))
              ->textArea(['rows' => 3])
      ?>
    </div>
  </div>

  <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t("app", "Add") : Yii::t("app", "Update"), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?= Html::a(Yii::t("app", "Back"), ["cancel"], ["class" => "form-back-button"]); ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>

