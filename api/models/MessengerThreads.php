<?php

namespace pantera\messenger\api\models;

use Yii;

/**
 * Class MessengerThreads
 * @package pantera\messenger\api\models
 *
 * @property MessengerMessages $lasMessage
 */
class MessengerThreads extends \pantera\messenger\models\MessengerThreads
{
    public function fields()
    {
        return [
            'id',
            'subject',
            'lastMessage',
        ];
    }

    public function getLastMessage()
    {
        $object = Yii::createObject(\pantera\messenger\models\MessengerMessages::className());
        return $this->hasOne($object::className(), ['thread_id' => 'id'])
            ->orderBy([$object::tableName() . '.created_at' => SORT_DESC]);
    }
}
