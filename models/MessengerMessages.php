<?php

namespace pantera\messenger\models;

use mikehaertl\tmp\File;
use pantera\media\behaviors\MediaUploadBehavior;
use pantera\media\models\Media;
use Yii;
use yii\db\ActiveRecord;
use function preg_match;

/**
 * This is the model class for table "{{messenger_messages}}".
 *
 * The followings are the available columns in table '{{messenger_messages}}':
 * @property string $id
 * @property integer $thread_id
 * @property integer $user_id
 * @property string $body
 * @property string $created_at
 * @property int $user_min [int(11)]
 * @property int $user_max [int(11)]
 * @property bool $notified [tinyint(4)]
 * @property bool $readed [tinyint(4)]
 * @property int $user_min_hide [int(11)]
 * @property int $user_max_hide [int(11)]
 * @property int $is_pinned
 * @property array $data
 *
 * @property $allunreadmessages
 * @property $lastmessage
 * @property ActiveRecord $user
 * @property Media[] $attachments
 * @property MessengerThreads $thread
 */
class MessengerMessages extends ActiveRecord
{
    /**
     * Рендеринг сообщения через twig
     * @return string
     */
    public function renderBody(): string
    {
        $file = new File($this->body, '.twig');
        $result = Yii::$app->view->renderFile($file->getFileName());
        return $result;
    }

    /**
     * Проверить используется ли twig в сообщении
     * @return bool
     */
    public function isTwig(): bool
    {
        if (preg_match('/^{{.*}}$/', $this->body)) {
            return true;
        }
        return false;
    }

    public function behaviors()
    {
        return [
            'media' => [
                'class' => MediaUploadBehavior::className(),
                'buckets' => [
                    'attachments' => [
                        'multiple' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{messenger_messages}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            [['body'], 'truncateEmoji'],
            array(['thread_id', 'user_id', 'body'], 'required'),
            array(['thread_id', 'user_id'], 'number', 'integerOnly' => true),
            [['is_pinned'], 'boolean'],
        );
    }

    public function truncateEmoji($attribute)
    {
        $emojiList = \Emoji\detect_emoji($this->{$attribute});
        foreach ($emojiList as $emoji) {
            $this->{$attribute} = str_replace($emoji['emoji'], '', $this->{$attribute});
        }
    }

    public function getLastThreadMessage()
    {
        $result = Yii::$app->db->createCommand("SELECT t.id , t.to , t.from , m.created_at, m.body, m.id FROM " . MessengerMessages::tableName() . " m 
                                                 LEFT JOIN " . MessengerThreads::tableName() . " t ON (t.from = m.user_id OR (t.to = m.user_id )) AND t.id = m.thread_id
                                                 WHERE
                                                   (m.thread_id = " . $this->thread_id . ")
                                                 ORDER BY m.created_at DESC 
                                                 LIMIT 1")
            ->queryAll();

        $result[0]['body'] = @$result[0]['body'];

        $result[0]['created_at'] = @$result[0]['created_at'];

        return $result;

    }

    public function getLastMessage()
    {
        $result = Yii::$app->db->createCommand("SELECT t.id , t.to , t.from ,m.readed, m.created_at, m.body, m.id
                                                 FROM " . MessengerMessages::tableName() . " m 
                                                 LEFT JOIN " . MessengerThreads::tableName() . " t ON (t.from = m.user_id OR (t.to = m.user_id )) AND t.id = m.thread_id
                                                 WHERE
                                                    (t.to = '" . $this->user_id . "' AND t.from = '" . Yii::$app->user->id . "' 
                                                    AND (m.user_id='" . $this->user_id . "' OR m.user_id='" . Yii::$app->user->id . "')) 
                                                    OR (t.to = '" . Yii::$app->user->id . "' AND t.from = '" . $this->user_id . "' AND 
                                                    (m.user_id='" . Yii::$app->user->id . "' OR m.user_id='" . $this->user_id . "')) 
                                                 ORDER BY m.created_at DESC
                                                 LIMIT 1")
            ->queryAll();

        $result['body'] = @$result[0]['body'];
        $result['created_at'] = @$result[0]['created_at'];
        return $result;
    }

    public function getAllUnreadMessages()
    {
        if ($this->user_id == Yii::$app->user->id) {
            return false;
        }
        $result = Yii::$app->db->createCommand("SELECT COUNT(1) AS unread_count
FROM " . MessengerMessages::tableName() . " m 
 LEFT JOIN " . MessengerThreads::tableName() . " t ON m.thread_id = t.id AND (t.from = m.user_id OR t.to = m.user_id)
WHERE 
(m.readed = 0 OR m.readed IS NULL) AND (m.user_id = " . $this->user_id . ") 
AND (
(t.from = " . $this->user_id . " AND t.to = " . Yii::$app->user->id . ")
OR 
(t.to = " . $this->user_id . " AND t.from = " . Yii::$app->user->id . ")
)")->queryAll();


        return $result[0]['unread_count'];
    }

    public function getThreadUnreadCount()
    {
//        return count($this->findAll(['condition' => "readed = 0 AND thread_id = :thread_id AND user_id <> :user_id",
//            'params' => [':thread_id' => $this->thread_id,
//                ':user_id' => Yii::$app->user->id,
//            ]
//
//        ]));
        return self::find()
            ->where("readed = 0 AND thread_id = :thread_id AND user_id <> :user_id", [':thread_id' => $this->thread_id,
                ':user_id' => Yii::$app->user->id,
            ])
            ->count();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'thread_id' => 'Thread',
            'user_id' => 'User',
            'body' => 'Body',
            'created_at' => 'Created At',
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(MessengerThreads::className(), ['id' => 'thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->getModule('messenger')->authorEntity, ['id' => 'user_id']);
    }
}
