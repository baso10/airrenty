<?php
/* @var $this View */

use yii\web\View;
use app\assets\MapAsset;

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

<?php
if (Yii::$app->language == "sl") {
  echo "var coord = [46.119944, 14.815333];";
  echo "var zoom = 8.5;";
} else {
  echo "var coord = [46.7985, 8.0318];";
  echo "var zoom = 8;";
}
?>

      var map = L.map('map', {zoomSnap: 0.25}).setView(coord, zoom);

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