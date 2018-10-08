<?php

namespace pantera\messenger\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{messenger_threads}}".
 *
 * The followings are the available columns in table '{{messenger_threads}}':
 * @property integer $id
 * @property string $subject
 * @property integer $from
 * @property string $last_message_at
 * @property string $created_at
 * @property string $key
 *
 * @property MessengerMessages[] $messengerMessages
 * @property IdentityInterface $userFrom
 * @property MessengerThreadUser[] $relationWithUsers
 */
class MessengerThreads extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%messenger_threads}}';
    }

    public function rules()
    {
        return [
            [['from'], 'number', 'integerOnly' => true],
            [['subject', 'key'], 'string', 'max' => 255],
            [['last_message_at', 'created_at'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'from' => 'From',
        ];
    }

    public function getMessengerMessages()
    {
        return $this->hasOne(MessengerMessages::className(), ['thread_id' => 'id']);
    }

    public function getUserFrom()
    {
        return $this->hasOne(Yii::$app->getModule('messenger')->authorEntity, ['id' => 'from']);
    }

    public function getRelationWithUsers()
    {
        return $this->hasMany(MessengerThreadUser::class, ['thread_id' => 'id']);
    }
}
