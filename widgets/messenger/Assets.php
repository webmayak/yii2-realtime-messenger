<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/9/18
 * Time: 12:48 PM
 */

namespace pantera\messenger\widgets\messenger;


use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/socket.io.js',
        'js/script.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}