<?php

use yii\helpers\Html;


/* @var $this yii\web\View */

$this->title = Yii::t("app", "Add organisation");
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
