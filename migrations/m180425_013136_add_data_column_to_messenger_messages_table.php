<?php

use pantera\messenger\models\MessengerMessages;
use yii\db\Migration;

/**
 * Handles adding data to table `messenger_messages`.
 */
class m180425_013136_add_data_column_to_messenger_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(MessengerMessages::tableName(), 'data', $this->json()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(MessengerMessages::tableName(), 'data');
    }
}
