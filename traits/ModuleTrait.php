<?php

namespace pantera\messenger\traits;

use pantera\messenger\api\ModuleApi;
use pantera\messenger\Module;
use Yii;

/**
 * @property ModuleApi $moduleApi
 * @property Module $module
 */
trait ModuleTrait
{
    /**
     * @return ModuleApi|null
     */
    public function getModuleApi()
    {
        $module = ModuleApi::getInstance();
        if (!$module) {
            $module = Yii::$app->getModule('messenger-api');
        }
        return $module;
    }

    /**
     * @return Module|null
     */
    public function getModule()
    {
        $module = Module::getInstance();
        if (!$module) {
            $module = Yii::$app->getModule('messenger');
        }
        return $module;
    }
}
