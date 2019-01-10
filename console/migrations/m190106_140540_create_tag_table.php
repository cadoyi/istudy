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
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string()->notNull()->unique(),
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
