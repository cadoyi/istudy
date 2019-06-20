<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190110_104016_create_admin_message_table extends Migration
{
    public $table = '{{%admin_message}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $adminTable = '{{%admin_user}}';

    public $adminFk = 'FK_ADMIN_MESSAGE_USER_ID_ADMIN_USER_ID';
    public $senderFk = 'FK_ADMIN_MESSAGE_SENDER_ID_ADMIN_USER_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'         => $this->big_id_key(),
            'user_id'    => $this->foreign_key(),
            'sender_id'  => $this->foreign_key(),
            'sender_name' => $this->string()->notNull()->comment('发送者名字'),
            'subject'    => $this->string()->notNull()->comment('摘要'),
            'message'    => $this->text()->notNull(),
            'watched'    => $this->boolean()->notNull()->defaultValue(0),
            'created_at' => $this->datetime_at(),
            'watched_at' => $this->integer(11)->unsigned(),
            'level'      => $this->tinyInteger()->unsigned()->notNull()->comment('消息级别'),
            'require_receipt' => $this->boolean()->notNull()->defaultValue(0)->comment('是否需要回执'),
        ], $this->tableOption);

        $this->addForeignKey(
            $this->adminFk, 
            $this->table, 
            '[[user_id]]',
            $this->adminTable,
            '[[id]]',
            'NO ACTION',
            'CASCADE'
        );
        $this->addForeignKey(
            $this->senderFk, 
            $this->table, 
            '[[sender_id]]',
            $this->adminTable,
            '[[id]]',
            'NO ACTION',
            'CASCADE'
        );        

        $this->insert($this->table, [
            'user_id'   => 1,
            'sender_id' => 1,
            'sender_name'  => 'admin',
            'subject' => '欢迎登陆系统后台,你也可以发送消息给别人哦',
            'message' => '欢迎登陆系统后台,你也可以发送消息给别人哦',
            'level'   => 1,   // LEVEL_INFO
            'created_at' => time(),
            'require_receipt' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey($this->adminFk, $this->table);
        $this->dropForeignKey($this->senderFk, $this->table);
        $this->dropTable($this->table);
    }
}
