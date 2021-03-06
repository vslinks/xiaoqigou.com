<?php
/**
 * 管理员模型类文件.
 * User: wanyunshan
 * Date: 2016/5/17
 * Time: 9:36
 */

namespace Admin\Model;

use Org\Util;
use Think\Model;
use Think\Verify;
/**
 * Class AdminModel
 * @package Admin\Model
 */
class AdminModel extends Model
{

    /**
     * @var array
     * 设置商品添加与修改的自动验证规则
     */
    protected $_validate = array(
        ['username', 'require', '用户名不能为空', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['role_id', 'require', '没有选择角色', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['email', 'email', '邮箱格式不正确', self::EXISTS_VALIDATE, '', self::MODEL_INSERT],
        ['email', '', '邮箱已经存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT],
        ['password', '6,12', '密码需要6-12位', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT],
        ['repassword', 'password', '两次密码输入不一致', self::EXISTS_VALIDATE, 'confirm', self::MODEL_INSERT],
//        ['old_password', 'old_new_check', '新旧密码一样,请重新选择新密码', self::EXISTS_VALIDATE, 'callback', self::MODEL_INSERT],
//        ['captcha', 'checkVerify', '验证码输入不正确', self::EXISTS_VALIDATE, 'callback', 'login'],
    );

    /**
     * 自动完成的功能
     * @var array
     */
    protected $_auto = array(
        //>>自动生成
        array('salt','\Org\Util\String::randString',self::MODEL_BOTH,'function',6),
        array('add_time',NOW_TIME,self::MODEL_BOTH),
        array('last_login_time',NOW_TIME,'login'),
        array('last_login_ip','get_client_ip','login','function',1),
    );

    /**
     * 验证新旧密码是否一致
     * @param $old_password
     */
  /*  protected function old_new_check($old_password)
    {
        $user_info = save_user_info();//>>从session中取出用户信息
        $salt_password = md5(md5($old_password) . $user_info['salt']);
        if($user_info['password'] == $salt_password){
            return false;
        }else{
            return true;
        }
    }*/
    protected function checkVerify($code)
    {
        $verify = new Verify();
        if($verify->check($code) === false){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 登录验证相关操作
     */
    public function checkLogin()
    {
        $username = $this->data['username'];
        $password = $this->data['password'];
        $create_data = $this->data;
       if($row = $this->getByUsername($username))
       {
           //>>如果有数据进行密码验证
           if(md5(md5($password) . $row['salt']) == $row['password'])
           {
               //>>验证通过.这里要保存登录时间及登录ip到数据库
                    $ip_time_info = array(
                        'last_login_time' => $create_data['last_login_time'],
                        'last_login_ip' => $create_data['last_login_ip'],
                        'id' => $row['id'],
                    );
               $this->setField($ip_time_info);
               //>>把数据信息保存到sessin中
                save_user_info($row);
               //>>再判断是否有勾选记住我.如果勾选了,进行保存信息
               $remember = I('post.remember');
               if(!empty($remember)){
                   $this->_save_cookie_info($row['id']);
               }
               //>>把登录管理对应的权限查询出来 存入session中
               $this->_save_permission_info($row['id']);
               //>>查询出所有的当前用户能看到的菜单
               $this->_save_menu_info($row['id'],$row['username']);
               return true;
           }
           //>>比对不成功,
           return false;
       }else
           {
              //>>没有数据,登录失败
               return false;
           }
    }
    /**
     * 专业添加管理员相关数据操作
     * @return bool|mixed
     */
    public function addAdmin()
    {
        //>>添加常规数据
        //>>添加之前先要对密码进行加密
        $password =  $this->data['password'];
        $salt =  $this->data['salt'];
        $this->data['password'] = md5(md5($password) . $salt);
        $this->startTrans();
        if(($admin_id = $this->add()) === false){
            //>>添加失败
            $this->rollback();
            return false;
        }
        //>>添加管理员角色对应关系表
        $admin_role = array(
            'admin_id' => $admin_id,
            'role_id'  => I('post.role_id'),
        );
        $admin_roleModel = M('AdminRole');
        if($admin_roleModel->add($admin_role) === false){
            //>>添加管理员角色对应数据失败
            $this->error = $admin_roleModel->getError();
            $this->rollback();
            return false;
        }

        //>>添加额外权限
        $admin_permission_ids = I('permission_id');
        $admin_permission = array();
        foreach($admin_permission_ids as $val){
            $admin_permission[] = array(
                'admin_id'  => $admin_id,
                'permission_id'  => $val,
            );
        }
        $admin_permissionModel = M('AdminPermission');
        if($admin_permissionModel->addAll($admin_permission) === false){
            $this->error = $admin_permissionModel->getError();
            $this->rollback();
            return false;
        }

        //>>添加成功 返回插入的主键值.
        $this->commit();
        return $admin_id;

    }

    /**
     *获取所有列表数据
     * @return array
     */
    public function getList()
    {
        return $this->select();
    }

    /**
     * 获取一条数据
     */
    public function getRow($admin_id)
    {
        //>>常规数据
        $row = $this->alias('a')
            ->join('JOIN admin_role AS ar ON a.id=ar.admin_id')
            ->find($admin_id);
        //>>获取额外权限
        $admin_permission = M('AdminPermission')
            ->where(array('admin_id' => $admin_id))
            ->getField('permission_id',true);
        $row['permission'] = json_encode($admin_permission);
        return $row;

    }

    /**
     * 专业保存管理员数据
     */
    public function editAdmin()
    {
        //>>保存收集到的数据
        $request_data = $this->data;
        if(empty($this->data['password'])){
            //>>如果密码为空,说明没有进行修改,删除此值.
            unset($this->data['password']);
            //>>同时不用更新盐.删除里面产生的盐
            unset($this->data['salt']);
        }else{
            //>>如果不为空,执行加密
            $salt =  $this->data['salt'];
            $password =  $this->data['password'];
            $this->data['password'] = md5(md5($password) . $salt);
        }
        //>>保存常规数据
        $this->startTrans();
        if($this->save() === false){
            //>>保存失败
            $this->rollback();
            return false;
        }

        //>>保存角色数据.
        $role_id = I('post.role_id');
        $admin_roleModel = M('AdminRole');
        if(($admin_roleModel->where(array('admin_id' => $request_data['id']))
                ->setField(array('role_id' =>$role_id))) === false){
            //>>保存角色失败
            $this->error = $admin_roleModel->getError();
            $this->rollback();
            return false;
        }

        //>>保存额外权限
        $admin_permissionModel = M('AdminPermission');
        //>>先删除原来的权限
        $admin_permissionModel->where(array('admin_id' => $request_data['id']))->delete();
        //>>获取新的额外权限
        $admin_permission_ids = I('post.permission_id');
        $admin_permission = array();
        foreach($admin_permission_ids as $val){
            $admin_permission[] = array(
                'admin_id'  => $request_data['id'],
                'permission_id'  => $val,
            );
        }
        if($admin_permissionModel->addAll($admin_permission) === false){
            $this->error = $admin_permissionModel->getError();
            $this->rollback();
            return false;
        }

        //>>添加成功 返回插入的主键值.
        $this->commit();
        return true;

    }

    /**
     * 专业进行删除操作
     */
    public function deleteAdmin($admin_id)
    {
        //>>先删除管理员数据
        $this->startTrans();
        if($this->delete($admin_id) === false)
        {
            //>>删除失败
            $this->rollback();
            return false;
        }
        //>>再删除管理员角色对就关系
        $admin_roleModel = M('AdminRole');
        if(($admin_roleModel->where(array('admin_id' => $admin_id))->delete()) === false)
        {
            //>>删除管理员对应角色失败
            $this->error = $admin_roleModel->getError();
            $this->rollback();
            return false;
        }

        //>>删除额外权限
        $admin_permissionModel = M('AdminPermission');

        if(($admin_permissionModel->where(array('admin_id' => $admin_id))->delete()) === false)
        {
            //>>删除管理员对应角色失败
            $this->error = $admin_permissionModel->getError();
            $this->rollback();
            return false;
        }

        //>>删除成功.
        $this->commit();
        return true;
    }

    public function _save_permission_info($id){
        //>>把登录管理对应的权限查询出来 存入session中
        $sql = "SELECT path FROM permission WHERE  path <> '' AND id IN(SELECT ap.permission_id FROM admin AS a INNER JOIN admin_permission AS ap ON a.id=ap.`admin_id`
WHERE id = " . $id . " UNION
SELECT permission_id FROM role_permission AS rp INNER JOIN
(SELECT role_id FROM admin AS a INNER JOIN admin_role AS ar ON A.`id`=ar.`admin_id`
WHERE id = " . $id . ") AS roleids ON rp.role_id=roleids.role_id);
";
        //>>根据sql 语句查询出权限表
        $permission_ids = M()->query($sql);
        //>>循环,把数据放在一个一维数组中
        $permission_info = array();
        foreach($permission_ids as $val){
            $permission_info[] = $val;
        }
        save_permission_info($permission_info);
    }

    public function _save_menu_info($id,$username){
        //>>查询出所有的当前用户能看到的菜单

        $sql = "SELECT DISTINCT `name`,id,path,parent_id FROM menu AS m,menu_permission AS mp
WHERE mp.permission_id IN(SELECT ap.permission_id FROM admin AS a INNER JOIN admin_permission AS ap ON a.id=ap.`admin_id`
WHERE id = " . $id . " UNION
SELECT permission_id FROM role_permission AS rp INNER JOIN
(SELECT role_id FROM admin AS a INNER JOIN admin_role AS ar ON A.`id`=ar.`admin_id`
WHERE id = " . $id . ") AS roleids ON rp.role_id=roleids.role_id) AND m.id=mp.menu_id;";

        if($username == 'admin@qq.com'){
            $sql = "select `name`,id,path,parent_id from menu";
        }
        $pathes = M('')->query($sql);
//        header("Content-Type:text/html;charset=utf-8");
//        dump($pathes);exit;
        save_menu_info($pathes);
    }


    public function _save_cookie_info($id){
            //>>如果不为空,说明有勾选
            //>>1.生成一个令牌与id一起保存到cookie中,并且把令牌保存到数据库中.
            $token =md5( mcrypt_create_iv(32));//>>生成令牌
            $cookie_info = array(
                'id'           => $id,
                'cookie_token' => $token,
            );
            save_cookie_info($cookie_info);//>>保存cookie
            //>>保存令牌到数据库.
            $this->setField($cookie_info);
        }

    /**
     * 专业修改密码
     */
    public function update_password()
    {
        $request_data = $this->data;

        $old_password = I('post.old_password');
        if($old_password == $request_data['password']){
            //>>新密码与原始密码一样.请重新输入.
            $this->error = "新密码与原始密码一样.请重新输入";
            return false;
        }
        //>>先验证原始密码是否正确
        $user_info = save_user_info();//>>从session中取出用户信息
        $salt_password = md5(md5($old_password) . $user_info['salt']);
//        dump($salt_password);exit;
        if($user_info['password'] == $salt_password){
            //>.验证成功,进行修改
            //>>准备数据
            $new_data = array(
                'id'       => $user_info['id'],
                'password' => md5(md5($request_data['password']) . $request_data['salt']),
                'salt'     => $request_data['salt'],
            );
            //>>保存
            if($this->save($new_data) === false){
                //>>修改失败
                return false;
            }
            //>>重新保存新的信息到session 和cookie
            //>>取出最新数据
            $user_info = $this->find($user_info['id']);
            //>>保存到session中
            save_user_info($user_info);
            //>>保存新的cookie信息
            $this->_save_cookie_info($user_info['id']);
            //>.所有的修成功后,才返回true;
            return true;
        }else{
            //>>原始密码不正确
            $this->error = '原始密码不正确';
            return false;
        }
    }

}