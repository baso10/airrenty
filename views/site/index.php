<?php
/* @var $this View */

use yii\web\View;
use app\assets\MapAsset;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

MapAsset::register($this);

$this->title = 'AirRenty.com';
?>
<div class="site-index">
  <div class="container"> 

    <div style="text-align: center;">
      <h5><?= Yii::t("app", "Rent your perfect and affordable airplane!") ?></h5>

    </div>

    <div id="map-wrapper">
      <div id="map"></div>
    </div>
    

    <?php foreach ($airportModels as $airportModel) : ?>
      <div id="map-popup-<?= $airportModel->id ?>" style="display: none;">
        <?=
        $this->render("_map_popup", [
            "airportModel" => $airportModel
        ]);
        ?>
      </div>
    <?php endforeach; ?>

    <?php ob_start(); ?>
    <script>

      var map = L.map('map').setView([46.7985, 8.0318], 8);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);

      var LeafIcon = L.Icon.extend({
        options: {
          iconSize: [24, 24]
        }
      });

      var airportIcon = new LeafIcon({iconUrl: '<?= yii\helpers\Url::to("@web/img/icons8-airport-24.png") ?>'});

<?php
foreach ($airportModels as $airportModel) :
  $popupHtml = $this->render("_map_popup", [
      "airportModel" => $airportModel
  ]);
  ?>
        L.marker([<?= $airportModel->lat ?>, <?= $airportModel->lon ?>], {icon: airportIcon})
                .bindPopup($("#map-popup-<?= $airportModel->id ?>").html()).addTo(map);
<?php endforeach; ?>

    </script>

    <?php
    $script = str_replace("<script>", "", ob_get_clean());
    $script = str_replace("</script>", "", $script);
    $this->registerJs($script);
    ?>

  </div>
</div>