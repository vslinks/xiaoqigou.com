<?php
/**
 * 菜单模型类文件.
 * User: wanyunshan
 * Date: 2016/5/17
 * Time: 14:01
 */

namespace Admin\Model;


use Admin\Service\NestedSetsService;
use Think\Model;

/**
 * Class MenuModel
 * @package Admin\Model
 */
class MenuModel extends Model
{
    //>>返回所有菜单数据
    public function getList()
    {
        $row = $this->field('id,parent_id,name')->order('lft')->select();
        array_unshift($row,array('id'=> 0,'parent_id'=>null,'name' => '顶级'));
        dump($orw);
        return $row;
    }

    /**
     * 获取一条回显数据
     * @param $id
     */
    public function getRow($id)
    {
        //>>获取常规数据
        $row  = $this->find($id);

        //>>获取关联权限数据
        $menu_permission = M('MenuPermission')
            ->where(array('menu_id' => $id))
            ->getField('permission_id',true);

        //>>把数据加入常规数据中一起返回
        $row['permission_ids'] = json_encode($menu_permission);
        return $row;
    }

    /**
     * 进行添加菜单相关操作
     */
    public function addMenu()
    {
        $request_data = $this->data;
        //>>因为要进行左右边界计算,所以需要引入方法.
        $nestedSets = $this->_initNestedSets();
        $this->startTrans();
        if(($menu_id = $nestedSets->insert($request_data['parent_id'],$request_data,'bottom')) === false)
        {
            //>>添加失败
            $this->error = "添加失败";
            $this->rollback();
            return false;
        }
        //>>添加关联权限
        $menu_permissionModel = M('MenuPermission');
        //>>获取新的关联权限
        $menu_permission_ids = I('post.permission_id');
        $menu_permission = array();
        foreach($menu_permission_ids as $val)
        {
            $menu_permission[] = array(
                'menu_id'  => $menu_id,
                'permission_id'  => $val,
            );
        }
        //>>添加关联权限
        if($menu_permissionModel->addAll($menu_permission) === false)
        {
            $this->error = $menu_permissionModel->getError();
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;

    }

    /**
     * 保存编辑修改后的数据
     * @param $id
     */
    public function editMenu($menu_id)
    {
        $this->startTrans();
        //>>先判断是否有改变上级菜单分类
        $re_parent_id = $this->getFieldById($menu_id,'parent_id');
        $parent_id = $this->data['parent_id'];
        if($parent_id != $re_parent_id)
        {
            //>>改变了上级菜单分类
            $nestedSets = $this->_initNestedSets();
            if(($nestedSets->moveUnder($menu_id,$parent_id,'bottom')) === false)
            {
                //>>修改失败
                $this->error = "修改失败";
                $this->rollback();
                return false;
            }
        }
        if($this->save() === false)
        {
            //>>添加失败
            $this->rollback();
            return false;
        }
        //>>保存关联权限数据
        $menu_permissionModel = M('MenuPermission');
        //>>先删除之前的数据
        if(($menu_permissionModel->where(array('menu_id' => $menu_id))->delete()) === false)
        {
            //>>删除失败
            $this->error = $menu_permissionModel->getError();
            $this->rollback();
            return false;
        }
        //>>再添加数据
        //>>获取新的关联权限
        $menu_permission_ids = I('post.permission_id');
        $menu_permission = array();
        foreach($menu_permission_ids as $val)
        {
            $menu_permission[] = array(
                'menu_id'  => $menu_id,
                'permission_id'  => $val,
            );
        }
        //>>添加关联权限
        if($menu_permissionModel->addAll($menu_permission) === false)
        {
            $this->error = $menu_permissionModel->getError();
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 删除菜单及相关数据操作
     * @param $menu_id
     */
    public function deleteMenu($menu_id)
    {
        $nestedSets = $this->_initNestedSets();
        $this->startTrans();
        if($nestedSets->delete($menu_id) === false){
            //>>删除失败
            $this->error = "删除失败";
            $this->rollback();
            return false;
        }
        //>>删除关联权限
        $menu_permissionModel = M('MenuPermission');
        if(($menu_permissionModel->where(array('menu_id' => $menu_id))->delete()) === false)
        {
            //>>删除关联权限失败
            $this->error = '删除关联权限失败';
            $this->rollback();
            return false;
        }
        //>>删除成功
        $this->commit();
        return true;
    }

    /**
     * @return Object NestedSetsService
     */
    private function _initNestedSets()
    {
        $orm = D('NestedDb','Logic');
        //>>实例化nestedSets 对象
        $nestedSets = new NestedSetsService($orm,$this->trueTableName,'lft','rght','parent_id','id','level');
        return $nestedSets;
    }
}