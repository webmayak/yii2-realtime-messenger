<?php

use yii\db\Migration;

/**
 * Handles adding created_at to table `messenger_threads`.
 */
class m180913_044250_add_created_at_column_to_messenger_threads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messenger_threads', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messenger_threads', 'created_at');
    }
}
