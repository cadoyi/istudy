<?php

use console\db\Migration;

/**
 * Class m190121_013550_drop_admin_user_cloumn_is_deleted
 */
class m190121_013550_drop_admin_user_cloumn_is_deleted extends Migration
{
    public $table = '{{%admin_user}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropColumn($this->table, '[[is_deleted]]');    
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m190121_013550_drop_admin_user_cloumn_is_deleted cannot be reverted.\n";
        return false;
    }

}
