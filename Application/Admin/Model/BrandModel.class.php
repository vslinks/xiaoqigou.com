<?php
/**
 * 商品品牌模型类文件
 * User: wanyunshan
 * Date: 2016/5/9
 * Time: 9:54
 */

namespace Admin\Model;


use Think\Model;
use Think\Image;
use Think\Upload;
use Think\Page;

/**
 * Class BrandModel
 * @package Admin\Model
 */
class BrandModel extends Model
{
    /**
     * @var array
     * 设置品牌添加与修改的自动验证规则
     */
    protected $_validate = array(
        array('name','require','品牌名称不能为空',self::MUST_VALIDATE,'regex',self::MODEL_BOTH),
        array('name','','品牌已经存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
    );
    //>> 开启批量验证
    protected $patchValidate  =  true;

    /**
     * @param $date
     * @return bool|string
     */
    public function checkImage($date){

        //>> 0 先进行图片上传验证
        // 0.1 取得图片信息
        $logo_info = $date;
        //>>数据太多,交给模型去处理
        if($logo_info['error'] == 0){
            //>>图片已经上传,进行图片后期操作
            $upload = new Upload();
            // 设置附件上传大小为2M.
            $upload->maxSize = 2097152 ;
            // 设置附件上传类型.
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            //>> 进行文件上传
            $result = $upload->upload();
            if($result === false){
                //>> 图片上传失败
               return false;
            }
            $logo_file = $result['logo'];
            //>> 0.2图片上传成功
            //>>0.21 拼接图片路径
            $save_path = $logo_file['savepath']; //>>保存路径
            $save_name = $logo_file['savename'];  //>> 保存文件名

            // D:\ThinkPhp\tp0508\admin.shop.com\Uploads
            $logo_path ='./Uploads/' . $save_path . "$save_name";
//                dump($logo_path);exit;
            //>> 0.3制作缩略图
            //>> 0.31 实例化图片操作类
            $image = new Image();
            //>> 0.32 打开刚刚上传的图片
            $image->open($logo_path);
            //>>拼接缩略图的保存路径
            $save_name_arr = explode('.',$save_name);
            $save_name_arr[0] = $save_name_arr[0] . '_thumb';
            $thumb_name = implode('.',$save_name_arr);
            //>>下面的就是缩略图的保存路径
            $thumb_path = './Uploads/' . $save_path . $thumb_name;
            //>>把缩略图地址放到post数据中
            $image->thumb(150, 150)->save($thumb_path);
            return ltrim($thumb_path,'.');
        }
    }

    /**
     * @param array $where
     */
    public function getResultPage(array $where = array()){
        //>>合并查询条件
        $where = array_merge(['status' =>array('neq',0)],$where);
        //>>获取总条数
        $count = $this->where($where)->count();
        //>>获取分页尺寸
        $page_size = C('PAGE_SIZE');
        //>>获取分页风格
        $page_theme = C('PAGE_THEME');
        //>>实例化分页类
        $page = new Page($count,$page_size);
        //>>设定分页风格
        $page->setConfig('theme',$page_theme);
        //>>得到分页html代码
        $page_html = $page->show();
        //>>查询出当前分页数据
        $rows = $this->where($where)->page(I('get.p'),$page_size)->select();
        return array(
            'rows' => $rows,
            'page_html' => $page_html,
        );

    }

}