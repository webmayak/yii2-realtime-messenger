<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 4/19/18
 * Time: 2:57 PM
 */

use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'itemView' => '_view',
]) ?>
