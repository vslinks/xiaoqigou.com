<?php
/**
 * 商品品牌控制器文件.
 * User: wanyunshan
 * Date: 2016/5/9
 * Time: 9:51
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Image;
use Think\Upload;

/**
 * Class BrandController
 * @package Admin\Controller
 */
class BrandController extends Controller
{
    /**
     * @var \Admin\Model\BrandModel
     */
    protected $_model = null;

    /**
     * 因为模型对象在此类中会经常使用,所以使用
     * initialize 方法初始化对象的时候把它放在一个属性中.
     */
    protected function _initialize(){
        //>> 用此方法初始化控制器类
        $this->_model=D('brand');
        //>> 管理标题 根据操作名定义不同的title_name
        $meta_titles = array(
            'index'  => '管理品牌',
            'add'  => '添加品牌',
            'edit'  => '编辑品牌',
        );
        //>> 根据当前操作取出标题
        $meta_title = isset($meta_titles[strtolower(ACTION_NAME)])?$meta_titles[strtolower(ACTION_NAME)]:'管理品牌';
        //>> 把标题赋值到模板中
        $this->assign('meta_title',$meta_title);
    }

    /**
     * 品牌列表操作
     */
    public function index(){
        //>> 0先进行搜索 判断
        $name = I('get.name');
        $where = array();
        if($name){
            //>>如果存在,就进行搜索
            $where = array('name' =>array('like','%' . $name .'%'));
        }
        //>> 分页,交给模型处理
        $rows = $this->_model->getResultPage($where);
        $this->assign($rows);
        //>> 2 渲染视图
        $this->display();
    }

    /**
     * 添加品牌操作
     */
    public function add(){
        //>> 1 判断是否有post 提交,有就进行添加操作,没有渲染视图
        if(IS_POST){
            //>> 0 先进行图片上传验证
            // 0.1 取得图片信息
           /* $date = $_FILES['logo'];
            $result = $this->_model->checkImage($date);
            if($result === false) {
                //>> 图片上传失败
                $this->error('图片上传失败');
            }else{
                //>>否则返回缩略图保存地址
                $_POST['logo'] = $result;
            }*/
            //>> 1.1有post 操作,进行添加.
            if($this->_model->create() === false){
                //>> 获取数据失败 提示错误信息并返回.
                $this->error(implode(',',$this->_model->getError()));
            }
            //>> 否则获取数据成功
            if($this->_model->add() === false){
                //>> 数据添加失败
                $this->error(implode(',',$this->_model->getError()));
            }
            //>> 数据添加成功
            $this->success('添加成功',U('index'));
        }else{
            //>> 1.2没有post 提交,直接渲染添加页面视图
            $this->display();
        }
    }

    /**
     * 品牌编辑操作
     */
    public function edit(){
        //>>判断 是否有post提交
        if(IS_POST){
            //>>编辑完成后提交保存
            //>>判断图片是否有进行修改
           /* $date = $_FILES['logo'];
            $result = $this->_model->checkImage($date);
            if($result === false) {
                //>> 图片上传失败
                $this->error('图片上传失败');
            }else{
                //>>否则返回缩略图保存地址
                $_POST['logo'] = $result;
            }*/
            if($this->_model->create() === false){
                //>>获取数据失败
                $this->error(implode(',',$this->_model->getError()));
            }
            if($this->_model->save() === false){
                //>>数据修改失败
                $this->error(implode(',',$this->_model->getError()));
            }
            //>>修改成功
            $this->success('修改成功',U('index'));
            exit;
        }
        //>> 1 获取回显数据
        $row = $this->_model->find(I('get.id'));
        //>> 把数据赋值到模板
        $this->assign('row',$row);
        //>> 渲染视图
        $this->display('add');
    }

    /**
     * 品牌删除操作
     */
    public function delete(){
        //>>由于要进行逻辑删除,所以要写好 需要修改的字段及更改后的值
        $map = array(
            'id' => I('get.id'),
            'status' => 0,
            'name' => array('exp',"concat(`name`,'_del_',`id`)"),
        );
        $result = $this->_model->save($map);
        if($result === false){
            //>>删除失败
            $this->error(implode(',',$this->_model->getError()));
        }
        //>>删除成功
        $this->success('删除成功',U('index'));
    }


    public function _empty($name){
        //>>空控制器下的_empty操作
        dump($name);
    }


}