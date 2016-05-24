<?php
/**
 * 管理员控制器类文件
 * User: wanyunshan
 * Date: 2016/5/17
 * Time: 9:34
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class AdminController
 * @package Admin\Controller
 */
class AdminController extends Controller
{
    /**
     * @var \Admin\Model\AdminModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize()
    {
        //>> 用此方法初始化控制器类
        $this->_model=D('Admin');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理管理员',
            'add'  => '添加管理员',
            'edit'  => '编辑管理员',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理管理员';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 列表操作
     */
    public function index()
    {
        //>>获取所有数据
        $this->assign('rows',$this->_model->getList());
        $this->display();
    }

    /**
     * 登录操作
     */
    public function login()
    {
        if(IS_POST)
        {
            if($this->_model->create('','login') === false){
                $this->error($this->_model->getError(),U('login'));
            }
             if($this->_model->checkLogin() === false)
             {
                 //>>登录失败
                 $this->error('账号或密码错误',U('login'));
             }

            //>>登录成功
            $this->success('登录成功',U('Index/index'));
        }else
            {
                //>>渲染登录视图页面
                $this->display('Login/login');
            }
    }

    /**
     * 退出操作
     */
    public function logout()
    {
        $user_info = save_user_info();
        $id = $user_info['id'];
        //>>生成一个新的令牌
        $cookie_token = encrypt();
        $data = array(
            'id' => $id,
            'cookie_token' => $cookie_token,
        );
        //>>更改数据库中的令牌
        $this->_model->setField($data);
        //>>清除session
        session(null);
        //>>清除cookie
        cookie(null);
        //>>删除数据库中的token
        $this->success('退出成功',U('login'));
    }
    /**
     * 添加操作
     */
    public function add()
    {
        if(IS_POST){
            //>>进行添加管理员操作
            if($this->_model->create() === false)
            {
                //>>收集数据失败
                $this->error($this->_model->getError());
            }

            if($this->_model->addAdmin() === false)
            {
                //>>添加数据失败
                $this->error($this->_model->getError());
            }
            //>> 添加成功
            $this->success('添加成功',U('index'));
        }else
            {
                //>>1获取所有数据
                $this->_before_view();
                //>>渲染添加视图页面
                $this->display();

            }

    }

    /**
     * 编辑操作
     */
    public function edit()
    {
        if(IS_POST){
            //>>进行修改保存操作
            if($this->_model->create() === false){
                //>>收集数据失败
                $this->error($this->_model->getError());
            }

            if($this->_model->editAdmin() === false){
                //>>修改数据失败
                $this->error($this->_model->getError());
            }
            //>> 修改成功
            $this->success('修改成功',U('index'));

        }else{
            //>>获取回显数据
            $row = $this->_model->getRow(I('get.id'));
//        dump($row['permission']);exit;
            $this->assign('row',$row);
            //>>其它数据
            $this->_before_view();
            $this->display('add');
        }

    }

    /**
     * 删除操作
     */
    public function delete()
    {
        if($this->_model->deleteAdmin(I('get.id')) === false){
            //>>删除失败
            $this->error($this->_model->getError());
        }
        $this->success('删除成功',U('index'));
    }

    private function _before_view()
    {
        $role_list = D('role')->getList();
        $this->assign('role_list',json_encode($role_list));
        //>>2获取所有权限数据
        $permission_list = D('permission')->getList();
        $this->assign('permission_list',json_encode($permission_list));
    }
    /**
     * 修改密码
     */
    public function updatepwd()
    {
        if(IS_POST){
            //>>收集数据
            if($this->_model->create() === false){
                //>>收集数据失败
                $this->error($this->_model->getError());
            }
            if($this->_model->update_password() === false){
                //>>修改数据失败
                $this->error($this->_model->getError());
            }
            //>> 修改成功
            $this->success('修改成功',U('index'));
        }else{
            //>.渲染修改密码视图
            $this->display();
        }
    }

}