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
        /* @var $object MessengerThreads */
        $this->load($params);
        $object = Yii::createObject(\pantera\messenger\models\MessengerThreads::className());
        $query = $object::find()
            ->isHasMessages()
            ->addSelectCountNotViewedForUserId(Yii::$app->user->identity->id)
            ->andWhere(['IN', $object::tableName() . '.key', Yii::$app->user->identity->getThreadKeyList()]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['last_message_at' => SORT_DESC],
            ],
            'pagination' => false,
        ]);
    }
}
