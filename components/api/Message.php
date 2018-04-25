<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/23/18
 * Time: 10:23 AM
 */

namespace pantera\messenger\components\api;


use pantera\messenger\models\MessengerMessages;
use yii\base\BaseObject;
use yii\db\JsonExpression;

class Message extends BaseObject
{
    /* @var MessengerMessages */
    private $_message;

    public function init(): void
    {
        parent::init();
        $this->_message = new MessengerMessages();
    }

    /**
     * Установить текст сообщения
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): Message
    {
        $this->_message->body = $body;
        return $this;
    }

    /**
     * Установить идентификатор треда
     * @param int $threadId
     * @return Message
     */
    public function setThreadId(int $threadId): Message
    {
        $this->_message->thread_id = $threadId;
        return $this;
    }

    /**
     * Установить идентификатор автора сообщения
     * @param int $userId
     * @return Message
     */
    public function setUserId(int $userId): Message
    {
        $this->_message->user_id = $userId;
        return $this;
    }

    /**
     * Установить флаг прикреплино ли сообщение
     * @param bool $isPinned
     * @return Message
     */
    public function setIsPinned(bool $isPinned): Message
    {
        $this->_message->is_pinned = $isPinned;
        return $this;
    }

    /**
     * Добавить дополнительные данные
     * @param array $data
     * @return Message
     */
    public function setData(array $data): Message
    {
        $this->_message->data = $data;
        return $this;
    }

    /**
     * Создание сообщения
     * @return MessengerMessages
     */
    public function create(): MessengerMessages
    {
        $this->_message->save();
        return $this->_message;
    }
}