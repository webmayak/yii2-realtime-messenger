<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerViewed;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ReadController extends Controller
{
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

    /**
     * Пометить сообщение прочитаным конкретным пользователем
     * @return void
     */
    public function actionIndex()
    {
        array_map(function ($id) {
            $message = $this->findModel($id);
            $model = new MessengerViewed([
                'user_id' => Yii::$app->user->id,
                'message_id' => $id,
            ]);
            $model->save();
        }, Yii::$app->request->post('ids'));
    }

    /**
     * @param $id
     * @return MessengerMessages
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws ForbiddenHttpException
     */
    protected function findModel($id)
    {
        $object = Yii::createObject(MessengerMessages::className());
        $model = $object::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        //Если ключа диалога нету в доступных для пользователя закроем доступ
        if (in_array($model->thread->key, Yii::$app->user->identity->getThreadKeyList()) === false) {
            throw new ForbiddenHttpException();
        }
        return $model;
    }
}