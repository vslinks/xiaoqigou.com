<?php
/**
 * 商品控制器类文件
 * User: wanyunshan
 * Date: 2016/5/13
 * Time: 12:48
 */
namespace Admin\Controller;
use Think\Controller;

/**
 * Class GoodsController
 * @package Admin\Controller
 */
class GoodsController extends Controller
{
    /**
     * @var \Admin\Model\GoodsModel
     */
    private $_model=null;
    protected function _initialize(){
        //>>初始化模型对象
        $this->_model = D('goods');
    }

    /**
     * 列表操作
     */
    public function index(){
        //>>获取分页数据
        $cond = array();//>>装搜索条件
        $this->assign($this->_model->getPageResult($cond));
        //>>取得品牌和分类数据
        $this->assign($this->_model->getGoods());
        $this->assign('goods_statuses',$this->_model->goods_statuses);
        $this->assign('is_on_sales',$this->_model->is_on_sales);
        //>>渲染视图
        $this->display();

    }

    /**
     * 添加商品操作
     */
    public function add(){
        if(IS_POST){
            //>>进行商品添加操作
            if($this->_model->create() === false){
                //>>获取数据失败
                $this->error($this->_model->getError());
            }
            if($this->_model->addGoods() === false){
                //>>添加数据失败
                $this->error($this->_model->getError());
            }
            //>>数据添加成功
            $this->success('商品添加成功',U('index'));
        }else{
            //>>获取并分配商品品牌以及供应商数据到页面
            $this->assign($this->_model->getRows());
            //>>渲染添加视图页面
            $this->display();
        }
    }

    /**
     * 商品编辑操作
     */
    public function edit(){
        if(IS_POST){
            //>>进行保存编辑操作
            if($this->_model->create() === false){
                //>> 获取数据失败
                $this->error($this->_model->getError());
            }

            if($this->_model->saveGoods() === false){
                //>> 保存数据失败
                $this->error($this->_model->getError());
            }
            //>>修改成功
            $this->success('修改成功',U('index'));

        }else{
            //>>获取回显数据
            $row = $this->_model->findOne(I('get.id'));
            if($row === false){
                //>>没有数据
                $this->error('没有相应数据');exit;
            }
            $this->assign('row',$row);
            //>>获取分类数据和品牌数据和供应商数据.
            $this->assign($this->_model->getRows());
            //>>回显编辑页面
            $this->display('add');
        }
    }

    /**
     * ajax删除图片操作
     */
    public function deletePhoto()
    {
        $id = I('get.id');
        if(M('GoodsPhoto')->delete($id) === false){
            //>>删除失败
            $flag = false;
        }else{
            //>>删除成功
            $flag = true;
        }
        $this->ajaxReturn($flag);
    }

    /**
     * 商品删除操作
     */
    public function delete(){
        if($this->_model->where(array('id' =>I('get.id')))->setField('status',0) === false){
            //>>提示跳转
            $this->error($this->_model->getError());
        }
        $this->success('删除成功',U('index'));

    }
}