<?php

use console\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m190106_140448_create_post_table extends Migration
{
    public $table = '{{%post}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $categoryTable = '{{%category}}';

    public $categoryFk = 'FK_POST_CATEGORY_ID_CATEGORY_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string()->notNull()->comment('文章标题'),
            'category_id' => $this->integer(11)->unsigned()->notNull()->comment('分类ID'),
            'description'  => $this->string(255)->notNull()->comment('短描述'),
            'is_active'    => $this->boolean()->notNull()->defaultValue(1),
            'url_path'     => $this->string(255)->notNull()->unique(),
            'meta_title'       =>  $this->string(),
            'meta_keywords'    =>  $this->string(),
            'meta_description' => $this->string(),
            'content'          => $this->longText()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->notNull(),
            'created_by' => $this->integer(11)->unsigned()->notNull(),
            'updated_by' => $this->integer(11)->unsigned()->notNull(),
        ], $this->tableOption);

        $this->addForeignKey($this->categoryFk,
            $this->table,
            '[[category_id]]',
            $this->categoryTable,
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('IDX_POST_IS_ACTIVE', $this->table, 'is_active');
        $this->createIndex('IDX_POST_TITLE_IS_ACTIVE', $this->table, ['title', 'is_active']);
        $this->createIndex('IDX_POST_DESCRIPTION', $this->table, 'description');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('IDX_POST_IS_ACTIVE', $this->table);
        $this->dropIndex('IDX_POST_TITLE_IS_ACTIVE', $this->table);
        $this->dropIndex('IDX_POST_DESCRIPTION', $this->table);
        $this->dropForeignKey($this->categoryFk, $this->table);
        $this->dropTable($this->table);
    }
}
