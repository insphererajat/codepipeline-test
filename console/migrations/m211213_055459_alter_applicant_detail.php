<?php

use yii\db\Migration;

/**
 * Class m211213_055459_alter_applicant_detail
 */
class m211213_055459_alter_applicant_detail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("INSERT INTO `mst_list_type` (`id`, `guid`, `name`, `parent_id`, `display_order`, `is_active`, `is_deleted`, `created_by`, `created_on`, `modified_by`, `modified_on`) VALUES ('219', '7b7d9755-023e-11eb-8c69-02fa15237a6b', 'Aadhar', '167', '4', '1', '0', '1', '1583778376', '1', '1583778376');")->execute();
        $this->db->createCommand("INSERT INTO `mst_list_type` (`id`, `guid`, `name`, `parent_id`, `display_order`, `is_active`, `is_deleted`, `created_by`, `created_on`, `modified_by`, `modified_on`) VALUES ('220', '7b7d9755-023e-11eb-8c69-02fa15237a6c', 'Voter ID', '167', '5', '1', '0', '1', '1583778376', '1', '1583778376');")->execute();
        $this->db->createCommand("INSERT INTO `mst_list_type` (`id`, `guid`, `name`, `parent_id`, `display_order`, `is_active`, `is_deleted`, `created_by`, `created_on`, `modified_by`, `modified_on`) VALUES ('221', '7b7d9755-023e-11eb-8c69-02fa15237a6d', 'Rashan Card', '167', '6', '1', '0', '1', '1583778376', '1', '1583778376');")->execute();
        $this->db->createCommand("ALTER TABLE `applicant_detail` CHANGE `identity_certificate_no` `identity_certificate_no` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;")->execute();
        $this->db->createCommand("ALTER TABLE `applicant_detail` ADD `identity_type_display` VARCHAR(50) NULL AFTER `identity_certificate_no`;")->execute();        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211213_055459_alter_applicant_detail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211213_055459_alter_applicant_detail cannot be reverted.\n";

        return false;
    }
    */
}
