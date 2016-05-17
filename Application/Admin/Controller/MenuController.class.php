<?php
/**
 * 菜单控制器类文件
 * User: wanyunshan
 * Date: 2016/5/17
 * Time: 13:59
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class MenuController
 * @package Admin\Controller
 */
class MenuController extends Controller
{

    /**
     * @var \Admin\Model\MenuModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize()
    {
        //>> 用此方法初始化控制器类
        $this->_model=D('Menu');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理菜单',
            'add'  => '添加菜单',
            'edit'  => '编辑菜单',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理菜单';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 列表操作
     */
    public function index()
    {
        $this->assign('rows',$this->_model->getList());
        $this->display();
    }

    /**
     * 添加操作
     */
    public function add()
    {
        if(IS_POST){
            //>>进行添加操作
            if($this->_model->create() === false){
                //>>收集数据失败
                $this->error($this->_model->getError());
            }

            if($this->_model->addMenu() === false){
                //>>添加数据失败
                $this->error($this->_model->getError());
            }
            //>> 添加成功
            $this->success('添加成功',U('index'));
        }else{
            //>>获取所有菜单数据
            $this->assign('menu_list',json_encode($this->_model->getList()));
            //>>获取权限数据
            $this->assign('permission_list',json_encode(D('permission')->getList()));
            $this->display();
        }

    }

    /**
     * 编辑操作
     */
    public function edit()
    {
        if(IS_POST)
        {
            //>>进行编辑修改操作
            if($this->_model->create() === false){
                //>>收集数据失败
                $this->error($this->_model->getError());
            }

            if($this->_model->editMenu(I('post.id')) === false){
                //>>修改数据失败
                $this->error($this->_model->getError());
            }
            //>> 添加成功
            $this->success('修改成功',U('index'));

        }else
        {
            //>>获取回显数据
            $row = $this->_model->getRow(I('get.id'));
            $this->assign('row',$row);
            //>>获取其它数据
            $this->_before_view();
            //>>回显编辑视图
            $this->display('add');
        }
    }

    /**
     * 删除操作
     */
    public function delete()
    {
        if($this->_model->deleteMenu(I('get.id')) === false){
            //>>删除失败
            $this->error($this->_model->getError());
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }

    /**
     * 展示视图之前的操作
     */
    public function _before_view()
    {
        $this->assign('menu_list',json_encode($this->_model->getList()));
        //>>获取权限数据
        $this->assign('permission_list',json_encode(D('permission')->getList()));


    }
}