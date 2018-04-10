<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/10/18
 * Time: 12:54 PM
 */

namespace pantera\messenger\admin\models;


use pantera\messenger\models\MessagesLog;
use yii\data\ActiveDataProvider;
use const SORT_DESC;

class MessagesLogSearch extends MessagesLog
{
    public function search($params)
    {
        $this->load($params);
        $query = MessagesLog::find();
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }
}