<?php
/**
 * 供货商模型类文件
 * User: wanyunshan <vslinks@qq.com>
 * Date: 2016/5/8
 * Time: 20:07
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

/**
 * Class SupplierModel
 * @package Admin\Model
 */
class SupplierModel extends Model
{

    //>>开启批量验证
    protected $patchValidate = true;
    //>> 设定供货商自动验证
    protected $_validate = array(
        array('name', 'require', '供货商名字不能为空',self::MUST_VALIDATE,'regex',self::MODEL_BOTH),
        array('name', '', '供货商已经存在',self::MUST_VALIDATE,'unique',self::MODEL_BOTH),
    );

    public function getPageResult(array $cond = array()){
        //>>把传入的条件和默认条件合并.设定默认查询条件为只查询status 为1 的.0表示已经逻辑删除
        $cond = array_merge(['status' => 1],$cond);
        //>>取得每页显示条数
        $page_size = C('PAGE_SIZE');
        //>>取得分页风格
        $theme = C('PAGE_THEME');
        //>>获取当前页数据
        $rows = $this->page(I('get.p'),$page_size)->where($cond)->select();
        //>>得到总页数
        $count = $this->where($cond)->count();
        //>>实例化page 类  传入两个参数 总页数 和 每页显示条数
        $page = new Page($count,$page_size);
        //>>设定分页风格
        $page->setConfig('theme',$theme);
        //>>获取分页html代码
        $page_html = $page->show();
        //>>返回结果集  变量名做键名,变量值做元素的值.
        return array('rows' => $rows, 'page_html' =>$page_html);
    }
}