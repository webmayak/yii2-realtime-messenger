<?php

use yii\db\Migration;

/**
 * Handles adding key to table `messenger_threads`.
 */
class m181008_140844_add_key_column_to_messenger_threads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%messenger_threads}}', 'key', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%messenger_threads}}', 'key');
    }
}
