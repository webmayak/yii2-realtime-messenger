<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/19/18
 * Time: 3:50 PM
 */

namespace pantera\messenger\widgets\messengerMessageForm;


use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class MessengerMessageForm extends Widget
{
    /* @var string */
    public $key;

    public function run()
    {
        parent::run();
        $thread = MessengerThreads::find()
            ->where(['=', MessengerThreads::tableName() . '.key', $this->key])
            ->one();
        $model = new MessengerMessages();
        $model->thread_id = $thread->id ?? null;
        return $this->render('index', [
            'model' => $model,
            'thread' => $thread,
            'key' => $this->key,
        ]);
    }

    public function init()
    {
        parent::init();
        if (is_null($this->key)) {
            throw new InvalidConfigException('Параметр {key} обязателен');
        }
    }
}