<?php

namespace pantera\messenger\api;

use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use Yii;

class ModuleApi extends \yii\base\Module
{
    /* @var string Адрес сокет сервера */
    public $nodeServer = 'http://127.0.0.1:8080/';
    /* @var boolean Флаг нужно ли показывать диалоги без сообщений */
    public $threadSearchShowEmpty = false;
    /* @var boolean Флаг нужно ли для комуникации с нодовй использовать редис */
    public $useRedis = false;

    public function init()
    {
        parent::init();
        if (Yii::$container->has(MessengerMessages::className()) == false) {
            Yii::$container->set(MessengerMessages::className(), [
                'class' => \pantera\messenger\api\models\MessengerMessages::className(),
            ]);
        }
        if (Yii::$container->has(MessengerThreads::className()) == false) {
            Yii::$container->set(MessengerThreads::className(), [
                'class' => \pantera\messenger\api\models\MessengerThreads::className(),
            ]);
        }
    }
}
