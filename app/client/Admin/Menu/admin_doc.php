<?php
return array (
  'app' => 'Admin',
  'model' => 'Doc',
  'action' => 'index',
  'data' => '',
  'type' => '1',
  'status' => '1',
  'is_cp' => '0',
  'name' => '文档中心',
  'icon' => '',
  'remark' => '',
  'listorder' => '0',
  'children' => 
  array (
    array (
      'app' => 'Admin',
      'model' => 'Doc',
      'action' => 'askindex',
      'data' => '',
      'type' => '1',
      'status' => '0',
      'is_cp' => '0',
      'name' => '帮助中心',
      'icon' => '',
      'remark' => '',
      'listorder' => '0',
    ),
    array (
      'app' => 'Admin',
      'model' => 'Doc',
      'action' => 'downindex',
      'data' => '',
      'type' => '1',
      'status' => '0',
      'is_cp' => '0',
      'name' => '下载中心',
      'icon' => '',
      'remark' => '',
      'listorder' => '0',
    ),
  ),
);