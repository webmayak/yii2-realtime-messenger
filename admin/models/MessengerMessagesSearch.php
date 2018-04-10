<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/10/18
 * Time: 1:25 PM
 */

namespace pantera\messenger\admin\models;


use pantera\messenger\models\MessengerMessages;
use yii\data\ActiveDataProvider;
use const SORT_DESC;

class MessengerMessagesSearch extends MessengerMessages
{
    public function search($params)
    {
        $this->load($params);
        $query = MessengerMessages::find();
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }
}