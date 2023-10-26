<?php

use yii\db\Migration;

/**
 * Class m220423_133138_alert_log_user_activity
 */
class m220423_133138_alert_log_user_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("ALTER TABLE `log_user_activity` ADD `device_type` VARCHAR(20) NULL AFTER `type`, ADD `ip_address` VARCHAR(15) NULL AFTER `device_type`, ADD `useragent` VARCHAR(255) NULL AFTER `ip_address`;")->execute();
        $this->db->createCommand("ALTER TABLE `mst_configuration` CHANGE `config_val1` `config_val1` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `config_val2` `config_val2` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `config_val3` `config_val3` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `config_val4` `config_val4` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `config_val5` `config_val5` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `config_val6` `config_val6` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `config_val7` `config_val7` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;")->execute();
        $this->db->createCommand("ALTER TABLE `mst_configuration` ADD `config_val11` VARCHAR(5000) NULL AFTER `config_val10`, ADD `config_val12` VARCHAR(5000) NULL AFTER `config_val11`, ADD `config_val13` VARCHAR(5000) NULL AFTER `config_val12`, ADD `config_val14` VARCHAR(5000) NULL AFTER `config_val13`, ADD `config_val15` VARCHAR(5000) NULL AFTER `config_val14`;")->execute();
        $this->db->createCommand("ALTER TABLE `log_otp` ADD `sent_to` VARCHAR(255) NULL AFTER `otp`;")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220423_133138_alert_log_user_activity cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220423_133138_alert_log_user_activity cannot be reverted.\n";

        return false;
    }
    */
}
