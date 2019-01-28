<?php

use console\db\Migration;

/**
 * Class m190128_005954_add_category_column
 */
class m190128_005954_add_category_column extends Migration
{

    public $table = '{{%category}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn($this->table, 'created_at', $this->integer(11)->notNull());
        $this->addColumn($this->table, 'updated_at', $this->integer(11)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn($this->table, 'created_at');
        $this->dropColumn($this->table, 'updated_at');
    }
}
