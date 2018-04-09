<?php

use yii\db\Migration;

/**
 * Class m180409_015013_create_triggers
 */
class m180409_015013_create_triggers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TRIGGER IF EXISTS `user_min_max_insert`;
        CREATE TRIGGER `user_min_max_insert` BEFORE INSERT ON `messenger_messages`
 FOR EACH ROW BEGIN
    SET NEW.user_min = LEAST(NEW.user_id, (SELECT IF(`from` = NEW.user_id, `to`, `from`) FROM messenger_threads WHERE messenger_threads.id = NEW.thread_id));
    SET NEW.user_max = GREATEST(NEW.user_id, (SELECT IF(`from` = NEW.user_id, `to`, `from`) FROM messenger_threads WHERE messenger_threads.id = NEW.thread_id));
  END;");
        $this->execute("DROP TRIGGER IF EXISTS `user_min_max_update`;
        CREATE TRIGGER `user_min_max_update` BEFORE UPDATE ON `messenger_messages`
 FOR EACH ROW BEGIN
    SET NEW.user_min = LEAST(NEW.user_id, (SELECT IF(`from` = NEW.user_id, `to`, `from`) FROM messenger_threads WHERE messenger_threads.id = NEW.thread_id));
    SET NEW.user_max = GREATEST(NEW.user_id, (SELECT IF(`from` = NEW.user_id, `to`, `from`) FROM messenger_threads WHERE messenger_threads.id = NEW.thread_id));
  END;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180409_015013_create_triggers cannot be reverted.\n";
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180409_015013_create_triggers cannot be reverted.\n";

        return false;
    }
    */
}
