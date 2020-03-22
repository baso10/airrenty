<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\components\EmailObfuscator;

$this->title = "Error";
?>
<div class="site-error">

  <p>
    The error occurred while the Web server was processing your request.
  </p>
  <p>
    <?=
    EmailObfuscator::widget([
        'email' => Yii::$app->params["adminEmail"],
    ])
    ?>
  </p>

</div>
