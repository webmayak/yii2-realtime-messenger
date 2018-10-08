<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messenger_threads_user`.
 * Has foreign keys to the tables:
 *
 * - `messenger_threads`
 * - `user`
 */
class m181008_113744_create_junction_messenger_threads_and_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messenger_thread_user}}', [
            'thread_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'PRIMARY KEY(thread_id, user_id)',
        ]);

        // creates index for column `thread_id`
        $this->createIndex(
            'idx-messenger_thread_user-thread_id',
            '{{%messenger_thread_user}}',
            'thread_id'
        );

        // add foreign key for table `messenger_threads`
        $this->addForeignKey(
            'fk-messenger_thread_user-thread_id',
            '{{%messenger_thread_user}}',
            'thread_id',
            '{{%messenger_threads}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-messenger_thread_user-user_id',
            '{{%messenger_thread_user}}',
            'user_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `messenger_threads`
        $this->dropForeignKey(
            'fk-messenger_thread_user-thread_id',
            '{{%messenger_thread_user}}'
        );

        // drops index for column `thread_id`
        $this->dropIndex(
            'idx-messenger_thread_user-thread_id',
            '{{%messenger_thread_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-messenger_thread_user-user_id',
            '{{%messenger_thread_user}}'
        );

        $this->dropTable('{{%messenger_thread_user}}');
    }
}
