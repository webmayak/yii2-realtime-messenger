<?php

use yii\db\Migration;

/**
 * Class m180728_140001_drop_triggers
 */
class m180728_140001_drop_triggers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TRIGGER IF EXISTS `user_min_max_insert`;");
        $this->execute("DROP TRIGGER IF EXISTS `user_min_max_update`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180728_140001_drop_triggers cannot be reverted.\n";
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
        return true;
    }
}
