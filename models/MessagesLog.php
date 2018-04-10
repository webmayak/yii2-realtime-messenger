<?php

namespace pantera\messenger\models;

use common\modules\user\common\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{messages_log}}".
 *
 * The followings are the available columns in table '{{messages_log}}':
 * @property integer $id
 * @property integer $to
 * @property integer $from
 * @property string $partition
 * @property integer $position
 * @property integer $position_count
 * @property string $message_body
 */
class MessagesLog extends ActiveRecord
{
    public static function add($to, $from, $body, $partition = 0, $count = 0, $position = 0)
    {
        $model = new self();
        $model->message_body = $body;
        $model->partition = $partition;
        $model->position = $position;
        $model->to = $to;
        $model->position_count = $count;
        $model->from = $from;
        $model->save();
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{messages_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(['to', 'from', 'partition'], 'required'),
            array(['to', 'from', 'position', 'position_count'], 'number', 'integerOnly' => true),
            array(['message_body'], 'safe'),
        );
    }

    public function getToUserName()
    {
        $user = User::findOne($this->to);
        return "<a href='/user/admin/view/id/" . $this->to . "'>" . $user->profile->name . " " . $user->profile->name . "</a>";
    }

    public function getFromUserName()
    {
        $user = User::findOne($this->from);
        return "<a href='/user/admin/view/id/" . $this->from . "'>" . $user->profile->name . " " . $user->profile->name . "</a>";
    }

    public function getPositionName()
    {
        if ($this->partition == "Запчасти") {
//            return Parts::model()->findByPk($this->position)->description;
        }
    }

    public function getPartitionLink()
    {
        if ($this->partition == "Запчасти") {
            return "<a href='/parts/search'>Запчасти</a>";
        } elseif ($this->partition == "Разборки") {
            return "<a href='/demountCars'>Разборки</a>";
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'to' => 'To',
            'from' => 'From',
            'partition' => 'Partition',
            'position' => 'Position',
            'position_count' => 'Position Count',
            'message_body' => 'Message Body',
        );
    }
}
