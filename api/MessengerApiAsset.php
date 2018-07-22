<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/22/18
 * Time: 1:08 PM
 */

namespace pantera\messenger\api;


use yii\web\AssetBundle;

class MessengerApiAsset extends AssetBundle
{
    public $js = [
        'socket.io.js',
    ];

    public function init()
    {
        parent::init();
        $this->sourcePath = __DIR__ . '/assets';
    }
}