<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- 每个页面的标题可能不一样,所以使用一个变量来更新,每个页面传入即可-->
    <title>小溪沟 管理中心 - <?php echo ($meta_title); ?> </title>
    <meta charset="utf-8"/>
    <link href="/Public/Admin/css/general.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Admin/css/main.css" rel="stylesheet" type="text/css"/>
    <!-- 以下是为css扩展预留-->
    
    <link rel="stylesheet" type="text/css" href="http://vslinks.shop.com/Public/Admin/css/page.css" />

</head>
<body>

    <h1>
        <span class="action-span"><a href="<?php echo U('add');?>">添加新商品</a></span>
        <span class="action-span1"><a href="">管理中心</a></span>
        <span id="search_id" class="action-span1"> <?php echo ($meta_title); ?> </span>
        <div style="clear:both"></div>
    </h1>
    <div class="form-div">
        <form action="" name="searchForm">
            <img src="/Public/Admin/images/icon_search.gif" width="26" height="22" border="0" alt="search" />
            <!-- 分类 -->
            分类:<?php echo showSelect($goods_cate_list,"name","id","brand_id");?>
            <!-- 品牌 -->
            品牌:<?php echo showSelect($cate_list,"name","id","brand_id");?>
            <!-- 推荐 -->
            <select name="intro_type">
                <option value="0">全部</option>
                <option value="is_best">精品</option>
                <option value="is_new">新品</option>
                <option value="is_hot">热销</option>
            </select>
            <!-- 上架 -->
            <select name="is_on_sale">
                <option value=''>全部</option>
                <option value="1">上架</option>
                <option value="0">下架</option>
            </select>
            <!-- 关键字 -->
            关键字 <input type="text" name="keyword" size="15" />
            <input type="submit" value=" 搜索 " class="button" />
        </form>
    </div>

    <!-- 商品列表 -->
    <form method="post" action="" name="listForm" onsubmit="">
        <div class="list-div" id="listDiv">
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>编号</th>
                    <th>商品名称</th>
                    <th>货号</th>
                    <th>价格</th>
                    <th>上架</th>
                    <th>精品</th>
                    <th>新品</th>
                    <th>热销</th>
                    <th>推荐排序</th>
                    <th>库存</th>
                    <th>操作</th>
                </tr>
                <?php if(is_array($goods_list)): foreach($goods_list as $key=>$row): ?><tr>
                        <td align="center"><?php echo ($row["id"]); ?></td>
                        <td align="center" class="first-cell"><?php echo ($row["name"]); ?></td>
                        <td align="center"><?php echo ($row["sn"]); ?></td>
                        <td align="center"><?php echo ($row["shop_price"]); ?>/<?php echo ($row["market_price"]); ?></td>
                        <td align="center"><img src="/Public/Admin/images/<?php echo ($row["is_on_sale"]); ?>.gif"/></td>
                        <td align="center"><img src="/Public/Admin/images/<?php echo ($row["is_best"]); ?>.gif"/></td>
                        <td align="center"><img src="/Public/Admin/images/<?php echo ($row["is_new"]); ?>.gif"/></td>
                        <td align="center"><img src="/Public/Admin/images/<?php echo ($row["is_hot"]); ?>.gif"/></td>
                        <td align="center"><?php echo ($row["sort"]); ?></td>
                        <td align="center"><?php echo ($row["stock"]); ?></td>
                        <td align="center">
                            <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑"><img src="/Public/Admin/images/icon_edit.gif" width="16" height="16" border="0" /></a>
                            <a href="<?php echo U('delete',['id'=>$row['id']]);?>" onclick="" title="回收站"><img src="/Public/Admin/images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
                    </tr><?php endforeach; endif; ?>
            </table>

            <!-- 分页开始 -->
            <div class="page">
                        <?php echo ($pageHtml); ?>
            </div>
            <!-- 分页结束 -->
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