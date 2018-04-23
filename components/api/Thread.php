<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/23/18
 * Time: 2:28 PM
 */

namespace pantera\messenger\components\api;


use pantera\messenger\models\MessengerThreads;
use yii\base\BaseObject;

class Thread extends BaseObject
{
    /* @var MessengerThreads */
    private $_thread;

    public function init()
    {
        parent::init();
        $this->_thread = new MessengerThreads();
    }

    /**
     * Установка ключа
     * @param string $key
     * @return Thread
     */
    public function setKey(string $key): Thread
    {
        $this->_thread->key = $key;
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