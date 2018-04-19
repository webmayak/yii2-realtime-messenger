<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/19/18
 * Time: 2:57 PM
 */

namespace pantera\messenger\widgets\messengerMessageList;


use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use function is_null;
use const SORT_DESC;

class MessengerMessageList extends Widget
{
    /* @var string */
    public $key;

    public function run()
    {
        parent::run();
        $dataProvider = $this->initDataProvider();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function initDataProvider()
    {
        $query = MessengerMessages::find()
            ->joinWith(['thread'])
            ->where(['=', MessengerThreads::tableName() . '.key', $this->key]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }

    public function init()
    {
        parent::init();
        if (is_null($this->key)) {
            throw new InvalidConfigException('Параметр {key} обязателен');
        }
    }
}