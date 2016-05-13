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
            $this->assign('goodsCate',D('goodsCategory')->getList());
//            var_dump(D('goodsCategory')->getList());exit;
            //>>渲染添加视图页面
            $this->display();
        }
    }

    /**
     * 商品编辑操作
     */
    public function edit(){

    }

    /**
     * 商品删除操作
     */
    public function delete(){

    }
}