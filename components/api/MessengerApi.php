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
use pantera\messenger\Module;
use Yii;
use yii\base\Component;
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
     * Получить идентификатор треда по клучю
     * @param string $key
     * @return int
     */
    public function getThreadIdByKey(string $key)
    {
        $result = MessengerThreads::find()
            ->select('id')
            ->where(['=', 'key', $key])
            ->scalar();
        return $result ?: null;
    }

    /**
     * Сбросить флаг приклеивания у всех сообщений в переписки
     * @param int $threadId
     * @return void
     */
    public function resetIsPinnedByThreadId(int $threadId)
    {
        MessengerMessages::updateAll([
            'is_pinned' => 0,
        ], [
            'AND',
            ['=', 'thread_id', $threadId],
            ['=', 'is_pinned', 1],
        ]);
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
     * @param int $userId
     * @return int
     */
    public function getCountNotViewedForUser(int $userId): int
    {
        return 1;
    }

    /**
     * Получить количество сообщений которые пользователь невидел в конкретном диалоге
     * @param int $userId
     * @param int $threadId
     * @return int
     */
    public function getCountNotViewedForUserInThread(int $userId, int $threadId): int
    {
        return 0;
    }
}