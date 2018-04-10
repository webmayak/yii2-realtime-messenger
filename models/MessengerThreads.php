<?php

namespace pantera\messenger\models;

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
 *
 * @property MessengerMessages[] $messengerMessages
 */
class MessengerThreads extends ActiveRecord
{
    public static $hide_to;
    public static $hide_from;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{messenger_threads}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(['from', 'to'], 'number', 'integerOnly' => true),
            array(['subject'], 'string', 'max' => 255),
        );
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
}
