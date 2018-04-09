<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messenger_threads`.
 */
class m180409_002400_create_messenger_threads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{messenger_threads}}', [
            'id' => $this->primaryKey(),
            'subject' => $this->string()->notNull()->defaultValue(0),
            'from' => $this->integer()->notNull()->defaultValue(0),
            'to' => $this->integer()->notNull()->defaultValue(0),
            'hide_to' => $this->integer()->notNull()->defaultValue(0),
            'hide_from' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->datetime()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{messenger_threads}}');
    }
}
