<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/19/18
 * Time: 2:57 PM
 */

use pantera\messenger\models\MessengerMessages;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'itemView' => function (MessengerMessages $model, $key, $index, $widget) {
        if ($model->isTwig()) {
            return $model->renderBody();
        } else {
            return $this->render('_view', [
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $widget,
            ]);
        }
    },
]) ?>
