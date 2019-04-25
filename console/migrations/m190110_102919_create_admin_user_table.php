<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190110_102919_create_admin_user_table extends Migration
{
    public $table = '{{%admin_user}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'       => $this->id_key(),
            'username' => $this->string(32)->notNull()->unique(),
            'nickname' => $this->string(32)->unique(),
            'email'    => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(191)->notNull(),
            'is_active' => $this->is_active(1),
            'created_at' => $this->datetime_at(),
            'updated_at' => $this->datetime_at(),
        ], $this->tableOption);
        
        $this->insert($this->table, [
            'username' => 'admin',
            'nickname' => '超级管理员',
            'email'    => 'admin@localhost',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'created_at' => time(),
            'updated_at' => time(),
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
