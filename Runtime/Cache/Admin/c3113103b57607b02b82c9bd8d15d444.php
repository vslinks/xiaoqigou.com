<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- 每个页面的标题可能不一样,所以使用一个变量来更新,每个页面传入即可-->
    <title>小溪沟 管理中心 - <?php echo ($meta_title); ?> </title>
    <meta charset="utf-8"/>
    <link href="/Public/Admin/css/general.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Admin/css/main.css" rel="stylesheet" type="text/css"/>
    <!-- 以下是为css扩展预留-->
    
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/page.css" />

</head>
<body>

    <h1>
        <span class="action-span"><a href="<?php echo U('add');?>">添加品牌</a></span>
        <span class="action-span1"><a href="#">小溪沟 管理中心</a></span>
        <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
    </h1>
    <div style="clear:both"></div>
    <div class="form-div">
        <form action="<?php echo U('');?>" name="searchForm" method="get">
            <img src="/Public/Admin/images/icon_search.gif" width="26" height="22" border="0" alt="search" />
            <input type="text" name="name" size="15"  value="<?php echo I('get.name');?>" />
            <input type="submit" value=" 搜索 " class="button" />
        </form>
    </div>
    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>品牌名称</th>
                    <th>品牌描述</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($rows)): $i = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
                    <td class="first-cell" align="center"><?php echo ($row["name"]); ?></td>
                    <td align="center"><?php echo ($row["intro"]); ?></td>
                    <td align="center"><?php echo ($row["sort"]); ?></td>
                    <td align="center">
                        <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑">编辑</a> |
                        <a href="<?php echo U('delete',['id'=>$row['id']]);?>" title="移除">移除</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr>
                    <td align="right" nowrap="true" colspan="6">
                        <div class="page">
                            <?php echo ($page_html); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </form>

<div id="footer">
    <!-- 以下是一些执行的信息,使用系统 内置的方法实现-->
    共执行 <?php echo N('db_query');?> 个查询，用时 <?php echo G('viewStartTime','viewEndTime');?>s，内存占用 <?php echo number_format((memory_get_usage() - $GLOBALS['_startUseMems'])/1024,2);?> KB<br/>
    版权所有 &copy; 1987-<?php echo date('Y');?> 小溪沟旅游开发有限公司，并保留所有权利。
</div>
<!--下面这个是为 js  预留的位置-->

</body>
</html>