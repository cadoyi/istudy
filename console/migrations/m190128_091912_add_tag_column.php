<?php

use console\db\Migration;

/**
 * Class m190128_091912_add_tag_column
 */
class m190128_091912_add_tag_column extends Migration
{

    public $table = 'tag';
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn($this->table, 'description', $this->string());
        $this->addColumn($this->table, 'created_at', $this->integer(11)->notNull());
        $this->addColumn($this->table, 'updated_at', $this->integer(11)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn($this->table, 'description');
        $this->dropColumn($this->table, 'created_at');
        $this->dropColumn($this->table, 'updated_at');
    }
}
