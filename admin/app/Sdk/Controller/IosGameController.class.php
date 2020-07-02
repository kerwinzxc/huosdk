<?php
/*
**游戏管理
**/
namespace Sdk\Controller;

use Common\Controller\AdminbaseController;

class IosGameController extends AdminbaseController {
    protected $game_model, $gc_model, $gv_model, $cp_model;

    function _initialize() {
        parent::_initialize();
        $this->game_model = D("Common/Game");
        $this->gc_model = M('game_client');
        $this->gv_model = M('game_version');
        $this->cp_model = M('cp');
    }

    /*
     * 获取对接参数
     */
    public function get_param() {
        $app_id = I('appid', 0);
        $param = $this->game_model->field('id app_id, name gamename, app_key, classify')->where(array('id' => $app_id))
                                  ->find();
        $client = $this->gc_model->field('id client_id,client_key')->where(array('app_id' => $app_id))->order('id DESC')
                                 ->find();
        $data = array_merge($param, $client);
        $this->assign('params', $data);
        $this->display();
    }

    /**
     * 游戏列表
     */
    public function index() {
//        $games = M('game')
//            ->field("g.*")
//            ->alias("g")
//            ->where(array("gc.name" => "ios", "g.is_delete" => 2))
//            ->join("LEFT JOIN ".C("DB_PREFIX")."game_class gc ON gc.id=g.classify")
//            ->select();
//        $this->assign("games", $games);
//        print_r($games);
//        exit;
        $this->_game(false, null, null, 1, null, 4);
        $this->_game_status();
        $this->_gList();
        $cp_list = $this->cp_model->select();
        $this->assign("cp_list", $cp_list);
        $this->display(); 
    }

    /**
     **游戏下拉列表
     **/
    public function _game_status($option = null) {
        if (empty($option)) {
            $cates = array(
                "0" => "全部",
                "1" => "游戏接入中",
                "2" => "已上线",
                "3" => "已下线",
                "4" => "已删除",
            );
        } elseif (1 == $option) {
            $cates = array(
                "1" => "游戏接入中",
            );
        } else {
            $cates = array(
                "1" => "游戏接入中",
                "2" => "已上线",
                "3" => "已下线",
            );
        }
        $this->assign("gamestatues", $cates);
    }

 

    /**
     * 游戏列表
     */
    public function _gList($is_delete = false) {
        $status = I('status/d', 0);
        $name = I('name', '', 'trim');
        $app_id = I('app_id', 0, 'trim');
        if ($is_delete) {
            $where_ands = array('g.is_delete=1');
        } else {
            $where_ands = array('g.is_delete=2');
        }
        array_push($where_ands, " g.is_own = 2 ");
        if (isset($name) && !empty($name)) {
            array_push($where_ands, "g.name like '%$name%'");
        }
        if (isset($status) && !empty($status)) {
            array_push($where_ands, "g.status = $status");
        }
        if (isset($app_id) && !empty($app_id)) {
            array_push($where_ands, "g.id = $app_id");
            $name = $this->game_model->where(array('id' => $app_id))->getField('name');
        }
        $where = join(" AND ", $where_ands);
        $count = $this->game_model->alias('g')
                                  ->join("LEFT JOIN ".C("DB_PREFIX")."game_class gc ON gc.id=g.classify")
                                  ->where($where)
                                  ->where(array("gc.name" => "ios"))
                                  ->count();
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        $field = "g.*, gv.packageurl,cp.company_name";
        $items = $this->game_model
            ->alias('g')
            ->field($field)
            ->join("LEFT JOIN ".C('DB_PREFIX')."game_version gv ON gv.app_id=g.id AND gv.status=2")
            ->join("LEFT JOIN ".C("DB_PREFIX")."game_class gc ON gc.id=g.classify")
            ->join("LEFT JOIN ".C('DB_PREFIX')."cp  cp ON g.cp_id=cp.id")
            ->where($where)
            ->where(array("gc.name" => "ios"))
            ->order("g.id DESC")
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $this->assign("formget", $_GET);
        $this->assign("items", $items);
        $this->assign("status", $status);
        $this->assign("name", $name);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }

    public function iosindex() {
        $games = M('game')
            ->alias("g")
            ->where(array("gc.name" => "ios_switch", "g.is_delete" => 2))
            ->join("LEFT JOIN ".C("DB_PREFIX")."game_class gc ON gc.id=g.classify")
            ->getField("g.id,g.name", true);
        $this->assign("games", $games);
        $this->_game_status();
        $this->_pay_status();
        $this->_iosgList();
        $cp_list = $this->cp_model->select();
        $this->assign("cp_list", $cp_list);
        $this->display();
    }

