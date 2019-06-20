<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 * 用户报名表.
 */
class m190219_012006_create_enroll_table extends Migration
{
    public $table = '{{%enroll}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'      => $this->id_key(),
            'name'    => $this->string(255)->notNull(),
            'phone'   => $this->string(255)->notNull(),
            'email'   => $this->string(255)->notNull(),
            'dob'     => $this->date()->notNull(),
            'sex'     => $this->boolean(),
            'status'  => $this->tinyInteger()->notNull()->defaultValue(0),
            'note'    => $this->string(),
            'created_at' => $this->datetime_at(),
            'updated_at' => $this->datetime_at(),
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
