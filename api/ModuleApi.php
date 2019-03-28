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
    /* @var string Роль пользователя для котой доступно получать любые данные */
    public $adminRole;
    /* @var array Массив доступынх для загрузки расширений файлов */
    public $mediaAvailableExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    /* @var array Массив дефолтных настроек редиса */
    private $redisDefaultConfig = [
        'host' => 'localhost',
        'port' => '6379',
        'chanel' => 'chat',
    ];

    /**
     * Проверить является ли текуший пользователь админом
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->adminRole && Yii::$app->user->can($this->adminRole);
    }

    public function init()
    {
        parent::init();
        $this->redisConfig = ArrayHelper::merge($this->redisDefaultConfig, $this->redisConfig);
        if (Yii::$container->has(MessengerMessages::class) == false) {
            Yii::$container->set(MessengerMessages::class, [
                'class' => \pantera\messenger\api\models\MessengerMessages::class,
            ]);
        }
        if (Yii::$container->has(MessengerThreads::class) == false) {
            Yii::$container->set(MessengerThreads::class, [
                'class' => \pantera\messenger\api\models\MessengerThreads::class,
            ]);
        }
    }
}
