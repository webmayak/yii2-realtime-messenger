<?php

namespace pantera\messenger\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{messenger_threads}}".
 *
 * The followings are the available columns in table '{{messenger_threads}}':
 * @property integer $id
 * @property string $subject
 * @property integer $from
 * @property integer $to
 * @property integer $hide_to
 * @property integer $hide_from
 * @property string $updated_at [datetime]
 * @property string $key
 * @property string $last_message_at
 *
 * @property MessengerMessages[] $messengerMessages
 * @property ActiveQuery $userFrom
 */
class MessengerThreads extends ActiveRecord
{
    public static $hide_to;
    public static $hide_from;

    public static function tableName()
    {
        return '{{%messenger_threads}}';
    }

    public function rules()
    {
        return [
            [['from', 'to'], 'number', 'integerOnly' => true],
            [['subject', 'key'], 'string', 'max' => 255],
            [['last_message_at'], 'safe']
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'subject' => 'Subject',
            'from' => 'From',
            'to' => 'To',

        );
    }

    public function getMessengerMessages()
    {
        return $this->hasOne(MessengerMessages::className(), ['thread_id' => 'id']);
    }

    public function getUserFrom()
    {
        return $this->hasOne(Yii::$app->getModule('messenger')->authorEntity, ['id' => 'from']);
    }
}
