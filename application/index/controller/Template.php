<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use \app\index\model\Option;
use \app\index\model\User;
use think\Session;

class Template extends Controller
{
    public $userObj;

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
		$groupData =  $this->userObj->getGroupData();

		$template_classes = Db::name('template_class')->where('id','not null')->select();
		$template = Db::name('template')->field('id,name,class_id,thumb,libs,text')->select();
		return view('index', [
			'options'  => Option::getValues(['basic','upload']),
			'userInfo' => $userInfo,
			'groupData' => $groupData,
			'templateClass'=>$template_classes,
			'templates'=>$template
		]);
	}

	public function getTemplate()
	{
		$templateName = input('get.template_name');
		$template = Db::name('template')->where('name',$templateName)->find();
		return array('result'=>$template);
	}
}