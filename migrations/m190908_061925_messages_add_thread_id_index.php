<?php

use yii\db\Migration;

/**
 * Class m190908_061925_messages_add_thread_id_index
 */
class m190908_061925_messages_add_thread_id_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-messenger_message-thread_id', '{{%messenger_messages}}', 'thread_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-messenger_message-thread_id', '{{%messenger_messages}}');
        return true;
    }
}
