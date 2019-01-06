<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m190106_140448_create_post_table extends Migration
{
    public $table = '{{%post}}';

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
            'status'       => $this->tinyInteger()->notNull()->defaultValue(0),
            'url_path'      => $this->string(255)->notNull()->unique(),
            'only_category' => $this->boolean()->notNull()->defaultValue(0)->comment('是否只作为分类文章'),
            'version'  => $this->integer(11)->unsigned()->notNull()->defaultValue(0)->comment('每次修改增加版本号'),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->notNull(),
            'created_by' => $this->integer(11)->unsigned()->notNull(),
            'updated_by' => $this->integer(11)->unsigned()->notNull(),
        ]);

        $this->addForeignKey($this->categoryFk,
            $this->table,
            '[[category_id]]',
            $this->categoryTable,
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
        $this->dropForeignKey($this->categoryFk, $this->table);
        $this->dropTable($this->table);
    }
}
