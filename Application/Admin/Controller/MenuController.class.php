<?php

namespace Admin\Controller;
/**
 * 后台菜单控制器
 */
class SystemMenuController extends AdminController{
    /**
     * 菜单列表
     */
    public function index(){
        //搜索
        $keyword = (string)I('keyword');
        $condition = array('like','%'.$keyword.'%');
        $map['id|title'] = array($condition, $condition, '_multi'=>true); //搜索条件

        //获取所有菜单
        $map['status'] = array('egt', 0); //禁用和正常状态
        $data_list = D('SystemMenu')->where($map)->order('sort asc, id asc')->select();

        //转换成树状列表
        $tree = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list);

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->title('菜单列表')  //设置页面标题
                ->AddNewButton()    //添加新增按钮
                ->addResumeButton() //添加启用按钮
                ->addForbidButton() //添加禁用按钮
                ->addDeleteButton() //添加删除按钮
                ->setSearch('请输入ID/菜单名称', U('index'))
                ->addField('id', 'ID', 'text')
                ->addField('title_show', '标题', 'text')
                ->addField('url', '链接', 'text')
                ->addField('icon', '图标', 'icon')
                ->addField('sort', '排序', 'text')
                ->addField('status', '状态', 'status')
                ->addField('right_button', '操作', 'btn')
                ->dataList($data_list)    //数据列表
                ->addRightButton('edit')   //添加编辑按钮
                ->addRightButton('forbid') //添加禁用/启用按钮
                ->addRightButton('delete') //添加删除按钮
                ->display();
    }

    /**
     * 新增菜单
     */
    public function add(){
        if(IS_POST){
            $this->add_or_edit();
        }else{
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->title('新增菜单')  //设置页面标题
                    ->setUrl(U('add')) //设置表单提交地址
                    ->addItem('pid', 'select', '上级菜单', '所属的上级菜单', $this->selectListAsTree('SystemMenu', null, '顶级菜单'))
                    ->addItem('title', 'text', '标题', '菜单标题')
                    ->addItem('url', 'text', '链接', 'U函数解析的URL或者外链')
                    ->addItem('icon', 'icon', '图标', '菜单图标')
                    ->addItem('sort', 'num', '排序', '用于显示的顺序')
                    ->display();
        }
    }

    
    /**
     * 添加或编辑配置
     */
    public function add_or_edit(){
        $this->jumpUrl = U('index');
        $this->os_add_or_edit(D('SystemMenu'));
    }
    
    /**
     * 编辑菜单
     */
    public function edit($id){
        if(IS_POST){
            $this->add_or_edit();
        }else{
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->title('新增菜单')  //设置页面标题
                    ->setUrl(U('edit')) //设置表单提交地址
                    ->addItem('id', 'hidden', 'ID', 'ID')
                    ->addItem('pid', 'select', '上级菜单', '所属的上级菜单', $this->selectListAsTree('SystemMenu', null, '顶级菜单'))
                    ->addItem('title', 'text', '标题', '菜单标题')
                    ->addItem('url', 'text', '链接', 'U函数解析的URL或者外链')
                    ->addItem('icon', 'icon', '图标', '菜单图标')
                    ->addItem('sort', 'num', '排序', '用于显示的顺序')
                    ->setFormData(D('SystemMenu')->find($id))
                    ->display();
        }
    }
}
