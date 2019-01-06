<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post_comment`.
 */
class m190106_140631_create_post_comment_table extends Migration
{
    public $table = '{{%post_comment}}';

    public $postTable = '{{%post}}';

    public $customerTable = '{{%customer}}';

    public $postFk = 'FK_POST_COMMENT_POST_ID_POST_ID';

    public $customerFk = 'FK_POST_COMMENT_CUSTOMER_ID_CUSTOMER_ID';

    public $selfFk = 'FK_SELF_POST_COMMENT_PARNET_ID_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->BigPrimaryKey()->unsigned(),
            'post_id' => $this->integer(11)->unsigned()->notNull(),
            'customer_id' => $this->integer(11)->unsigned()->notNull(),
            'parent_id' => $this->bigInteger()->unsigned(),
            'comment' => $this->string(255)->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('审核状态'),
        ]);

        $this->addForeignKey($this->postFk, 
            $this->table,
            '[[post_id]]',
            $this->postTable,
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey($this->customerFk,
            $this->table,
            '[[customer_id]]',
            $this->customerTable,
            '[[id]]',
            'NO ACTION',
            'CASCADE'
        );

        $this->addForeignKey($this->selfFk,
           $this->table,
           '[[parent_id]]',
           $this->table,
           '[[id]]',
           'NO ACTION',
           'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        foreach([$this->postFk, $this->customerFk, $this->selfFk] as $fk) {
            $this->dropForeignKey($fk, $this->table);
        }
        $this->dropTable($this->table);
    }
}
