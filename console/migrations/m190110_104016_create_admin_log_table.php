<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190110_104016_create_admin_log_table extends Migration
{
    public $table = '{{%admin_log}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),

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
