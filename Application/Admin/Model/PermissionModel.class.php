<?php
/**
 *权限模型类文件
 * User: wanyunshan
 * Date: 2016/5/15
 * Time: 19:09
 */

namespace Admin\Model;


use Admin\Service\NestedSetsService;
use Think\Model;

/**
 * Class PermissionModel
 * @package Admin\Model
 */
class PermissionModel extends Model
{
    /**
     * 获取并返回所有权限数据
     * @return array
     */
    public function getList()
    {
        $row = $this->field('id,parent_id,name')->order('lft')->select();
        array_unshift($row,array('id'=> 0,'parent_id'=>null,'name' => '所有权限'));
        return $row;
    }

    /**
     * 完成添加权限的操作
     */
    public function addPermission()
    {
        //>>保存数据
        $parent_id =  $this->data['parent_id'];
        $requestDate = $this->data;
        //>>实例化nestedSets 数据库操作对象
        $nestedSets = $this->_initNestedSets();
        //>>调用insert方法进行数据添加操作
        $result = $nestedSets->insert($parent_id,$requestDate,'bottom');
        if($result === false){
            //>>添加失败
            $this->error = '添加失败';
            return false;
        }else{
            //>>添加成功
            return true;
        }
    }

    public function getRow($id)
    {
        $row = $this->find($id);
        //>>取得所有权限数据
        return $row;

    }

    /**
     * 修改数据操作
     */
    public function editPermission()
    {
        //>>保存收集到的数据
        $requestDate = $this->data;
        //>>根据
        $parent_id_copy = I('post.parent_id_copy');
        if($requestDate['parent_id'] !=$parent_id_copy){
            //>>说明有修改了父级分类 进行重新计算左右边界

            $nestedSets = $this->_initNestedSets();
            //>>调用insert方法进行数据添加操作
            $result = $nestedSets->moveUnder($requestDate['id'],$requestDate['parent_id'],'bottom');
            if($result === false){
                //>>添加失败
                $this->error = '修改失败';
                return false;
            }
        }
        return $this->save($requestDate);
    }

    /**
     * 进行删除权限操作
     */
    public function deletePermission($id)
    {
        //>>进行物理删除
        $nestedSets = $this->_initNestedSets();
        $this->startTrans();
       if($nestedSets->delete($id) === false)
       {
           //>>删除权限失败
           $this->rollback();
           return false;
       }

        //>>删除角色权限对应表
        $role_permissionModel = M('RolePermission');
        if(($role_permissionModel->where(array('permission_id' => $id))->delete()) === false)
        {
            //>>删除角色权限对应表数据失败
            $this->error = $role_permissionModel->getError();
            $this->rollback();
            return false;

        }
        //>>删除管理员额外权限对应表数据
        $admin_permissionModel = M('AdminPermission');
        if(($admin_permissionModel->where(array('permission_id' => $id))->delete()) === false)
        {
            //>>删除角色权限对应表数据失败
            $this->error = $admin_permissionModel->getError();
            $this->rollback();
            return false;

        }
        $this->commit();
        return true;

    }

    private function _initNestedSets()
    {
        $orm = D('NestedDb','Logic');
        //>>实例化nestedSets 对象
        $nestedSets = new NestedSetsService($orm,$this->trueTableName,'lft','rght','parent_id','id','level');
        return $nestedSets;
    }
}