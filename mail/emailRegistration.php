<?php

use yii\helpers\Html;

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->confirmation_token]);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>
        <h1 style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-size: 40px;font-style: normal;font-weight: bold;line-height: 125%;letter-spacing: -1px;text-align: left;color: #606060;">
          Welcome to <?= Yii::$app->params["site_name"] ?>
        </h1>

        <div style="padding: 20px; border: 1px solid rgb(204, 204, 204); border-radius: 5px;">
          <table>
            <tr>
              <td>
                <p style="color: #606060;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">

                  <?= Yii::t("email", "Your account has been successfully created."); ?>
                </p>
                <br/>
                <br/>
                <p><?= Yii::t("email", "Follow the link below to verify your email"); ?></p>

                <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
                <br/>
                <br/>



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