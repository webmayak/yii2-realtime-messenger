<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\NotificationSearch;
use pantera\messenger\api\traits\FindModelTrait;
use Yii;
use yii\rest\Controller;

class NotificationController extends Controller
{
    use FindModelTrait;

    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    /**
     * Получить общие количество не просмотренных сообщений и последние диалоги где есть не просмотренные сообшения
     * @return array
     */
    public function actionIndex()
    {
        $countNotViewed = Yii::$app->messengerApi->getCountNotViewedForUser(Yii::$app->user->identity);
        $searchModel = new NotificationSearch();
        $threads = $searchModel->search();
        return [
            'countNotViewed' => $countNotViewed,
            'threads' => $threads,
        ];
    }
}