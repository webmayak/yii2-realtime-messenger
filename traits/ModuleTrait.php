<?php

namespace pantera\messenger\traits;

use pantera\messenger\api\ModuleApi;
use pantera\messenger\Module;

/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 9/13/18
 * Time: 2:35 PM
 *
 * @property ModuleApi $moduleApi
 */
trait ModuleTrait
{
    public function getModuleApi()
    {
        return ModuleApi::getInstance();
    }

    public function getModule()
    {
        return Module::getInstance();
    }
}