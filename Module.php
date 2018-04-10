<?php

namespace pantera\messenger;

use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{
    public $threadsMode = false;
    /* @var string|null Адрес node сервера для работы сокета */
    public $nodeServer;

    public function init()
    {
        parent::init();
        if (empty($this->nodeServer)) {
            throw new InvalidConfigException('Необходимо указать nodeServer');
        }
    }
}
