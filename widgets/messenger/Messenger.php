<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/9/18
 * Time: 12:46 PM
 */

namespace pantera\messenger\widgets\messenger;


use common\modules\user\common\models\User;
use pantera\messenger\models\MessengerMessages;
use yii\base\Widget;

class Messenger extends Widget
{
    /* @var MessengerMessages[] */
    public $messages;
    /* @var User[] */
    public $users;
    /* @var int */
    public $threadId;
    private $_sound;

    public function run()
    {
        parent::run();
        return $this->render('index', [
            'messages' => $this->messages,
            'users' => $this->users,
            'threadId' => $this->threadId,
            'sound' => $this->_sound,
        ]);
    }

    public function init()
    {
        parent::init();
        $bundle = Assets::register($this->view);
        $this->_sound = $bundle->baseUrl . '/sounds/new-order';
    }
}