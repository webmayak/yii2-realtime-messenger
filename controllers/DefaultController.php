<?php

namespace pantera\messenger\controllers;

use common\modules\user\common\models\User;
use pantera\messenger\helpers\MessagesEncodeHelper;
use pantera\messenger\models\MessagesLog;
use pantera\messenger\models\MessengerMessages;
use pantera\messenger\models\MessengerThreads;
use function var_dump;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionHideThread()
    {

        $selfId = Yii::$app->user->id;
        $toId = $_POST['user_id'];
        $model = MessengerThreads::find('(`to`=:me AND `from`=:id)  OR (`to`=:id AND `from`=:me)', [':me' => $selfId, ':id' => $toId, 'sort' => 'id']);
        if ($model->to == $selfId) {
            $model->hide_to = 1;
        } else {
            $model->hide_from = 1;
        }
        $model->save();
//        $messagesModel = MessengerMessages::model()->findAll(['thread_id'=>$model->id]);
//
//        foreach($messagesModel as $message){
//            $message->readed = 1;
//            $message->save();
//            print_r($message);exit;
//        }
        Yii::$app->db->createCommand("UPDATE ag_messenger_messages SET readed = 1 WHERE thread_id =" . $model->id . " AND user_" . ($selfId == min($selfId, $toId) ? 'min' : 'max') . " = " . (int)$selfId)->execute();

        $hideUser = "user_" . ($selfId == min((int)$model->to, (int)$model->from) ? 'min' : 'max') . "_hide";
        $sql = "UPDATE ag_messenger_messages SET " . $hideUser . " = 1 WHERE thread_id =" . $model->id;

        Yii::$app->db->createCommand($sql)->execute();

    }

    /*
     * Функция сохранения лога какого-либо сообщения
     * @noresult
     *
     */
    public function messageLog($to, $from, $body, $partition = 0, $count = 0, $position = 0)
    {
        $model = new MessagesLog();
        $model->message_body = $body;
        $model->partition = $partition;
        $model->position = $position;
        $model->to = $to;
        $model->position_count = $count;
        $model->from = $from;
        $model->save();


    }

    public function actionReadMessages()
    {
        $threadId = $_POST['$threadId'];
        Yii::$app->db->createCommand("UPDATE ag_messenger_messages SET readed=1 WHERE thread_id = " . $threadId . " AND user_id <>" . Yii::app()->user->id . "")->execute();

    }

    public function actionSuccessMessage()
    {
        print("kek");
    }

    public function register($email, $name)
    {
//        $user = User::model()->find(['condition' => 'email = :email', 'params' => [':email' => $email]]);
////        if (!is_null($user)) {
////            Yii::app()->user->setFlash('success','Ваше письмо успешно отправлено!');
////            Yii::app()->user->setFlash('warning','Вы уже зарегистрированы на сайте! Войдите чтобы получить больше возможностей!!');
////            return $this->redirect('/user/login/');
////        }
//        $modelRegistration = new RegistrationForm;
//        $profile = new Profile;
//        $balance = new Balance;
//        $modelRegistration->email = $email;
//        $password = Yii::app()->securityManager->generateRandomString(8);
//        $modelRegistration->password = $password;
//        $modelRegistration->username = $email;
//        $modelRegistration->verifyPassword = $modelRegistration->password;
//        $modelRegistration->activkey = UserModule::encrypting(microtime() . $modelRegistration->password);
//        $modelRegistration->password = UserModule::encrypting($modelRegistration->password);
//        $modelRegistration->verifyPassword = UserModule::encrypting($modelRegistration->verifyPassword);
//        $modelRegistration->superuser = 0;
//        $modelRegistration->status = User::STATUS_ACTIVE;
//        if ($modelRegistration->save()) {
//            $profile->first_name = $name;
//            $profile->user_id = $modelRegistration->id;
//            $profile->save();
////            print_r($profile);exit;
//            $balance->user_id = $modelRegistration->id;
//            $balance->save();
//            $content = "<p>Вами было отправлено сообщение на сайте AVTO.GURU! Для того чтобы продолжить работу со своим заказом, мы создали для вас аккаунт на нашем сайте!</p><p>Логин для входа: <b>{$modelRegistration->email}</b></p><p>Пароль для входа: <b>{$password}</b></p>";
//            $content .= "<a href='" . Yii::app()->params->siteName . "/user/login'>Войти на сайт</a>";
//            $template = $this->renderPartial('mail', array('content' => $content, 'title' => 'Быстрая регистрация на сайте AVTO.GURU'), true);
//            EmailTemplate::sendEmail($modelRegistration->email, Yii::app()->params->noReply, 'Быстрая регистрация на сайте AVTO.GURU', $template);
//            return $modelRegistration->id;
//        } else {
//
//        }
//        return $profile->user_id;
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest && empty($_POST)) {
            return $this->redirect('/user/login/');
        }
        //Определим наш userId
        $selfId = Yii::$app->user->id;
        //Получим список пользователей с которыми у нас есть переписки
        $messagesUsers = [];
        //Массив для добавления передаваемых на вью переменных
        $renderArray = [];
        $sql = "SELECT `id`,`updated_at`,`from`,`to` FROM " . MessengerThreads::tableName() . " WHERE `to` = " . $selfId . " 
                UNION
                SELECT `id`,`updated_at`,`from`,`to` FROM " . MessengerThreads::tableName() . " WHERE `from` = " . $selfId . " ORDER BY updated_at DESC";
        $threads = @Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($threads as $thread) {
            if ($thread['from'] != $selfId) {
                $messagesUsers[$thread['from']] = $thread['from'];
            } else {
                $messagesUsers[$thread['to']] = $thread['to'];
            }
        }


