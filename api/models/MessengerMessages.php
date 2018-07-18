<?php

namespace pantera\messenger\api\models;


class MessengerMessages extends \pantera\messenger\models\MessengerMessages
{
    public function fields()
    {
        return [
            'id',
            'body',
            'created_at',
        ];
    }
}
