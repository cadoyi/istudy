<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer_notification`.
 */
class m190106_130910_create_customer_notification_table extends Migration
{
    public $table = '{{%customer_notification}}';

    public $customerTable = '{{%customer}}';

    public $customerFk = 'FK_CUSTOMER_NOTIFICATION_CUSTOMER_ID_CUSTOMER_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'customer_id' => $this->integer(11)->unsigned()->notNull(),
            'message'    => $this->string(191)->notNull(),
            'level'      => $this->tinyInteger()->notNull()->defaultValue(0),
            'watched'    => $this->boolean()->notNull()->defaultValue(0),
            'rewatch'    => $this->boolean()->notNull()->defaultValue(0),
            'expire_at'  => $this->integer(11)->unsigned()->comment('过期时间'),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->notNull(),
            'created_by' => $this->integer(11)->unsigned()->notNull(),
            'updated_by' => $this->integer(11)->unsigned()->notNull(),
        ]);

        $this->addForeignKey($this->customerFk,
            $this->table,
            '[[customer_id]]',
            $this->customerTable,
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
        $this->dropTable($this->table);
    }
}
