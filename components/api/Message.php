<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/23/18
 * Time: 10:23 AM
 */

namespace pantera\messenger\components\api;


use pantera\messenger\models\MessengerMessages;
use Yii;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\web\Application;
use function array_key_exists;

class Message extends BaseObject
{
    /* @var MessengerMessages */
    private $_message;
    /* @var array Массив масивов файлов которые нужно скопировать в медиа нового сообщения */
    private $_files = [];

    public function init()
    {
        parent::init();
        $this->_message = Yii::createObject(MessengerMessages::className());
        if (Yii::$app instanceof Application) {
            $this->_message->user_id = Yii::$app->user->id;
        }
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
     * Добавить файлы к сообщению которые есть на диске
     * будут просто скопированы в модуль медиа
     * @param array $files
     * @param string $bucket Название группы в которую отправить файл
     * @return Message
     */
    public function setFiles(array $files, $bucket): Message
    {
        $this->_files[] = [
            'files' => $files,
            'bucket' => $bucket,
        ];
        return $this;
    }

    /**
     * Создание сообщения
     * @return MessengerMessages
     */
    public function create(): MessengerMessages
    {
        if ($this->_message->save()) {
            foreach ($this->_files as $data) {
                if (array_key_exists('files', $data) && array_key_exists('bucket', $data)) {
                    foreach ($data['files'] as $file) {
                        Yii::$app->mediaApi->initNewMedia($this->_message, $data['bucket'])
                            ->setFile($file)
                            ->create();
                    }
                }
            }
            $this->_message->thread->updateAttributes([
                'last_message_at' => new Expression('NOW()'),
            ]);
        }
        return $this->_message;
    }
}