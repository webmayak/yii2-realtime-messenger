<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messenger_messages`.
 */
class m180409_003702_create_messenger_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('messenger_messages', [
            'id' => $this->primaryKey(),
            'thread_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'user_min' => $this->integer()->null(),
            'user_max' => $this->integer()->null(),
            'body' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'notified' => $this->tinyInteger(4)->notNull()->defaultValue(0),
            'readed' => $this->tinyInteger(4)->notNull()->defaultValue(0),
            'user_min_hide' => $this->integer()->null()->defaultValue(0),
            'user_max_hide' => $this->integer()->null()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('messenger_messages');
    }
}
