<?php
namespace Install\Controller;
use Think\Controller;
use Think\Storage;
class IndexController extends Controller {
    
    //安装首页
    public function index(){
        $this->display();
    }
    
    //安装完成
    public function complete(){
        $step = session('step');
        if(!$step){
            $this->error('请正确安装系统', U('index'));
        } elseif($step != 3) {
            $this->error('请正确安装系统', U('Install/step'.$step));
        }
        //写入安装锁定文件
        Storage::put(APP_PATH . 'Common/Conf/install.lock', 'lock');
        session('step', null);
        session('error', null);
        $this->display();
    }
}