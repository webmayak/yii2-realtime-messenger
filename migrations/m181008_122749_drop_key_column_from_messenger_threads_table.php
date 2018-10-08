<?php

use yii\db\Migration;

/**
 * Handles dropping key from table `messenger_threads`.
 */
class m181008_122749_drop_key_column_from_messenger_threads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%messenger_threads}}', 'key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%messenger_threads}}', 'key', $this->string()->null());
    }
}
