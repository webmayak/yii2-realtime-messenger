<?php

namespace pantera\messenger\controllers;

use pantera\messenger\helpers\MessagesEncodeHelper;
use pantera\messenger\models\MessagesLog;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use pantera\messenger\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use function is_null;

class SendController extends Controller
{
    /* @var Module */
    public $module;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
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
                MessagesLog::add($to, $from, $model->body, 0, 0, 0);
                $nodeSendUserId = MessengerThreads::findOne($model->thread_id);
                if ($nodeSendUserId->to == $model->user_id) {
                    $nodeSendUserId = $nodeSendUserId->from;
                } else {
                    $nodeSendUserId = $nodeSendUserId->to;
                }
                // Отправим информацию о сообщении
                //Закодируем ид пользователя
                file_get_contents($this->module->nodeServer . "/new-message?hash=" . MessagesEncodeHelper::encrypt($nodeSendUserId) . "&sender=" . Yii::$app->user->id);
                if (!Yii::$app->request->isAjax) {
                    $this->redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }

    /**
     * Создание сообщения для канала с ключом
     */
    public function actionCreate()
    {
        $model = new MessengerMessages();
        $model->load(Yii::$app->request->post());
        //Если не передан идентификатор канала то созданим новый
        if (empty($model->thread_id)) {
            if (is_null(Yii::$app->request->get('key'))) {
                throw new BadRequestHttpException('Отсутствует ключ для нового канала');
            }
            $thread = new MessengerThreads();
            $thread->key = Yii::$app->request->get('key');
            $thread->save();
            $model->thread_id = $thread->id;
        }
        $model->user_id = Yii::$app->user->id;
        $model->save();
        if (Yii::$app->request->isAjax) {
            return $this->asJson([]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}