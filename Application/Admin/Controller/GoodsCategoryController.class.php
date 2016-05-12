<?php
/**
 * 这是商品分类 类 文件.
 * User: wanyunshan
 * Date: 2016/5/11
 * Time: 12:44
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class GoodsCategoryController
 * @package Admin\Controller
 */
class GoodsCategoryController extends Controller
{
    /**
     * @var \Admin\Model\GoodsCategoryModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize(){
        //>> 用此方法初始化控制器类
        $this->_model=D('GoodsCategory');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理商品分类',
            'add'  => '添加商品分类',
            'edit'  => '编辑商品分类',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理商品分类';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 列表操作
     */
    public function index(){
        //>>获取所有分类列表
        $rows = $this->_model->getZtreeList();
        //>>赋值数据到模板
        $this->assign('rows',$rows);
        //>>渲染添加页面视图
        $this->display();
    }

    /**
     * 添加商品操作
     */
    public function add(){
        //>>判断 如果有post 提交进行添加操作
        if(IS_POST){
            //>>再判断取得数据是否成功
            if($this->_model->create() === false){
                //>>数据获取失败
                $this->error($this->_model->getError());
            }
            //>>取得数据,调用addZtree自定义方法进行添加操作 并判断添加是否成功
            if($this->_model->addZtree() === false){
                //>> 添加失败
                $this->error($this->_model->getError());
            }
            //>>添加成功
            $this->success('添加成功',U('index'));
        }else{
            //>>获取所有分类列表
            $rows = $this->_model->getZtreeList();
            //>>赋值数据到模板
            $this->assign('rows',json_encode($rows));

            //>>渲染添加页面视图
            $this->display();
        }
    }

    /**
     * 编辑修改操作
     */
    public function edit(){
        //>>判断是否有post提交,
        if(IS_POST){
            //>>进行修改操作
            if($this->_model->create() === false){
                //>>获取数据错误, 提示跳转
                $this->error($this->_model->getError());
            }
            //>>获取数据成功 ,调用方法进行修改
            if($this->_model->editZtree() === false){
                //>>修改失败
                $this->error($this->_model->getError());
            }
            //>>修改成功
            $this->success('修改成功',U('index'));
        }else{
            //>>展示修改页面,并回显数据
            $row = $this->_model->find(I('get.id'));
            $this->assign('row',$row);
            //>>获取所有分类列表
            $rows = $this->_model->getZtreeList();
            //>>赋值数据到模板
            $this->assign('rows',json_encode($rows));

            //>>渲染添加页面视图
            $this->display('add');
        }
    }

    /**
     * 删除操作
     */
    public function delete(){
        //>.调用方法删除
        $result = $this->_model->deleteZtree(I('get.id'));
        if($result === false){
            //>>删除失败
            $this->error($this->_model->getError());
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }
}