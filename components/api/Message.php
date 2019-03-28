<?php

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
    private $message;
    /* @var array Массив масивов файлов которые нужно скопировать в медиа нового сообщения */
    private $files = [];

    public function init()
    {
        parent::init();
        $this->message = Yii::createObject(MessengerMessages::class);
        if (Yii::$app instanceof Application) {
            $this->message->user_id = Yii::$app->user->id;
        }
    }

    /**
     * Установить сценарий для модели
     * @param string $scenario
     * @return Message
     */
    public function setScenario(string $scenario): Message
    {
        $this->message->setScenario($scenario);
        return $this;
    }

    /**
     * Установить текст сообщения
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): Message
    {
        $this->message->body = $body;
        return $this;
    }

    /**
     * Установить идентификатор треда
     * @param int $threadId
     * @return Message
     */
    public function setThreadId(int $threadId): Message
    {
        $this->message->thread_id = $threadId;
        return $this;
    }

    /**
     * Установить идентификатор автора сообщения
     * @param int|null $userId
     * @return Message
     */
    public function setUserId($userId): Message
    {
        $this->message->user_id = $userId;
        return $this;
    }

    /**
     * Добавить дополнительные данные
     * @param array $data
     * @return Message
     */
    public function setData(array $data): Message
    {
        $this->message->data = $data;
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
        $this->files[] = [
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
        if ($this->message->save()) {
            foreach ($this->files as $data) {
                if (array_key_exists('files', $data) && array_key_exists('bucket', $data)) {
                    foreach ($data['files'] as $file) {
                        Yii::$app->mediaApi->initNewMedia($this->message, $data['bucket'])
                            ->setFile($file)
                            ->create();
                    }
                }
            }
            $this->message->thread->updateAttributes([
                'last_message_at' => new Expression('NOW()'),
            ]);
        }
        return $this->message;
    }
}
