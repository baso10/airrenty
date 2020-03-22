<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Nav;

$this->beginContent('@app/views/layouts/main.php');
?>


<div class="page-wrapper">

  <header>

    <?= $this->render("menu") ?>



  </header>




  <div class="content-wrapper">
    <div class="content">
      <?= $this->render('/_alert') ?>

      <?= $content; ?>
    </div>
  </div>

  <footer class="footer mt-auto">
    <div class="copyright bg-white">
      <p>
        Copyright &copy; <span id="copy-year">2019 - <?= date("Y"); ?></span> AirRenty.com.
        
        <small><?= Html::a("Impressum", ["site/legal"], ["style" => "color: grey; padding-left: 10px;"]); ?></small>
      </p>
    </div>
  </footer>
</div> 

<?php $this->endContent(); ?>

