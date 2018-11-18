<?php
namespace app\index\controller;

use \app\index\model\User;
use think\Controller;

class Editor extends Controller{
	public function _initialize(){
		$this->userObj = new User(cookie('user_id'),cookie('login_key'));
		if(!$this->userObj->loginStatus){
			$this->redirect(url('/Login','',''));
			exit();
		}
	}
    
    public function index(){
        return view('index');
    }
}