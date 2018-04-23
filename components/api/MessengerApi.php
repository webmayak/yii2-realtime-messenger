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
}