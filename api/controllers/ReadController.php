<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 7/17/18
 * Time: 10:52 PM
 */

namespace pantera\messenger\api\controllers;

use pantera\messenger\api\traits\FindModelTrait;
use pantera\messenger\models\MessengerViewed;
use Yii;
use yii\rest\Controller;

class ReadController extends Controller
{
    use FindModelTrait;

    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

    /**
     * Пометить сообщение прочитаным конкретным пользователем
     * @return void
     */
    public function actionIndex()
    {
        array_map(function ($id) {
            $this->findModel($id);
            $model = new MessengerViewed([
                'user_id' => Yii::$app->user->id,
                'message_id' => $id,
            ]);
            $model->save();
        }, Yii::$app->request->post('ids', []));
    }
}