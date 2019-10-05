<?php

namespace pantera\messenger\api\models;

use pantera\messenger\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

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

    public function search($params, $userId = null)
    {
        /* @var $object MessengerThreads */
        $this->load($params);
        $object = Yii::createObject(\pantera\messenger\models\MessengerThreads::class);
        $userId = $userId ?: Yii::$app->user->identity->id;
        $query = $object::find()
            ->isAvailableForMe($userId)
            ->addSelectCountNotViewedForUserId($userId);
        if ($this->moduleApi->threadSearchShowEmpty === false) {
            $query->isHasMessages();
        }
        $sortExpression = new Expression('IF(' . MessengerThreads::tableName() . '.`last_message_at`,
         ' . MessengerThreads::tableName() . '.`last_message_at`,
          ' . MessengerThreads::tableName() . '.`created_at`) DESC');
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['default' => SORT_DESC],
                'attributes' => [
                    'default' => [
                        'desc' => [$sortExpression],
                    ],
                ],
            ],
            'pagination' => $this->moduleApi->threadsSearchPagination,
        ]);
    }
}
