<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190106_040029_create_customer_group_table extends Migration
{
    public $table = '{{%customer_group}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'          => $this->primaryKey()->unsigned(),
            'name'        => $this->string(32)->notNull(),
            'description' => $this->string(),
            'is_default'  => $this->boolean()->notNull()->defaultValue(0),
            'created_at'  => $this->integer(11)->unsigned()->notNull(),
            'updated_at'  => $this->integer(11)->unsigned()->notNull(),
        ], $this->tableOption);

        $time = time();
        $this->insert($this->table, [
            'name'         => 'General',
            'description'  => 'General group',
            'is_default'   => 1,
            'created_at'   => $time,
            'updated_at'   => $time,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
