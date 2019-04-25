<?php

use console\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m190106_140410_create_category_table extends Migration
{

    public $table = '{{%category}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $selfFk = 'FK_SELF_CATEGORY_PARENT_ID_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->id_key(),
            'parent_id' => $this->foreign_key(true),
            'level' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1),
            'path' => $this->string()->notNull()->comment('父子关系路径'),
            'url_path' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull()->comment('分类标题'),
            'description' => $this->string()->notNull()->comment('分类描述'),
            'is_active' => $this->is_active(1),
            'position' => $this->integer()->notNull()->defaultValue(1),
            'image' => $this->string()->comment('category image'),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
            'content' => $this->mediumText(),
            'created_at' => $this->datetime_at(),
            'updated_at' => $this->datetime_at(),
            'created_by' => $this->foreign_key(),
            'updated_by' => $this->foreign_key(),
        ], $this->tableOption);
        
        $this->addForeignKey($this->selfFk,
            $this->table,
            '[[parent_id]]',
            $this->table,
            '[[id]]',
            'SET NULL',
            'CASCADE'
        );
        $this->createIndex('IDX_CATEGORY_IS_ACTIVE', $this->table, 'is_active');
        $this->createIndex('IDX_CATEGORY_PATH', $this->table, 'path');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('IDX_CATEGORY_IS_ACTIVE', $this->table);
        $this->dropIndex('IDX_CATEGORY_PATH', $this->table);
        $this->dropForeignKey($this->selfFk, $this->table);
        $this->dropTable($this->table);
    }
}
