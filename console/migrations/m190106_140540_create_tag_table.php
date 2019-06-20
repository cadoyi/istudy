<?php

use console\db\Migration;

/**
 * Handles the creation of table `tag`.
 */
class m190106_140540_create_tag_table extends Migration
{

    public $table = '{{%tag}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->id_key(),
            'title' => $this->string()->notNull()->unique(),
            'description' => $this->string(),
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
