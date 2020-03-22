<?php
/* @var $this View */

use yii\web\View;

$this->title = 'AirRenty.com';
?>
<div class="site-index">
  <div class="container-sm"> 

    <div>
      <h2>Welcome to AirRenty!</h2>

      <div class="text-row">
        Our service is to provide you the details where you can rent an airplane. 
      </div>



      <div class="text-row">
        <h5>Info</h5>
        We do not own any of the airplanes. 
        We do not process rentals.
        Prices are not binding to anyone. 
        All information is without guarantee. 

      </div>

      <div class="text-row">
        <h5>Contact</h5>
        Drop us an email at <?= \app\components\EmailObfuscator::widget(["email" => Yii::$app->params["adminEmail"]]) ?>
      </div>

    </div>
  </div>
</div>