<?php

use yii\db\Migration;

/**
 * Class m220203_175751_alter_mst_config
 */
class m220203_175751_alter_mst_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("ALTER TABLE `mst_configuration` ADD `config_val8` VARCHAR(10000) NULL AFTER `config_val7`, ADD `config_val9` VARCHAR(10000) NULL AFTER `config_val8`, ADD `config_val10` VARCHAR(10000) NULL AFTER `config_val9`, ADD `is_payment_mode` TINYINT(1) NOT NULL DEFAULT '0' AFTER `config_val10`, ADD `configuration_rule` VARCHAR(1000) NULL AFTER `is_payment_mode`;")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220203_175751_alter_mst_config cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220203_175751_alter_mst_config cannot be reverted.\n";

        return false;
    }
    */
}
