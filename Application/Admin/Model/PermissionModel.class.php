<?php
/**
 *Ȩ��ģ�����ļ�
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
     * ��ȡ����������Ȩ������
     * @return array
     */
    public function getList()
    {
        $row = $this->order('lft')->select();
        return $row;
    }

    /**
     * ������Ȩ�޵Ĳ���
     */
    public function addPermission()
    {
        //>>��������
        $parent_id =  $this->data['parent_id'];
        $requestDate = $this->data;
        //>>ʵ����nestedSets ���ݿ��������
        $nestedSets = $this->_initNestedSets();
        //>>����insert��������������Ӳ���
        $result = $nestedSets->insert($parent_id,$requestDate,'bottom');
        if($result === false){
            //>>���ʧ��
            $this->error = '���ʧ��';
            return false;
        }else{
            //>>��ӳɹ�
            return true;
        }
    }

    public function getRow($id)
    {
        $row = $this->find($id);
        //>>ȡ������Ȩ������
        return $row;

    }

    /**
     * �޸����ݲ���
     */
    public function editPermission()
    {
        //>>�����ռ���������
        $requestDate = $this->data;
        //>>����
        $parent_id_copy = I('post.parent_id_copy');
        if($requestDate['parent_id'] !=$parent_id_copy){
            //>>˵�����޸��˸������� �������¼������ұ߽�

            $nestedSets = $this->_initNestedSets();
            //>>����insert��������������Ӳ���
            $result = $nestedSets->moveUnder($requestDate['id'],$requestDate['parent_id'],'bottom');
            if($result === false){
                //>>���ʧ��
                $this->error = '�޸�ʧ��';
                return false;
            }
        }
       return $this->save($requestDate);
    }

    /**
     * ����ɾ��Ȩ�޲���
     */
    public function deletePermission($id)
    {
        //>>��������ɾ��
        $nestedSets = $this->_initNestedSets();
        $result = $nestedSets->delete($id);
        return $result;

    }

    private function _initNestedSets()
    {
        $orm = D('NestedDb','Logic');
        //>>ʵ����nestedSets ����
        $nestedSets = new NestedSetsService($orm,$this->trueTableName,'lft','rght','parent_id','id','level');
        return $nestedSets;
    }
}