<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/19/18
 * Time: 3:48 PM
 */

use pantera\messenger\models\MessengerMessages;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model MessengerMessages */
/* @var $key string */
?>
<?php $form = ActiveForm::begin([
    'action' => ['/messenger/send/create', 'key' => $key],
]) ?>

<?= $form->field($model, 'body')->textarea([
    'rows' => 4,
])->label(false) ?>

<?= Html::activeHiddenInput($model, 'thread_id') ?>

<?= Html::submitButton('Отправить', [
    'class' => 'btn btn-success',
]) ?>

<?php ActiveForm::end() ?>
