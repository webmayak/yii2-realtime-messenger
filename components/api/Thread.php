<?php

namespace pantera\messenger\components\api;

use pantera\messenger\models\MessengerThreads;
use pantera\messenger\models\MessengerThreadUser;
use Yii;
use yii\base\BaseObject;
use yii\web\Application;

class Thread extends BaseObject
{
    /* @var MessengerThreads */
    private $thread;
    /* @var array Массив пользователей для которых диалог будет доступен */
    private $userIds = [];

    public function init()
    {
        parent::init();
        $this->thread = Yii::createObject(MessengerThreads::class);
        if (Yii::$app instanceof Application) {
            $this->thread->from = Yii::$app->user->id;
        }
    }

    /**
     * Добавить пользователей для которых будет доступен диалог
     * @param array $userIds
     * @return Thread
     */
    public function setUserIds(array $userIds): self
    {
        $this->userIds = $userIds;
        return $this;
    }

    /**
     * Установка ключа
     * @param string $key
     * @return Thread
     */
    public function setKey(string $key): self
    {
        $this->thread->key = $key;
        return $this;
    }

    /**
     * Добавить доп данные
     * @param $data
     * @return Thread
     */
    public function load($data): self
    {
        $this->thread->load($data, '');
        return $this;
    }

    /**
     * Установить заголовок диалога
     * @param $subject
     * @return $this
     */
    public function setSubject($subject): self
    {
        $this->thread->subject = $subject;
        return $this;
    }

    /**
     * Создание треда
     * @return MessengerThreads
     * @throws \yii\base\InvalidConfigException
     */
    public function create(): MessengerThreads
    {
        if ($this->thread->save()) {
            foreach ($this->userIds as $userId) {
                $relation = Yii::createObject(MessengerThreadUser::className());
                $relation->thread_id = $this->thread->id;
                $relation->user_id = $userId;
                $relation->save();
            }
            $this->thread->trigger(MessengerThreads::EVENT_AFTER_CREATE);
        }
        return $this->thread;
    }
}
