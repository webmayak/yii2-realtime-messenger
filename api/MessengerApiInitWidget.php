<?php

namespace pantera\messenger\api;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\View;

class MessengerApiInitWidget extends Widget
{
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest === false) {
            MessengerApiAsset::register($this->view);
            /* @var $module ModuleApi */
            $module = Yii::$app->getModule('messenger-api');
            $userId = Yii::$app->user->id;
            $mediaAvailableExtensionsJson = Json::encode($module->mediaAvailableExtensions);
            $js = <<<JS
            let messengerApi = {
                server: '{$module->nodeServer}',
                userId: {$userId},
                mediaAvailableExtensions: {$mediaAvailableExtensionsJson},
            };
JS;
            $this->view->registerJs($js, View::POS_HEAD);
        }
    }
}
