<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/10/18
 * Time: 1:25 PM
 */

namespace pantera\messenger\admin\models;


use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use yii\data\ActiveDataProvider;
use const SORT_DESC;

class MessengerMessagesSearch extends MessengerMessages
{
    public $threadKey;

    public function rules()
    {
        return [
            [['body', 'threadKey'], 'safe'],
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $query = MessengerMessages::find()
            ->joinWith(['thread']);
        $query->andFilterWhere(['LIKE', MessengerThreads::tableName() . '.key', $this->threadKey]);
        $query->andFilterWhere(['LIKE', MessengerMessages::tableName() . '.body', $this->body]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }
}