<?php

namespace pantera\messenger\components\api;

use Exception;
use pantera\messenger\api\ModuleApi;
use pantera\messenger\api\traits\FindModelTrait;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use pantera\messenger\models\MessengerThreadUser;
use pantera\messenger\models\MessengerViewed;
use pantera\messenger\Module;
use pantera\messenger\traits\ModuleTrait;
use Redis;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

class MessengerApi extends Component
{
    use FindModelTrait;
    use ModuleTrait;

    public function hasThreadsByUser(IdentityInterface $user)
    {
        return $this->getBaseQueryForFindByUser($user)
            ->exists();
    }

    /**
     * Получить последний активный диалог пользователя
     * @param IdentityInterface $user
     * @return MessengerThreads|null
     */
    public function getLastThreadByUser(IdentityInterface $user)
    {
        $sortExpression = new Expression('IF(' . MessengerThreads::tableName() . '.`last_message_at`,
         ' . MessengerThreads::tableName() . '.`last_message_at`,
          ' . MessengerThreads::tableName() . '.`created_at`) DESC');
        return $this->getBaseQueryForFindByUser($user)
            ->orderBy($sortExpression)
            ->one();
    }

    /**
     * Инициализирует процес создания сообщения
     * @return Message
     * @throws InvalidConfigException
     */
    public function createMessage(): Message
    {
        return Yii::createObject(Message::class);
    }

    /**
     * Инициализирует процес создания треда
     * @return Thread
     * @throws InvalidConfigException
     */
    public function createThread(): Thread
    {
        return Yii::createObject(Thread::class);
    }

    /**
     * Получить количество сообщений в переписки
     * @param int $threadId
     * @return int
     */
    public function getCountMessageByThreadId(int $threadId): int
    {
        return MessengerMessages::find()
            ->where(['=', 'thread_id', $threadId])
            ->count();
    }

    /**
     * Получить список пользователей участвующих в переписки
     * @param int $threadId
     * @param bool $onlyIds Флаг что нужно вернуть только идентификаторы
     * @param bool $isAdmin Флаг что пользователь админ и ему не надо делать проверку на доступность
     * @return array
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function getUserListInThread(int $threadId, bool $onlyIds = false, $isAdmin = false): array
    {
        $thread = $this->findThreadModel($threadId, $isAdmin);
        $userIds = ArrayHelper::getColumn($thread->users, function (IdentityInterface $user) {
            return $user->getId();
        });
        if ($onlyIds) {
            return $userIds;
        }
        /* @var $module Module */
        $module = Yii::$app->getModule('messenger');
        $authorEntity = Yii::createObject($module->authorEntity);
        return $authorEntity::find()
            ->where(['IN', 'id', $userIds])
            ->all();
    }

    /**
     * Получить последние сообщение в переписки
     * @param int $threadId
     * @return MessengerMessages
     */
    public function getLastMessageInThread(int $threadId)
    {
        return MessengerMessages::find()
            ->where(['=', 'thread_id', $threadId])
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->one();
    }

    /**
     * Получить количество сообщений которые пользователь невидел
     * @param IdentityInterface $user Пользователь для которого хотим получить
     * @return int
     */
    public function getCountNotViewedForUser(IdentityInterface $user): int
    {
        $subQuery = MessengerViewed::find()
            ->select(MessengerViewed::tableName() . '.message_id')
            ->andWhere(['=', MessengerViewed::tableName() . '.user_id', $user->getId()]);
        return MessengerMessages::find()
            ->joinWith(['thread', 'thread.relationWithUsers'])
            ->andWhere([
                'OR',
                ['!=', MessengerMessages::tableName() . '.user_id', $user->getId()],
                ['IS', MessengerMessages::tableName() . '.user_id', null],
            ])
            ->andWhere(['IN', MessengerThreadUser::tableName() . '.user_id', $user->getId()])
            ->andWhere(['NOT IN', MessengerMessages::tableName() . '.id', $subQuery])
            ->count();
    }

    /**
     * Получить количество сообщений которые пользователь невидел в конкретном диалоге
     * @param IdentityInterface $user
     * @param int $threadId
     * @return int
     */
    public function getCountNotViewedForUserInThread(IdentityInterface $user, int $threadId): int
    {
        $subQuery = MessengerViewed::find()
            ->joinWith(['message'])
            ->select(MessengerViewed::tableName() . '.message_id')
            ->andWhere(['=', MessengerViewed::tableName() . '.user_id', $user->getId()])
            ->andWhere(['=', MessengerMessages::tableName() . '.thread_id', $threadId]);
        return MessengerMessages::find()
            ->andWhere(['!=', MessengerMessages::tableName() . '.user_id', $user->getId()])
            ->andWhere(['IN', MessengerMessages::tableName() . '.thread_id', $threadId])
            ->andWhere(['NOT IN', MessengerMessages::tableName() . '.id', $subQuery])
            ->count();
    }

    /**
     * Отправить сообщения в сокет
     * @param MessengerMessages $model
     * @param bool $isAdmin Флаг что пользователь админ и ему не надо делать проверку на доступность
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function sendToSocket(MessengerMessages $model, $isAdmin = false)
    {
        /* @var $moduleApi ModuleApi */
        $moduleApi = Yii::$app->getModule('messenger-api');
        $userIds = $this->getUserListInThread($model->thread->id, true, $isAdmin);
        $params = [
            'notifiedUserIds' => $userIds,
            'threadId' => $model->thread->id,
            'messageId' => $model->id,
        ];
        try {
            if ($moduleApi->useRedis) {
                $redis = new Redis();
                $redis->pconnect($moduleApi->redisConfig['host'], $moduleApi->redisConfig['port']);
                if (array_key_exists('password', $moduleApi->redisConfig)) {
                    $redis->auth($moduleApi->redisConfig['password']);
                }
                $params = Json::encode($params);
                $redis->publish($moduleApi->redisConfig['chanel'], $params);
            } else {
                $client = new Client(['baseUrl' => $moduleApi->nodeServer]);
                $client->post('/new-message', $params)->send();
            }
        } catch (Exception $e) {
        }
    }
}
