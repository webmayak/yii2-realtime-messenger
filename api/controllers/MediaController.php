<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\ModuleApi;
use pantera\messenger\api\traits\FindModelTrait;
use pantera\messenger\models\MessengerMessages;
use Yii;
use yii\httpclient\Client;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class MediaController extends Controller
{
    use FindModelTrait;

    protected function verbs()
    {
        return [
            'create' => ['POST'],
        ];
    }

    /**
     * Создание нового сообщения с файлом
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $file = UploadedFile::getInstanceByName('file');
        if (is_null($file)) {
            throw new BadRequestHttpException();
        }
        $thread = $this->findThreadModel(Yii::$app->request->post('threadId'));
        $message = Yii::$app->messengerApi->createMessage()
            ->setScenario(MessengerMessages::SCENARIO_EMPTY)
            ->setUserId(Yii::$app->user->id)
            ->setThreadId($thread->id)
            ->setFiles([
                $file,
            ], 'attachments')
            ->create();
        $userIds = Yii::$app->messengerApi->getUserListInThread($thread->id, true);
        $params = [
            'notifiedUserIds' => $userIds,
            'threadId' => $message->thread_id,
            'messageId' => $message->id,
        ];
        /* @var $module ModuleApi */
        $module = Yii::$app->getModule('messenger-api');
        $client = new Client(['baseUrl' => $module->nodeServer]);
        $client->post('/new-message', $params)->send();
    }
}