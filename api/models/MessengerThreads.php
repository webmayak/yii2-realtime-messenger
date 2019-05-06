<?php

namespace pantera\messenger\api\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;

/**
 * Class MessengerThreads
 * @package pantera\messenger\api\models
 *
 * @property MessengerMessages $lasMessage
 */
class MessengerThreads extends \pantera\messenger\models\MessengerThreads
{
    /* @var int|null Поле которое будет содержать количество не просмотренных сообщений для текушего пользователя */
    public $countNotViewed;

    const COLUMN_COUNT_NOT_VIEWED_ALIAS = 'countNotViewed';

    public function behaviors()
    {
        return [
            [
                'class' => AttributeTypecastBehavior::class,
                'typecastAfterFind' => true,
                'attributeTypes' => [
                    self::COLUMN_COUNT_NOT_VIEWED_ALIAS => AttributeTypecastBehavior::TYPE_INTEGER,
                ],
            ],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'subject',
            'lastMessage',
            'created_at',
            self::COLUMN_COUNT_NOT_VIEWED_ALIAS,
        ];
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

    /**
     * @return MessengerThreadsQuery|\yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(MessengerThreadsQuery::class, [get_called_class()]);
    }
}
