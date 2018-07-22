<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\MessengerMessagesSearch;
use pantera\messenger\api\models\MessengerThreads;
use pantera\messenger\api\ModuleApi;
use pantera\messenger\models\MessengerMessages;
use Yii;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class MessagesController extends Controller
{
    public function behaviors()
    {
        $behaviors = [];
        return array_merge(parent::behaviors(), $behaviors);
    }

    protected function verbs()
    {
        return [
            'create' => ['POST'],
            'index' => ['GET'],
        ];
    }

    /**
     * Загрузить список сообщений в диалоге
     * также получить полную модель диалога
     * @param int $id Идентификатор диалога
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws ForbiddenHttpException
     */
    public function actionIndex($id)
    {
        $thread = $this->findThreadModel($id);
        $searchModel = new MessengerMessagesSearch();
        $searchModel->thread_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->response->headers->set('pagination-total-page', $dataProvider->pagination->getPageCount());
        $models = $dataProvider->getModels();
        $models = array_reverse($models);
        return [
            'thread' => $thread,
            'models' => $models,
        ];
    }

    /**
     * Загрузить конкретное сообщение
     * @param int $threadId Идентификатор диалога
     * @param int $id Идентификатор нужного сообщения
     * @return MessengerMessages
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGet($threadId, $id)
    {
        $this->findThreadModel($threadId);
        $model = $this->findModel($id);
        return $model;
    }

    /**
     * Создание нового сообщения
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (is_null(Yii::$app->request->post('message'))) {
            throw new BadRequestHttpException();
        }
        $thread = $this->findThreadModel(Yii::$app->request->post('threadId'));
        $message = Yii::$app->messengerApi->createMessage()
            ->setUserId(Yii::$app->user->id)
            ->setThreadId($thread->id)
            ->setBody(Yii::$app->request->post('message'))
            ->create();
        $message = $this->findModel($message->id);
        $userIds = Yii::$app->messengerApi->getUserListInThread($thread->id, true);
        ArrayHelper::removeValue($userIds, (string)Yii::$app->user->id);
        $params = [
            'notifiedUserIds' => $userIds,
            'threadId' => $message->thread_id,
            'messageId' => $message->id,
        ];
        /* @var $module ModuleApi */
        $module = Yii::$app->getModule('messenger-api');
        $client = new Client(['baseUrl' => $module->nodeServer]);
        $client->post('/new-message', $params)->send();
        return [
            'status' => true,
            'message' => $message,
        ];
    }

    /**
     * Найти модели диалога по её идентификатору
     * @param int $id
     * @return MessengerThreads
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws ForbiddenHttpException
     */
    protected function findThreadModel($id)
    {
        $object = Yii::createObject(\pantera\messenger\models\MessengerThreads::className());
        $model = $object::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        //Если ключа диалога нету в доступных для пользователя закроем доступ
        if (in_array($model->key, Yii::$app->user->identity->getThreadKeyList()) === false) {
            throw new ForbiddenHttpException();
        }
        return $model;
    }

    /**
     * @param $id
     * @return MessengerMessages
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($id)
    {
        $object = Yii::createObject(MessengerMessages::className());
        $model = $object::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}