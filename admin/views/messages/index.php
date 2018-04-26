<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/10/18
 * Time: 12:53 PM
 */

use pantera\messenger\admin\models\MessengerMessagesSearch;
use pantera\messenger\models\MessengerMessages;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel MessengerMessagesSearch */
/* @var $dataProvider ActiveDataProvider */
$this->title = 'Messenger messages';
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
                'header' => 'Direction',
                'format' => 'raw',
                'value' => function (MessengerMessages $model) {
                    $from = \common\modules\user\common\models\User::findOne($model->user_id);
                    if ($model->user_min == $model->user_id) {
                        $to = $model->user_max;
                    } else {
                        $to = $model->user_min;
                    }
                        $fromLink = Html::a($from->profile->name, [
                            '/user/admin/update',
                            'id' => $from->id,
                        ], [
                            'data-pjax' => 0,
                        ]);
                    if ($to) {
                        $to = \common\modules\user\common\models\User::findOne($to);
                        $toLink = Html::a($to->profile->name, [
                            '/user/admin/update',
                            'id' => $to->id,
                        ], [
                            'data-pjax' => 0,
                        ]);
                    }
                    return 'From: ' . $fromLink . ($to ? '<br>To: ' . $toLink : '');
                }
            ],
            'body',
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
            ],
        ],
    ]) ?>
    <?php Pjax::end(); ?>
</div>
