<?php

namespace pantera\messenger;

class AdminModule extends \yii\base\Module
{
    public $controllerNamespace = 'pantera\messenger\admin\controllers';

    public function getViewPath()
    {
        return __DIR__ . '/admin/views';
    }
}
