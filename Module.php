<?php

namespace pantera\messenger;

use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{
    public $threadsMode = false;
    /* @var string|null Адрес node сервера для работы сокета */
    public $nodeServer;
    /* @var string Название класа который отвечает за модель автора сообщения */
    public $authorEntity;

    public function init()
    {
        parent::init();
        if (empty($this->nodeServer)) {
            throw new InvalidConfigException('Необходимо указать {nodeServer}');
        }
        if (empty($this->authorEntity)) {
            throw new InvalidConfigException('Необходимо указать {authorEntity}');
        }
    }
}
