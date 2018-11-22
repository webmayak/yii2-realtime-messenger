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
     * @param bool $isAdmin Флаг что пользователь админ и ему не надо делать проверку на доступность
     * @return MessengerThreads
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function findThreadModel($id, $isAdmin = false)
    {
        /* @var $object \pantera\messenger\api\models\MessengerThreads */
        $object = Yii::createObject(MessengerThreads::className());
        $query = $object::find()
            ->andWhere(['=', $object::tableName() . '.id', $id]);
        if ($isAdmin === false) {
            $query->isAvailableForMe();
        }
        $model = $query->one();
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * @param $id
     * @param int|null $threadId Идентификатор диалога
     * @return MessengerMessages
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($id, $threadId = null)
    {
        $object = Yii::createObject(MessengerMessages::className());
        $model = $object::find()
            ->andWhere(['=', $object::tableName() . '.id', $id])
            ->andFilterWhere(['=', $object::tableName() . '.thread_id', $threadId])
            ->one();
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}