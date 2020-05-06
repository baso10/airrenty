<?php

use yii\bootstrap4\Alert;

$flashes = [];
if(!Yii::$app->user->isGuest) {
  $flashes = Yii::$app->session->getAllFlashes();
}
?>
<?php if (!empty($flashes)): ?>
  <div class="row" style="margin-top: 5px;">
    <div class="col-12">
      <?php foreach ($flashes as $type => $message): ?>
        <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
          <?=
          Alert::widget([
              'options' => ['class' => 'alert-dismissible alert-' . $type],
              'body' => $message
          ])
          ?>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
<?php endif; ?>
