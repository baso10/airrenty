<?php
/* @var $this View */

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

$this->title = "AirRenty.com";
?>
<div class="site-index">
  <div class="container-fluid"> 

    <h2><?= Yii::t("app", "Organisation") ?></h2>
    <?php
    echo GridView::widget([
        'dataProvider' => $orgarnisationDataProvider,
        'columns' => [
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'web_page',
                'value' => function ($model) {
                  $newPageIcon = Html::tag("i", "", ["class" => "fas fa-external-link-alt"]);
                  return Html::a(Html::encode($model->name) . " " . $newPageIcon, Url::to($model->web_page), ["target" => "_blank"]);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'description',
                'value' => function ($model) {
                  return nl2br(Html::encode($model->description));
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'airport_id',
                'value' => 'airport.name'
            ],
            [
                'class' => ActionColumn::class,
                'controller' => 'organisation',
                'template'=>'{update}',
                'header' => false,
                'noWrap' => true,
                'contentOptions' => [
                    'class' => 'actionColumn'
                ],
            ],
        ],
        'responsiveWrap' => false,
        'hover' => true,
        'export' => false,
        'bordered' => false,
        'condensed' => true,
        'layout' => "{items}\n<div class='pagination-wrap'>{pager}</div>",
    ]);
    ?>

    <h2><?= Yii::t("app", "Airplanes") ?></h2>
    
    <?php if (!Yii::$app->user->isGuest) : ?>
      <div class="index-actions">
        <?= Html::a(Yii::t("app", "Add airplane"), ["airplane/create"], ["class" => "btn btn-primary"]); ?> 
      </div>
    <?php endif; ?>
    <?php
    echo GridView::widget([
        'dataProvider' => $airplaneDataProvider,
        'columns' => [
            [
                'attribute' => 'code_name',
                'label' => Html::tag("span", Yii::t("app", "Registration"), ["class" => "longTitle"]) .
                Html::tag("span", Yii::t("app", "Tail number"), ["class" => "shortTitle"]),
                'encodeLabel' => false,
                'contentOptions' => [
                    "class" => "nowrap"
                ],
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
                'contentOptions' => [
                    "class" => "nowrap"
                ],
            ],
            [
                'attribute' => 'min_price',
                'value' => function($model) {
                  return empty($model->min_price_original) ? "" :
                          app\components\BBAmount::amountToString($model->min_price_original, $model->currency, ['numberOfDecimals' => 0]);
                },
                'contentOptions' => [
                    "class" => "nowrap"
                ],
            ],
            [
                'attribute' => 'organisation',
                'value' => 'organisation.name'
            ],
            [
                'attribute' => 'seats_num',
                'label' => Html::tag("span", Yii::t("app", "Seats"), ["class" => "longTitle"]) .
                Html::tag("span", Yii::t("app", "Seats no"), ["class" => "shortTitle"]),
                'encodeLabel' => false,
            ],
            [
                'class' => ActionColumn::class,
                'template'=>'{update} {delete}',
                'header' => false,
                'noWrap' => true,
                'contentOptions' => [
                    'class' => 'actionColumn'
                ],
                'urlCreator' => function( $action, $model, $key, $index ) {

                  if ($action == "view") {

                    return Url::to(['airplane/view', 'id' => $model->code_name]);
                  } else if ($action == "update") {

                    return Url::to(['airplane/update', 'id' => $model->code_name]);
                  } else if ($action == "delete") {

                    return Url::to(['airplane/delete', 'id' => $model->code_name]);
                  }
                }
            ],
        ],
        'responsiveWrap' => false,
        'hover' => true,
        'export' => false,
        'bordered' => false,
        'condensed' => true,
        'layout' => "{items}\n{summary}\n<div class='pagination-wrap'>{pager}</div>",
    ]);
    ?>

  </div>
</div>