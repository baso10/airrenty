<?php
/* @var $this View */

use yii\web\View;

$this->title = 'AirRenty.com';
?>
<div class="site-index">
  <div class="container-sm"> 

    <div>
      <h2><?= Yii::t("app", "Welcome to AirRenty!") ?></h2>
      <br>

      <div class="text-row">
        <?= Yii::t("app", "Our service is to provide you the details where you can rent an airplane.") ?> 
      </div>



      <div class="text-row">
        <h5><?= Yii::t("app", "Info") ?></h5>
        <br>
        <?= Yii::t("app", "We do not own any of the airplanes. 
        We do not process rentals.
        Prices are not binding to anyone. 
        All information is without guarantee. ") ?>

      </div>

      <div class="text-row">
        <h5><?= Yii::t("app", "Contact") ?></h5>
        <br>
        <?= Yii::t("app", "Drop us an email at") ?> <?= \app\components\EmailObfuscator::widget(["email" => Yii::$app->params["adminEmail"]]) ?>
      </div>

    </div>
  </div>
</div>