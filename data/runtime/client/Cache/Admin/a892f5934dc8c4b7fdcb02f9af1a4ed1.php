<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<!--<link rel="shortcut icon" href="favicon.ico">-->
	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/h/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/public/h/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/public/h/css/animate.min.css" rel="stylesheet">
    <link href="/public/h/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/public/",
    JS_ROOT: "js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/h/js/jquery.min.js?v=2.1.4"></script>
    <script src="/public/h/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="/public/h/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/public/h/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/public/h/js/plugins/layer/layer.min.js"></script>
    <script src="/public/h/js/hplus.min.js?v=4.1.0"></script>
    <script type="text/javascript" src="/public/h/js/contabs.min.js"></script>
    <script src="/public/h/js/plugins/pace/pace.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>

<link rel="shortcut icon" href="favicon.ico"> <link href="/public/h/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/public/h/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/public/h/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/public/h/css/plugins/cropper/cropper.min.css" rel="stylesheet">
    <link href="/public/h/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="/public/h/css/plugins/switchery/switchery.css" rel="stylesheet">

    <link href="/public/h/css/animate.min.css" rel="stylesheet">
    <link href="/public/h/css/style.min862f.css?v=4.1.0" rel="stylesheet">

	<!-- Data Tables -->
    <link href="/public/h/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
	<style>
	.table td{word-break:break-word};
	</style>

</head>
<body class="J_scroll_fixed">
	<div class="wrap J_check_wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Gameaccess/iospay');?>">版本支付列表</a></li>
			<li ><a href="<?php echo U('Gameaccess/addiospay');?>">添加版本支付</a></li>
		</ul>
        <div class="ibox-content">
		  <form role="form" class="form-inline" method="post" action="<?php echo U('Gameaccess/iospay');?>">
		     
			<div class="form-group">
                                <label class="font-noraml">游戏名称： </label>
                                <input type="text" placeholder="请输入游戏名" name="name" value="<?php echo ($formget['name']); ?>" class="form-control">
							    
            </div>
			<button class="btn btn-primary" name='submit' type="submit" value="搜索">搜索</button>
			
		</form>
		</div>
		<form class="J_ajaxForm" action="" method="post">
			<div class="ibox-content"> 
		     <table class="table table-striped table-bordered table-hover dataTables-example" style="margin-bottom:5px">
				<thead>
					<tr>
						<th>APPID</th>
						<th>游戏名称</th>
						<th>版本号</th>
						<th>运行平台</th>
						<th>更新时间</th>
						<th>支付状态</th>
						<th>是否开启苹果正式环境</th>
						<th>管理操作</th>
					</tr>
				</thead>
				
				<?php if(is_array($games)): foreach($games as $key=>$vo): ?><tr>
					<td><?php echo ($vo["gid"]); ?></td>
					<td><?php echo ($vo["gamename"]); ?></td>
					<td><?php echo ($vo["versions"]); ?></td>
					<td>
					    <?php if($vo['type'] == 1): ?>Android
						<?php elseif($vo['type'] == 2): ?>
							    IOS<?php endif; ?> 
					</td>
					<td><?php echo (date('Y-m-d  H:i:s',$vo["create_time"])); ?></td>
					<td><?php if($vo['paytype'] == 1): ?>WAP支付
						<?php elseif($vo['type'] == 2): ?>
							    内购支付<?php endif; ?> 
					</td>
					<td>
						<div class="switch">
                            <div class="onoffswitch">
							    <?php $gid_select=1==$vo['isappstr'] ?"checked":""; ?>
								
								<?php if($vo['isappstr'] == 1): ?><input type="checkbox" checked class="onoffswitch-checkbox js-check-change" data-aid="<?php echo ($vo['id']); ?>" 
									id="example<?php echo ($vo['id']); ?>" value="<?php echo ($vo['isappstr']); ?>">
								<?php else: ?>
									<input type="checkbox" class="onoffswitch-checkbox js-check-change" data-aid="<?php echo ($vo['id']); ?>" 
									id="example<?php echo ($vo['id']); ?>" value="<?php echo ($vo['isappstr']); ?>"><?php endif; ?>
                                <label class="onoffswitch-label" for="example<?php echo ($vo['id']); ?>">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
						
					</td>
					<td>
						<a href="<?php echo U('Gameaccess/iospay_post',array('id'=>$vo['id']));?>">切换</a>|
						
						<a href="<?php echo U('Gameaccess/deliospay',array('id'=>$vo['id']));?>">删除</a>
					</td>
					
				</tr><?php endforeach; endif; ?>
			</table>
			<div class="pagination"><?php echo ($Page); ?></div>
         </div>
         <?php if(empty($games)): ?><div class="ibox-content" style="text-align:center;font-size:18px;font-weight:bold;border:none">暂无相关内容</div><?php endif; ?>
        <input type="hidden" id="appstr" value="<?php echo U('Gameaccess/appstpay_post');?>">
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script src="/public/h/js/plugins/chosen/chosen.jquery.js"></script>
    <script src="/public/h/js/content.min.js?v=1.0.0"></script>
    <script src="/public/h/js/plugins/chosen/chosen.jquery.js"></script>
    <script src="/public/h/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="/public/h/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/public/h/js/plugins/clockpicker/clockpicker.js"></script>
    <script src="/public/h/js/plugins/cropper/cropper.min.js"></script>
    <script src="/public/h/js/demo/form-advanced-demo.min.js"></script>
    <script src="/public/h/js/plugins/switchery/switchery.js"></script>
    <script src="/public/h/js/plugins/jeditable/jquery.jeditable.js"></script>
    <script src="/public/h/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/public/h/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/public/h/js/content.min.js?v=1.0.0"></script>
	
	<script type="application/javascript">
		var changeCheckbox = document.querySelectorAll('.js-check-change');
		
		for(var i= 0; i< changeCheckbox.length; i ++){
			var cbox = changeCheckbox[i];
			cbox.onchange = function() {
				var url = $("#appstr").val();
				var aid = $(this).data('aid');
				var ast = 2;
				
				if($(this).val() == 2){
					ast = 1;
				}
				
				$.ajax({
                         url: url,
					     type: 'post',
					     data: {id:aid,st:ast},
					     dataType: 'json',
					     success:function(data){
						    if (data.success){
							   window.location.reload();
						    } else {
							   alert(data.msg);
							   //window.location.reload();//刷新当前页面.
						    }
					     }
					  });
			};
		}
		
	</script>
</body>
</html>