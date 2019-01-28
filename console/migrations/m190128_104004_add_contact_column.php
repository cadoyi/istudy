<?php

use console\db\Migration;

/**
 * Class m190128_104004_add_contact_column
 */
class m190128_104004_add_contact_column extends Migration
{

    public $table = '{{%contact}}';


    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn($this->table, 'status', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn($this->table, 'created_at', $this->integer(11)->notNull());
        $this->addColumn($this->table, 'updated_at', $this->integer(11)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn($this->table, 'status');
        $this->dropColumn($this->table, 'created_at');
        $this->dropColumn($this->table, 'updated_at');
    }
}
