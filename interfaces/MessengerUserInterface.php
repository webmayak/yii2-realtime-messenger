<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/22/18
 * Time: 11:32 PM
 */

namespace pantera\messenger\interfaces;


interface MessengerUserInterface
{
    public function getPrimaryKey();

    public function getThreadKeyList(): array;
}