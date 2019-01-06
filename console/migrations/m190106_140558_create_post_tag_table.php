<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post_tag`.
 */
class m190106_140558_create_post_tag_table extends Migration
{
    public $table = '{{%post_tag}}';

    public $pkName = 'PK_POST_TAB_TAG_ID_POST_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'tag_id' => $this->integer(11)->unsigned()->notNull(),
            'post_id' => $this->integer(11)->unsigned()->notNull(),
        ]);
        $this->addPrimaryKey($this->pkName, $this->table, [
            '[[tag_id]]',
            '[[post_id]]',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropPrimaryKey($this->pkName, $this->table);
        $this->dropTable($this->table);
    }
}
