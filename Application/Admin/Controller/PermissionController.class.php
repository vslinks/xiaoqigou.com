<?php
/**
 * 权限控制器类文件
 * User: wanyunshan
 * Date: 2016/5/15
 * Time: 19:06
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class PermissionController
 * @package Admin\Controller
 */
class PermissionController extends Controller
{
    /**
     * @var \Admin\Model\PermissionModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize()
    {
        //>> 用此方法初始化控制器类
        $this->_model=D('Permission');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理权限',
            'add'  => '添加权限',
            'edit'  => '编辑权限',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理权限';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 列表页面操作
     */
    public function index()
    {
        $this->assign('rows',$this->_model->getList());
        $this->display();
    }

    /**
     * 添加权限操作
     */
    public function add()
    {
        if(IS_POST){
            //>>判断如果有post提交，进行添加操作
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->addPermission() === false){
                $this->error($this->_model->getError());
            }
            //>>添加成功
            $this->success('添加成功',U('index'));
        }else{
            //>>没有post提交进行渲染添加视图
            //>>获取全部权限,用于展示上级权限
//            var_dump($this->_model->getList());exit;
            $this->assign('rows',json_encode($this->_model->getList()));
            $this->display();
        }
    }

    /**
     * 编辑权限操作
     */
    public function edit()
    {
        if(IS_POST){
            //>>进行修改操作
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->editPermission() === false){
                //>>修改失败
                $this->error($this->_model->getError());
            }
            //>>修改成功
            $this->success('修改成功',U('index'));
        }else{
            //>>获取回显数据
            $row = $this->_model->getRow(I('get.id'));
            $this->assign('row',$row);
            $this->assign('rows',json_encode($this->_model->getList()));
            $this->display('add');
        }

    }

    /**
     * 删除操作
     */
    public function delete()
    {
        if($this->_model->deletePermission(I('get.id')) === false){
            $this->error($this->_model->getError());
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }
}