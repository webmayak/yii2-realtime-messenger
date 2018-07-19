<?php

namespace pantera\messenger\api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MessengerThreadsSearch extends MessengerThreads
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
        $object = Yii::createObject(\pantera\messenger\models\MessengerThreads::className());
        $query = $object::find();
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => false,
        ]);
    }
}
