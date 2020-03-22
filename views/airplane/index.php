<?php
/* @var $this View */

use yii\web\View;
use app\assets\MapAsset;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

MapAsset::register($this);

$this->title = 'AirRenty.com';
?>
<div class="site-index">
  <div class="container-fluid"> 

    <?php if (!Yii::$app->user->isGuest) : ?>
      <div class="index-actions">
        <?= Html::a("New", ["create"], ["class" => "btn btn-primary"]); ?> 
      </div>
    <?php endif; ?>

    <?php
    echo GridView::widget([
        'dataProvider' => $airplaneDataProvider,
        'filterModel' => $airplaneSearchModel,
        'columns' => [
            [
                'attribute' => 'code_name',
            ],
            [
                'attribute' => 'name',
                'value' => function ($model) {
                  return Html::a(Html::encode($model->name), Url::to(['airplane/view', 'id' => $model->code_name]));
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'price',
                'value' => function($model) {
                  return
                          app\components\BBAmount::amountToString($model->price_original, $model->currency, ['numberOfDecimals' => 0]);
                },
            ],
            [
                'attribute' => 'min_price',
                'value' => function($model) {
                  return empty($model->min_price_original) ? "" :
                          app\components\BBAmount::amountToString($model->min_price_original, $model->currency, ['numberOfDecimals' => 0]);
                },
            ],
            [
                'attribute' => 'airport',
                'value' => function($model) {
                  return empty($model->airport) ? "" : ($model->airport->code_name . " - " . $model->airport->name);
                },
            ],
            [
                'attribute' => 'organisation',
                'value' => 'organisation.name'
            ],
            'seats_num',
            [
                'class' => ActionColumn::class,
                'visible' => !Yii::$app->user->isGuest,
                'header' => false,
                'noWrap' => true,
                'contentOptions' => [
                    'class' => 'actionColumn'
                ],
                'urlCreator' => function( $action, $model, $key, $index ) {

                  if ($action == "view") {

                    return Url::to(['view', 'id' => $model->code_name]);
                  } else if ($action == "update") {

                    return Url::to(['update', 'id' => $model->code_name]);
                  } else if ($action == "delete") {

                    return Url::to(['delete', 'id' => $model->code_name]);
                  }
                }
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'export' => false,
        'bordered' => false,
        'condensed' => true,
        'resizableColumns' => false,
        'layout' => "{items}\n{summary}\n<div class='pagination-wrap'>{pager}</div>",
    ]);
    ?>
  </div>
</div>