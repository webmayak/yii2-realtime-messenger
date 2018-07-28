<?php

use yii\db\Migration;

/**
 * Class m180728_134842_update_tables_name
 */
class m180728_134842_update_tables_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('messages_log', '{{%messages_log}}');
        $this->renameTable('messenger_messages', '{{%messenger_messages}}');
        $this->renameTable('messenger_threads', '{{%messenger_threads}}');
        $this->renameTable('messenger_viewed', '{{%messenger_viewed}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180728_134842_update_tables_name cannot be reverted.\n";
        $this->renameTable('{{%message_log}}', 'message_log');
        $this->renameTable('{{%messenger_messages}}', 'messenger_messages');
        $this->renameTable('{{%messenger_threads}}', 'messenger_threads');
        $this->renameTable('{{%messenger_viewed}}', 'messenger_viewed');
        return true;
    }
}
