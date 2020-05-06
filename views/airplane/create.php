<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t("app", "Add airplane");
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <hr>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
