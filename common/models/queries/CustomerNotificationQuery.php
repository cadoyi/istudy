<?php

namespace common\models\queries;

use Yii;
use core\db\ActiveQuery;
use common\models\CustomerNotification;

class CustomerNotificationQuery extends ActiveQuery
{

    /**
     * 过滤消息级别,可以使用常量,也可以使用字符串
     * 比如:
     *    $query->filterLevel(CustomerNotification::MESSAGE_LEVEL_ERROR | 
     *                        CustomerNotification::MESSAGE_LEVEL_WARNING);
     *
     * 也可以使用字符串:
     *    $query->filterLevel('error');
     *
     * 也可以使用数组:
     *    $query->filterLevel(['error', 'warning']);
     * 
     * @param  mixed $level 过滤的级别
     * @return $this
     */
    public function filterLevel($level)
    {
        $levels = [
            'error' => CustomerNotification::MESSAGE_LEVEL_ERROR,
            'warning' => CustomerNotification::MESSAGE_LEVEL_WARNING,
            'info' => CustomerNotification::MESSAGE_LEVEL_INFO,
        ];
        $selected = [];
        if(is_numeric($level)) {
            foreach($levels as $_level) {
                if($_level & $level) {
                    $selected[] = $_level;
                }
            }
        } else {
            $level = (array) $level;
            foreach($level as $_level) {
                if(isset($levels[$_level])) {
                    $selected[] = $levels[$_level];
                }
            }
        }
        return $this->andWhere(['level' => $selected]);
    }

    /**
     * 过滤 error 级别的消息.
     * 
     * @return $this
     */
    public function filterError()
    {
        return $this->filterLevel('error');
    }

    /**
     * 过滤 warning 级别的消息
     * 
     * @return $this
     */
    public function filterWarning()
    {
        return $this->filterLevel('warning');
    }


    /**
     * 过滤 info 级别的消息
     * 
     * @return $this
     */
    public function filterInfo()
    {
        return $this->filterLevel('info');
    }


    /**
     * 过滤 已阅读/未读 的消息.
     *
     * @param  boolean $bool - 是否已读
     * @return $this 
     */
    public function filterWatched($bool = true)
    {
        return $this->andWhere(['watched' => $bool ? 1 : 0]);
    }


    /**
     * 过滤 需要重新阅读 的消息
     *
     * @param  boolean $bool - 是否需要重新阅读
     * @return  $this 
     */
    public function filterRewatch($bool = true)
    {
        return $this->andWhere(['rewatch' => $bool ? 1 : 0]);
    }


    /**
     * 过滤 已经过期/未过期 的消息.
     *
     * @param  boolean $bool - 是否过期
     * @return  $this 
     */
    public function filterExpired($bool = true)
    {
        $time = time();
        $op = $bool ? '<' : '>=';
        return $this->andWhere([$op, 'expire_at', $time]);
    }




    

}