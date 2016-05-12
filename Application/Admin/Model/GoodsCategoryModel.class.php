<?php
/**
 * 商品分类模型 类 文件.
 * User: wanyunshan
 * Date: 2016/5/11
 * Time: 12:49
 */

namespace Admin\Model;


use Admin\Logic\NestedDbLogic;
use Admin\Service\NestedSetsService;
use Think\Model;

/**
 * Class GoodsCategoryModel
 * @package Admin\Model
 */
class GoodsCategoryModel extends Model
{
    /**
     * @var array
     * 设置商品分类添加与修改的自动验证规则
     */
    protected $_validate = array(
        array('name','require','商品分类名称不能为空',self::MUST_VALIDATE,'regex',self::MODEL_BOTH),
        array('name','','商品分类已经存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
    );
//    /**
//     * 开启批量验证
//     */
//    protected $patchValidate = true;


    public function getZtreeList(){
        //>>获取列表数据方法
        $rows = $this->field('*')
                ->order('`lft`')                 //>> 以left左边界排序 就自动是按照无限极分类排序的
                ->where(array('status' => 1))  //>>状态为1 表示正在使用的
                ->select();
        return $rows;
    }

    /**
     * 添加商品分类方法
     */
    public function addZtree(){
      //>>调用私有方法得到对象
        $nestedSets = $this->inistNes();
        //>> 调用实例化后的对象中的inset方法传入三个参数进行数据添加操作
        $result = $nestedSets->insert($this->data['parent_id'],$this->data,'bottom');
        //>> 对返回结果进行判断 ,
        if($result === false){
            //>> 把错误信息放到基础模型对象的错误信息中
            $this->error = M()->getError();
            return false;
        }
        //>>添加成功  //>>返回最后的主键值.
        return $result;
    }

    /**
     * 修改商品分类操作
     */
    public function editZtree(){
        //>>先判断是否有更改父级分类
        $data = $this->data;
        $parent_id = $this->getFieldById($this->data['id'],'parent_id');
        if($this->data(['parent_id']) != $parent_id){
            //>>不等于说明移动了层级  ,才使用nestedSes 插件
            $nestedSets = $this->inistNes();
            //>>调用对象中的moveUnder  方法进行修改
            $resutl = $nestedSets->moveUnder($data['id'],$data['parent_id'],'bottom');
            if($resutl === false){
                //>>失败
                $this->error = '请重新选择';
                return false;
            }
        }
        //>>然后使用save 方法保存修改数据 并返回结果
        return $this->save();

    }

    /**
     * @param $id  传入的参数
     */
    public function deleteZtree($id){
        //>>逻辑删除
            //>>根据id事情取得当前分类的左右边界
        $filesInfo = $this->field('lft,rght')->find($id);
        $map = array(
            'lft' => array('egt',$filesInfo['lft']),
            'rght' => array('elt',$filesInfo['rght']),
        );
        return $this->where($map)->save(array('status' => 0));
       /* //>>调用私有方法得到nestedSets 对象
        $nested = $this->inistNes();
        //>>进行删除操作,这个 操作是一个物理删除
        return $nested->delete($id);*/
    }

    /**
     * @return Object NestedSetsService
     */
    private function inistNes(){
        //>>实例化nestedSets 类需要的数据库操作类对象
        $ORM = new NestedDbLogic();
        //>>实例化一个nestedSets类  传入7个参数
        $nestedSets = new NestedSetsService($ORM,$this->trueTableName,'lft','rght','parent_id','id','level');
        return $nestedSets;
    }
}