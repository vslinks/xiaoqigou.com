<?php
/**
 * Created by PhpStorm.
 * User: wanyunshan
 * Date: 2016/5/17
 * Time: 19:10
 */

namespace Admin\Behaviors;


use Think\Behavior;

/**
 * Class checkRoleBehavior
 * @package Admin\Behaviors
 */
class checkRoleBehavior extends Behavior
{
    //>>实现抽象方法
    public function run(&$params) //>>使用引用传值
    {
        //>>在这里写具体的行为代码;
        $admin_info = save_user_info();
        $url = MODULE_NAME . '/' . CONTROLLER_NAME  . '/' .  ACTION_NAME;
        if(!$admin_info){
            $login = C('login');
            if(in_array($url,$login)){
                //>>在首页.什么都不用做
               return false;
            }
            //>>在这里验证是否有cookie登录
            $cookie_info = save_cookie_info();
            if($cookie_info){
                //>>如果存在cookie拿出数据与数据库进行对比.看是否比对成功.
                $result = D('Admin')->where($cookie_info)->find();
                if($result){
                    //>>如果取出了数据,说明比对成功.把数据放入session
                    save_user_info($result);
                    //>>更新令牌.重新生成cookie
                    $adminModel =  D('Admin');
                    $id = $result['id'];
                    $adminModel ->_save_cookie_info($id);
                    //>>同时要把,可显示视图信息存入session中.
                    $adminModel->_save_menu_info($id);
                    //>>同时要把权限信息,存入session中.
                    $adminModel->_save_permission_info($id);
                    return false;
                }
            }
            //>>未登录
            header("Content-Type:text/html;charset=utf-8");
            redirect(U('Admin/login'),3,'请重新登录');
        }else{
            if($admin_info['username'] == "admin@qq.com"){
                //>>超级管理员
                return false;
            }
            //>>获取当前管理员拥有的 权限
            $permission_info = save_permission_info();
            //>>循环把灵气放在一维数组中
            $perInfo = array();
            foreach($permission_info as $val){
                $perInfo[] = $val['path'];
            }
            //>>从配置文件中取出所有人都拥有的权限
            $ignore = C('ignore');
            //>>合并所有权限
            $userPermission = array_merge($ignore,$perInfo);
            if(!in_array($url,$userPermission)){
                //>>验证失败
//                echo "你没有权限";exit;
               echo "<script>alert('你没有权限');history.back();</script>";
            }
        }

    }
}