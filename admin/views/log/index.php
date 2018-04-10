<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/10/18
 * Time: 12:53 PM
 */

use pantera\messenger\admin\models\MessagesLogSearch;
use pantera\messenger\models\MessagesLog;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel MessagesLogSearch */
/* @var $dataProvider ActiveDataProvider */
$this->title = 'Messenger log';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'to',
                'format' => 'raw',
                'value' => function (MessagesLog $model) {
                    return Html::a($model->toUser->profile->name, [
                        '/user/admin/update',
                        'id' => $model->to,
                    ], [
                        'data-pjax' => 0,
                    ]);
                },
            ],
            [
                'attribute' => 'from',
                'format' => 'raw',
                'value' => function (MessagesLog $model) {
                    return Html::a($model->fromUser->profile->name, [
                        '/user/admin/update',
                        'id' => $model->from,
                    ], [
                        'data-pjax' => 0,
                    ]);
                },
            ],
            [
                'attribute' => 'position'
            ],
            [
                'attribute' => 'position_count'
            ],
            [
                'attribute' => 'message_body'
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
            ],
        ],
    ]) ?>
    <?php Pjax::end(); ?>
</div>
