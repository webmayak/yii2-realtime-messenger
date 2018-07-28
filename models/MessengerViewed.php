<?php

namespace pantera\messenger\models;

use Yii;

/**
 * This is the model class for table "messenger_viewed".
 *
 * @property int $user_id
 * @property int $message_id
 *
 * @property MessengerMessages $message
 */
class MessengerViewed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messenger_viewed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message_id'], 'required'],
            [['user_id', 'message_id'], 'integer'],
            [['user_id', 'message_id'], 'unique', 'targetAttribute' => ['user_id', 'message_id']],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => MessengerMessages::className(), 'targetAttribute' => ['message_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'message_id' => 'Message ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getMessage()
    {
        $object = Yii::createObject(MessengerMessages::className());
        return $this->hasOne($object::className(), ['id' => 'message_id']);
    }
}
