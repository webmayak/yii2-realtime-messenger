<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\models\MessengerMessagesSearch;
use Yii;
use yii\rest\Controller;

class MessagesController extends Controller
{
    /**
     * Загрузить список сообщений в диалоге
     * @param int $id Идентификатор диалога
     * @return \yii\data\ActiveDataProvider
     */
    public function actionIndex($id)
    {
        $searchModel = new MessengerMessagesSearch();
        $searchModel->thread_id = $id;
        return $searchModel->search(Yii::$app->request->queryParams);
    }
}