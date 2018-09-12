<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `messages_log`.
 */
class m180912_055939_drop_messages_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%messages_log}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%messages_log}}', [
            'id' => $this->primaryKey(),
            'to' => $this->integer()->notNull(),
            'from' => $this->integer()->notNull(),
            'partition' => $this->string()->notNull(),
            'position' => $this->integer()->null(),
            'position_count' => $this->integer()->null(),
            'message_body' => $this->text()->null(),
        ]);
    }
}
