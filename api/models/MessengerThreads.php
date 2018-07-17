<?php

namespace pantera\messenger\api\models;

class MessengerThreads extends \pantera\messenger\models\MessengerThreads
{
    public function fields()
    {
        return [
            'id',
            'simpleMessage',
        ];
    }

    public function getLastMessageSimple()
    {
        return [
            'awg'
        ];
    }
}
