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
 * @property array $data
 *
 * @property ActiveRecord $user
 * @property Media[] $attachments
 * @property MessengerThreads $thread
 */
class MessengerMessages extends ActiveRecord
{
    /* @var string Сценарий когда создаеться пустое сообщение */
    const SCENARIO_EMPTY = 'scenarioEmpty';

    /**
     * Формируем сценарии
     * добавляем новый в котором будет отсутствовать требования заполнять тело сообщения
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $default = $scenarios[self::SCENARIO_DEFAULT];
        $empty = $default;
        unset($empty['body']);
        $scenarios[self::SCENARIO_EMPTY] = $empty;
        return $scenarios;
    }

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
        return '{{%messenger_messages}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['body'], 'truncateEmoji'],
            [['body'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['thread_id'], 'required'],
            [['thread_id', 'user_id'], 'number', 'integerOnly' => true],
        ];
    }

    public function truncateEmoji($attribute)
    {
        $emojiList = \Emoji\detect_emoji($this->{$attribute});
        foreach ($emojiList as $emoji) {
            $this->{$attribute} = str_replace($emoji['emoji'], '', $this->{$attribute});
        }
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
