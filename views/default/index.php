<?php

use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use pantera\messenger\widgets\messenger\Messenger;
use yii\web\View;

/* @var $this View */
/* @var $messages MessengerMessages[] */
/* @var $threadId int */
/* @var $users \common\modules\user\common\models\User[] */
/* @var $threads MessengerThreads[] */
$this->title = 'Мои сообщения';
?>

<?= Messenger::widget([
    'users' => $users,
    'messages' => $messages,
    'threadId' => $threadId,
    'threads' => $threads,
]) ?>
