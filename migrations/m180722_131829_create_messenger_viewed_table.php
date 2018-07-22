<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messenger_viewed`.
 * Has foreign keys to the tables:
 *
 * - `messenger_messages`
 */
class m180722_131829_create_messenger_viewed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('messenger_viewed', [
            'user_id' => $this->integer()->notNull(),
            'message_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('messenger_viewed_pk', 'messenger_viewed', [
            'user_id',
            'message_id',
        ]);

        // creates index for column `message_id`
        $this->createIndex(
            'idx-messenger_viewed-message_id',
            'messenger_viewed',
            'message_id'
        );

        // add foreign key for table `messenger_messages`
        $this->addForeignKey(
            'fk-messenger_viewed-message_id',
            'messenger_viewed',
            'message_id',
            'messenger_messages',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `messenger_messages`
        $this->dropForeignKey(
            'fk-messenger_viewed-message_id',
            'messenger_viewed'
        );

        // drops index for column `message_id`
        $this->dropIndex(
            'idx-messenger_viewed-message_id',
            'messenger_viewed'
        );

        $this->dropTable('messenger_viewed');
    }
}
