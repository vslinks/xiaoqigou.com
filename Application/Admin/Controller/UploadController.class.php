<?php
/**
 *文件上传类文件
 * User: wanyunshan
 * Date: 2016/5/11
 * Time: 9:04
 */

namespace Admin\Controller;


use Think\Controller;
use Think\Upload;

/**
 * Class UploadContrller
 * @package Admin\Controller
 */
class UploadController extends Controller
{
    /**
     * 文件上传操作
     */
    public function upload(){
        // 书写上传文件的配置文件
        $config = array(
            'mimes'         =>  array('image/jpg','image/png','image/gif','image/jpeg',), //允许上传的文件MiMe类型
            'maxSize'       =>  1024 * 2048, //上传的文件大小限制 (0-不做限制)
            'exts'          =>  array('jpg','jpeg','gif','png'), //允许上传的文件后缀
            'autoSub'       =>  true, //自动子目录保存文件
            'subName'       =>  array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath'      => './Public/Uploads/', //保存根路径
            'savePath'      =>  '', //保存路径
            'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
            'replace'       =>  false, //存在同名是否覆盖
            'hash'          =>  true, //是否生成hash编码
            'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
            'driver'        =>  'Qiniu', // 文件上传驱动
            // 上传驱动配置
            'driverConfig'  =>  array(
                'secretKey'      => 'XfGfeDjLMvKgw-sq0vvsHSgv_PRjxdlmRC5ESdpe', //七牛服务器AK密钥
                'accessKey'      => 'D5O30j5FGa6t47LmFk_d8cNhRCVmaqGtzheXbUUv', //七牛服务器SK
                'domain'         => 'o6znemxiz.bkt.clouddn.com', //七牛空间域名
                'bucket'         => 'xiaoqigou', //空间名称
                'timeout'        => 300, //超时时间
            ),
        );
        //>>实例化上传文件类
//        dump("这是一个测试");exit;
//        dump($config['rootPath']);exit;
        $upload = new Upload($config);
        //>>
        $file_url = "";
        $file_info = array();

        $result = $upload->upload($_FILES);
            if($result !== false){
                //>>上传成功
                $file_info = array_shift($result);
                if($upload->driver == 'Qiniu'){
                    $file_url = $file_info['url'];
                }else{
                    $file_url = ltrim($config['rootPath'] . $file_info['savepath'] . $file_info['savename'], '.');
                }
            }
        $return = array(
            'status'   => $file_info?1:0,  //>>如果上传成功返回1  上传失败 返回0
            'file_url' => $file_url,      //>> 返回文件的最终存放位置
            'msg'      => $upload->getError(),//>>如果出错 有错误就返回
        );
       $this->ajaxReturn($return);
    }
}