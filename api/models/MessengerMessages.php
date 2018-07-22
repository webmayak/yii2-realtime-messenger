<?php

namespace pantera\messenger\api\models;


use pantera\messenger\models\MessengerViewed;
use Yii;

class MessengerMessages extends \pantera\messenger\models\MessengerMessages
{
    public function fields()
    {
        return [
            'id',
            'body',
            'created_at',
            'isMy' => function () {
                return $this->isMy();
            },
            'isViewed' => function () {
                return $this->isViewed();
            }
        ];
    }

    /**
     * Проверяет являится ли текуший пользователем автором
     * @return bool
     */
    public function isMy(): bool
    {
        return $this->user_id === Yii::$app->user->id;
    }

    /**
     * Проверить прочитано ли сообщение текушим пользователем
     * @return bool
     */
    public function isViewed(): bool
    {
        return Yii::$app->user->id === $this->user_id || MessengerViewed::find()
                ->andWhere(['=', MessengerViewed::tableName() . '.user_id', Yii::$app->user->id])
                ->andWhere(['=', MessengerViewed::tableName() . '.message_id', $this->id])
                ->count() > 0;
    }
}
