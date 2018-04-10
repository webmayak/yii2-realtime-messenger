<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/9/18
 * Time: 12:46 PM
 */

namespace pantera\messenger\widgets\messenger;


use common\modules\user\common\models\User;
use pantera\messenger\helpers\MessagesEncodeHelper;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\Module;
use Yii;
use yii\base\Widget;

class Messenger extends Widget
{
    /* @var MessengerMessages[] */
    public $messages;
    /* @var User[] */
    public $users;
    /* @var int */
    public $threadId;

    public function run()
    {
        parent::run();
        return $this->render('index', [
            'messages' => $this->messages,
            'users' => $this->users,
            'threadId' => $this->threadId,
        ]);
    }

    public function init()
    {
        parent::init();
        $bundle = Assets::register($this->view);
        /* @var $module Module */
        $module = Yii::$app->getModule('messenger');
        $userId = MessagesEncodeHelper::encrypt(Yii::$app->user->id);
        $userName = Yii::$app->user->identity->profile->name;
        $sound = $bundle->baseUrl . '/sounds/new-order';
        $js = <<<JS
            connectToSocketIo('{$module->nodeServer}', '{$userId}', '{$sound}', '{$userName}');
JS;
        $this->view->registerJs($js);
    }
}