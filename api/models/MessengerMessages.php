<?php

namespace pantera\messenger\api\models;


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
        ];
    }

    /**
     * Проверяет являится ли текуший пользователем автором
     * @return bool
     */
    public function isMy()
    {
        return $this->user_id === Yii::$app->user->id;
    }
}
