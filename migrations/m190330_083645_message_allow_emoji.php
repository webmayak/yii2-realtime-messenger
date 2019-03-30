<?php

use yii\db\Migration;

/**
 * Class m190330_083645_message_allow_emoji
 */
class m190330_083645_message_allow_emoji extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE {{%messenger_messages}} MODIFY body TEXT CHARACTER SET utf8mb4');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('ALTER TABLE {{%messenger_messages}} MODIFY body TEXT CHARACTER SET utf8');
        return true;
    }
}
