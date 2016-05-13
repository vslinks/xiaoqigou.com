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
    <link rel="stylesheet" type="text/css" href="http://vslinks.shop.com/Public/Admin/ext/zTree/css/zTreeStyle/zTreeStyle.css" />
    <style type="text/css">
        .myupload-pre-item img{
            width:150px;
        }

        .myupload-pre-item{
            display:inline-block;
        }

        .myupload-pre-item a{
            position:relative;
            top:5px;
            right:15px;
            float:right;
            color:red;
            font-size:16px;
            text-decoration:none;
        }

        /**商品分类的ztree样式**/
        .ztree{
            width:220px;
            height:auto;
            margin-top: 10px;
            border: 1px solid #617775;
            background: #f0f6e4;
            overflow-y: scroll;
            overflow-x: auto;
        }
    </style>

</head>
<body>

<h1>
    <span class="action-span"><a href="<?php echo U('index');?>">商品列表</a>
    </span>
    <span class="action-span1"><a href="">小溪沟管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
    <div style="clear:both"></div>
</h1>

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="<?php echo U('');?>" method="post">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value=""size="30" />
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品货号： </td>
                    <td>
                        <input type="text" name="goods_sn" value="" size="20"/>
                        <span id="goods_sn_notice"></span><br />
                        <span class="notice-span"id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">供应商：</td>
                    <td>
                        <?php echo showSelect($supplier_list,"name","id","supplier_id");?>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品品牌：</td>
                    <td>
                        <?php echo showSelect($cate_list,"name","id","brand_id");?>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <input type="text" value="" id="goods_category_name" disabled="disabled"/>
                        <input type="hidden" value="" name="goods_category_id" id="goods_category_id"/>
                        <ul id="goods-categories-tree" class="ztree"></ul>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品数量：</td>
                    <td>
                        <input type="text" name="goods_number" size="8" value=""/>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="1"/> 是
                        <input type="radio" name="is_on_sale" value="0"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">加入推荐：</td>
                    <td>
                        <input type="checkbox" name="goods_status[]" value="1" class="goods_status" /> 精品
                        <input type="checkbox" name="goods_status[]" value="2"  class="goods_status"  /> 新品
                        <input type="checkbox" name="goods_status[]" value="4"  class="goods_status"  /> 热销
                    </td>
                </tr>
                <tr>
                    <td class="label">推荐排序：</td>
                    <td>
                        <input type="text" name="sort" size="5" value="100"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="0" size="20" />
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="0" size="20" />
                    </td>
                </tr>
                <tr>
                    <td class="label">商品LOGO：</td>
                    <td>
                        <input type="hidden" value="$row.logo" id="logo" name="logo"/>
                        <input id="file_upload" name="file_upload" type="file" multiple="true">
                        <img src="<?php echo ($row["logo"]); ?>" id="logo_view" />
                    </td>
                </tr>
                <tr>
                    <td class="label">商品详细描述：</td>
                    <td>
                        <textarea id="editor" type="text/plain" style="width:500px;height:200px;" name="content"></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品相册：</td>
                    <td>
                        <div class="myupload-img-box">
                            <?php if(is_array($row["paths"])): foreach($row["paths"] as $key=>$path): ?><div class="myupload-pre-item">
                                    <img src=""/>
                                    <a href="#">×</a>
                                </div><?php endforeach; endif; ?>
                        </div>

                        <div>
                            <input type="file" id='photo' multiple="true"/>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<div id="footer">
    <!-- 以下是一些执行的信息,使用系统 内置的方法实现-->
    共执行 <?php echo N('db_query');?> 个查询，用时 <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br/>
    版权所有 &copy; 1987-<?php echo date('Y');?> 小溪沟旅游开发有限公司，并保留所有权利。
</div>
<!--下面这个是为 js  预留的位置-->

      <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/myue/myue.config.js"></script>
      <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/myue/ueditor.all.min.js"></script>
      <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/myue/lang/zh-cn/zh-cn.js"></script>
      <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
      <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/layer/layer.js"></script>
      <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/uploadify/jquery.uploadify.min.js"></script>
      <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/zTree/js/jquery.ztree.core.min.js"></script>
    <script type="text/javascript">
      $(function(){
          //>>实现编辑器
          UE.getEditor('editor');
        //>>实现商品分类的树状显示
          create_ztree();
            //>>实现logo图片上传优化
          creatae_logo();
          //>>实现相册图片上传
          creatae_photo();

      });

      //>> 实现上传控件的使用,上传logo图片
      function creatae_logo(){
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

      }

      function creatae_photo(){
          $('#photo').uploadify({
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

                   /*   $('#logo').val(file_url);
                      $('#logo_view').attr('src',file_url);
                      $('#logo_view').css('width','200px');*/
                      var photo_html = '<div class="myupload-pre-item">';
                      photo_html += '<input type="hidden" name="goods_photo[]" value="' + file_url + '"/>';
                      photo_html += '<img src="' + file_url + '"/>';
                      photo_html += '<a href="#">×</a>';
                      photo_html += '</div>';
                        $(photo_html).appendTo($('.myupload-img-box'));
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

      }
      function create_ztree(){
          var setting = {
              "data"  : {
                  "simpleData" : {
                      "enable"  : true,
                      "pIdKey"  : 'parent_id',  //>>告知 zTree插件 ,我们使用父级id号为parent_id  .
//                      "idKey"   : "id",
//                      "rootPId" : 0
                  }
              },
              "callback" : {
                  "onClick" : function(event,treeId,treeNode){
                      //>>点击事件时的回调函数 ,event 为标准点击事件对象,treeId对应ztree的treeid
                      //>> treeNode  被点击节点的json数据对象
                      //>>当点击 的时候 ,我们要把点击 的数据回显到上面一个文本框内,
                      // 并且把数据id放到一个隐藏域中,以便于数据提交
//                    console.debug(treeNode);
                      $('#goods_category_name').val(treeNode.name);
                      $('#goods_category_id').val(treeNode.id);
                  }
              }
          };
          var zNodes = <?php echo ($goods_cate_list); ?>;  //>>保存所有节点数据
          var zTreeInit = $.fn.zTree.init($('#goods-categories-tree'),setting,zNodes);
          zTreeInit.expandAll(true);
      }
    </script>

</body>
</html>