<?php

use console\db\Migration;

/**
 * Handles the creation of table `post_comment`.
 */
class m190106_140631_create_post_comment_table extends Migration
{
    public $table = '{{%post_comment}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

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
            'id' => $this->big_id_key(),
            'post_id' => $this->foreign_key(),
            'customer_id' => $this->foreign_key(),
            'parent_id' => $this->big_foreign_key(true),
            'comment' => $this->string(255)->notNull(),
            'created_at' => $this->datetime_at(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('审核状态'),
        ], $this->tableOption);

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
