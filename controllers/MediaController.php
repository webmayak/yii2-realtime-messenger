<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 11/10/18
 * Time: 3:10 PM
 */

namespace pantera\messenger\controllers;

use pantera\messenger\api\traits\FindModelTrait;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class MediaController extends Controller
{
    use FindModelTrait;

    /**
     * Отдать файл из сообщения пользователю
     * @param int $threadId
     * @param int $id
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionDownload(int $threadId, int $id)
    {
        $this->findThreadModel($threadId);
        $message = $this->findModel($id, $threadId);
        if (empty($message->attachments)) {
            throw new BadRequestHttpException();
        }
        $media = current($message->attachments);
        Yii::$app->response->sendFile($media->getPath(), $media->name);
    }
}
