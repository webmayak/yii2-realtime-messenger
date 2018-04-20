<?php

namespace pantera\messenger\controllers;

use pantera\messenger\models\MessengerMessages;
use pantera\messenger\Module;
use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AttachmentController extends Controller
{
    /* @var Module */
    public $module;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::className(),
                'only' => ['upload', 'delete'],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => \pantera\media\actions\kartik\MediaUploadActionKartik::className(),
                'model' => function () {
                    if (Yii::$app->request->get('id')) {
                        return MessengerMessages::findOne(Yii::$app->request->get('id'));
                    } else {
                        return new MessengerMessages();
                    }
                },
                'rules' => [
                    'media' => [
                        'media',
                        'file',
                        'maxSize' => 1024 * 1024 * 10,
                        'extensions' => 'jpg, jpeg, gif, png, zip, rar, doc, docx, xls, xslx, pdf',
                    ],
                ],
            ],
            'delete' => [
                'class' => \pantera\media\actions\kartik\MediaDeleteActionKartik::className(),
                'model' => function () {
                    return \pantera\media\models\Media::findOne(Yii::$app->request->post('id'));
                }
            ],
            'download' => [
                'class' => \pantera\media\actions\MediaDownloadAction::className(),
                'model' => function () {
                    return \pantera\media\models\Media::findOne(Yii::$app->request->get('id'));
                }
            ],
        ];
    }
}