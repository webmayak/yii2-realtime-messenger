<?php

use yii\db\Migration;

/**
 * Handles adding last_message_at to table `messenger_threads`.
 */
class m180721_033935_add_last_message_at_column_to_messenger_threads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messenger_threads', 'last_message_at', $this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messenger_threads', 'last_message_at');
    }
}
