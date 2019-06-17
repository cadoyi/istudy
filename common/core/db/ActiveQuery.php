<?php

namespace core\db;

use Yii;
use yii\caching\TagDependency;

/**
 *
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class ActiveQuery extends \yii\db\ActiveQuery
{


    /**
     * 开启标签缓存.
     *
     * @param  string|array 缓存标签
     * @param  boolean $duration  过期时间,不设置表示使用 db 的过期时间
     * @return $this
     */
    public function tagCache($tags, $duration = true)
    {
        return $this->cache($duration, new TagDependency([
            'tags' => $tags,
        ]));
        return $this;
    }


}