    /**
     **支付切换状态
     **/
    public function _pay_status() {
        $cates = array(
            "0" => "全部",
            "1" => "是",
            "2" => "否",
        );
        $this->assign("paySwitchstatus", $cates);
    }

    public function _iosgList($is_delete = false) {
        $status = I('status/d', 0);
        $name = I('name', '', 'trim');
        $app_id = I('app_id', 0, 'trim');
        $switch_type = I('switch_type', 0, 'trim');
        if ($is_delete) {
            $where_ands = array('g.is_delete=1');
        } else {
            $where_ands = array('g.is_delete=2');
        }
        array_push($where_ands, " g.is_own = 2 ");
        if (isset($name) && !empty($name)) {
            array_push($where_ands, "g.name like '%$name%'");
        }
        if (isset($status) && !empty($status)) {
            array_push($where_ands, "g.status = $status");
        }
        if (isset($app_id) && !empty($app_id)) {
            array_push($where_ands, "g.id = $app_id");
            $name = $this->game_model->where(array('id' => $app_id))->getField('name');
        }
        if (isset($switch_type) && !empty($switch_type)) {
            array_push($where_ands, "g.pay_switch = $switch_type");
        }
        $where = join(" AND ", $where_ands);
        $count = $this->game_model->alias('g')
                                  ->join("LEFT JOIN ".C("DB_PREFIX")."game_class gc ON gc.id=g.classify")
                                  ->where($where)
                                  ->where(array("gc.name" => "ios_switch"))
                                  ->count();
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        $field = "g.*, gv.packageurl,cp.company_name";
        $items = $this->game_model
            ->alias('g')
            ->field($field)
            ->join("LEFT JOIN ".C('DB_PREFIX')."game_version gv ON gv.app_id=g.id AND gv.status=2")
            ->join("LEFT JOIN ".C("DB_PREFIX")."game_class gc ON gc.id=g.classify")
            ->join("LEFT JOIN ".C('DB_PREFIX')."cp  cp ON g.cp_id=cp.id")
            ->where($where)
            ->where(array("gc.name" => "ios_switch"))
            ->order("g.id DESC")
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $this->assign("formget", $_GET);
        $this->assign("items", $items);
        $this->assign("status", $status);
        $this->assign("name", $name);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }

    /*
     * 添加游戏
     */
    /**
     * 删除列表
     */
    public function delindex() {
        $this->_game_status();
        $this->_gList(true);
        $this->display();
    }

    /*
     * 编辑游戏
     */
    public function iosdelindex() {
        $this->_game_status();
        $this->_iosgList(true);
        $this->display();
    }

    public function add() {
        $this->_game_status(1);
        $this->display();
    }

    public function edit() {
        $appid = I('appid/d');
        $from = I("from");
        $this->assign("from", $from);
        if (empty($appid)) {
            $this->error("参数错误");
        }
        $gdata = $this->game_model->where(array('id' => $appid))->find();
        $gvdata = $this->gv_model->where(array('app_id' => $appid))->find();
        $this->_game_status(2);
        $fp = SITE_PATH.$gdata['icon'];
        if (!file_exists($fp)) {
            $gdata['icon'] = '';
        }
        $this->assign('gdata', $gdata);
        $this->assign('gvdata', $gvdata);
        $this->display();
    }

