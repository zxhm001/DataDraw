<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use \app\index\model\Option;
use \app\index\model\User;
use think\Session;

class Vip extends Controller
{
    public function _initialize(){
		$this->userObj = new User(cookie('user_id'),cookie('login_key'));
		if(!$this->userObj->loginStatus){
			$this->redirect(url('/Login','',''));
			exit();
		}
	}
	
	public function isVip()
	{
		$groupData =  $this->userObj->getGroupData();
		if ($groupData['group_name'] == '终身会员' || $groupData['group_name'] == '管理员') {
			return true;
		}
		else
		{
			return false;
		}
	}

    public function index(){
		$userInfo = $this->userObj->getInfo();
		$policyData = $this->userObj->getPolicy();
		$groupData =  $this->userObj->getGroupData();
		$inviteCount = Db::name('invite')->where('invite_id',$userInfo['uid'])->count();
		return view('index', [
			'options'  => Option::getValues(['basic','upload']),
			'userInfo' => $userInfo,
			'groupData' => $groupData,
			'inviteCount'=>$inviteCount
		]);
	}
}