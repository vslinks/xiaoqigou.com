<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>H+ 后台主题UI框架 - 日期选择器layerDate</title>
    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <link rel="shortcut icon" href="favicon.ico"> <link href="./Public/Admin/ext/blog/css/bootstrap.min.css@v=3.3.5" rel="stylesheet">
    <link href="./Public/Admin/ext/blog/css/font-awesome.min.css@v=4.4.0" rel="stylesheet">
    <link href="./Public/Admin/ext/blog/css/animate.min.css" rel="stylesheet">
    <link href="./Public/Admin/ext/blog/css/animate.min.css" rel="stylesheet">
    <link href="./Public/Admin/ext/blog/css/style.min.css@v=4.0.0" rel="stylesheet"><base target="_blank">
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>layerDate简介</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="tabs_panels.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="tabs_panels.html#">选项1</a>
                                </li>
                                <li><a href="tabs_panels.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <p>你是时候换一款日期控件了，而layDate非常愿意和您成为工作伙伴。她致力于成为全球最用心的web日期支撑，为国内外所有从事web应用开发的同仁提供力所能及的动力。她基于原生JavaScript精心雕琢，兼容了包括IE6在内的所有主流浏览器。她具备优雅的内部代码，良好的性能体验，和完善的皮肤体系，并且完全开源，你可以任意获取开发版源代码，一扫某些传统日期控件的封闭与狭隘。layDate本着资源共享的开发者精神和对网页日历交互无穷的追求，延续了layui一贯的简单与易用。她遵循LGPL协议，您可以免费将她用于任何个人项目。</p>
                        <p>官网：<a href="http://sentsin.com/layui/laydate/" target="_blank">http://sentsin.com/layui/laydate/</a>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>layerDate示例</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="tabs_panels.html#">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a href="tabs_panels.html#">选项1</a>
                                        </li>
                                        <li><a href="tabs_panels.html#">选项2</a>
                                        </li>
                                    </ul>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">

                                <form class="form-horizontal m-t">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">普通：</label>
                                        <div class="col-sm-10">
                                            <input class="form-control layer-date" placeholder="YYYY-MM-DD hh:mm:ss" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                            <label class="laydate-icon"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">外部js调用：</label>
                                        <div class="col-sm-10">
                                            <input id="hello" class="laydate-icon form-control layer-date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图标触发日期：</label>
                                        <div class="col-sm-10">
                                            <input readonly class="form-control layer-date" id="hello1">
                                            <label class="laydate-icon inline demoicon" onclick="laydate({elem: '#hello1'});"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">图标触发日期：</label>
                                        <div class="col-sm-10">
                                            <input placeholder="开始日期" class="form-control layer-date" id="start">
                                            <input placeholder="结束日期" class="form-control layer-date" id="end">
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <p>日期选择器时，请给input添加<code>class="form-control layer-date"</code>，否则可能出现错位的情况。</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>layerDate API文档</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="tabs_panels.html#">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a href="tabs_panels.html#">选项1</a>
                                        </li>
                                        <li><a href="tabs_panels.html#">选项2</a>
                                        </li>
                                    </ul>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="panel-body">
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                <span class="label label-info">1</span> <a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseOne">核心方法：<code>aydate(options)</code></a>
                                            </h5>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <p>options是一个对象，它包含了以下key: '默认值'</p>
                                                    <pre>
elem: '#id', //需显示日期的元素选择器
event: 'click', //触发事件
format: 'YYYY-MM-DD hh:mm:ss', //日期格式
istime: false, //是否开启时间选择
isclear: true, //是否显示清空
istoday: true, //是否显示今天
issure: true, 是否显示确认
festival: true //是否显示节日
min: '1900-01-01 00:00:00', //最小日期
max: '2099-12-31 23:59:59', //最大日期
start: '2014-6-15 23:00:00',    //开始日期
fixed: false, //是否固定在可视区域
zIndex: 99999999, //css z-index
choose: function(dates){ //选择好日期的回调

}</pre>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                <span class="label label-info">2</span>
                                                <a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseTwo">其它方法/属性</a>
                                            </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <pre>
laydate.v   //获取laydate版本号
laydate.skin(lib);  //加载皮肤，参数lib为皮肤名 

/*
    layer.now支持多类型参数。timestamp可以是前后若干天，也可以是一个时间戳。format为日期格式，为空时则采用默认的“-”分割。
    如laydate.now(-2)将返回前天，laydate.now(3999634079890)将返回2096-09-28
*/
layer.now(timestamp, format);   //该方法提供了丰富的功能，推荐灵活使用。

laydate.reset();    //重设日历控件坐标，一般用于页面dom结构改变时。无参</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./Public/Admin/ext/blog/js/jquery.min.js@v=2.1.4"></script>
    <script src="./Public/Admin/ext/blog/js/bootstrap.min.js@v=3.3.5"></script>
    <script src="./Public/Admin/ext/blog/js/content.min.js@v=1.0.0"></script>
    <script src="./Public/Admin/ext/blog/js/plugins/layer/laydate/laydate.js"></script>
    <script>
        laydate({elem:"#hello",event:"focus"});var start={elem:"#start",format:"YYYY/MM/DD hh:mm:ss",min:laydate.now(),max:"2099-06-16 23:59:59",istime:true,istoday:false,choose:function(datas){end.min=datas;end.start=datas}};var end={elem:"#end",format:"YYYY/MM/DD hh:mm:ss",min:laydate.now(),max:"2099-06-16 23:59:59",istime:true,istoday:false,choose:function(datas){start.max=datas}};laydate(start);laydate(end);
    </script>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>

</html>