<?php

namespace console\db;

class Migration extends \yii\db\Migration
{

    /**
     * Creates a medium text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     * @since 2.0.6
     */
    public function mediumText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
    }

    /**
     * Creates a long text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     * @since 2.0.6
     */
    public function longText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
    }

}