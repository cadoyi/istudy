<?php

namespace common\components;

use Yii;
use yii\di\Instance;
use yii\web\Session;

/**
 * 尝试组件, 目的是为了构建一个统一的尝试调用.
 *  比如: 用户在一分钟之内尝试三次错误登录则进行一些动作.
 *  这其中有几个因素:
 *     1. 是否基于 session,也就是可变因素.
 *     2. 两次尝试间隔的最大时间,超过这个时间要重置计数器
 *          比如允许用户尝试三次,已经试了两次,第三次和第二次间隔一天,这种情况下尝试次数要变为1
 *     3. 到达指定次数后,调用回调函数.
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class Attempt extends Component
{

    /**
     * @var string|array|Session 如果使用 session 存储,这里指定 session 组件.
     *   需要注意 REST 接口不支持 session 存储,因此可以使用 cache 存储.
     */
    public $session = 'session';


    /**
     * @var string 唯一的 key,用这个 key 能找到上一次的内容.
     */
    public $key;

    /**
     * @var integer  最大的尝试次数.设置为3, 表示第四次输入,也就是尝试了三次.
     */
    public $retryCount = 3;


    /**
     * @var string 两次尝试间隔, 0 表示不限制,一直有效. 默认 300 秒,也就是 5 分钟.
     */
    public $timeout = 300;




    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->session = Instance::ensure($this->session, Session::className());
    }



    /**
     * 发起尝试.
     * 
     * @return int 尝试次数
     */
    public function attempt()
    {
        $this->check();
        $this->addCounter();

        return $this->counter;
    }


    /**
     * 到达指定的次数之后, 执行回调.
     * 
     * @param  callback $callback 回调.
     * @return mixed 回调返回值.
     */
    public function execute( $callback )
    {
        if($this->counter >= $this->retryCount && $callback) {
            return call_user_func($callback, $this);
        }        
        return null;
    }


    /**
     * 获取计数器的键
     * 
     * @return string
     */
    public function getCounterKey()
    {
        return 'counter_' . $this->key;
    }

  
    /**
     * 获取计数器超时的键
     * 
     * @return string
     */
    public function getTimeoutKey()
    {
        return 'counter_timeout_' . $this->key;
    }



    /**
     * 检查计数器,并在必要的时候重置计数器
     * 
     */
    public function check()
    {
        $counter = $this->counter;
        $time = time();

        if($this->timeout == 0 || $counter < 1) { //不检查两次重试间隔
            return;
        }

        // 如果距离上一次尝试超过了 timeout 的时间,则重置计数器.
        $timeout = $this->get($this->timeoutKey, $time);
        if($time - $timeout >= $this->timeout) {
            $this->reset();
            return;
        }
    }


    /**
     * 获取计数器的值
     * 
     * @return integer
     */
    public function getCounter()
    {
        return $this->get($this->counterKey, 0);
    }


    /**
     * 将计数器的值增加一个
     */
    public function addCounter()
    {
        $counter = $this->counter;
        $time = time();
        $this->set($this->counterKey, $counter + 1);
        $this->set($this->timeoutKey, $time);
    }


    /**
     * 重置计数器
     * 
     */
    public function reset()
    {
        $this->remove($this->counterKey);
        $this->remove($this->timeoutKey);
    }


    /**
     * 获取值
     * 
     * @param  string $key          键
     * @param  mixed $defaultValue  默认值
     * @return mixed 值如果不存在,则返回默认值.
     */
    public function get($key, $defaultValue = null)
    {
        return $this->session->get($key, $defaultValue);
    }

    /**
     * 设置键值
     * @param string $key   键名
     * @param mixed $value  设置的值.
     */
    public function set($key, $value)
    {
        return $this->session->set($key, $value);
    }

    /**
     * 移除键
     * 
     * @param  string $key 键名
     */
    public function remove($key)
    {
        $this->session->remove($key);
    }



}