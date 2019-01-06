<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post_content`.
 */
class m190106_140512_create_post_content_table extends Migration
{
    public $table = '{{%post_content}}';

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
            'content' => 'longtext not null',
            'created_at' => $this->integer(11)->unsigned()->notNull()->comment('可以实现版本控制'),
        ]);

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