//        print_r($threadsTo);exit;
//        $threadsTo = MessengerThreads::model()->findAllByAttributes(array('to'=>$selfId),array('order'=>'updated_at ASC LIMIT 0,1000'));//findAllByAttributes(['to'=>$selfId],['order'=>'updated_at']);
//        foreach ($threadsTo as $thread) {
//            $messagesUsers[$thread->from] = $thread->from;
//        }
//        $threadsFrom = MessengerThreads::model()->findAll('`from`=:from ORDER BY updated_at DESC', [':from' => $selfId]);
//        $sql = "SELECT * FROM `ag_messenger_threads` WHERE `from` = ".$selfId." ORDER BY updated_at DESC";
//        $threadsTo = Yii::app()->db->createCommand($sql)->queryAll();
//        print_r($threadsTo);exit;
//        foreach ($threadsFrom as $thread) {
//            $messagesUsers[$thread->to] = $thread->to;
//        }
        if (isset($_GET["user"]) || isset($_POST['name'])) {
            //Найдем все треды свзянаые с нами и переданым пользователем
            //Если включена настройка модуля
            if (Yii::$app->getModule('messenger')->threadsMode) {
                $model = MessengerThreads::find()->where('(`to`=:me AND `from`=:id) OR (`to`=:id AND `from`=:me)', [':me' => $selfId, ':id' => $_GET['user']])->one();
                $renderArray['threads'] = $model;
                if (isset($_GET['thread_id'])) {
                    if ($threadModel = MessengerThreads::model()->findByPk($_GET['thread_id'])) {
                        if ($threadModel->to == $selfId || $threadModel->from == $selfId) {
                            $messagesModel = MessengerMessages::model()->findAllByAttributes(['thread_id' => $_GET['thread_id']]);

                            $renderArray['messages'] = $messagesModel;
                        } else {
                            $this->redirect('/messages/');
                        }
                    } else {
                        $this->redirect('/messages/');
                    }
                }
            } else {
                //Найдем тред в пределах которого два пользователя будут общаться
                //ЭТО ВРЕМЕННО
                if (!empty($_POST)) {

                    //При условии что пользователь не авторизован.
                    //--- Начало неавторизованного пользователя -----//
                    if (Yii::$app->user->isGuest) {
                        if (Recaptcha::check()) {
                            if (!empty(@$_POST['name'])) {
                                $this->layout = false;
//                                $userEmail = User::model()->findByPk(@$_POST['seller_id'])->email;
//                                $subject = "Пользователь желает приобрести у Вас запчасть на AVTO.GURU";
//                                $content = 'Пользователь ' . $_POST['name'] . " желает приобрести у вас";
//                                if (!empty(@$_POST['part_code'])) {
//                                    $content = "Запчасти <br> " . $content . "<br>- Запчасть" . preg_replace(
//                                            '/^(.*)[#№](\S+)(.*)$/ui',
//                                            '$1 <a target="_blank" href="/parts/search/$2">$2</a> $3',
//                                            '#' . $_POST['part_code']);
//                                }
//                                if (!empty(@$_POST['demountId'])) {
//                                    $content = "Разборки <br> " . $content . "<br>-Автомобиль в разборе:" . DemountCars::model()->findByPk($_POST['demountId'])->name . "<br>";
//                                }
//                                if (!empty(@$_POST['CompanyMessages']['body'])) {
//                                    $content = $content . "<br>Текст сообщения:<br>" . $_POST['CompanyMessages']['body'];
//                                }
//                                if (empty(@$_POST['demountId']) && empty(@$_POST['part_code'])) {
//                                    $content = "Пользователь " . @$_POST['name'] . " написал вам на сайте AVTO.GURU<br>Вы можете ответить ему отправив письмо на e-mail: " . @$_POST['email'] . "  или связаться с ним по телефону: " . @$_POST['phone'] . "<br>" . "Текст сообщения:<br>" . @$_POST['CompanyMessages']['body'];
//                                }

//                                $template = $this->render('mail', array('content' => $content, 'title' => $subject), true);
//
//                                $queue = new EmailsQueue();
//                                $queue->to_email = $userEmail;
//                                $queue->from_email = Yii::app()->params['adminEmail'];
//                                $queue->subject = $subject;
//                                $queue->body = $template;
//                                $queue->reply_to = @$_POST['email'];
//                                $queue->key = 'msgFromKekToCompany';
//                                $queue->save();

                                if (User::model()->findByAttributes(['email' => $_POST['email']])->id) {

                                    $newUserId = User::model()->findByAttributes(['email' => $_POST['email']])->id;

                                } else {
                                    $newUserId = $this->register($_POST['email'], $_POST['name']);
                                }

                            }
                        } else {
                            Yii::$app->user->setFlash('warning', 'Каптча не валидна!');
                            $url = 'company/' . $_POST["seller_id"] . '/message?exit=1';
                            if (!empty($_POST['part_code'])) {
                                $url = $url . '&partId=' . $_POST['part_id'];
                            }
                            if (!empty($_POST['demountId'])) {
                                $url = $url . '&demountId' . $_POST['demountId'];
                            }
                            return $this->redirect($url);
                        }
                    }
                    /* Конец неавторизованного пользователя */

                    //если пользователь авторизован
                    if (!empty(@$_GET['user_id'])) {
                        $id = $_GET['user_id'];
                    } else {
                        $id = $_POST['seller_id'];
                    }
                    $model = MessengerThreads::model()->find('(`to`=:me AND `from`=:id)  OR (`to`=:id AND `from`=:me)', [':me' => !empty($newUserId) ? $newUserId : $selfId, ':id' => $id, 'sort' => 'id']);
                    //Создадим запись нового треда, если до этого у пользователей небыло диалогов.
                    if (User::model()->findByPk(!empty($_POST['seller_id']) ? $_POST['seller_id'] : $_GET['user'])) {
                        if (empty($model->from)) {
                            $model = new MessengerThreads();
                            $model->from = !empty($newUserId) ? $newUserId : $selfId;
                            $model->to = !empty($_POST['seller_id']) ? $_POST['seller_id'] : $_GET["user"];
                            $model->subject = 'kek';
                            $model->updated_at = date("Y-m-d H:i:s");
                            $model->save();
//                                print_r($model);exit;


                        } else {
                            $model->updated_at = date("Y-m-d H:i:s");
                            $model->hide_to = 0;
                            $model->hide_from = 0;
                            $model->save();

                        }

                        $newMessage = new MessengerMessages();
                        $newMessage->user_id = !empty($newUserId) ? $newUserId : $selfId;
                        $newMessage->thread_id = $model->id;
                        $partition = 'Обычное сообщение';
                        $count = 0;
                        $positionId = 0;
                        if (!empty(@$_POST['part_code'])) {
                            $part_code = (string)'#' . $_POST['part_code'];
                            $_POST['CompanyMessages']['body'] = "Сообщение из раздела Запчасти:" . PHP_EOL . $_POST['CompanyMessages']['body']
                                . PHP_EOL . " - Позиция: " . $part_code
                                . PHP_EOL . " - Количество: " . $_POST['count'];
                            $partition = "Запчасти";
                            $count = $_POST['count'];
                            if (!empty($_POST['part_id'])) {
                                $positionId = $_POST['part_id'];
                            }

                        }
                        if (!empty($_POST['demountId'])) {
                            $_POST['CompanyMessages']['body'] = "Сообщение из раздела Разборки:" . PHP_EOL . "Авто: demount" . $_POST['demountId'] . "[" . DemountCars::model()->findByPk($_POST['demountId'])->name . "]" . PHP_EOL . $_POST['CompanyMessages']['body'];
                            $partition = "Разборки";//[NAME] -> <a href=\"http://avto.guru/demountCars/ID\">NAME</a>;
                            $positionId = $_POST['demountId'];
                        }
                        if (empty($_POST['phone'])) {
                            $_POST['phone'] = "Не указан";
                        }

                        $_POST['CompanyMessages']['body'] = $_POST['CompanyMessages']['body']
                            . PHP_EOL . " - Тел: " . @$_POST['phone'] . "" . PHP_EOL . "- e-mail: " . @$_POST['email'];

                        $newMessage->body = @$_POST['CompanyMessages']['body'];
                        $newMessage->save();
                        $nodeSendUserId = MessengerThreads::model()->findByPk($newMessage->thread_id);
                        if ($nodeSendUserId->to == $newMessage->user_id) {
                            $nodeSendUserId = $nodeSendUserId->from;
                        } else {
                            $nodeSendUserId = $nodeSendUserId->to;
                        }
                        $this->curl_request_async(Yii::app()->params['node_server'] . "/hash/" . $this->encrypt($nodeSendUserId) . "/sender/" . Yii::app()->user->id, ['a' => 'b'], 'GET');
                        $this->messageLog($model->to, !empty($newUserId) ? $newUserId : $selfId, $newMessage->body, $partition, $count, $positionId);
                        return $this->renderPartial('message_success', [
                            'sellerId' => $model->to,
                        ]);
                    }
                    /* Конец авторизованного пользователя */
                }
                if ($model = MessengerThreads::find()->where('(`to`=:me AND `from`=:id)  OR (`to`=:id AND `from`=:me)', [':me' => !empty($newUserId) ? $newUserId : $selfId, ':id' => !empty($_POST['seller_id']) ? $_POST['seller_id'] : $_GET["user"], 'sort' => 'id'])->one()) {
//                    if($model->to == $selfId){
                    $model->hide_to = 0;
//                    }
//                    else{
                    $model->hide_from = 0;
//                    }

//                print_r($model);exit;
                    $sql = "SELECT m.body, m.user_id, m.created_at FROM " . MessengerMessages::tableName() . " m 
                        WHERE m.user_min = " . min((int)$model->to, (int)$model->from) . " AND user_max = " . max((int)$model->to, (int)$model->from) . "
                        AND user_" . ($selfId == min((int)$model->to, (int)$model->from) ? 'min' : 'max') . "_hide = 0
                        ORDER BY m.id ASC";
                    $messagesModel = MessengerMessages::findBySql($sql)->all();
                    $renderArray['messages'] = $messagesModel;
                    $renderArray['threadId'] = $model->id;
                    //Работаем с полученой моделью
                    //Получим все сообщения этого треда
                } else {
                    return $this->redirect('/messages/');
                }

            }
        }
        $users = [];
        foreach ($messagesUsers as $user) {
            array_push($users, User::findOne($user));
        }

