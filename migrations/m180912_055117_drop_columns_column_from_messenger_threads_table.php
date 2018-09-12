<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `messenger_threads`.
 */
class m180912_055117_drop_columns_column_from_messenger_threads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%messenger_threads}}', 'to');
        $this->dropColumn('{{%messenger_threads}}', 'hide_to');
        $this->dropColumn('{{%messenger_threads}}', 'hide_from');
        $this->dropColumn('{{%messenger_threads}}', 'updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%messenger_threads}}', 'to', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%messenger_threads}}', 'hide_to', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%messenger_threads}}', 'hide_from', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%messenger_threads}}', 'updated_at', $this->datetime()->null());
    }
}
