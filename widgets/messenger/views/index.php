<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/9/18
 * Time: 12:47 PM
 */

use pantera\messenger\helpers\MessagesEncodeHelper;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use pantera\messenger\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $threadId int */
/* @var $users \common\modules\user\common\models\User[] */
/* @var $messages MessengerMessages[] */
/* @var $module Module */
/* @var $threads MessengerThreads[] */
$module = Yii::$app->getModule('messenger');
?>
<div class="messages card" id="messages_card">
    <div class="m-sidebar">
        <header>
            <h2 class="hidden-xs">Диалоги</h2>
        </header>

        <div id="dialogsArea" class="list-group c-overflow mCustomScrollbar mCS-autoHide">
            <div id="dialogsAreaInner">
                <?php foreach ($users as $user) : ?>

                    <?php
                    if (empty($user->id) || empty($user->profile)) {
                        continue;
                    }
                    //найдем последнее сообщение
                    $lastMsgModel = new MessengerMessages();
                    $lastMsgModel->user_id = $user->id;
                    $myId = Yii::$app->user->id
                    ?>
                    <?php
                    $hideThreadModel = MessengerThreads::find()->where('(`to`=:me AND `from`=:id)  OR (`to`=:id AND `from`=:me)', [':me' => $myId, ':id' => $user->id])->orderBy(['id' => SORT_ASC])->one();
                    if ($hideThreadModel->to == $myId && $hideThreadModel->hide_to == 1) {
                        $hideThread = 'hidden';
                    } elseif ($hideThreadModel->from == $myId && $hideThreadModel->hide_from == 1) {
                        $hideThread = 'hidden';
                    } else {
                        $hideThread = '';
                    }
                    ?>
                    <a class="list-group-item media <?= $hideThread ?>" id="thread_block-<?= $user->id ?>"
                       href="<?= "?user=" . $user->id ?>"
                       <?php if ($user->id == @$_GET['user']) { ?>style="background: #175FBF; color: #fff;"<?php } ?>>
                        <div class="media-body">
                            <div class="lgi-heading">
                                <b><?= $user->profile->name . " " . $user->profile->name ?></b>
                                <?php if ($lastMsgModel->allunreadmessages) { ?>
                                    <span class="badge pull-right"><?= $lastMsgModel->allunreadmessages ?></span>
                                <?php } ?>
                            </div>
                            <div class="lgi-text">
                                <?= mb_substr($lastMsgModel->lastmessage['body'], 0, 25, 'utf-8') . (mb_strlen($lastMsgModel->lastmessage['body'], 'utf-8') > 25 ? '..' : '') ?>
                                <small class="ms-time pull-right"><?= substr($lastMsgModel->lastmessage['created_at'], 0, 10) == date('Y-m-d') ? 'сегодня ' . Yii::$app->formatter->asTime(strtotime($lastMsgModel->lastmessage['created_at']), "H:mm ") : Yii::$app->formatter->asDate(strtotime($lastMsgModel->lastmessage['created_at']), "d MMM ") ?></small>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="m-body">
        <header class="mb-header">
            <div class="mbh-user clearfix">
                <div class="p-t-5" style="line-height: 30px;">
                    <?php
                    if ($thisUser = \common\modules\user\common\models\User::findOne(@$_GET['user'])) {
                        echo !$module->threadsMode ? "Диалог -- " : "";

                        echo "<b>" . @$thisUser->profile->name . " " . @$thisUser->profile->name . "</b>  ";

                        if (@$_GET['thread_id'] && $module->threadsMode) {
                            $thread = MessengerThreads::findOne(isset($threadId) ? $threadId : $_GET['thread_id']);
                            echo '<a href="?user=' . @$_GET['user'] . '">' . $thread->subject . '</a>';
                        } elseif ($module->threadsMode) {
                            echo 'выбор диалога';
                        }
                        echo ' <a class="pull-right" href="' . Url::to(['/messenger/default/hide-thread']) . '" id="hide_thread-' . @$_GET["user"] . '" data-user-id="' . @$_GET["user"] . '"   style=" position:relative; font-size:11px; color:#bbb; margin-right:5px; top:0; right:-5px; cursor: pointer; text-decoration:underline ">удалить диалог</a>';
                    } else {
                        echo 'Выбор собеседника';
                    }
                    ?>
                </div>

            </div>

        </header>


        <div class="mb-list">
            <div id="messagesArea"
                 class="mbl-messages c-overflow mCustomScrollbar <?= empty($messages) ? "empty_dialogs" : "" ?> mCS-autoHide <?php if (!isset($_GET['thread_id'])): ?>threads<?php endif; ?>">
                <?php if (isset($messages) && !empty($messages)): ?>
                    <div id="messagesAreaInner">
                        <?php foreach ($messages as $message): ?>
                            <div
                                    class="mblm-item mblm-item-<?= $message->user_id == @$_GET['user'] ? "left" : "right" ?> ">
                                <div>
                                    <?php
                                    $body2rows = preg_split('/\n+/', strip_tags($message->body));
                                    echo implode('<br/>', $body2rows); ?>
                                </div>
                                <small>
                                    <?= substr($message->created_at, 0, 10) == date('Y-m-d') ? 'сегодня' : Yii::$app->formatter->asDate(strtotime($message->created_at), "d MMM") ?>
                                    в
                                    <?=
                                    Yii::$app->formatter->asTime(strtotime($message->created_at), "H:mm ")
                                    //                                    : Yii::$app->dateFormatter->format("d MMM ", strtotime($message->created_at))
                                    ?>

                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <?php if (isset($threads)): ?>
                        <div class="threads-list" id="messagesAreaInner">
                            <?php foreach ($threads as $thread): ?>
                                <a class="list-group-item media"
                                   href="?user=<?= @$_GET['user'] ?>&thread_id=<?= $thread->id ?>">
                                    <div class="media-body">
                                        <div class="lgi-heading">
                                            <b><?= $thread->subject ?></b>
                                            <?php if (@$thread->messengerMessages->threadunreadcount) { ?>
                                            <span class="badge"><?= $thread->messengerMessages->threadunreadcount ?>
                                                <?php } ?>
                                        </div>
                                        <div class="lgi-text" style="color:black;">
                                            <?= mb_substr(@$thread->messengerMessages->lastthreadmessage[0]['body'], 0, 25, 'utf-8') . (mb_strlen(@$thread->messengerMessages->lastthreadmessage[0]['body'], 'utf-8') > 25 ? '..' : '') ?>
                                            <small class="ms-time pull-right" style="color:black;">
                                                <?= substr(@$thread->messengerMessages->lastthreadmessage[0]['created_at'], 0, 10) == date('Y-m-d') ? 'сегодня' : Yii::$app->formatter->asDate(strtotime(@$thread->messengerMessages->lastthreadmessage[0]['created_at']), "d MMM") ?>
                                                в
                                                <?= Yii::$app->formatter->asTime(strtotime(@$thread->messengerMessages->lastthreadmessage[0]['created_at']), "H:mm") ?>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php if (isset($_GET['thread_id']) || !$module->threadsMode && @$_GET['user'] > 0): ?>
                <div class="mbl-compose">
                    <?= Html::beginForm(['/messenger/send/index'], 'POST', [
                        'id' => 'message-form',
                    ]) ?>
                    <?php $encryptedUserId = MessagesEncodeHelper::encrypt(Yii::$app->user->id) ?>
                    <input type="hidden" name="user_id" value="<?= $encryptedUserId ?>" />
                    <input type="hidden" name="thread_id"
                           value="<?= isset($threadId) ? $threadId : @$_GET['thread_id'] ?>" />
                    <textarea
                            onclick="readMessages('<?= $module->threadsMode ? @$_GET['thread_id'] : (string)MessagesEncodeHelper::encrypt(@$_GET['user']) ?>')"
                            placeholder="Текст сообщения..." name="text"
                            style="display: flex; width: 100%;"
                            onkeypress="if(event.keyCode===10 || event.keyCode===13) formSubmit();"></textarea>
                    <small style="font-size: 11px; color: #999">Используйте <b>#кодзапчасти</b> или
                        <b>№кодзапчасти</b> для вставки ссылки на поиск, например <b>#C110</b></small>
                    <div class="text-right">
                        <button type="submit"><i class="zmdi zmdi-mail-send"></i></button>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>