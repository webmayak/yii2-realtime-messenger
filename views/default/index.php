<?php

use pantera\messenger\models\MessengerMessages;
use pantera\messenger\widgets\messenger\Messenger;
use yii\web\View;

/* @var $this View */
/* @var $messages MessengerMessages[] */
/* @var $threadId int */
/* @var $users \common\modules\user\common\models\User[] */

$this->title = 'Мои сообщения';
?>

<?= Messenger::widget([
    'users' => $users,
    'messages' => $messages,
    'threadId' => $threadId,
]) ?>
