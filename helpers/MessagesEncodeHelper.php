<?php
/**
 * Created by PhpStorm.
 * User: Оля
 * Date: 14.12.2016
 * Time: 20:56
 */

namespace pantera\messenger\helpers;

use function hex2bin;
use function openssl_decrypt;

class MessagesEncodeHelper
{
    const encryptKey = 'MySecretKey12345';
    const iv = '1234567890123456';
//    const iv = '';
//    const method = 'seed-ecb';
    const method = 'AES-128-CBC';

    static function decrypt($data)
    {
//        return Yii::$app->security->decryptByKey(base64_decode($data), self::encryptKey);
        return openssl_decrypt(hex2bin($data), self::method, self::encryptKey, 0, self::iv);
//        return $data;
    }

    static function encrypt($data)
    {
//        return base64_encode(Yii::$app->security->encryptByKey($data, self::encryptKey));
//        foreach (openssl_get_cipher_methods() as $method) {
//            var_dump($method);
//            var_dump(@openssl_encrypt($data, $method, self::encryptKey, 0, self::iv));
//        }
//        die();
        return bin2hex(openssl_encrypt($data, self::method, self::encryptKey, 0, self::iv));
//        return $data;
    }
}