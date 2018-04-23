<?php

use yii\db\Migration;

/**
 * Handles adding is_pinned to table `messenger_messages`.
 */
class m180423_040400_add_is_pinned_column_to_messenger_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messenger_messages', 'is_pinned', $this->boolean()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messenger_messages', 'is_pinned');
    }
}
