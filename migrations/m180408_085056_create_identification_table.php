<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `identification`.
 */
class m180408_085056_create_identification_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%identification}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'user_id' => $this->unsignedInteger()->notNull()->comment('User ID'),
            'real_name' => $this->string()->comment('Real Name'),
            'id_type' => $this->string(10)->notNull()->comment('ID Type'),
            'id_card' => $this->string()->notNull()->comment('ID Card'),
            'passport_cover' => $this->string()->comment('Passport Cover'),
            'passport_person_page' => $this->string()->comment('Passport Person Page'),
            'passport_self_holding' => $this->string()->comment('Passport Self Holding'),
            'status' => $this->smallInteger(1)->unsigned()->defaultValue(0)->comment('Status'),
            'failed_reason' => $this->string()->comment('Failed Reason'),
            'created_at' => $this->unixTimestamp()->comment('Created At'),
            'updated_at' => $this->unixTimestamp()->comment('Updated At'),
        ], $tableOptions);

        $this->addPrimaryKey('identification', $this->tableName, 'user_id');
        $this->addForeignKey('identification_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
