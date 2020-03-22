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

    <div class="index-actions">
      <?= Html::a("New", ["create"], ["class" => "btn btn-primary"]); ?> 
    </div>
    

    <?php
    echo GridView::widget([
        'dataProvider' => $searchDataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'web_page',
            ],
            [
                'attribute' => 'description',
            ],
            [
                'attribute' => 'airport',
                'value' => 'airport.name'
            ],
            [
                'class' => ActionColumn::class,
                'header' => false,
                'noWrap' => true,
                'contentOptions' => [
                    'class' => 'actionColumn'
                ],
                'visibleButtons' => [
                    'view' => false,
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'export' => false,
        'bordered' => false,
        'condensed' => true,
        'layout' => "{items}\n{summary}\n<div class='pagination-wrap'>{pager}</div>",
    ]);
    ?>
  </div>
</div>