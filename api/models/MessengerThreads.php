<?php

namespace pantera\messenger\api\models;

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
        return $this->hasOne(MessengerMessages::className(), ['thread_id' => 'id'])
            ->orderBy([MessengerMessages::tableName() . '.created_at' => SORT_DESC]);
    }
}
