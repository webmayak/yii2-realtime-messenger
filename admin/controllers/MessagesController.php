<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/10/18
 * Time: 12:42 PM
 */

namespace pantera\messenger\admin\controllers;

use pantera\messenger\admin\models\MessengerMessagesSearch;
use pantera\messenger\models\MessengerMessages;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use function is_null;

class MessagesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MessengerMessagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    private function findModel($id)
    {
        $model = MessengerMessages::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}