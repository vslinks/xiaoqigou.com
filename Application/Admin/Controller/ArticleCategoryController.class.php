<?php
/**
 * 文章分类控制器类文件
 * User: wanyunshan
 * Date: 2016/5/9
 * Time: 14:21
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class ArticleCategoryController
 * @package Admin\Controller
 */
class ArticleCategoryController extends Controller
{
    /**
     * @var \Admin\Model\ArticleCategoryModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize(){
        //>> 用此方法初始化控制器类
        $this->_model=D('ArticleCategory');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理文章分类',
            'add'  => '添加文章分类',
            'edit'  => '编辑文章分类',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理文章分类';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }
    
    /**
     * 添加文章分类操作
     */
    public function add(){
        if(IS_POST){
            //>> 进行添加操作
            if($this->_model->create() === false){
                //>>如果获取数据失败 提示错误信息并跳转
                $this->error(implode(',',$this->_model->getError()));
            }
            if($this->_model->add() === false){
                //>> 如果插入数据失败 提示错误信息并跳转
                $this->error(implode(',',$this->_model->getError()));
            }
            //>>添加数据成功
            $this->success('添加成功',U('index'));
        }else{
            //>>渲染视图
            $this->display();
        }
    }

    /**
     * 文章分类列表页面操作
     */
    public function index(){
        $name = I('get.name');
        $where = array('status' => 1);
        if($name){
            //>>进行搜索 书写模糊查询条件
            $where['name'] =  array('like', '%' . $name . '%');
        }
        //>> 1 分页,交给模型去处理
        $rows = $this->_model->getResultPage($where);
        //>> 3 赋值数据到模板
        $this->assign($rows);
        //>> 4 渲染视图
        $this->display();

    }

    /**
     * 文章分类修改操作
     */
    public function edit(){

        if(IS_POST){
            //>>进行数据修改后的保存
            if($this->_model->create() === false){
                //>>如果获取数据失败 提示错误信息并跳转
                $this->error(implode(',',$this->_model->getError()));
            }
            if($this->_model->save() === false){
                //>> 如果修改数据失败 提示错误信息并跳转
                $this->error(implode(',',$this->_model->getError()));
            }
            //>>添加数据成功
            $this->success('修改成功',U('index'));

        }else{
            //>> 获取回显数据
            $row = $this->_model->find(I('get.id'));
            //>> 赋值数据到模板
            $this->assign('row',$row);
            $this->display('add');
        }

    }

    /**
     * 删除文章分类操作
     */
    public function delete(){
        //>>进行逻辑删除
        $date = array(
            'id'      => I('get.id'),
            'status'  => 0,
            'name'    => array('exp', 'concat(`name`, "_del_", `id`)'),
        );
        if($this->_model->save($date) === false){
            //>>删除失败
            $this->error(implode(',',$this->_model->getError()));
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }
}