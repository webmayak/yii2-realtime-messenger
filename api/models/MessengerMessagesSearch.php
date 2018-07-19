<?php

namespace pantera\messenger\api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MessengerMessagesSearch extends MessengerMessages
{
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $this->load($params);
        $model = Yii::createObject(MessengerMessages::className());
        $query = $model::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
            'pagination' => false,
        ]);
        if ($this->validate() === false) {
            return $dataProvider;
        }
        $query->andWhere(['=', MessengerMessages::tableName() . '.thread_id', $this->thread_id]);
        return $dataProvider;
    }
}
