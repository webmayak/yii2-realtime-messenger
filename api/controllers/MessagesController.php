<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\MessengerMessagesSearch;
use pantera\messenger\api\traits\FindModelTrait;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\traits\ModuleTrait;
use Redis;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use function array_key_exists;

class MessagesController extends Controller
{
    use FindModelTrait;
    use ModuleTrait;

    protected function verbs()
    {
        return [
            'create' => ['POST'],
            'index' => ['GET'],
            'get' => ['GET'],
        ];
    }

    /**
     * Загрузить список сообщений в диалоге
     * также получить полную модель диалога
     * @param int $id Идентификатор диалога
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex($id)
    {
        $thread = $this->findThreadModel($id, $this->moduleApi->isAdmin());
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
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGet($threadId, $id)
    {
        $this->findThreadModel($threadId);
        $model = $this->findModel($id, $threadId);
        return $model;
    }

    /**
     * Создание нового сообщения
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        if (empty(Yii::$app->request->post('message'))) {
            throw new BadRequestHttpException();
        }
        $thread = $this->findThreadModel(Yii::$app->request->post('threadId'));
        $message = Yii::$app->messengerApi->createMessage()
            ->setUserId(Yii::$app->user->id)
            ->setThreadId($thread->id)
            ->setBody(Yii::$app->request->post('message'))
            ->create();
        Yii::$app->messengerApi->sendToSocket($message);
    }
}
