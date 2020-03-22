<?php

/*
 * Copyright 2020 baso10.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Domen Basic
 * @since 1.0
 */
class AppIE9Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $jsOptions = ['condition' => 'lt IE 9', 'position' => \yii\web\View::POS_HEAD];
    public $js = [
        'js/html5.js',
    ];
    public $depends = [
    ];
}
