<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/22/18
 * Time: 1:45 PM
 */

namespace pantera\messenger\api;


use Yii;
use yii\base\Widget;
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
            $js = <<<JS
            let messengerApi = {
                server: '{$module->nodeServer}',
                userId: {$userId},
            };
JS;
            $this->view->registerJs($js, View::POS_HEAD);
        }
    }
}