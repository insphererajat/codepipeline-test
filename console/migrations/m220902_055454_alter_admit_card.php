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
        $this->db->createCommand("ALTER TABLE `mst_classified`   
        ADD COLUMN `admit_card_start_date` DATE NULL AFTER `cancellation_status`,
        ADD COLUMN `admit_card_end_date` DATE NULL AFTER `admit_card_start_date`;")->execute();
        $this->db->createCommand("ALTER TABLE `applicant_exam`   
            ADD COLUMN `is_downloaded` TINYINT(1) DEFAULT 0 NULL AFTER `comments`,
            ADD COLUMN `downloaded_on` INT(11) NULL AFTER `is_downloaded`,
            ADD COLUMN `is_notification` TINYINT(1) DEFAULT 0 NULL AFTER `downloaded_on`,
            ADD COLUMN `notification_on` INT(11) NULL AFTER `is_notification`;
        ")->execute();
        $this->db->createCommand("ALTER TABLE `exam_centre_detail`   
        ADD COLUMN `examination` VARCHAR(255) NULL AFTER `examtime`;")->execute();
        $this->db->createCommand("ALTER TABLE `hpslsa`.`mst_classified`   
        ADD COLUMN `is_attendance` TINYINT(1) DEFAULT 0 NULL AFTER `admit_card_end_date`;")->execute();
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
