<?php

namespace pantera\messenger\controllers;

use pantera\messenger\helpers\MessagesEncodeHelper;
use pantera\messenger\models\MessagesLog;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SendController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function messageLog($to, $from, $body, $partition = 0, $count = 0, $position = 0)
    {
        $model = new MessagesLog();
        $model->message_body = $body;
        $model->partition = $partition;
        $model->position = $position;
        $model->to = $to;
        $model->position_count = $count;
        $model->from = $from;
        $model->save();
    }

    public function actionIndex()
    {
        if (isset($_POST['text'])) {
            $model = new MessengerMessages();
            $model->body = strip_tags($_POST['text']);
            $model->thread_id = $_POST['thread_id'];
            $model->user_id = MessagesEncodeHelper::decrypt($_POST['user_id']);
            $model->readed = 0;
            $model->notified = 0;
            if ($model->save()) {
                $thread = MessengerThreads::findOne($model->thread_id);
                $from = $model->user_id;
                if ($model->user_id == $thread->to) {
                    $to = $thread->from;
                } else {
                    $to = $thread->to;
                }
                $thread->hide_to = 0;
                $thread->hide_from = 0;
                $thread->updated_at = date("Y-m-d H:i:s");
                $thread->save();
                $this->messageLog($to, $from, $model->body, 0, 0, 0);
                $nodeSendUserId = MessengerThreads::findOne($model->thread_id);
                if ($nodeSendUserId->to == $model->user_id) {
                    $nodeSendUserId = $nodeSendUserId->from;
                } else {
                    $nodeSendUserId = $nodeSendUserId->to;
                }
                // Отправим информацию о сообщении
                //Закодируем ид пользователя
                file_get_contents(Yii::$app->params['node_server'] . "/new-message?hash=" . MessagesEncodeHelper::encrypt($nodeSendUserId) . "&sender=" . Yii::$app->user->id);
                if (!Yii::$app->request->isAjax) {
                    $this->redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }
}