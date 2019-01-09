<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Handles the creation of table `customer`.
 */
class m190106_121548_create_customer_table extends Migration
{

    public $table = '{{%customer}}';
   
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'            => $this->primaryKey()->unsigned(),
            'nickname'      => $this->string(32),
            'phone'         => $this->string(11)->unique(),
            'password_hash' => $this->string(191)->notNull(),
            'auth_key'      => $this->string(32)->notNull(),
            'is_active'     => $this->boolean()->notNull()->defaultValue(1),
            'created_at'    => $this->integer(11)->unsigned()->notNull(),
            'updated_at'    => $this->integer(11)->unsigned()->notNull(),
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
