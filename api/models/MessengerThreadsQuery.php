<?php

namespace pantera\messenger\api\models;

use pantera\messenger\models\MessengerThreadUser;
use pantera\messenger\models\MessengerViewed;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @see MessengerThreads
 */
class MessengerThreadsQuery extends ActiveQuery
{
    /**
     * Только доступный для текушего пользователя
     * @param int|null $userId Идентификатор пользователя
     * @return MessengerThreadsQuery
     */
    public function isAvailableForMe($userId = null): self
    {
        $userId = $userId ?: Yii::$app->user->id;
        return $this->joinWith(['relationWithUsers'])
            ->andWhere(['=', MessengerThreadUser::tableName() . '.user_id', $userId]);
    }

    /**
     * Добавить к выборке количество не просмотренных сообщений переданного пользователя
     * @param int $userId Идентификатор пользователя чеи непросмотренные сообщения будем считать
     * @return MessengerThreadsQuery
     */
    public function addSelectCountNotViewedForUserId(int $userId): self
    {
        $query = MessengerMessages::find()
            ->select(new Expression('COUNT(1)'))
            ->leftJoin(MessengerViewed::tableName(), [
                'AND',
                ['=', MessengerViewed::tableName() . '.message_id', new Expression(MessengerMessages::tableName() . '.id')],
                ['=', MessengerViewed::tableName() . '.user_id', $userId]
            ])
            ->andWhere(['!=', MessengerMessages::tableName() . '.user_id', $userId])
            ->andWhere(['=', MessengerMessages::tableName() . '.thread_id', new Expression(MessengerThreads::tableName() . '.id')])
            ->andWhere(['IS', MessengerViewed::tableName() . '.message_id', null]);
        return $this->addSelect(MessengerThreads::tableName() . '.*')
            ->addSelect([MessengerThreads::COLUMN_COUNT_NOT_VIEWED_ALIAS => $query]);
    }

    /**
     * Выбрать диалоги где есть сообщения
     * @return MessengerThreadsQuery
     */
    public function isHasMessages(): self
    {
        return $this->andWhere(['>', new Expression('(SELECT COUNT(1) FROM ' . MessengerMessages::tableName() . ' WHERE ' . MessengerMessages::tableName() . '.thread_id=' . MessengerThreads::tableName() . '.id' . ')'), 0]);
    }
}
