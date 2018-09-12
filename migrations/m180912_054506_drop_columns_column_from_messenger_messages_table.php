<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `messenger_messages`.
 */
class m180912_054506_drop_columns_column_from_messenger_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%messenger_messages}}', 'user_min');
        $this->dropColumn('{{%messenger_messages}}', 'user_max');
        $this->dropColumn('{{%messenger_messages}}', 'notified');
        $this->dropColumn('{{%messenger_messages}}', 'readed');
        $this->dropColumn('{{%messenger_messages}}', 'user_min_hide');
        $this->dropColumn('{{%messenger_messages}}', 'user_max_hide');
        $this->dropColumn('{{%messenger_messages}}', 'is_pinned');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%messenger_messages}}', 'user_min', $this->integer()->null());
        $this->addColumn('{{%messenger_messages}}', 'user_max', $this->integer()->null());
        $this->addColumn('{{%messenger_messages}}', 'notified', $this->tinyInteger(4)->notNull()->defaultValue(0));
        $this->addColumn('{{%messenger_messages}}', 'readed', $this->tinyInteger(4)->notNull()->defaultValue(0));
        $this->addColumn('{{%messenger_messages}}', 'user_min_hide', $this->integer()->null()->defaultValue(0));
        $this->addColumn('{{%messenger_messages}}', 'user_max_hide', $this->integer()->null()->defaultValue(0));
        $this->addColumn('{{%messenger_messages}}', 'is_pinned', $this->boolean()->notNull()->defaultValue(0));
    }
}