    public function add_post() {
        if (IS_POST) {
            /* 获取POST数据 */
            $game_data['name'] = trim(I('post.gamename'));
            $version = I('post.version/s', '1.0');
            $game_data['status'] = I('post.gstatus', 1);
            $game_data['create_time'] = time();
            $game_data['update_time'] = $game_data['create_time'];
            $iconfile = $_FILES['logo'];
            $game_benefit_data['agent_rate'] = I('post.agent_rate');
            $game_benefit_data['game_rate'] = I('post.game_rate');
            /* 检测输入参数合法性, 游戏名 */
            if (empty($game_data['name'])) {
                $this->error("游戏名为空，请填写游戏名");
            }
            $checkgame = $this->game_model->where(array('name' => $game_data['name']))->find();
            if (!empty($checkgame)) {
                if ($checkgame['is_delete'] == 1) {
                    $this->error("亲，该游戏已在删除列表中存在，如若恢复，请在删除列表中还原！");
                    exit;
                }
                $this->error("亲，该游戏已添加");
                exit;
            }
            /* 检测输入参数合法性, 游戏分成比例  */
            if (empty($game_benefit_data['game_rate'])) {
                $this->error("CP分成比例为空，请填写CP分成比例");
            } else {
                $game_benefit_data['game_rate'] = (float)$game_benefit_data['game_rate'];
                if (empty($game_benefit_data['game_rate']) || $game_benefit_data['game_rate'] < 0
                    || $game_benefit_data['game_rate'] > 1
                ) {
                    $this->error("CP分成比例填写错误，请填写正确比例");
                }
            }
            /* 检测输入参数合法性, 渠道分成比例  */
            if (empty($game_benefit_data['agent_rate'])) {
                $this->error("渠道分成比例为空，请填写渠道分成比例");
            } else {
                $game_benefit_data['agent_rate'] = (float)$game_benefit_data['agent_rate'];
                if (empty($game_benefit_data['agent_rate']) || $game_benefit_data['agent_rate'] < 0
                    || $game_benefit_data['agent_rate'] > 1
                ) {
                    $this->error("渠道分成比例填写错误，请填写正确比例");
                }
            }
            /* 检测输入参数合法性, 游戏版本  */
            if (empty($version)) {
                $this->error("游戏版本为空，请填写游戏版本");
            } else {
                $checkExpressions = "/^\d+(\.\d+){0,2}$/";
                $len = strlen($version);
                if ($len > 10 || false == preg_match($checkExpressions, $version)) {
                    $this->error("游戏版本号填写错误，数字与小数点组合");
                }
            }
            /* 检测输入参数合法性, 游戏LOGO */
            if (empty($iconfile['name'])) {
                $this->error("游戏LOGO为空!");
            }
            // 获取游戏名称拼音
            import('Vendor.Pin');
            $pin = new \Pin();
            $game_data['pinyin'] = $pin->pinyin($game_data['name']);
            $game_data['initial'] = $pin->pinyin($game_data['name'], true);
            /* 上传LOGO 文件 */
            $logoinfo = $this->upload($iconfile, '', 'logo'.$game_data['initial'].'_'.$game_data['create_time']);
            if (0 == $logoinfo['status']) {
                $this->error($logoinfo['msg']);
            } else {
                $game_data['icon'] = $logoinfo['msg'];
            }
            if ($this->game_model->create($game_data)) {
                $app_id = $this->game_model->add();
                /* 插入游戏类型  */
                if ($app_id > 0) {
                    $hs_benefit_obj = new \Huosdk\Benefit();
                    $hs_benefit_obj->set_app_benefit_info($app_id, $game_benefit_data);
                    $update_data['app_key'] = md5($app_id.md5($game_data['pinyin'].$game_data['create_time']));
                    $update_data['initial'] = $game_data['initial'].'_'.$app_id;
                    $update_data['id'] = $app_id;
                    $this->game_model->save($update_data);
                    //游戏版本插入
                    $gv_data['app_id'] = $app_id;
                    $gv_data['version'] = $version;
                    $gv_data['create_time'] = $game_data['create_time'];
                    $gv_id = $this->gv_model->add($gv_data);
                    //client_id 操作
                    $gc_data['app_id'] = $app_id;
                    $gc_data['version'] = $version;
                    $gc_data['client_key'] = md5($version.md5($game_data['initial'].rand(10, 1000)));
                    $gc_data['gv_id'] = $gv_id;
                    $gc_data['gv_new_id'] = $gv_id;
                    $this->gc_model->add($gc_data);
                    /* 直接对接到oa */
                    $_oa_data = $game_data;/* 对接到oa 避免被改变 */
                    $_oa_data['app_id'] = $app_id;
                    $_oa_data['version'] = $version;
                    $do_request = \Huosdk\Request::add_oa_game($_oa_data);
                    if ($do_request) {
                        \Think\Log::write(is_array($do_request) ? json_encode($do_request) : $do_request, 'error');
                    }
                    $gameinfo = $this->game_model->field('classify')->where("id = %d", $app_id)->find();
                    if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                        $this->success("添加成功！", U("IosGame/iosindex"));
                    } else {
                        $this->success("添加成功！", U("IosGame/index"));
                    }
                }
            } else {
                $this->error($this->game_model->getError());
            }
            exit;
        }
        $this->error('页面不存在');
    }

    private function upload_icon($app_id) {
        $image_fp = '';
        if (isset($_FILES['icon']) && ($_FILES['icon']['name'])) {
            $upload_dir = SITE_PATH.'upload/';
            $allow_exts = array("image/jpeg", "image/jpg", "image/png");
            $maxSize = 10 * 1024 * 1024;
            if (($_FILES['icon']['error'] == UPLOAD_ERR_OK)) { //PHP常量UPLOAD_ERR_OK=0，表示上传没有出错
                $temp_name = $_FILES['icon']['tmp_name'];
                $extension = $this->get_extension($_FILES['icon']['name']);
                $file_name = "icon_".$app_id.".".$extension;
                $size = $_FILES['icon']['size'];
                $ext = $_FILES['icon']['type'];
                if (in_array($ext, $allow_exts) && $size <= $maxSize) {
                    $new_fp = $upload_dir.$file_name;
                    if (file_exists($new_fp)) {
                        unlink($new_fp);
                    }
                    move_uploaded_file($temp_name, $new_fp);
                    $image_fp = '/upload/'.$file_name;
                }
            }
        }
        return $image_fp;
    }

    function get_extension($file) {
        return end(explode('.', $file));
    }

    public function edit_post() {
        if (IS_POST) {
            $game_data['id'] = I('appid/d');
            /* 获取POST数据 */
//          $game_data['name']        = trim(I('post.gamename'));
//          $game_data['agent_rate']  = I('post.agent_rate');
//          $game_data['game_rate']   = I('post.game_rate');
//          $version   = I('post.version');
            $game_data['update_time'] = time();
            /* 检测输入参数合法性, 游戏ID */
            if (empty($game_data['id'])) {
                $this->error("参数错误");
            }
            /* 检测输入参数合法性, 游戏名 */
//          if (empty($game_data['name'])) {
//              $this->error("游戏名为空，请填写游戏名");
//          }
            /* 检测输入参数合法性, 游戏分成比例  */
//          if (empty($game_data['game_rate'])) {
//              $this->error("CP分成比例为空，请填写CP分成比例");
//          }else{
//              $game_data['game_rate'] = (float)$game_data['game_rate'];
//              if (empty($game_data['game_rate'])  || $game_data['game_rate'] < 0 || $game_data['game_rate'] > 1){
//                  $this->error("CP分成比例填写错误，请填写正确比例");
//              }
//          }
            /* 检测输入参数合法性, 渠道分成比例  */
//          if (empty($game_data['agent_rate'])) {
//              $this->error("渠道分成比例为空，请填写渠道分成比例");
//          }else{
//              $game_data['agent_rate'] = (float)$game_data['agent_rate'];
//              if (empty($game_data['agent_rate']) || $game_data['agent_rate'] < 0 || $game_data['agent_rate'] > 1){
//                  $this->error("渠道分成比例填写错误，请填写正确比例");
//              }
//          }
//            if(empty($game_data['rebate'])){
//                $this->error("游戏默认自然流水返点不能为空");
//            }else{
//                $rebate=(float)$game_data['rebate'];
//                if($rebate<0 || $rebate>1){
//                    $this->error("游戏默认自然流水返点必须是介于0和1之间的小数");
//                }                
//            }
            $hs_upload_obj = new \Huosdk\Upload();
            $appid = $game_data['id'];
            $image_fp = $hs_upload_obj->image_upload("icon");
            if ($image_fp) {
                M('game')->where(array("id" => $appid))->setField("icon", $image_fp);
            }
            /* 检测输入参数合法性, 游戏版本  */
//          if (empty($version)) {
//              $this->error("游戏版本为空，请填写游戏版本");
//          }else{
//              $checkExpressions = "/^\d+(\.\d+){0,2}$/";
//              $len = strlen($version);
//              if ($len>10 || false == preg_match($checkExpressions, $version)){
//                  $this->error("游戏版本号填写错误，数字与小数点组合");
//              }
//          }
            //  $app_id = $game_data['id'];
//            $hs_benefit_obj=new \Huosdk\Benefit();
//            
//            $game_benefit_data=array();
//            $game_benefit_data['agent_rate']=$game_data['agent_rate'];
//            $game_benefit_data['game_rate']=$game_data['game_rate'];
//            $hs_benefit_obj->set_app_benefit_info($app_id,$game_benefit_data);
            $game_self_data = array();
//            $game_self_data['name']=$game_data['name'];
            $game_self_data['update_time'] = $game_data['update_time'];
            $_time = time();
            $this->game_model->where(array("id" => $appid))->setField("update_time", $_time);
//          if($this->game_model->create($game_self_data)){
//              $rs = $this->game_model->save();
            /* 更新游戏版本  */
//              if($rs>0){
//                  //游戏版本插入
//                  $gv_data['app_id'] = $game_data['id'];
//                  $gv_data['version'] = $version;
//                  $gv_data['update_time'] = $game_data['update_time'];
//                  $rs = $this->gv_model->where(array('app_id'=>$app_id))->save($gv_data);
//                  //client_id 操作
//                  $gc_data['app_id'] = $game_data['id'];
//                  $gc_data['version'] = $version;
//                  $this->gc_model->where(array('app_id'=>$app_id))->save($gc_data);
            $gameinfo = $this->game_model->field('classify')->where("id = %d", $appid)->find();
            /* 对接到oa 避免被改变 */
            $_oa_data = array(
                'icon'        => $image_fp,
                'update_time' => $_time,
                'app_id'      => $appid
            );
            $do_request = \Huosdk\Request::update_oa_game($_oa_data);
            if ($do_request) {
                \Think\Log::write(is_array($do_request) ? json_encode($do_request) : $do_request, 'error');
            }
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("更新成功！", U("IosGame/iosindex"));
            } else {
                $this->success("更新成功！", U("IosGame/index"));
            }
//              }
//          }else{
//              $this->error($this->game_model->getError());
//          }
        } else {
            $this->error('页面不存在');
        }
    }

    /**
     * 添加游戏回调
     */
    public function addurl() {
        $appid = I("appid");
        $from = I("from");
        $this->assign("from", $from);
        $games = $this->game_model->where("id = %d", $appid)->find();
        if ($games) {
            $this->assign("games", $games);
        } else {
            $this->error("请生成参数对接后,再添加回调");
        }
        $this->assign("games", $games);
        $this->display();
    }

    public function addstandard_mem_cnt() {
        $appid = I("appid");
        $from = I("from");
        $this->assign("from", $from);
        $games = $this->game_model->where("id = %d", $appid)->find();
        if ($games) {
            $this->assign("games", $games);
        } else {
            $this->error("请生成参数对接后,再添达标人数");
        }
        $this->assign("games", $games);
        $this->display();
    }

    public function addstandard_level() {
        $appid = I("appid");
        $from = I("from");
        $this->assign("from", $from);
        $games = $this->game_model->where("id = %d", $appid)->find();
        if ($games) {
            $this->assign("games", $games);
        } else {
            $this->error("请生成参数对接后,再添达标等级");
        }
        $this->assign("games", $games);
        $this->display();
    }

    /**
     * 渠道添加游戏回调
     */
    public function addurl_post() {
        $appid = I("appid");
        $cpurl = I("post.cpurl", "", "trim");
        if (empty($cpurl)) {
            $this->error("请填写回调地址");
        }
        $checkExpressions = '|^http://|';
        $httpsExpressions = '|^https://|';
        if (false == preg_match($checkExpressions, $cpurl) && false == preg_match($httpsExpressions, $cpurl)) {
            $this->error("请输入正确的回调地址http://或者https://开头");
        }
        $g_data['id'] = $appid;
        $g_data['update_time'] = time();
        $g_data['cpurl'] = $cpurl;
        $rs = $this->game_model->where(array('id' => $appid))->save($g_data);
        if (false != $rs) {
            $gameinfo = $this->game_model->field('classify')->where("id = %d", $appid)->find();
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("添加成功！", U("IosGame/iosindex"));
            } else {
                $this->success("添加成功！", U("IosGame/index"));
            }
            exit;
        } else {
            $this->error("添加失败！");
        }
    }

    /**
     * 修改游戏回调
     */
    public function editurl() {
        $appid = I("appid");
        $from = I("from");
        $this->assign("from", $from);
        $games = $this->game_model->where("id = %d", $appid)->find();
        $this->assign("games", $games);
        $this->display();
    }

    /**
     * 修改游戏回调
     */
    public function editurl_post() {
        $appid = I("appid");
        $cpurl = I("post.cpurl", "", "trim");
        if (empty($cpurl)) {
            $this->error("请填写回调地址");
        }
        $checkExpressions = '|^http://|';
        if (false == preg_match($checkExpressions, $cpurl)) {
            $this->error("请输入正确的回调地址http://开头");
        }
        $g_data['id'] = $appid;
        $g_data['update_time'] = time();
        $g_data['cpurl'] = $cpurl;
        $rs = $this->game_model->where(array('id' => $appid))->save($g_data);
        if (false != $rs) {
            $gameinfo = $this->game_model->field('classify')->where("id = %d", $appid)->find();
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("修改成功！", U("IosGame/iosindex"));
            } else {
                $this->success("修改成功！", U("IosGame/index"));
            }
            exit;
        } else {
            $this->error("修改失败！");
        }
    }

    /**
     * 添加游戏母包
     */
    public function addpackageurl() {
        $appid = I("appid");
        $games = $this->gv_model->where("app_id=%d", $appid)->find();
        $initial = $this->game_model->where("id = %d", $appid)->getField('initial');
        if (empty($games)) {
            $this->error("请生成参数对接后,再添加游戏母包地址");
        }
        $opt = md5(md5($initial.$initial).'resub');
        $pinyin = base64_encode($initial);
        $agentgame = base64_encode($initial);
        $opt = base64_encode($opt);
        $data_string = array('p' => $pinyin, 'a' => $agentgame, 'o' => $opt);
        $data_string = json_encode($data_string);
        $url = DOWNIP."/sub.php";
        $cnt = 0;
        $flag = false;
        while (1) {
            $return_content = base64_decode(http_post_data($url, $data_string));
            if (!is_int($return_content) && strlen($return_content) > 20) {
                $flag = true;
                break;
            }
            if (0 < $return_content || 3 == $cnt) {
                break;
            }
            $cnt++;
        }
        //若存在则更新地址
        if (true == $flag) {
            $games['packageurl'] = $initial.'/'.$initial.'.plist';
            $this->gv_model->save($games);
            $apkdata = (array)json_decode($return_content);
            if (!empty($apkdata)) {
                if (empty($apkdata['size'])) {
                    $apkdata['size'] = 0;
                }
                //游戏版本插入
                $games['version'] = $apkdata['vername'];
                $games['size'] = $apkdata['size'];
                $gv_id = $this->gv_model->save($games);
                $gi_info = M('game_info')->where(array('app_id' => $appid))->find();
                $gi_info['yiosurl'] = DOWNIOSSITE.$games['packageurl'];
                $gi_info['size'] = format_file_size($apkdata['size']);
                if (empty($gi_info['app_id'])) {
                    $downurl['ios']['local'] = $gi_info['yiosurl'];
                    $gi_info['downurl'] = json_encode($downurl);
                    $gi_info['app_id'] = $appid;
                    M('game_info')->add($gi_info);
                } else {
                    M('game_info')->save($gi_info);
                }
                //游戏报名插入
                $this->game_model->where(array('id' => $appid))->setField('packagename', $apkdata['pakagename']);
            }
            $gameinfo = $this->game_model->field('classify')->where("id = %d", $appid)->find();
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->redirect('IosGame/iosindex');
            } else {
                $this->redirect('IosGame/index');
            }
        }
        $this->assign('initial', $initial);
        $this->assign("games", $games);
        $this->display();
    }

    /**
     * 渠道添加游戏母包
     */
    public function addpackageurl_post() {
        $appid = I("appid/d");
        $gv_id = I("id/d");
        if (empty($appid)) {
            $this->error("参数错误");
        }
        if (empty($gv_id)) {
            $this->error("参数错误");
        }
        $packageurl = I("post.packageurl", "", "trim");
        if (empty($packageurl)) {
            $this->error("请填写回调地址");
        }
        $checkExpressions = '|^http://|';
        if (false == preg_match($checkExpressions, $packageurl)) {
            $this->error("请输入正确的游戏母包地址http://开头");
        }
        $g_data['id'] = $gv_id;
        $g_data['app_id'] = $appid;
        $g_data['packageurl'] = $packageurl;
        $rs = $this->gv_model->where(array('id' => $gv_id))->save($g_data);
        if (false != $rs) {
            $gameinfo = $this->game_model->field('classify')->where("id = %d", $appid)->find();
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("添加成功！", U("IosGame/iosindex"));
            } else {
                $this->success("添加成功！", U("IosGame/index"));
            }
            exit;
        } else {
            $this->error("地址已存在，添加失败！");
        }
    }

    /**
     * 修改游戏母包
     */
    public function editpackageurl() {
        $appid = I("appid");
        $games = $this->gv_model->where("app_id = %d", $appid)->order('id desc')->find();
        $this->assign("games", $games);
        $this->display();
    }

    /**
     * 修改游戏母包POST
     */
    public function editpackageurl_post() {
        $appid = I("appid");
        $gv_id = I("id/d");
        $packageurl = I("post.packageurl", "", "trim");
        if (empty($packageurl)) {
            $this->error("请填写母包地址");
        }
        $checkExpressions = '|^http://|';
        if (false == preg_match($checkExpressions, $packageurl)) {
            $this->error("请输入正确的回调地址http://开头");
        }
        $g_data['id'] = $gv_id;
        $g_data['update_time'] = time();
        $g_data['packageurl'] = $packageurl;
        $rs = $this->gv_model->where(array('id' => $gv_id))->save($g_data);
        if (false != $rs) {
            $gameinfo = $this->game_model->field('classify')->where("id = %d", $appid)->find();
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("修改成功！", U("IosGame/iosindex"));
            } else {
                $this->success("修改成功！", U("IosGame/index"));
            }
            exit;
        } else {
            $this->error("修改失败！");
        }
    }

    /**
     * 删除游戏
     */
    public function delGame() {
        $id = I('id', 0);
        $data['is_delete'] = 1;
        $rs = $this->game_model->where("id = %d", $id)->save($data);
        $gameinfo = $this->game_model->field('classify')->where("id = %d", $id)->find();
        if ($rs) {
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("删除成功", U("IosGame/iosdelindex", array('appid' => $id)));
            } else {
                $this->success("删除成功", U("IosGame/delindex", array('appid' => $id)));
            }
            exit;
        }
        $this->error('删除失败.');
    }

    /**
     * 还原游戏
     */
    public function restoreGame() {
        $id = I('id/d', 0);
        $data['is_delete'] = 2;
        $rs = $this->game_model->where("id = %d", $id)->save($data);
        $gameinfo = $this->game_model->field('classify')->where("id = %d", $id)->find();
        if ($rs) {
            if (isset($gameinfo['classify']) && 401 == $gameinfo['classify']) {
                $this->success("还原成功", U("IosGame/iosindex", array('appid' => $id)));
            } else {
                $this->success("还原成功", U("IosGame/index", array('appid' => $id)));
            }
            exit;
        }
        $this->error('请求失败.');
    }

    /**
     * 游戏状态处理
     */
    public function set_status() {
        $id = I('id', 0);
        $status = I('status', 0);
        if (empty($status)) {
            $this->error("状态错误");
        }
        $g_data = $this->game_model->field('cpurl,classify')->where(array('id' => $id))->find();
        if (2 == $status) {
            // $g_data = $this->game_model->where(array('id' => $id))->find();
            if (empty($g_data['cpurl'])) {
                $this->error("请填写回调地址");
            }
            $gv_id = $this->gc_model->where(array('app_id' => $id))->getField('gv_id');
            $packageurl = $this->gv_model->where(array('id' => $gv_id))->getField('packageurl');
            if (4 != substr($g_data['classify'], 0, 1)) {
                if (empty($packageurl)) {
                    $this->error("请上传母包");
                }
            }
            $data['run_time'] = time();
        }
        $data['status'] = $status;
        $rs = $this->game_model->where("id = %d", $id)->save($data);
        if ($rs) {
            /* 对接到oa 避免被改变 */
            $_oa_data = array(
                'status'      => $status,
                'update_time' => time(),
                'app_id'      => $id
            );
            $do_request = \Huosdk\Request::update_oa_game($_oa_data);
            if ($do_request) {
                \Think\Log::write(is_array($do_request) ? json_encode($do_request) : $do_request, 'debug');
            }
            if (isset($g_data['classify']) && 401 == $g_data['classify']) {
                $this->success("状态切换成功", U("IosGame/iosindex", array('appid' => $id)));
            } else {
                $this->success("状态切换成功", U("IosGame/index", array('appid' => $id)));
            }
            exit;
        } else {
            $this->error('状态切换失败.');
        }
    }

    /**
     * 设置是否在app中显示
     */
    public function set_appstatus() {
        $id = I('id', 0);
        $status = I('appstatus', 0);
        if (empty($status)) {
            $this->error("状态错误");
        }
        $map['id'] = $id;
        $data['is_app'] = $status;
        $rs = $this->game_model->where($map)->save($data);
        if ($rs) {
            $this->success("APP中显示成功", U("Newapp/Game/index", array('appid' => $id)));
            exit;
        } else {
            $this->error('APP中显示失败');
        }
    }

    /**
     **支付切换
     **/
    public function set_pay_switch() {
        $_id = I("id", 0);
        $_status = I("status", 0);
        if (empty($_status)) {
            $this->error("状态错误");
            exit;
        }
        $data["pay_switch"] = $_status;
        $rs = M("game")->where(array("id" => $_id))->data($data)->save();
        if ($rs) {
            $this->success("设置成功", U("IosGame/iosindex", array('appid' => $_id)));
            exit;
        } else {
            $this->error("设置失败");
        }
    }

    /**
     *  设置切换条件
     * 1、时间段
     * 2、IP归属地
     * 3、首充是否切换
     * 4、根据金额切换
     */
    public function set_pay_switch_condition() {
        $appid = I('appid/d', 0);
        $item = [];
        if ($appid > 0) {
            $item = M('game_pay_switch')->where(['app_id' => $appid])->find();
        }
        $this->assign("item", $item);
        $this->display("IosGame/condition");
    }

    /**
     **设置支付切换选项
     **/
    public function set_pay_switch_do() {
        $appid = I('appid', 0);
        if ($appid <= 0) {
            $this->error("数据传输失败，请刷新页面重试");
        }
        $start_time = I('start_time', '');
        $end_time = I('end_time', '');
        $is_first = I('is_first', '');
        $price = I('price', '');
        $version = I('version', '');
        $code_str_start= I('code_str_start', '');
        $code_str_end= I('code_str_end', '');
        $is_domestic = I('is_domestic', '');
        $is_overseas = I('is_overseas', '');
        $param = [
            'app_id'   => $appid,
            'is_first' => 0
        ];
        if (empty($start_time) && !empty($end_time)) {
            $this->error("请输入开始时间");
        }
        if (!empty($start_time) && empty($end_time)) {
            $this->error("请输入结束时间");
        }
        if (!empty($start_time) && !empty($end_time)) {
            if ($start_time >= $end_time) {
                $this->error("开始时间必须小于结束时间");
            }
            $param['start_time'] = $start_time;
            $param['end_time'] = $end_time;
        }
        if (!empty($is_first)) {
            $param['is_first'] = 1;
        }
        if (!empty($code_str_start)) {
            $param['code_str_start'] = $code_str_start;
        }
        if (!empty($code_str_end)) {
            $param['code_str_end'] = $code_str_end;
        }
        if (!empty($version)) {
            $param['version'] = $version;
        }
        if (!empty($is_domestic)) {
            $param['is_domestic'] = 1;
        } else {
            $param['is_domestic'] = 2;
        }
        if (!empty($is_overseas)) {
            $param['is_overseas'] = 1;
        } else {
            $param['is_overseas'] = 2;
        }
        if (strlen($price) > 0) {
            if ($price < 0) {
                $this->error("请输入大于0的金额");
            }
            $param['price'] = $price;
        }
        $id = M('game_pay_switch')->where(['app_id' => $appid])->getField("id");
        if (!empty($id) && $id > 0) {
            $res = M('game_pay_switch')->where(['id' => $id])->save($param);
        } else {
            $res = M('game_pay_switch')->add($param);
        }
        if ($res) {
            $this->success("设置成功");
        } else {
            $this->error("设置没有变动，请重试");
        }
    }

    public function getCpuser($app_id) {
        $_cp_user = array();
        $_cp_user = M("user_game")
            ->alias("ug")
            ->where(array("ug.app_id" => $app_id))
            ->field("u.user_login")
            ->join("LEFT JOIN ".C('DB_PREFIX')."users u ON u.id=ug.uid")
            ->getField("user_login", true);
        if (!empty($_cp_user)) {
            $_cp_user = implode("，", $_cp_user);
        } else {
            $_cp_user = '';
        }
        return $_cp_user;
    }

    public function getCps() {
        $_model = M("users");
        $_where['user_type'] = 12;
        $data = $_model->where($_where)->getField("id,user_login", true);
        return $data;
    }

    public function changeCp() {
        $_app_id = I("app_id");
        $_agent_id = I("agent_id");
        $_model = M("user_game");
        $_model->where(array("app_id" => $_app_id))->delete();
        foreach ($_agent_id as $v) {
            $_data["app_id"] = $_app_id;
            $_data["uid"] = $v;
            $_model->add($_data);
        }
        $this->ajaxReturn(array("error" => "0", "msg" => "修改成功"));
    }

    /**
     *浮点是否显示设置
     */
    public function set_float_show() {
        $_id = I("id", 0);
        $_status = I("status", 0);
        if (empty($_status)) {
            $this->error("状态错误");
            exit;
        }
        $data["float_is_show"] = $_status;
        $rs = M("game")->where(array("id" => $_id))->data($data)->save();
        if ($rs) {
            $this->success("设置成功", U("IosGame/iosindex", array('appid' => $_id)));
            exit;
        } else {
            $this->error("设置失败");
        }
    }

    
        public function set_appleid() {
        $_id = I("id/d",0);
        $_appleid = I("appleid","");
        if (empty($_id)){
            $this->ajaxReturn(array("error" => "0", "msg" => "请选择游戏"));
            exit;
        }
        $_data["apple_id"] = $_appleid;
        $_data["update_time"] = time();
        $_rs = M("game")->where(array("id"=>$_id))->data($_data)->save();
        if ($_rs){
            $this->ajaxReturn(array("error" => "0", "msg" => "修改成功"));
            exit;
        }else{
            $this->ajaxReturn(array("error" => "1", "msg" => "修改失败"));
            exit;
        }
    }
}
