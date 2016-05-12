<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- 每个页面的标题可能不一样,所以使用一个变量来更新,每个页面传入即可-->
    <title>小溪沟 管理中心 - <?php echo ($meta_title); ?> </title>
    <meta charset="utf-8"/>
    <link href="/Public/Admin/css/general.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Admin/css/main.css" rel="stylesheet" type="text/css"/>
    <!-- 以下是为css扩展预留-->
    
    <link rel="stylesheet" type="text/css" href="http://vslinks.shop.com/Public/Admin/ext/uploadify/uploadify.css" />

</head>
<body>

    <h1>
        <span class="action-span"><a href="<?php echo U('index');?>">品牌管理</a></span>
        <span class="action-span1"><a href="#">小溪沟 管理中心</a></span>
        <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
    </h1>
    <div style="clear:both"></div>
    <div class="main-div">
        <form method="post" action="<?php echo U('');?>" enctype="multipart/form-data" >
            <table cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">品牌名称</td>
                    <td>
                        <input type="text" name="name" maxlength="60" value="<?php echo ($row["name"]); ?>" />
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">品牌描述</td>
                    <td>
                        <textarea  name="intro" cols="60" rows="4"><?php echo ($row["intro"]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="label">排序</td>
                    <td>
                        <input type="text" name="sort" maxlength="40" size="15" value="<?php echo ((isset($row["sort"]) && ($row["sort"] !== ""))?($row["sort"]):20); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="label">logo</td>
                    <td>
                        <input type="hidden" name="logo" value="<?php echo ($row["logo"]); ?>" id="logo"/>
                        <input id="file_upload" name="file_upload" type="file" multiple="true">
                        <img src="<?php echo ($row["logo"]); ?>" style="width: 200px;margin-top: 5px" id="logo_view">
                    </td>
                </tr>
                <tr>
                    <td class="label">是否显示</td>
                    <td>
                        <input type="radio" name="status" value="1" class="status"/> 是
                        <input type="radio" name="status" value="0" class="status"/> 否
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><br />
                        <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>" />
                        <input type="submit" class="button" value=" 确定 " />
                        <input type="reset" class="button" value=" 重置 " />
                    </td>
                </tr>
            </table>
        </form>
    </div>

<div id="footer">
    <!-- 以下是一些执行的信息,使用系统 内置的方法实现-->
    共执行 <?php echo N('db_query');?> 个查询，用时 <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br/>
    版权所有 &copy; 1987-<?php echo date('Y');?> 小溪沟旅游开发有限公司，并保留所有权利。
</div>
<!--下面这个是为 js  预留的位置-->

    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            /**
             * 备注一下,在select check  中进行赋值选中时要用数组.
             * 注意:在使用默认值输出时 | 运算符左右不能有空格
             */
            $('.status').val([<?php echo ((isset($row["status"]) && ($row["status"] !== ""))?($row["status"]):1); ?>]);

        /**
         * 使用uploadify插件做文件上传
         * 第一步,引入uploadify js脚本
         * 第二步,引入uploadify css样式
         */
        $('#file_upload').uploadify({
           //>>选择上传文件对象并调用uploadify方法 ,传入一个json对象做参数.
            "swf"  :   'http://vslinks.shop.com/Public/Admin/ext/uploadify/uploadify.swf',  //指定上传插件的主体文件.
            "uploader"  :  '<?php echo U("Upload/upload");?>',       // 指定服务器上的处理文件
            "auto"   :  true,    //>>  auto  设为true  当文件被添加到上传队列时会自动上传
            "buttonText"   :   '请选择图片' ,  //  定义选择控件上显示 的文字
            "fileSizeLimit" : '2000000KB',  //  允许上传的文件大小限制
            "fileTypeExts"  :  '*.gif;*.png;*.jpg;*.jpeg;*.bmp',//允许上传的文件后缀
            "removeTimeout" :  3,  //>>上传成功后的等待时间自动关闭.
            "onUploadSuccess" : function(file,data){
                var  file_info = $.parseJSON(data);
                //>>判断是否上传成功
                if(file_info.status){
                    //>> 取得上传成功后文件的保存路径
                    var file_url = file_info.file_url;
                    //>>把文件路径更新到隐域中,并回显图片
                    $('#logo').val(file_url);
                    $('#logo_view').attr('src',file_url);
                    $('#logo_view').css('width','200px');
                    layer.msg("上传成功",{"icon":5,"time":3000});
                }else{
                    //>>提示信息,
                    layer.msg(file_info.msg,{"icon":5,"time":3000});
                }
            },
           /* "onUploadError" :function(){
                alert('这是测试');
            },*/
        });

        });
    </script>

</body>
</html>