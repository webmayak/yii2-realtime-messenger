<?php

use yii\db\Migration;

/**
 * Class m180731_105214_alter_message_body
 */
class m180731_105214_alter_message_body extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%messenger_messages}}', 'body', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180731_104951_alter_message_body cannot be reverted.\n";
        $this->alterColumn('{{%messenger_messages}}', 'body', $this->text()->notNull());
        return true;
    }
}
