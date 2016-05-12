<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- 每个页面的标题可能不一样,所以使用一个变量来更新,每个页面传入即可-->
    <title>小溪沟 管理中心 - <?php echo ($meta_title); ?> </title>
    <meta charset="utf-8"/>
    <link href="/Public/Admin/css/general.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Admin/css/main.css" rel="stylesheet" type="text/css"/>
    <!-- 以下是为css扩展预留-->
    
    <link rel="stylesheet" type="text/css" href="http://vslinks.shop.com/Public/Admin/ext/zTree/css/zTreeStyle/zTreeStyle.css" />
    <style type="text/css">
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
        <span class="action-span"><a href="<?php echo U('index');?>">商品分类管理</a></span>
        <span class="action-span1"><a href="#">小溪沟 管理中心</a></span>
        <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
    </h1>
    <div style="clear:both"></div>
    <div class="main-div">
        <form method="post" action="<?php echo U('');?>" enctype="multipart/form-data" >
            <table cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <td class="label">商品分类名称</td>
                    <td>
                        <input type="text" name="name" maxlength="60" value="<?php echo ($row["name"]); ?>" />
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类描述</td>
                    <td>
                        <textarea  name="intro" cols="60" rows="4"><?php echo ($row["intro"]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="label">上级分类</td>
                    <td>
                        <input type="hidden" name="parent_id" value="" id="goods_category_id" />
                        <input type="text" value="" disabled="disabled" id="goods_category_name" />
                        <ul id="goods-categories-tree" class="ztree"></ul>
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
                        <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>"/>
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
    <script type="text/javascript" src="http://vslinks.shop.com/Public/Admin/ext/zTree/js/jquery.ztree.core.js"></script>
    <script type="text/javascript">
        $(function(){
            /**
             * 备注一下,在select check  中进行赋值选中时要用数组.
             * 注意:在使用默认值输出时 | 运算符左右不能有空格
             */
            $('.status').val([<?php echo ((isset($row["status"]) && ($row["status"] !== ""))?($row["status"]):1); ?>])

        /**
         * 在这里使用zTree 树 插件
         */

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
        var zNodes = <?php echo ($rows); ?>;  //>>保存所有节点数据
        var zTreeInit = $.fn.zTree.init($('#goods-categories-tree'),setting,zNodes);
        zTreeInit.expandAll(true);
        //>>设置父级分类为顶级分类

       /* <?php if(isset($row)): ?>//回显父级分类
        var parent_node = zTreeInit.getNodeByParam('id',<?php echo ($row["parent_id"]); ?>);
        <?php else: ?>
        var parent_node = zTreeInit.getNodeByParam('id',0);<?php endif; ?>
        console.debug(parent_node);
        zTreeInit.selectNode(parent_node);
        $('#goods_category_id').val(parent_node.id);
        $('#goods_category_name').val(parent_node.name);
*/

        <?php if(isset($row)): ?>//>>如果row 变量有定义 在这里回显上级分类
            var parent_node = zTreeInit.getNodeByParam('id',<?php echo ($row['parent_id']); ?>);
            <?php else: ?>
             var parent_node = zTreeInit.getNodeByParam('id',10);<?php endif; ?>
        zTreeInit.selectNode(parent_node);
        console.debug(parent_node);
        $('#goods_category_id').val(parent_node.id);
        $('#goods_category_name').val(parent_node.name);
        });
    </script>

</body>
</html>