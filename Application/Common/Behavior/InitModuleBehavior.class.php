<?php

namespace Common\Behavior;
use Think\Behavior;
defined('THINK_PATH') or exit();
/**
 * 初始化允许访问模块信息
 */
class InitModuleBehavior extends Behavior{
    /**
     * 行为扩展的执行入口必须是run
     */
    public function run(&$content){
        //安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;
        //允许访问模块列表加上安装的功能模块
        $module_name_list = D('StoreModule')->where(array('status' => array('gt',0)))->getField('name', true);
        $module_allow_list = array_merge(C('MODULE_ALLOW_LIST'), $module_name_list);
        C('MODULE_ALLOW_LIST', $module_allow_list);
    }
}
