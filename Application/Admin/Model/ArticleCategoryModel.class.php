<?php
/**
 * 文章分类模型类文件
 * User: wanyunshan
 * Date: 2016/5/9
 * Time: 14:24
 */

namespace Admin\Model;

use Think\Page;
use Think\Model;

/**
 * Class ArticleCategoryModel
 * @package Admin\Model
 */
class ArticleCategoryModel extends Model
{
    /**
     * @var array
     * 设置文章分类添加与修改的自动验证规则
     */
    protected $_validate = array(
        array('name','require','文章分类名称不能为空',self::MUST_VALIDATE,'regex',self::MODEL_BOTH),
        array('name','','文章分类已经存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
    );
    /**
     * 开启批量验证
     */
    protected $patchValidate = true;

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