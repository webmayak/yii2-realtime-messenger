<?php

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\MessengerThreadsSearch;
use pantera\messenger\traits\ModuleTrait;
use Yii;
use yii\rest\Controller;

class ThreadsController extends Controller
{
    use ModuleTrait;

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
        $userId = null;
        if ($this->moduleApi->isAdmin() && Yii::$app->request->get('userId')) {
            $userId = Yii::$app->request->get('userId');
        }
        return $searchModel->search(Yii::$app->request->queryParams, $userId);
    }
}
