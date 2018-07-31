<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/31/18
 * Time: 8:44 PM
 */

namespace pantera\messenger\api\traits;


use pantera\messenger\api\models\MessengerMessages;
use pantera\messenger\api\models\MessengerThreads;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

trait FindModelTrait
{
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