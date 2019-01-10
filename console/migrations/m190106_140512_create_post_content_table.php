<?php

use console\db\Migration;

/**
 * Handles the creation of table `post_content`.
 */
class m190106_140512_create_post_content_table extends Migration
{
    public $table = '{{%post_content}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $postTable = '{{%post}}';

    public $postFk = 'FK_POST_CONTENT_POST_ID_POST_ID';
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'post_id' => $this->integer(11)->unsigned()->notNull(),
            'keywords' => $this->string(255),
            'description' => $this->string(255),
            'content' => $this->longText()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull()->comment('记录最后创建时间'),
        ], $this->tableOption);

        $this->addForeignKey($this->postFk,
            $this->table,
            '[[post_id]]',
            $this->postTable,
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey($this->postFk, $this->table);
        $this->dropTable($this->table);
    }
}
