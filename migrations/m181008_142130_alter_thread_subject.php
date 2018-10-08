<?php

use pantera\messenger\models\MessengerThreads;
use yii\db\Migration;

/**
 * Class m181008_142130_alter_thread_subject
 */
class m181008_142130_alter_thread_subject extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(MessengerThreads::tableName(), 'subject', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181008_142130_alter_thread_subject cannot be reverted.\n";
        $this->alterColumn(MessengerThreads::tableName(), 'subject', $this->string()->notNull());
        return true;
    }
}
