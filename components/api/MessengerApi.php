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
    public function getThreadIdByKey(string $key): int
    {
        return MessengerThreads::find()
            ->select('id')
            ->where(['=', 'key', $key])
            ->scalar();
    }

    /**
     * Сбросить флаг приклеивания у всех сообщений в переписки
     * @param int $threadId
     */
    public function resetIsPinnedByThreadId(int $threadId): void
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
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserListInThread(int $threadId): array
    {
        $userIds = MessengerMessages::find()
            ->select('user_id')
            ->distinct()
            ->where(['=', 'thread_id', $threadId])
            ->column();
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
    public function getLastMessageInThread(int $threadId): ?MessengerMessages
    {
        return MessengerMessages::find()
            ->where(['=', 'thread_id', $threadId])
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->one();
    }
}