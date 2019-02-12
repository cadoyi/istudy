<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190130_044042_create_admin_profile_table extends Migration
{
    public $table = '{{%admin_profile}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $adminTable = '{{%admin_user}}';
    public $adminFk = 'FK_ADMIN_PROFILE_USER_ID_ADMIN_USER_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'phone'   => $this->char(11),
            'email'   => $this->string(),
            'wechat'  => $this->string(),
            'qq'      => $this->integer(11),
            'avator'  => $this->string(),
            'sex'     => $this->boolean(),
            'note'    => $this->string(),
        ], $this->tableOption);

        $this->addForeignKey($this->adminFk, 
            $this->table, 
            'user_id',
            $this->adminTable,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->insert($this->table, [
            'user_id' => 1,
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey($this->adminFk, $this->table);
        $this->dropTable($this->table);
    }
}
