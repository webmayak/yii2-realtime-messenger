<?php

namespace pantera\messenger\api\models;

use Yii;
use yii\base\Model;

class NotificationSearch extends MessengerThreads
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search()
    {
        /* @var $object MessengerThreads */
        $object = Yii::createObject(\pantera\messenger\models\MessengerThreads::className());
        $query = $object::find()
            ->addSelectCountNotViewedForUserId(Yii::$app->user->identity->id)
            ->isAvailableForMe()
            ->orderBy([MessengerThreads::COLUMN_COUNT_NOT_VIEWED_ALIAS => SORT_DESC])
            ->having(['>', MessengerThreads::COLUMN_COUNT_NOT_VIEWED_ALIAS, 0])
            ->limit(5);
        return $query->all();
    }
}
