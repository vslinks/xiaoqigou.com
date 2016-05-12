<?php
/**
 * 这是后台首页类文件
 */
namespace Admin\Controller;
use Think\Controller;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class IndexController extends Controller {
    /**
     * 列表页操作
     */
    public function index(){
        // 渲染视图
        $this->display();

    }
    public function index_v1(){
        // 渲染视图
        $this->display();

    }
    public function index_v2(){
        // 渲染视图
        $this->display();

    }
    public function index_v3(){
        // 渲染视图
        $this->display();

    }
    public function index_v4(){
        // 渲染视图
        $this->display();

    }
    public function index_v5(){
        // 渲染视图
        $this->display();

    }

    public function _empty($name){
        $this->display($name);
    }
}