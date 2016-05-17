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
        $row = $this->order('lft')->select();
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
        $result = $nestedSets->delete($id);
        return $result;

    }

    private function _initNestedSets()
    {
        $orm = D('NestedDb','Logic');
        //>>实例化nestedSets 对象
        $nestedSets = new NestedSetsService($orm,$this->trueTableName,'lft','rght','parent_id','id','level');
        return $nestedSets;
    }
}