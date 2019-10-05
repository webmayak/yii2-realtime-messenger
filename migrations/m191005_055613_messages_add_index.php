<?php

use yii\db\Migration;

/**
 * Class m191005_055613_messages_add_index
 */
class m191005_055613_messages_add_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-messenger_messages-thread_id-created_at', '{{%messenger_messages}}', [
            'thread_id',
            'created_at',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-messenger_messages-thread_id-created_at', '{{%messenger_messages}}');
        return true;
    }
}
