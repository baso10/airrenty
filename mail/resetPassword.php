<?php

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->recovery_token]);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
        <h1 style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-size: 40px;font-style: normal;font-weight: bold;line-height: 125%;letter-spacing: -1px;text-align: left;color: #606060;">
          <?= Yii::t("email", "Reset password") ?>
        </h1>

        <div style="padding: 20px; border: 1px solid rgb(204, 204, 204); border-radius: 5px;">
          <table>
            <tr>
              <td>
                <p style="color: #606060;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">

                  <p><?= Yii::t("email", "Follow the link below to reset your password") ?>:</p>
                  <br/>
                  <?= Html::a(Html::encode($resetLink), $resetLink) ?>

                </p>
              </td>
            </tr>
          </table>

        </div>
        <br>

        <?php include __DIR__ . '/footer.php'; ?>

      </td>
    </tr>
  </tbody>
</table>