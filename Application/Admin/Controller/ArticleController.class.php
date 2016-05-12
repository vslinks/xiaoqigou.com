<?php
/*
 *文章 的类文件
 * User: wanyunshan
 * Date: 2016/5/9
 * Time: 15:00
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class ArticleController
 * @package Admin\Controller
 */
class ArticleController extends Controller
{
    /**
     * @var \Admin\Model\ArticleModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize(){
        //>> 用此方法初始化控制器类
        $this->_model=D('Article');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理文章',
            'add'  => '添加文章',
            'edit'  => '编辑文章',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理文章';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 文章添加操作
     */
    public function add(){
        if(IS_POST){
            //>> 进行添加文章的操作
            if($this->_model->create() === false){
                //>>如果获取数据失败 提示错误信息并跳转
                $this->error(implode(',',$this->_model->getError()));
            }
            if($this->_model->add() === false){
                //>> 如果插入数据失败 提示错误信息并跳转
                $this->error(implode(',',$this->_model->getError()));
            }
            $data = array();
            $data['article_id'] = $this->_model->getLastInsID();
            $data['content'] = I('post.content');
                $contentModel = M('articleContent');
            if($contentModel->add($data) === false){
                //>>文章详细内容添加失败
                $this->error(implode(',',$contentModel->getError()));
            }
            //>>添加数据成功
            $this->success('添加成功',U('index'));
        }else{
            //>>查询出文章分类数据  实例化文章分类表
            $article_cate_list = D('articleCategory')->where(['status' => 1])->getField('id,name');
            //>>把数据赋值到模板中
            $this->assign('article_cate_list',$article_cate_list);
            //>> 渲染视图
            $this->display();
        }
    }

    public function index(){
        $name = I('get.name');
       $where = array();
        if($name){
            //>>进行搜索
            $where['name']  = array('like','%' . $name . '%');
        }
        //>>因为有分页,所有交给 模型来处理
        $rows = $this->_model->getResultPage($where);
//        dump($rows);exit;
        //>>把数据赋值到模板中
        $this->assign($rows);
        $this->display();

    }

    /**
     * 编辑操作
     */
    public function edit(){
        if(IS_POST){
            //>>进行数据修改后的保存
            if($this->_model->create() === false){
                //>>获取数据失败 提示错误并跳转
                $this->error($this->_model->getError());
            }
            if($this->_model->save() === false){
                //>>文章修改失败 提示错误并跳转
                $this->error($this->_model->getError());
            }
            //>>更新文章内容表
            $map = array(
                'article_id' => I('post.id'),
                'content' => I('post.content'),
            );
            if(M('articleContent')->save($map) === false){
                //>>文章内容更新失败
                $this->error('文章内容更新失败');
            }
            //>> 文章修改成功
            $this->success('文章修改成功',U('index'));
            exit;
        }
        //>>获取回显数据
        $rows = $this->_model->getRows(I('get.id'));
        //>>赋值数据到模板
        $this->assign($rows);
        //>>渲染视图
        $this->display('add');
    }


    /**
     * 文章删除操作
     */
    public function delete(){
        $map = array(
            'id'  => I('get.id'),
            'status'  => 0,
            'name'  => array('exp',"concat(`name`,'_del_',`id`)"),
        );
        if(D('article')->save($map) === false){
            //>>删除失败
            $this->error('删除失败');
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }

}