<?php

use yii\db\Migration;

/**
 * Class m220902_055454_alter_admit_card
 */
class m220902_055454_alter_admit_card extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("CREATE TABLE `log_transaction` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `gateway_id` VARCHAR(100) DEFAULT NULL,
            `transaction_id` INT(11) NOT NULL,
            `response_amount` FLOAT(13,2) DEFAULT NULL,
            `status` VARCHAR(50) DEFAULT NULL,
            `response` LONGTEXT,
            `created_on` INT(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_transaction_id` (`transaction_id`),
            CONSTRAINT `fk_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
          ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220902_055454_alter_admit_card cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220902_055454_alter_admit_card cannot be reverted.\n";

        return false;
    }
    */
}
