<?php

use yii\db\Migration;

/**
 * Class m220124_072728_alter_applicant_criteria
 */
class m220124_072728_alter_applicant_criteria extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("ALTER TABLE `applicant_criteria` CHANGE `field2` `field2` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;")->execute();
        $this->db->createCommand("ALTER TABLE `mst_classified` ADD `description` VARCHAR(1000) NULL AFTER `title`;")->execute();
        $this->db->createCommand("UPDATE `mst_classified` SET `description` = 'Advertisement Notice for the post of Steno-Typist (on contract basis) for ADR Centres (under District Legal Services Authorities) at Bilaspur, Hamirpur, Kinnaur, Sirmaur, Una and DLSA Kangra' WHERE `mst_classified`.`id` = 4;")->execute();
        $this->db->createCommand("UPDATE `mst_classified` SET `description` = 'Advertisement Notice for the post of Junior Office Assistant IT (on contract basis) for ADR Centres (under District Legal Services Authorities) at Bilaspur, Hamirrpur, Kangra, Kinnaur, Sirmaur, Shimla and Una dated 17.01.2022' WHERE `mst_classified`.`id` = 5;")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220124_072728_alter_applicant_criteria cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220124_072728_alter_applicant_criteria cannot be reverted.\n";

        return false;
    }
    */
}
