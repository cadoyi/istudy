<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag`.
 */
class m190106_140540_create_tag_table extends Migration
{

    public $table = '{{%tag}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
