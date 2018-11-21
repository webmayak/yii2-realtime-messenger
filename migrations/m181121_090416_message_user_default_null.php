<?php

use yii\db\Migration;

/**
 * Class m181121_090416_message_user_default_null
 */
class m181121_090416_message_user_default_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%messenger_messages}}', 'user_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%messenger_messages}}', 'user_id', $this->integer()->notNull());
        return true;
    }
}
