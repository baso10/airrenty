<?php
/* @var $this View */

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "AirRenty.com";
?>
<div class="site-index">
  <div class="container-fluid"> 
    <div class="airplane-view-wrapper">

      <div>
        <?=
        !$model->airport ? "" :
                Html::a(Html::tag("i", "", ["class" => "fas fa-long-arrow-alt-left"]) . " " . Yii::t("app", "Back to airport"), ["airport/view", "id" => $model->airport->code_name]);
        ?>
      </div>
      
      <h3><?= Html::encode($model->code_name) ?> - <?= Html::encode($model->name) ?></h3>

      <?php if (!empty($model->image_url)) : ?>
        <div class="airplane-view-image">
          <?= Html::img($model->image_url, ["style" => "height: 200px;"]) ?>
          <div>
            <span class="small text-muted">* <?= Yii::t("app", "Not actual image") ?></span>
          </div>
        </div>
      <?php endif; ?>
      
      <hr>

      <p><?= Yii::t("app", "Price per hour") ?> *</p>

      <?php
      echo ($model->min_price_original ? (app\components\BBAmount::amountToString($model->min_price_original, $model->currency, ['numberOfDecimals' => 0])
              . ' - ') : '') .
      app\components\BBAmount::amountToString($model->price_original, $model->currency, ['numberOfDecimals' => 0]);
      ?>
      <span class="small text-muted">inkl 7.7% MwSt</span>

      <?php if (!empty($model->description)) : ?>
      <div class="airplane-view-description">
          <?= nl2br(Html::encode($model->description)) ?>
        </div>
      <?php endif; ?>
      
      <hr>


      <p><?= Yii::t("app", "To rent this plane, please contact") ?>:</p>
      <table class="center-table">
        <tr>
          <td><?= nl2br(Html::encode($model->organisation->description)) ?></td>
        </tr>
        <?php if (empty($model->organisation->description)) : ?>
          <tr>
            <td><?= Html::encode($model->organisation->name) ?></td>
          </tr>
        <?php endif; ?>
        <tr>
          <td><?= Html::a(Html::encode($model->organisation->web_page), $model->organisation->web_page, ["target" => "_blank"]) ?></td>
        </tr>

      </table>
      <br>
      <?php if (!empty($model->airport)) : ?>
        <p><?= Yii::t("app", "Airplane is parked at") ?>:</p>

        <?= Html::encode($model->airport->code_name) ?> - <?= Html::encode($model->airport->name) ?>
      <?php endif; ?>

      <br>
      <br>

      <?php if (!empty($model->wiki_url)) : ?>
      <div class="airplane-view-wiki">
          <?= Html::a(Yii::t("app", "Details about airplane"), $model->wiki_url, ["target" => "_blank"]) ?>
        </div>
      <?php endif; ?>

      <div>
        <span class="small text-muted">* <?= Yii::t("app", "All information without guarantee. Actual price may vary. Please concact organisation for details") ?></span>
      </div>

    </div>
  </div>
</div>