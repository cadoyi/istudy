<?php

namespace common\query;

use Yii;
use yii\base\InvalidParamException;

/**
 * 提供一个所有 ActiveQuery 的父类,用于设置 scope 查询,减少查询长度.
 * 这里可以定义一些通用的 scope
 */
class ActiveQuery extends \yii\db\ActiveQuery
{

    /**
     * 过滤 datetime 时间
     * @param  array $datetime  每个元素可以是时间戳,具体时间字符串,或者数组表示的多个时间.
     * @return $this
     */
    protected function _filterDatetime($column, array $datetimes)
    {
        $_datetimes = [];
        foreach($datetimes as $datetime) {
            if(is_numeric($datetime)) {
                $_datetimes[] = $datetime;
            } else {
                $_datetimes[] = Yii::$app->formatter->asTimestamp($datetime);
            }
        }
        if(count($_datetimes) == 1) {
            return $this->andWhere([$column => $_datetimes[0]]);
        } else {
            if($_datetimes[0] <= $_datetimes[1]) {
                return $this->andWhere(['between', $column, $_datetimes[0], $_datetimes[1]]);
            } else {
                throw new InvalidParamException('start datetime muse be less than stop datetime');
            }
        }
    }

    /**
     * 过滤布尔值
     * @param  string $column 字段名   
     * @param  boolean $bool  
     * @return $this
     */
    public function _filterBoolean($column, $bool = true)
    {
        $where = [$column => $bool ? 1 : 0];
        return $this->andWhere($where);
    }


    /**
     * 过滤创建时间,可以是具体的创建时间,也可以传递两个参数表示起始时间和结束时间.
     * 如果传递的是两个参数,那么他会使用 between , 否则使用 = 
     * 
     * 
     * @param  mixed $created_at unix 时间戳/字符串表示的时间格式.
     * @return $this
     */
    public function filterCreatedAt($created_at)
    {
        $args = func_get_args();
        return $this->_filterDatetime('created_at', $args);
    }



    /**
     * 过滤更新时间, 可以是具体的时间,也可以传递两个参数表示起始时间和结束时间.
     * 如果传递的是两个参数,那么他会使用 between , 否则使用 = 
     * 
     * @param  mixed $updated_at  unix 时间戳/字符串 表示的时间格式.
     * @return $this
     */
    public function filterUpdatedAt($updated_at)
    {
        $args = func_get_args();
        return $this->_filterDatetime('updated_at', $args);
    }

}