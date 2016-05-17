<?php
/**
 * 角色控制器类文件.
 * User: wanyunshan
 * Date: 2016/5/15
 * Time: 22:00
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class RoleController
 * @package Admin\Controller
 */
class RoleController extends Controller
{

    /**
     * @var \Admin\Model\RoleModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize()
    {
        //>> 用此方法初始化控制器类
        $this->_model=D('Role');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理角色',
            'add'  => '添加角色',
            'edit'  => '编辑角色',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理角色';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 列表操作
     */
    public function index()
    {
        //>>取出所有角色数据
        $this->assign('rows',$this->_model->getList());
        $this->display();
    }

    /**
     * 添加角色操作
     */
    public function add()
    {
        if(IS_POST){
            //>>进行添加操作
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->addRole() === false){
                $this->error($this->_model->getError());
            }
            //>>添加成功
            $this->success('添加成功',U('index'));
        }else{
            //>>获取所有权限数据
            $rows = D('Permission')->getList();
            $this->assign('rows',json_encode($rows));
            //>>渲染视图
            $this->display();
        }
    }

    /**
     * 编辑操作
     */
    public function edit()
    {
        if(IS_POST){
            //>>进行修改操作
            if($this->_model->create() === false){
                $this->error($this->_model->getError());
            }
            if($this->_model->editRole(I('post.id')) === false){
                $this->error($this->_model->getError());
            }
            //>>添加成功
            $this->success('修改成功',U('index'));
        }else{
            //>>获取回显数据
            $row = $this->_model->find(I('get.id'));
            $this->assign('row',$row);
            //>>获取拥有的权限数据
            $permissiones = D('RolePermission')
                ->where(array('role_id' => $row['id']))
                ->select();
            //>>获取所有权限数据
//            dump($permissiones);exit;
            $rows = D('Permission')->getList();
            $this->assign('permissiones',json_encode($permissiones));
            $this->assign('rows',json_encode($rows));
            //>>渲染视图
            $this->display('add');
        }
    }

    /**
     * 删除操作
     */
    public function delete()
    {
        if($this->_model->deleteRole(I('get.id')) === false){
            //>>删除失败
            $this->error($this->_model->getError());
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }
}