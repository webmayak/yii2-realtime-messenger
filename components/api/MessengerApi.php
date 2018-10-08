<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/23/18
 * Time: 10:20 AM
 */

namespace pantera\messenger\components\api;


use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use pantera\messenger\models\MessengerThreadUser;
use pantera\messenger\models\MessengerViewed;
use pantera\messenger\Module;
use Yii;
use yii\base\Component;
use yii\web\IdentityInterface;
use const SORT_DESC;

class MessengerApi extends Component
{
    /**
     * Инициализирует процес создания сообщения
     * @return Message
     * @throws \yii\base\InvalidConfigException
     */
    public function createMessage(): Message
    {
        return Yii::createObject(Message::className());
    }

    /**
     * Инициализирует процес создания треда
     * @return Thread
     * @throws \yii\base\InvalidConfigException
     */
    public function createThread(): Thread
    {
        return Yii::createObject(Thread::className());
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
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserListInThread(int $threadId, bool $onlyIds = false): array
    {
        $userIds = MessengerMessages::find()
            ->select('user_id')
            ->distinct()
            ->where(['=', 'thread_id', $threadId])
            ->column();
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
            ->andWhere(['!=', MessengerMessages::tableName() . '.user_id', $user->getId()])
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
}