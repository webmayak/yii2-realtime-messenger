<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/23/18
 * Time: 2:28 PM
 */

namespace pantera\messenger\components\api;


use pantera\messenger\models\MessengerThreads;
use pantera\messenger\models\MessengerThreadUser;
use Yii;
use yii\base\BaseObject;
use yii\web\Application;

class Thread extends BaseObject
{
    /* @var MessengerThreads */
    private $_thread;
    /* @var array Массив пользователей для которых диалог будет доступен */
    private $_userIds = [];

    public function init()
    {
        parent::init();
        $this->_thread = Yii::createObject(MessengerThreads::className());
        if (Yii::$app instanceof Application) {
            $this->_thread->from = Yii::$app->user->id;
        }
    }

    /**
     * Добавить пользователей для которых будет доступен диалог
     * @param array $userIds
     * @return Thread
     */
    public function setUserIds(array $userIds): self
    {
        $this->_userIds = $userIds;
        return $this;
    }

    /**
     * Установка ключа
     * @param string $key
     * @return Thread
     */
    public function setKey(string $key): self
    {
        $this->_thread->key = $key;
        return $this;
    }

    /**
     * Добавить доп данные
     * @param $data
     * @return Thread
     */
    public function load($data): self
    {
        $this->_thread->load($data, '');
        return $this;
    }

    /**
     * Установить заголовок диалога
     * @param $subject
     * @return $this
     */
    public function setSubject($subject): self
    {
        $this->_thread->subject = $subject;
        return $this;
    }

    /**
     * Создание треда
     * @return MessengerThreads
     * @throws \yii\base\InvalidConfigException
     */
    public function create(): MessengerThreads
    {
        if ($this->_thread->save()) {
            foreach ($this->_userIds as $userId) {
                $relation = Yii::createObject(MessengerThreadUser::className());
                $relation->thread_id = $this->_thread->id;
                $relation->user_id = $userId;
                $relation->save();
            }
        }
        return $this->_thread;
    }
}