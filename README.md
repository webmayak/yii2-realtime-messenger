# yii2-realtime-messenger

#### Установка
```
composer require pantera-digital/yii2-realtime-messenger "@dev"
```
#### Настройка
```
'modules' => [
    'messenger' => [
        'class' => \pantera\messenger\Module::className(),
        'nodeServer' => 'http://127.0.0.1:8008',
    ],
]
```
Обязательно нужно заполнить параметр модуля nodeServer

в проекте необходимо запустить vendor/pantera-digital/yii2-realtime-messenger/server.js
#### Api
Для работы с месенжером есть api
```
'components' => [
    'messengerApi' => [
        'class' => pantera\messenger\components\api\MessengerApi::className(),
    ],
],
```
###### Создание сообщения
```
Yii::$app->messengerApi->createMessage()
    ->setBody('test')
    ->setThreadId(241)
    ->setUserId(Yii::$app->user->id)
    ->send();
```
###### Получить идентификатор треда по клучю
```
Yii::$app->messengerApi->getThreadIdByKey($key)
```