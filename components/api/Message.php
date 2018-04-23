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

class Message extends BaseObject
{
    /* @var string */
    private $_body;
    /* @var int */
    private $_threadId;
    /* @var int */
    private $_userId;

    /**
     * Установить текст сообщения
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): Message
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * Установить идентификатор треда
     * @param int $threadId
     * @return Message
     */
    public function setThreadId(int $threadId): Message
    {
        $this->_threadId = $threadId;
        return $this;
    }

    /**
     * Установить идентификатор автора сообщения
     * @param int $userId
     * @return Message
     */
    public function setUserId(int $userId): Message
    {
        $this->_userId = $userId;
        return $this;
    }

    /**
     * Создание сообщения
     * @return MessengerMessages
     */
    public function send(): MessengerMessages
    {
        $message = new MessengerMessages();
        $message->user_id = $this->_userId;
        $message->thread_id = $this->_threadId;
        $message->body = $this->_body;
        $message->save();
        return $message;
    }
}