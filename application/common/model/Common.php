<?php
namespace app\common\model;

use think\Model;

/**
 * description:公共Model,保存一些公共属性
 * @author wuyanwen
 */
class Common extends Model
{

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

}