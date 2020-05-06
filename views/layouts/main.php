<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use app\assets\AppIE9Asset;

AppAsset::register($this);
AppIE9Asset::register($this);

$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Yii::$app->user->isGuest ? '' : Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <meta name="description" content="<?= Yii::$app->params["site_description"] ?>">
    <meta name="keywords" content="<?= Yii::$app->params["site_keywords"] ?>">

    <?php $this->head() ?>
    <?= Yii::$app->params["tracking_js"] ?>
  </head>
  <body id="body">
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
