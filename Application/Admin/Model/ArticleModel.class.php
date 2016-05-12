<?php
/**
 * Created by PhpStorm.
 * User: wanyunshan
 * Date: 2016/5/9
 * Time: 15:01
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

/**
 * Class ArticleModel
 * @package Admin\Model
 */
class ArticleModel extends Model
{
    /**
     * @var array
     * 设置文章添加与修改的自动验证规则
     */
    protected $_validate = array(
        array('name', 'require', '文章名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

//    //>> 开启批量验证
//    protected $patchValidate  =  true;

    /**
     * @param array $where
     */
    public function getResultPage(array $where = array())
    {
        //>>合并查询条件
        $where = array_merge(['status' => array('neq', 0)], $where);
        //>>获取总条数
        $count = $this->where($where)->count();
        //>>获取分页尺寸
        $page_size = C('PAGE_SIZE');
        //>>获取分页风格
        $page_theme = C('PAGE_THEME');
        //>>实例化分页类
        $page = new Page($count, $page_size);
        //>>设定分页风格
        $page->setConfig('theme', $page_theme);
        //>>得到分页html代码
        $page_html = $page->show();
        //>>查询出当前分页数据
        $rows = $this->where($where)->page(I('get.p'), $page_size)->select();
        //>>查询出分类列表
        $article_cate_list = D('articleCategory')->getField('id,name');
        return array(
            'rows' => $rows,
            'page_html' => $page_html,
            'article_cate_list' => $article_cate_list,
        );

    }

    /**
     * 取得文章分类,文章详细内容,以及文章信息
     * @param $id
     * @return array
     */
    public function getRows($id)
    {
        //>>查询出一条连回显文章信息数据
        $row = $this->find($id);
        //>>查询出分类数据
        $article_cate_list = D('articleCategory')->getField('id,name');
        //>>查询出文章详细内容
        $article_content_list = D('articleContent')->getField('article_id,content');

        return array(
            'row' => $row,
            'article_cate_list' => $article_cate_list,
            'article_content_list' => $article_content_list,
        );
    }
}