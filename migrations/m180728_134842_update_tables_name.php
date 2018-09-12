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
        $this->renameTable('messages_log', 'messages_log_temp');
        $this->renameTable('messages_log_temp', '{{%messages_log}}');

        $this->renameTable('messenger_messages', 'messenger_messages_temp');
        $this->renameTable('messenger_messages_temp', '{{%messenger_messages}}');

        $this->renameTable('messenger_threads', 'messenger_threads_temp');
        $this->renameTable('messenger_threads_temp', '{{%messenger_threads}}');

        $this->renameTable('messenger_viewed', 'messenger_viewed_temp');
        $this->renameTable('messenger_viewed_temp', '{{%messenger_viewed}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180728_134842_update_tables_name cannot be reverted.\n";
        $this->renameTable('{{%messages_log}}', 'messages_log_temp');
        $this->renameTable('messages_log_temp', 'messages_log');

        $this->renameTable('{{%messenger_messages}}', 'messenger_messages_temp');
        $this->renameTable('messenger_messages_temp', 'messenger_messages');

        $this->renameTable('{{%messenger_threads}}', 'messenger_threads_temp');
        $this->renameTable('messenger_threads_temp', 'messenger_threads');

        $this->renameTable('{{%messenger_viewed}}', 'messenger_viewed_temp');
        $this->renameTable('messenger_viewed_temp', 'messenger_viewed');
        return true;
    }
}
