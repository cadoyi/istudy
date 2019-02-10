<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190202_065317_create_menu_table extends Migration
{
    public $table = '{{%menu}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string(32)->notNull()->unique(),
            'description' => $this->string(),
            'created_at'  => $this->integer(11)->unsigned()->notNull(),
            'updated_at'  => $this->integer(11)->unsigned()->notNull(),
            'created_by'  => $this->integer(11)->unsigned()->notNull(),
            'updated_by'  => $this->integer(11)->unsigned()->notNull(),
         ], $this->tableOption);
        
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
