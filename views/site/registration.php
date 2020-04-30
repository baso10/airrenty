<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\RegistrationForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Airport;
use kartik\select2\Select2;

$this->title = Yii::t("app", 'Create account');
?>
<div class="site-signup">

  <?php if (!empty($success)) : ?>
    <div class="row">
      <div class="col-lg-4 offset-lg-4">
        <h3><?= Yii::t("app", 'Thank you for registration') ?></h3>
        <p><?= Yii::t("app", 'Please check your inbox for verification email.') ?></p>

      </div>

    </div>
  <?php else : ?>
    <div class="row">
      <div class="col-lg-4 offset-lg-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <hr>
        <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>


        <hr>
        <h3>Organisation</h3>

        <div class="row">
          <div class="col col-xl-12">
            <?= $form->field($model, 'name')->textInput() ?>
          </div>
          <div class="col col-xl-12">
            <?= $form->field($model, 'web_page')->textInput() ?>
          </div>

          <div class="col col-xl-12">
            <?=
            $form->field($model, 'airport_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Airport::find()->all(), 'id', 'codeNameWithName'),
                'options' => ['placeholder' => Yii::t("app", "Select airport") . " ..."],
                'pluginOptions' => [
                    'allowClear' => true,
        ]])
            ?>

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
                    ->hint(Yii::t("app", "Full name and contact details"))
                    ->textArea(['rows' => 6])
            ?>
          </div>
        </div>
        <div class="form-group">
          <?= Html::submitButton(Yii::t("app", 'Create account'), ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>

  <?php endif; ?>



</div>

