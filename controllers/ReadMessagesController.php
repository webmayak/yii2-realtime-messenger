<?php

namespace pantera\messenger\controllers;

use pantera\messenger\helpers\MessagesEncodeHelper;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ReadMessagesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->getModule('messenger')->threadsMode) {
            $threadId = $_POST['$threadId'];
            Yii::$app->db->createCommand("UPDATE " . MessengerMessages::tableName() . " SET readed=1 WHERE thread_id = " . $threadId . " AND user_id <>" . Yii::$app->user->id . "")->execute();
        } else {
            //Получим все треды пользователя
            //при выключеном тредсмод получаем постом ид пользователя а не треда
            $user = MessagesEncodeHelper::decrypt($_POST['$threadId']);
            $threadsIds = Yii::$app->db->createCommand("SELECT id FROM " . MessengerThreads::tableName() . " WHERE (`to` = " . $user . " AND `from` = " . Yii::$app->user->id . " OR `to` = " . Yii::$app->user->id . " AND `from` = " . $user . ")")
                ->queryAll();
            foreach ($threadsIds as $threadId) {
                Yii::$app->db->createCommand("UPDATE " . MessengerMessages::tableName() . " SET readed=1 WHERE thread_id = " . $threadId['id'] . " AND user_id <>" . Yii::$app->user->id . "")->execute();

            }
        }
    }


}