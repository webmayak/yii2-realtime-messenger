<?php

namespace pantera\messenger\api;

use Closure;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use Yii;
use yii\base\Module;
use yii\helpers\ArrayHelper;

class ModuleApi extends Module
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
    /* @var bool Флаг нужно ли вырезать эмоджи в сообщениях */
    public $truncateEmoji = true;
    /* @var bool|array Конфиг постраничной навигации поиска диалогов */
    public $threadsSearchPagination = false;
    /* @var Closure|null Возможность модифицировать запрос на выборку диалогов */
    public $threadsSearchQueryModifier;
    /* @var Closure|null Возможность модифицировать запрос на выборку сообщений в диалоге */
    public $messagesSearchQueryModifier;
    /* @var Closure|null Возможность модифицировать запрос на выборку диалогов у уведомлении */
    public $notificationSearchQueryModifier;

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
