<?php
/**
 * 角色模型类文件.
 * User: wanyunshan
 * Date: 2016/5/15
 * Time: 22:04
 */

namespace Admin\Model;


use Think\Model;

/**
 * Class RoleModel
 * @package Admin\Model
 */
class RoleModel extends Model
{
    /**
     * 进行添加角色操作
     */
    public function addRole()
    {
        $this->startTrans();
        if(($role_id = $this->add()) === false){
            //>>添加失败
            $this->rollback();
            return false;
        }
        //>>收集数据保存到角色权限对应表
        $permissiones = I('post.permission_id');
        if(!empty($permissiones)){
            $data = array();
            foreach($permissiones as $val){
                $data[] = array(
                    'role_id' => $role_id,
                    'permission_id' => $val,
                );
            }
            $rolePermission = M('RolePermission');
            if($rolePermission->addAll($data) === false){
                $this->error = $rolePermission->getError();
                $this->rollback();
                return false;
            }
        }

        //>>添加成功
        $this->commit();
        return true;
    }

    /**
     * 获取所有角色数据
     */
    public function getList()
    {
        $rows = $this->select();
        return $rows;
    }

    /**
     * 编辑保存操作
     * @param $id
     */
    public function editRole($id)
    {
        $this->startTrans();
        if($this->save() === false){
            //添加失败
            $this->rollback();
            return false;
        }
        //>>保存权限对应数据  先删除对应的所有数据
        $rolePermission = M('RolePermission');
        if($rolePermission->where(array('role_id' => $id))->delete() === false){
            //>>删除原始数据失败
            $this->rollback();
            $this->error = $rolePermission->getError();
            return false;
        }
        //>>添加新的权限
        //>>收集数据保存到角色权限对应表
        $permissiones = I('post.permission_id');
        if(!empty($permissiones)){
            $data = array();
            foreach($permissiones as $val){
                $data[] = array(
                    'role_id' => $id,
                    'permission_id' => $val,
                );
            }
            $rolePermission = M('RolePermission');
            if($rolePermission->addAll($data) === false){
                $this->error = $rolePermission->getError();
                $this->rollback();
                return false;
            }
        }
        //添加成功
        $this->commit();
        return true;

    }

    /**
     * 删除角色及对应权限操作
     * @param $id
     * return boolean
     */
    public function deleteRole($id)
    {
        //>>删除角色数据
        $this->startTrans();
        if($this->delete($id) === false){
            //>>删除失败
            $this->rollback();
            return false;
        }

        //>>删除角色权限对应表
        $rolePermission = M('RolePermission');
        $result = $rolePermission->where(array('role_id' => $id))->delete();
        if($result === false){
            $this->error = $rolePermission->getError();
            $this->rollback();
            return false;
        }
        //>>删除角色管理员对应表
        $admin_rloeModel = M('AdminRole');
        if(($admin_rloeModel->where(array('role_id' => $id))->delete()) === false){
            $this->error = $admin_rloeModel->getError();
            $this->rollback();
            return false;
        }
        //>>删除成功
        $this->commit();
        return true;
    }
}