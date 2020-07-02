<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" >
<style >
    html {
        display: none;
    }
</style >
<script >
    if (self == top) {
        document.documentElement.style.display = 'block';
    } else {
        top.location = self.location;
    }
</script >
<head >
    <meta name="viewport"
          content="width=device-width,maximum-scale=1.0,initial-scale=1,user-scalable=no" />
    <meta charset="UTF-8" >
    <meta name="format-detection" content="telephone=no" />
    <title ><?php echo ($title); ?></title >
</head >
<link rel="stylesheet" href="/public/mobile/css/public.css" />
<body >

<!-- <header class="header">
<ul class="main_layout">
    <li class="back_btn" ><img src="/public/mobile/images/arrow_l.png" alt=""/></li>
    <li class="text"><?php echo ($title); ?></li>
    <li class="close_btn" ><img src="/public/mobile/images/closebtn.png" alt=""/></li>
</ul>
</header> -->
<link rel="stylesheet" href="/public/mobile/css/forgetpwd.css" />
<div class="box" >
    <h3 >请输入需要找回密码的帐号！</h3 >
    <input type="text" class="inputBox" id="username" name="username" value="" placeholder="请输入需要找回密码的帐号" />
</div >
<div class="error_box" ></div >
<div class="confim_change" >
    <button >下一步</button >
</div >
<!-- 加载底部 -->
<footer class="footer" >
    <p style="cursor:pointer;" >
        <?php if(!empty($contactdata['qq'])): ?><a href="###" class="QQ" onclick="huosdk_openqq('<?php echo ($contactdata['qq']); ?>');" ><span >客服QQ：</span ><?php echo ($contactdata['qq']); ?></a ><?php endif; ?>
        <?php if(!empty($contactdata['qqgroup'])): ?><a href="###" class="QQgroup"
               onclick="huosdk_openqqgroup('<?php echo ($contactdata['qqgroup']); ?>','<?php echo ($contactdata['qqgroupkey']); ?>');" ><span >	Q Q 群：</span ><?php echo ($contactdata['qqgroup']); ?></a ><?php endif; ?>
    </p >
</footer >
<div class="main_model_box" ></div >
<input type="hidden" value="<?php echo U('Mobile/Forgetpwd/check');?>" id="ajaxUrl" >
</body>
<script src="/public/mobile/js/jquery.js" ></script >
<script src="/public/mobile/js/huosdk_base.js" ></script >
<script src="/public/mobile/js/public.js" ></script >
<script src="/public/mobile/js/forgetpwd.js" ></script >
</html>