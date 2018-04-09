<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages_log`.
 */
class m180409_003940_create_messages_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('messages_log', [
            'id' => $this->primaryKey(),
            'to' => $this->integer()->notNull(),
            'from' => $this->integer()->notNull(),
            'partition' => $this->string()->notNull(),
            'position' => $this->integer()->null(),
            'position_count' => $this->integer()->null(),
            'message_body' => $this->text()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('messages_log');
    }
}
