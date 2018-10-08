<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/31/18
 * Time: 8:44 PM
 */

namespace pantera\messenger\api\traits;


use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use Yii;
use yii\web\NotFoundHttpException;

trait FindModelTrait
{
    /**
     * Найти модели диалога по её идентификатору
     * @param int $id
     * @return MessengerThreads
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function findThreadModel($id)
    {
        /* @var $object \pantera\messenger\api\models\MessengerThreads */
        $object = Yii::createObject(MessengerThreads::className());
        $model = $object::find()
            ->isAvailableForMe()
            ->andWhere(['=', $object::tableName() . '.id', $id])
            ->one();
        if (is_null($model)) {
            throw new NotFoundHttpException();
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