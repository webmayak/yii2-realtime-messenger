<?php

namespace pantera\messenger\api;

use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use Yii;
use yii\helpers\ArrayHelper;

class ModuleApi extends \yii\base\Module
{
    /* @var string Адрес сокет сервера */
    public $nodeServer = 'http://127.0.0.1:8080/';
    /* @var boolean Флаг нужно ли показывать диалоги без сообщений */
    public $threadSearchShowEmpty = false;
    /* @var boolean Флаг нужно ли для комуникации с нодовй использовать редис */
    public $useRedis = false;
    /* @var array Массив настроек для редиса */
    public $redisConfig = [];
    /* @var array Массив дефолтных настроек редиса */
    private $_redisDefaultConfig = [
        'host' => 'localhost',
        'port' => '6379',
    ];

    public function init()
    {
        parent::init();
        $this->redisConfig = ArrayHelper::merge($this->redisConfig, $this->_redisDefaultConfig);
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
