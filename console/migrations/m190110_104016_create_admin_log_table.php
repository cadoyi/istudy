<?php

use console\db\Migration;

/**
 * Handles the creation of table `$this->table`.
 */
class m190110_104016_create_admin_log_table extends Migration
{
    public $table = '{{%admin_log}}';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public $adminTable = '{{%admin_user}}';

    public $adminFk = 'FK_ADMIN_LOG_USER_ID_ADMIN_ID';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'       => $this->bigPrimaryKey()->unsigned(),
            'user_id'  => $this->integer(11)->unsigned()->notNull(),
            'username' => $this->string(32)->notNull(),
            'is_login' => $this->boolean()->notNull()->defaultValue(0),
            'route'    => $this->string()->notNull(),
            'method'   => $this->string(10)->notNull(),
            'params'   => $this->blob(),
            'comment'  => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(), 
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

        $this->createIndex('IDX_CREATED_AT_COMMENT', $this->table, ['created_at', 'comment']);
        
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey($this->adminFk, $this->table);
        $this->dropIndex('IDX_CREATED_AT_COMMENT', $this->table);
        $this->dropTable($this->table);
    }
}
