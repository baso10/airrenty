<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Nav;

$imgUrl = "@web/img/icons8-airport-24.png";
NavBar::begin([
    'brandLabel' => Html::img($imgUrl, ["style" => "width: 30px;"]) . Html::tag("span", 'AirRenty.com', ["class" => "headerBrandLabel"]),
    'brandUrl' => Yii::$app->homeUrl,
    'brandOptions' => [
        'class' => 'headerBrand'
    ],
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-light bg-light justify-content-center',
    ],
//        'renderInnerContainer' => false,
    'collapseOptions' => [
        'class' => 'justify-content-center headerCollapseDiv',
    ]
]);
$menuItems = [
    ['label' => Yii::t("app", "Map"), 'url' => '/'],
    ['label' => Yii::t("app", "Airplanes"), 'url' => ['/airplane/index']],
    ['label' => 'Organisations', 'url' => ['/organisation/index'], 'visible' => !Yii::$app->user->isGuest],
    ['label' => Yii::t("app", "About"), 'url' => ['/site/about']],
    ['label' => Yii::t("app", "Register"), 'url' => ['/site/register']],
];
if (!Yii::$app->user->isGuest) {
  $menuItems[] = [
      'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
      'url' => ['/site/logout'],
      'linkOptions' => ['data-method' => 'post']
  ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
?>
