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
    <div class="airport-view-wrapper">
      <?= Html::a(Html::tag("i", "", ["class" => "fas fa-long-arrow-alt-left"]) . " " . Yii::t("app", "Back to map"), ["/"]); ?>

      <h3><?= Html::encode($model->code_name) ?> - <?= Html::encode($model->name) ?></h3>

      <?= Html::a(Html::encode($model->web_page), $model->web_page, ["target" => "_blank"]) ?>

      <table class="center-table fuel-list-airport-table">
        <?php if (!empty($model->price_fuel91)) : ?>
          <tr>
            <td class="map-popup-label">UL 91: </td>
            <td class="map-popup-value">
              <?= app\components\BBAmount::amountToString($model->price_fuel91, $model->currency) ?>
              <span class="small text-muted">(<?= Yii::$app->formatter->asDate($model->fuel_date) ?>)</span>
            </td>
          </tr>
        <?php endif; ?> 

        <?php if (!empty($model->price_fuel100)) : ?>
          <tr>
            <td class="map-popup-label">Avgas 100L: </td>
            <td class="map-popup-value">
              <?= app\components\BBAmount::amountToString($model->price_fuel100, $model->currency) ?>
              <span class="small text-muted">(<?= Yii::$app->formatter->asDate($model->fuel_date) ?>)</span>
            </td>
          </tr>
        <?php endif; ?> 
      </table>
    </div>

    <?php
    echo GridView::widget([
        'dataProvider' => $airplaneDataProvider,
//        'filterModel' => $airplaneSearchModel,
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