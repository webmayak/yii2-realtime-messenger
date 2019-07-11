<?php

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\MessengerThreadsSearch;
use pantera\messenger\models\MessengerThreads;
use pantera\messenger\traits\ModuleTrait;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class ThreadsController extends Controller
{
    use ModuleTrait;

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'get' => ['GET'],
        ];
    }

    public function actionGet($id)
    {
        /* @var $object \pantera\messenger\api\models\MessengerThreads */
        $object = Yii::createObject(MessengerThreads::class);
        $model = $object::find()
            ->isAvailableForMe()
            ->andWhere(['=', 'id', $id])
            ->one();
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * Загрузить список диалогов
     */
    public function actionIndex()
    {
        $searchModel = new MessengerThreadsSearch();
        $userId = null;
        if ($this->moduleApi->isAdmin() && Yii::$app->request->get('userId')) {
            $userId = Yii::$app->request->get('userId');
        }
        return $searchModel->search(Yii::$app->request->queryParams, $userId);
    }
}
