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
        $object = Yii::createObject(\pantera\messenger\models\MessengerMessages::className());
        $query = $object::find();
        if ($this->moduleApi->messagesSearchQueryModifier) {
            call_user_func($this->moduleApi->messagesSearchQueryModifier, $query);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC, 'id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if ($this->validate() === false) {
            return $dataProvider;
        }
        $query->andWhere([
            '=',
            \pantera\messenger\models\MessengerMessages::tableName() . '.thread_id',
            $this->thread_id
        ]);
        $dataProvider->prepare();
        return $dataProvider;
    }
}
