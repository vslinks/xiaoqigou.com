<?php
/**
 * 验证码控制器类文件
 * User: wanyunshan
 * Date: 2016/5/17
 * Time: 18:37
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Verify;

/**
 * Class CaptchaController
 * @package Admin\Controller
 */
class CaptchaController extends Controller
{
    public function captcha()
    {
        $config = array(
            'length'    =>  4,  //>>设置验证码个数.
        );
        $verify = new Verify($config);
        $verify->entry();
    }
}