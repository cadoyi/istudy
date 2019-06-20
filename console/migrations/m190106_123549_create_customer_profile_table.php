<?php

use console\db\Migration;

/**
 * Handles the creation of table `customer_profile`.
 */
class m190106_123549_create_customer_profile_table extends Migration
{

    public $table = '{{%customer_profile}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $customerTable = '{{%customer}}';

    public $customerFk = 'FK_CUSTOMER_PROFILE_CUSTOMER_ID_CUSTOMER_ID';


    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%customer_profile}}', [
            'id' => $this->id_key(),
            'customer_id' => $this->foreign_key(),
            'username' => $this->string(32)->comment('用户名'),
            'bio'      => $this->string()->comment('用户简介'),
            'url'      => $this->string()->comment('用户主页'),
            'wechat'   => $this->string()->comment('微信'),
            'qq'       => $this->string()->comment('QQ'),
            'sex'      => $this->boolean()->comment('性别'),
            'dob'      => $this->date()->comment('出生日期'),
            'avator'   => $this->string()->comment('头像'),
            'city'     => $this->string()->comment('城市'),
            'note'     => $this->string()->comment('个性签名'),
        ], $this->tableOption);
        
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
