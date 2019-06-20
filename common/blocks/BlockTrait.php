<?php

namespace common\blocks;

use Yii;
use yii\base\InvliadArgumentException;

/**
 * 设置一个 trait, 让所有 block 都能作为父级别存在.
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
trait BlockTrait
{


    /**
     * @var array 存储的 block
     */
    protected $_blocks = [];


    /**
     * @var  Object 源对象.
     */
    public $owner;


    /**
     * @var Object 父对象.
     */
    public $parent;


    public $registerBlocks = [];

    

    /**
     * 注册 block
     * 
     * @param  array  $blocks block 配置数组
     */
    public function registerBlocks()
    {
        $blocks = $this->registerBlocks;
        foreach($blocks as $id => $block) {
            if(!isset($block['id'])) {
                $block['id'] = $id;
            }
            $this->setBlock($block);
        }
    }


    /**
     * 获取 block , 并可以级联获取
     * 获取名为 main 的 block
     *    $mian = $block->getBlock();
     *     
     * 获取 a 下的 b 下的 c:
     *    $c = $block->getBlock('a.b.c');
     *
     * 获取 main 下的 b 下的 c
     *    $c = $block->getBlock('.b.c');
     * 
     * @param  string $id 
     * @return Block|null
     */
    public function getBlock($id = 'main')
    {
        if(strpos($id, '.') === false) {
            if($this->hasBlock($id)) {
                $block = $this->_blocks[$id];
                if(is_array($block)) {
                    $block = Yii::createObject($block);
                    $this->_blocks[$id] = $block;
                }
                return $block;
            }
            return null;
        }
        if(strpos($id, '.') === 0) {
            $id = 'main' . $id;
        }
        $ids = explode('.', $id);
        $block = $this;
        while(!empty($ids)) {
            $blockId = array_shift($ids);
            $block = $block->getBlock($blockId);
            if(is_null($block)) {
                break; 
            }
        }
        return $block;
    }




    /**
     * 注册 block
     * 
     * @param string $id  block id
     * @param Block $block 
     */
    public function setBlock($block, $replace = true)
    {
        $parent = ($this instanceof BlockInterface) ? $this : null;
        if($block instanceof BlockInterface) {
            $id = $block->id;
            $block->owner = $this->owner;
            $block->parent = $parent;
        } elseif(is_array($block) && isset($block['id'])) {
            $id = $block['id'];
            $block['owner'] = $this->owner;
            $block['parent'] = $parent;
        } else {
            throw new InvliadArgumentException('The "block" argument is invalid.');
        }
        if($replace || !$this->hasBlock($id)) {
            $this->_blocks[$id] = $block;
        }
    }

    
    /**
     * 添加 block
     * 
     * @param Block $block 
     */
    public function addBlock($block)
    {
        $this->setBlock($block, false);
    }

    
    /**
     * 移除 block
     * 
     * @param  string $id 
     */
    public function removeBlock($id)
    {
        if($this->hasBlock($id)) {
            unset($this->_blocks[$id]);
        }
    }

    


    /**
     * 检查是否有 block
     * 
     * @param  string  $id 
     * @return boolean 
     */
    public function hasBlock($id)
    {
        return isset($this->_blocks[$id]) || array_key_exists($id, $this->_blocks);
    }

    

}