//        print_r($users);exit;
//        $users = User::model()->findAllByPk($messagesUsers);
//        print_r($users);exit;
        $renderArray['users'] = $users;
//        var_dump(MessagesEncodeHelper::encrypt(7));
//        var_dump(MessagesEncodeHelper::decrypt(MessagesEncodeHelper::encrypt(7)));
//        die();

//        var_dump(Yii::$app->params['node_server'] . "/new-message?hash=" . MessagesEncodeHelper::encrypt(Yii::$app->user->id) . "&sender=" . Yii::$app->user->id);
////        die();
//        file_get_contents(Yii::$app->params['node_server'] . "/new-message?hash=" . MessagesEncodeHelper::encrypt(Yii::$app->user->id) . "&sender=" . Yii::$app->user->id);
//        die();
        return $this->render('index', $renderArray);

    }

    // $type must equal 'GET' or 'POST'
    private function curl_request_async($url, $params, $type = 'POST')
    {
        foreach ($params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);

        $parts = parse_url($url);

        $fp = fsockopen($parts['host'],
            isset($parts['port']) ? $parts['port'] : 80,
            $errno, $errstr, 30);

        // Data goes in the path for a GET request
        if ('GET' == $type) $parts['path'] .= '?' . $post_string;

        $out = "$type " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        // Data goes in the request body for a POST request
        if ('POST' == $type && isset($post_string)) $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }


}