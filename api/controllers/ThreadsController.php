<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\MessengerThreadsSearch;
use Yii;
use yii\rest\Controller;

class ThreadsController extends Controller
{
    public function behaviors()
    {
        $behaviors = [];
        return array_merge(parent::behaviors(), $behaviors);
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    /**
     * Загрузить список диалогов
     */
    public function actionIndex()
    {
        $searchModel = new MessengerThreadsSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }
}