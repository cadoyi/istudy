<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer_emails`.
 */
class m190106_122731_create_customer_emails_table extends Migration
{
    public $table = '{{%customer_email}}';

    public $customerTable = '{{%customer}}';

    public $customerFk = 'FK_CUSTOMER_EMAILS_CUSTOMER_ID_CUSTOMER_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'customer_id' => $this->integer(11)->unsigned()->notNull(),
            'email'       => $this->string(191)->unique(),
            'is_primary'  => $this->boolean()->notNull()->defaultValue(0),
            'is_public'   => $this->boolean()->notNull()->defaultValue(0),
            'can_login'   => $this->boolean()->notNull()->defaultValue(0),
            'created_at'  => $this->integer(11)->unsigned()->notNull(),
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
