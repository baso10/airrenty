<?php
$airplanesCount = $airportModel->airplanesCount;
?>
<div class="map-popup">
  <table>
    <tr>
      <td class="map-popup-label" colspan="2"><?= $airportModel->code_name . " " . $airportModel->name ?></td>
    </tr>

    <?php if (!empty($airportModel->price_fuel91)) : ?>
      <tr>
        <td class="map-popup-label nowrap">UL 91: </td>
        <td class="map-popup-value nowrap">
          <?= app\components\BBAmount::amountToString($airportModel->price_fuel91, $airportModel->currency) ?>
          <span class="small text-muted">(<?= Yii::$app->formatter->asDate($airportModel->fuel_date) ?>)</span>
        </td>
      </tr>
    <?php endif; ?> 
      
      <?php if (!empty($airportModel->price_fuel100)) : ?>
      <tr>
        <td class="map-popup-label nowrap">Avgas 100L: </td>
        <td class="map-popup-value nowrap">
          <?= app\components\BBAmount::amountToString($airportModel->price_fuel100, $airportModel->currency) ?>
          <span class="small text-muted">(<?= Yii::$app->formatter->asDate($airportModel->fuel_date) ?>)</span>
        </td>
      </tr>
    <?php endif; ?> 

    

  </table>
  <table>
    <tr>
      <td class="map-popup-label"><?= Yii::t("app", "Airplanes for rent") ?>: </td>
      <td class="map-popup-value"><?= $airplanesCount ?></td>
    </tr>
  </table>
  
  <div>
    <?= \yii\helpers\Html::a(Yii::t("app", "More info"), ['airport/view', 'id' => $airportModel->code_name]) ?>
  </div>

</div>