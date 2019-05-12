<?php
namespace app\index\controller;

use \app\index\model\User;
use \app\index\model\Option;
use think\Controller;
use think\Db;


class Editor extends Controller{
	public function _initialize(){
		$this->userObj = new User(cookie('user_id'),cookie('login_key'));
		if(!$this->userObj->loginStatus){
			$this->redirect(url('/Login','',''));
			exit();
		}
	}
    
    public function index(){
		$userInfo = $this->userObj->getInfo();
		$policyData = $this->userObj->getPolicy();

		return view('index', [
			'options'  => Option::getValues(['basic','upload']),
			'userInfo' => $userInfo,
			'policyData' => $policyData,
		]);
        return view('index');
    }
}