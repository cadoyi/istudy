<?php

use console\db\Migration;


/**
 * Handles the creation of table `customer`.
 */
class m190106_121548_create_customer_table extends Migration
{

    public $table = '{{%customer}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $groupTable = '{{%customer_group}}';
    public $groupFk = 'FK_CUSTOMER_GROUP_ID_GROUP_ID';
   
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'            => $this->primaryKey()->unsigned(),
            'email'         => $this->string()->notNull()->unique(),
            'nickname'      => $this->string(32),
            'phone'         => $this->string(11)->unique(),
            'password_hash' => $this->string(191)->notNull(),
            'auth_key'      => $this->string(32)->notNull(),
            'is_active'     => $this->boolean()->notNull()->defaultValue(1),
            'group_id'      => $this->integer(11)->unsigned()->notNull(),
            'created_at'    => $this->integer(11)->unsigned()->notNull(),
            'updated_at'    => $this->integer(11)->unsigned()->notNull(),
        ], $this->tableOption);

        $this->createIndex('IDX_CUSTOMER_IS_ACTIVE', $this->table, 'is_active');

        $this->addForeignKey($this->groupFk, 
            $this->table, 
            'group_id',
            $this->groupTable,
            'id',
            'NO ACTION',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey($this->groupFk, $this->table);
        $this->dropTable($this->table);
    }
}
