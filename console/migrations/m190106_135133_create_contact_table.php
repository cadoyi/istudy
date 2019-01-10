<?php

use console\db\Migration;

/**
 * Handles the creation of table `contact`.
 */
class m190106_135133_create_contact_table extends Migration
{

    public $table = '{{%contact}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'name'    => $this->string(5)->notNull(),
            'phone'   => $this->string(11)->notNull(),
            'email'   => $this->string(191)->notNull(),
            'subject' => $this->string(32)->notNull(),
            'message' => $this->string(255)->notNull(),
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
