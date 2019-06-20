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


    /**
     * Creates a blob column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     * @since 2.0.6
     */
    public function blob()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('blob');
    }  


    public function id_key()
    {
        return $this->primaryKey()->unsigned();
    }

    public function big_id_key()
    {
        return $this->bigPrimaryKey()->unsigned();
    }

    public function foreign_key($allowNull = false)
    {
        $key = $this->integer(11)->unsigned();
        if(!$allowNull) {
            $key->notNull();
        }
        return $key;
    }

    public function big_foreign_key($allowNull = false)
    {
        $key = $this->bigInteger()->unsigned();
        if(!$allowNull) {
            $key->notNull();
        }
        return $key;
    }


    public function datetime_at()
    {
        return $this->integer(11)->unsigned()->notNull();
    }

    public function is_active($default = 1)
    {
        return $this->boolean()->notNull()->defaultValue($default);
    }

    public function is_default($default = 0)
    {
        return $this->boolean()->notNull()->defaultValue($default);
    }



}