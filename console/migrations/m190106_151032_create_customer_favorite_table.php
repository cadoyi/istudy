<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer_favorite`.
 */
class m190106_151032_create_customer_favorite_table extends Migration
{

    public $table = '{{%customer_favorite}}';

    public $customerTable = '{{%customer}}';

    public $postTable = '{{%post}}';

    public $customerFk = 'FK_CUSTOMER_FAVORITE_CUSTOMER_ID_CUSTOMER_ID';

    public $postFk = 'FK_CUSTOMER_FAVORITE_POST_ID_POST_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'customer_id' => $this->integer(11)->unsigned()->notNull(),
            'post_id' => $this->integer(11)->unsigned()->notNull(),
            'reason'  => $this->string(64)->notNull()->defaultValue('就是这么任性')->comment('收藏原因'),
            'is_user_reason' => $this->boolean()->notNull()->defaultValue(0)->comment('是否是用户自己输入的原因'),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->notNull(),
        ]);

        $this->addForeignKey($this->customerFk,
            $this->table,
            '[[customer_id]]',
            $this->customerTable,
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );

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
        $this->dropForeignKey($this->customerFk, $this->table);
        $this->dropForeignKey($this->postFk, $this->table);
        $this->dropTable($this->table);
    }
}
