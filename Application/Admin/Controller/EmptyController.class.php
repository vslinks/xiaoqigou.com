<?php
/**
 * 空控制器 类 文件
 * User: wanyunshan
 * Date: 2016/5/11
 * Time: 18:44
 */

namespace Admin\Controller;


use Think\Controller;

/**
 * Class EmptyController
 * @package Admin\Controller
 */
class EmptyController extends Controller
{
    public function _empty($name){
        //>>空控制器下的_empty操作
        dump($name);
    }

}