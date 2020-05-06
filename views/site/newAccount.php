<?php

use yii\helpers\Html;

$this->title = Yii::t("app", "Add your own airplane");
?>
<div class="site-account">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    <div class="row">
        <div class="col-12">
          Adding your airplane is easy and free. Please login or create free account to manage your airplanes.<br>
          <br>
          <?= Html::a(Yii::t("app", "Login"), ["site/login"], ["class" => "btn btn-primary"]) ?>
          <?= Yii::t("app", "or") ?>
          <?= Html::a(Yii::t("app", "Create account"), ["site/register"], ["class" => "btn btn-primary"]) ?>
        </div>
    </div>
</div>

