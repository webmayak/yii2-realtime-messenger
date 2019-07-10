<?php

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\ModuleApi;
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
use yii\web\UploadedFile;

class MediaController extends Controller
{
    use FindModelTrait;
    use ModuleTrait;

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
     */
    public function actionCreate()
    {
        $file = UploadedFile::getInstanceByName('file');
        if (is_null($file) || $this->fileValidate($file) === false) {
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
        Yii::$app->messengerApi->sendToSocket($message);
    }

    /**
     * Валидация переданого файла
     * @param UploadedFile $file
     * @return bool
     */
    protected function fileValidate(UploadedFile $file)
    {
        return in_array($file->extension, $this->moduleApi->mediaAvailableExtensions);
    }
}
