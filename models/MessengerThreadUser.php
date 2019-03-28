<?php

namespace pantera\messenger\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%messenger_thread_user}}".
 *
 * @property int $thread_id
 * @property int $user_id
 * @property string $created_at
 *
 * @property MessengerThreads $thread
 * @property IdentityInterface $user
 */
class MessengerThreadUser extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messenger_thread_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thread_id', 'user_id'], 'required'],
            [['thread_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['thread_id', 'user_id'], 'unique', 'targetAttribute' => ['thread_id', 'user_id']],
            [
                ['thread_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => MessengerThreads::class,
                'targetAttribute' => ['thread_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'thread_id' => 'Thread ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(MessengerThreads::class, ['id' => 'thread_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Yii::$app->getModule('messenger')->authorEntity, ['id' => 'user_id']);
    }
}
