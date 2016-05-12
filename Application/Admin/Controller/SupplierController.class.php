<?php
/**
 * 供货商的控制器文件
 * User: wanyunshan <vslinks@qq.com>
 * Date: 2016/5/8
 * Time: 19:49
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class SupplierController
 * @package Admin\Controller
 */
class SupplierController extends Controller
{
    /**
     * @var \Admin\Model\SupplierModel
     */
    protected $_model = null;

    /**
     * 通过 _initialize 方法来初始化
     */
    protected function _initialize(){
        // 由于Supplier 模型在此控制器中多次使用,所以初始化控制器的时候 放在一个属性中.
        $this->_model = D('Supplier');
        // 初始化meta_title标题
        $meta_titles = array(
            'add'=> '添加供货商',
            'index'=> '管理供货商',
            'edit'=> '编辑供货商',
        );
        // 根据当前操作取出标题并赋值到模板中
        $meta_title = isset($meta_titles[ACTION_NAME]) ? $meta_titles[strtolower(ACTION_NAME)] : '管理供货商';
        $this->assign('meta_title', $meta_title);
    }
    /**
     * 供货商列表操作.
     */
    public function index(){
        // 1.1取得搜索提交的数据.
        $name = I('get.name');
        $cond = array();
        // 如果提交数据存在
        if($name){
            $cond = array('name' => array('like','%' . $name . '%'));
        }
        // 查询出列表数据
        // 为了做分页,所以把它交给模型来处理
        $rows = $this->_model->getPageResult($cond);
        // 2.分配数据到模板中. 直接传入一个参数,里面的每一个元素都会被当成变量分配到模板中
        $this->assign($rows);
        // 3.渲染视图.
        $this->display();
    }

    /**
     * 添加供应商操作
     */
    public function add(){
        if(IS_POST){
            //>>判断是否取得数据
            if($this->_model->create() === false){
                //>>提示错误并跳转
                $this->error($this->_model->getError());
            }
            if($this->_model->add() === false){
                //>>添加失败,提示并跳转
                $this->error($this->_model->getError());
            }
            //>> 添加成功
            $this->success('添加成功', U('index'));
        }else{
            //>>渲染添加视图页面
            $this->display();
        }
    }

    /**
     * 这是供货商的删除操作
     */
    public function delete(){
        //>> 取得需要删除的id号
        $id = I('get.id');
        $map = array(
            'id'      => $id,
            'status'  => 0,
            //>>备注:exp 只能用小写.不能用大写.
            'name'    => array('exp', 'concat(`name`, "_del_", `id`)')
        );
        if($this->_model->save($map) === false){
            //>>删除失败,提示错误信息并跳转回上一页面
            $this->error($this->_model->getError());
        }else{
            //>>删除成功
            $this->success('删除成功', U('index'));
        }
    }

    public function edit(){
        //>> 1 回显数据
        //>> 2 保存修改后数据

        if(IS_POST){
            if($this->_model->create() === false){
                //>> 取得post数据失败,跳转并提示错误信息
                $this->error($this->_model->getError());
            }
            //>> 保存修改后数据
            if($this->_model->save() === false){
                //>> 保存数据失败,跳转并提示错误信息
                $this->error($this->_model->getError());
            }
            $this->success('修改成功',U('index'));
        }else{
            //>> 回显数据
            //>> 取得id号 并根据id号取得回显数据
            $row = $this->_model->find(I('get.id'));
            //>> 赋值回显数据到模板中
            $this->assign('row', $row);
            //>> 渲染视图.这里和add共用一个模板
            $this->display('add');
        }
    }

}