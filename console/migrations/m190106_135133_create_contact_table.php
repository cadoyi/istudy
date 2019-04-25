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
            'id' => $this->id_key(),
            'name'    => $this->string(5)->notNull(),
            'phone'   => $this->string(11)->notNull(),
            'email'   => $this->string(191)->notNull(),
            'subject' => $this->string(32)->notNull(),
            'message' => $this->string(255)->notNull(),
            'status'  => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->datetime_at(),
            'updated_at' => $this->datetime_at(),
        ], $this->tableOption);

        $this->createIndex('IDX_CONTACT_CREATED_AT_STATUS', $this->table, ['created_at', 'status']);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('IDX_CONTACT_CREATE_AT_STATUS', $this->table);
        $this->dropTable($this->table);
    }
}
