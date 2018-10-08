<?php

namespace pantera\messenger\api\models;

use pantera\messenger\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MessengerThreadsSearch extends MessengerThreads
{
    use ModuleTrait;

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
            ->isAvailableForMe()
            ->addSelectCountNotViewedForUserId(Yii::$app->user->identity->id);
        if ($this->moduleApi->threadSearchShowEmpty === false) {
            $query->isHasMessages();
        }
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['last_message_at' => SORT_DESC],
            ],
            'pagination' => false,
        ]);
    }
}
