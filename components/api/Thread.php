<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/23/18
 * Time: 2:28 PM
 */

namespace pantera\messenger\components\api;


use pantera\messenger\models\MessengerThreads;
use Yii;
use yii\base\BaseObject;
use yii\web\Application;

class Thread extends BaseObject
{
    /* @var MessengerThreads */
    private $_thread;

    public function init()
    {
        parent::init();
        $this->_thread = Yii::createObject(MessengerThreads::className());
        if (Yii::$app instanceof Application) {
            $this->_thread->from = Yii::$app->user->id;
        }
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
     */
    public function create(): MessengerThreads
    {
        $this->_thread->save();
        return $this->_thread;
    }
}