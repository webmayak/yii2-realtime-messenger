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