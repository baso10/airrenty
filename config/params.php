<?php

$customConfig = [];
if (file_exists(__DIR__ . '/../customConfig.php')) {
  $customConfig = include (__DIR__ . '/../customConfig.php');
}
return \yii\helpers\ArrayHelper::merge([
            'adminEmail' => '',
            'email_from' => '',
            'email_replyTo' => '',
            'email_disabled' => true,
            'user_register_disabled' => false,
            'user_enable_confirmation' => false,
            'user_recovery_disabled' => false,
            'user_recover_token_valid_time' => 10800, /* 3h */
            'site_name' => '',
            'site_description' => '',
            'site_keywords' => '',
            'language' => '',
            'components' => [
            ],
            'tracking_js' => '',
            'bsVersion' => '4.x'
                ], $customConfig);
