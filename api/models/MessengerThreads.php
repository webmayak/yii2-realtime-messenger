<?php

namespace pantera\messenger\api\models;

use pantera\messenger\models\MessengerViewed;
use Yii;
use yii\db\Expression;

/**
 * Class MessengerThreads
 * @package pantera\messenger\api\models
 *
 * @property MessengerMessages $lasMessage
 */
class MessengerThreads extends \pantera\messenger\models\MessengerThreads
{
    public function fields()
    {
        return [
            'id',
            'subject',
            'lastMessage',
            'countNotViewed',
        ];
    }

    /**
     * Получить количество не прочитанных сообщений в диалоге
     * @return int
     */
    public function getCountNotViewed(): int
    {
        return MessengerMessages::find()
            ->joinWith(['thread'])
            ->leftJoin(MessengerViewed::tableName(), [
                'AND',
                ['=', MessengerViewed::tableName() . '.message_id', new Expression(MessengerMessages::tableName() . '.id')],
                ['=', MessengerViewed::tableName() . '.user_id', Yii::$app->user->id]
            ])
            ->andWhere(['!=', MessengerMessages::tableName() . '.user_id', Yii::$app->user->id])
            ->andWhere(['=', MessengerMessages::tableName() . '.thread_id', $this->id])
            ->andWhere(['IS', MessengerViewed::tableName() . '.message_id', null])
            ->count();
    }

    /**
     * Получить последние сообщение в диалоге
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getLastMessage()
    {
        $object = Yii::createObject(\pantera\messenger\models\MessengerMessages::className());
        return $this->hasOne($object::className(), ['thread_id' => 'id'])
            ->orderBy([$object::tableName() . '.created_at' => SORT_DESC]);
    }
}
