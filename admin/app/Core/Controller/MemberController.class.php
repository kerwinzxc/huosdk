<?php
namespace Core\Controller;

use Common\Controller\AdminbaseController;
use think\log;
class MemberController extends AdminbaseController {
    function _initialize() {
        parent::_initialize();
    }

    public function changeAgent() {
        $mem_id = I("mem_id",0);
        $agent_id = I("agent_id",0);
        M('members')->where(array("id" => $mem_id))->setField("agent_id", $agent_id);
        M('members')->where(array("id" => $mem_id))->setField("update_time", time());
        $username = $this->get_username($mem_id);
        $agentname = $this->get_agentname($agent_id);
        Log::write('core\Controller\cahngeAgent','error');
        Log::write(['mem_id'=>$mem_id,'username'=>$username,'agent_id'=>$agent_id,'agentname'=>$agentname],'error');
        $oa_api=new \Huosdk\Request();
        $result=$oa_api->mem_update_agentname($username,$agentname);
        if($result['code']=='200'){
        $this->ajaxReturn(array("error" => "0", "msg" => "修改成功"));
        }else{
            $this->ajaxReturn(array("error" => "1", "msg" => "修改失败"));
        }

    }

    public function getGameClassId($type) {
        $classify = M('game_class')->where(array("name" => $type))->getField("id");

        return $classify;
    }

    public function add() {
        $game_data['name'] = trim(I('post.name'));
        $type = "android";
        if (isset($_POST['type']) && $_POST['type']) {
            $type = $_POST['type'];
        }
        $classify = $this->getGameClassId($type);
        if ($classify) {
            $game_data['classify'] = $classify;
        } else {
            $game_data['classify'] = $this->getGameClassId("android");
        }
        /**
         * 刚添加游戏的时候，游戏的状态肯定是接入中
         *
         * 严旭
         * 2016-10-28 23:03:02
         */
        $game_data['status'] = 1;
        $current_time = time();
        $game_data['create_time'] = $current_time;
        $game_data['update_time'] = $current_time;
        /* 检测输入参数合法性, 游戏名 */
        if (empty($game_data['name'])) {
            $this->ajaxReturn(array("error" => "1", "msg" => "游戏名为空，请填写游戏名"));
            exit;
        }
        $checkgame = M('game')->where(array('name' => $game_data['name']))->find();
        if (!empty($checkgame)) {
            if ($checkgame['is_delete'] == 1) {
                $this->ajaxReturn(array("error" => "1", "msg" => "亲，该游戏已在删除列表中存在，如若恢复，请在删除列表中还原！"));
                exit;
            }
            $this->ajaxReturn(array("error" => "1", "msg" => "亲，该游戏已存在"));
            exit;
        }
        // 获取游戏名称拼音
        import('Vendor.Pin');
        $pin = new \Pin();
        $game_data['pinyin'] = $pin->pinyin($game_data['name']);
        $game_data['initial'] = $pin->pinyin($game_data['name'], true);
        $version = '1.0';
        if (!$this->game_model->create($game_data)) {
            $this->ajaxReturn(array("error" => "1", "msg" => $this->game_model->getError()));
            exit;
        }
        $app_id = $this->game_model->add();
        /* 插入游戏类型  */
        if ($app_id > 0) {
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
            $this->ajaxReturn(array("error" => "0", "msg" => "添加成功！"));
        }
    }
    public function   get_username($mem_id){
        $memberData=M('members')->where('id',$mem_id)->field('username')->find();
        if($memberData){
            return $memberData['username'];
        }else{
            return false;
        }
    }
    public function  get_agentname($agent_id){

        $agentData=M('users')->where(['id'=>$agent_id])->find();
        if($agentData){
            return $agentData['user_login'];
        }else{
            return false;
        }
    }
}

