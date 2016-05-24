<?php
/**
 * 商品模型类文件.
 * User: wanyunshan
 * Date: 2016/5/13
 * Time: 12:52
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

/**
 * Class GoodsModel
 * @package Admin\Model
 */
class GoodsModel extends Model
{

    /**
     * @var array
     * 设置商品添加与修改的自动验证规则
     */
    protected $_validate = array(
        ['name', 'require', '商品名不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
        ['goods_category_id', 'require', '商品分类不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
        ['brand_id', 'require', '商品品牌不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
        ['supplier_id', 'require', '供货商不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
        ['shop_price', 'currency', '售价不合法', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
        ['market_price', 'currency', '市场价不合法', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
//        ['goods_status', 'require', '商品状态不能为空', self::EXISTS_VALIDATE, '', self::MODEL_BOTH],
    );

    /**
     * 自动完成的功能
     * @var array
     */
    protected $_auto = array(
        //>>自动生成
        array('inputtime',NOW_TIME,self::MODEL_INSERT),
        array('sn','create_sn',self::MODEL_BOTH,'callback'),
        array('goods_status','array_sum',self::MODEL_BOTH,'function'),
    );
    public $goods_statuses = array(
        1  => '精品',
        2  => '新品',
        4  => '热销',
    );

    public $is_on_sales = array(
        1  => '上架',
        0  => '下架',
    );

    /**
     * 获取好多数据
     * @return array
     */
    public function getRows(){
        //>>获取品牌数据
        $cate_list = D('Brand')->where(array('status' => 1))->select();
        //>>获取供应商数据
        $supplier_list = D('Supplier')->where(array('status' => 1))->select();
        //>>获取商品分类数据
        $goods_cate_list = D('GoodsCategory')->where(array('status' => 1))->select();
//        dump($goods_cate_list);exit;
        //>>返回获取到的数据
        return array(
            'cate_list' =>$cate_list,
            'supplier_list' => $supplier_list,
            'goods_cate_list' => json_encode($goods_cate_list),
        );
    }

    /**
     * 取得商品品牌与分类数据
     */
    public function getGoods(){
        //>>获取品牌数据
        $cate_list = D('Brand')->where(array('status' => 1))->select();
        //>>获取商品分类数据
        $goods_cate_list = D('GoodsCategory')->where(array('status' => 1))->select();
        return array(
            'cate_list' =>$cate_list,
            'goods_cate_list' => $goods_cate_list,
        );
    }

    /**
     * 专业分页
     * @param array $cond
     */
    public function getPageResult($cond = array()){
        //>>合并查询条件
        $cond = array_merge(array('status' => 1),$cond);
        //>>取得总页数
        $count = $this->where($cond)->count();
        //>>取得分页尺寸
        $size = C('PAGE_SIZE');
        //>>实例化分页工具类
        $page = new Page($count,$size);
        //>>设置分页样式
        $page->setConfig('theme',C('PAGE_THEME'));
        //>>取得分页html代码
        $pageHtml = $page->show();
        //>>取得所有商品数据
        $goods_list = $this->where($cond)->page(I('get.p'),$size)->select();
        //>>在这里把是否热销等状态弄出来
        foreach($goods_list as &$val){
            $val['is_new'] = $val['goods_status']&2?1:0;
            $val['is_best'] = $val['goods_status']&1?1:0;
            $val['is_hot'] = $val['goods_status']&4?1:0;
        }
        //>>返回取得的所有数据
        return array(
            'pageHtml' => $pageHtml,
            'goods_list' => $goods_list,
        );

    }

    /**
     * 货号自动生成方法
     * @param $sn
     * @return string
     */
    protected function create_sn($sn){
        if($sn){
            return $sn;
        }
        //>>生成货号
        $data =  date('Ymd');
        $goodsNumModel = M('GoodsNum');
        $num = $goodsNumModel->where(array('data' => $data))->getField('num');
        if($num){
            //>>如果存在 ,就加1
            $num++;
            //>>把货号写入数据库
         if($goodsNumModel->where(array('data' => $data))->save(array('num' => $num)) === false){
             //>>货号生成写入失败,
             die('货号生成写入失败');
         };
        }else{
            //>>如果不存在
            $num=1;
            $goodsNumModel->add(array('data' => $data, 'num' => $num));
        }

        //>>拼接货号后8位  从左边补齐8位
        $num = str_pad($num,8,'0',STR_PAD_LEFT);
        return $data . $num;


    }
    /**
     * 添加商品相关信息操作
     */
    public function addGoods(){
        //>>专业用来添加数据
            //>>先添加常规数据  因为要全部执行完成才算完成,所以要开启事务
        $this->startTrans();
        if(($goods_id = $this->add()) === false){
            $this->rollback();
            return false;
        }
        //>>添加详细信息
        $content = array(
            'goods_id' => $goods_id,
            'content' =>  I('post.content','',false),
        );
        //>>实例化商品详细描述模型
        $gooodsIntroModel = M('GoodsIntro');
        if($gooodsIntroModel->add($content) === false){
            //>.把错误信息放到当前模型错误信息中
            $this->error = $gooodsIntroModel->getError();
            $this->rollback();
            return false;
        }
        //>>添加相册信息
        $goods_pathes = I('post.goods_photo');
        //>>遍历拼接添加相册路径数组
        $map = array();
       foreach($goods_pathes as $val){
           $map[] = array(
               'goods_id' => $goods_id,
               'path'     => $val,
           );
       }
        //>>实例化商品相册模型对象
        $goodsPhotoModel = M('goodsPhoto');
        //>>进行添加
        if($goodsPhotoModel->addAll($map) === false){
            //>>相册添加失败
            $this->error = $goodsPhotoModel->getError();
            $this->rollback();
            return false;
        }
        //>>都 完成了.
        $this->commit();
        return $goods_id;

    }

    public function findOne($id){
        //>>获取一条回显数据
        $row = $this
            ->alias('g')
            ->join('JOIN __GOODS_INTRO__ AS gc ON gc.goods_id=g.id')
            ->find($id);

        //>>获取相册数据
        $photos = M('GoodsPhoto')
            ->where(array('goods_id' => $id))
            ->getField('id,path',true);
        //>>判断并把相册数据放入回显数组中
        if($row && $photos){
            $row['photos'] = $photos;
        }
        //>>对商品状态进行重新处理.以便于回显 在这里做不选,还是要在js里面去做.
/*        $goods_status = array();
        if($row['goods_status'] & 1){
            $goods_status[] = 1;
        }
        if($row['goods_status'] & 2){
            $goods_status[] = 2;
        }
        if($row['goods_status'] & 4){
            $goods_status[] = 4;
        }
        $row['goods_status'] = $goods_status;*/
//        var_dump($row['goods_status']);exit;
        return $row?$row:false;
    }

    /**
     * 数据修改保存操作
     */
    public function saveGoods(){
        //>>1保存常规数据
        //>>开启事务
        $this->startTrans();
       if($this->save() === false){
           $this->rollback();
           return false;
       } ;
        //>>保存详细描述
        $goods_id =  I('post.id');
        $goods_intro = array(
            'content' => I('post.content','',false),
            'goods_id' => $goods_id,
        );
        $introModel = M('GoodsIntro');
        if($introModel->save($goods_intro) === false){
            $this->error = $introModel->getError();
            $this->rollback();
            return false;
        };
        //>>保存相册信息
         $goods_photo = I('post.goods_photo');
         $goods_photo = I('post.goods_photo');
        if($goods_photo){
            $photos = array();
            //>>遍历出所有数据转换成一对就的二维数组便于保存
            foreach($goods_photo as $val){
                $photos[] = array(
                    'goods_id' => $goods_id,
                    'path'     => $val,
                );
            }
            $photoModel = M('GoodsPhoto');
            if($photoModel->addAll($photos) === false){
                //>>相册数据保存失败
                $this->error = $photoModel->getError();
                $this->rollback();
                return false;
            }
        }
        //>>数据修改成功
        $this->commit();
        return true;

    }
}