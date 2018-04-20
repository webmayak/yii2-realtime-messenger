<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/19/18
 * Time: 3:00 PM
 */

use pantera\messenger\models\MessengerMessages;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MessengerMessages */
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <?= $model->user->username ?>
        </div>
        <div class="pull-right">
            <?= $model->created_at ?>
        </div>
    </div>
    <div class="panel-body">
        <?= nl2br($model->body) ?>
        <?php if ($model->attachments): ?>
            <ul>
                <?php foreach ($model->attachments as $attachment): ?>
                    <li>
                        <?= Html::a($attachment->name, [
                            '/messenger/attachment/download',
                            'id' => $attachment->id,
                        ]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
