<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use \app\index\model\Option;
use \app\index\model\User;

class Index extends Controller{

	public $userObj;

    public function index(){

    	$this->userObj = new User(cookie('user_id'),cookie('login_key'));
    	if($this->userObj->loginStatus){
    		$this->redirect(url('/Home','',''));
    		exit();
    	}
		$userInfo = $this->userObj->getInfo();
		$list = Db::name('shares')
				->where('type',"public")
				->order('view_num DESC')
				->paginate(12);
		$listData = $list->all();
		foreach ($listData as $key => $value) {
			if($value["source_type"]=="file"){
				$listData[$key]["fileData"] = str_replace('.xml','',$value["origin_name"]);
			}else{
				$pathDir = explode("/",$value["source_name"]);
				$listData[$key]["fileData"] = end($pathDir);
			}
		}
    	return view('index', [
			'options'  => Option::getValues(['basic']),
			'list' => $listData,
    		'userInfo' => $userInfo,
		]);
    }

}
