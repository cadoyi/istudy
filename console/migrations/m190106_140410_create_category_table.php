<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m190106_140410_create_category_table extends Migration
{

    public $table = '{{%category}}';

    public $selfFk = 'FK_SELF_CATEGORY_PARENT_ID_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer(11)->unsigned(),
            'level' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1),
            'path' => $this->string()->notNull()->comment('父子关系路径'),
            'url_path' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull()->comment('分类标题'),
        ]);
        
        $this->addForeignKey($this->selfFk,
            $this->table,
            '[[parent_id]]',
            $this->table,
            '[[id]]',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey($this->selfFk, $this->table);
        $this->dropTable($this->table);
    }
}
