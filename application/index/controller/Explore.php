<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\User;
use think\Cookie;
use think\Db;
use \app\index\model\Option;

class Explore extends Controller{

	public $visitorObj;
	public $userObj;
	public $siteOptions;

	public function _initialize(){
		$this->siteOptions = Option::getValues(["basic"]);
	}

	public function Search(){
		// $this->visitorObj = new User(cookie('user_id'),cookie('login_key'));
		// return view("search",[
		// 	"options" => $this->siteOptions,
		// 	'loginStatus' => $this->visitorObj->loginStatus,
		// 	'userData' => $this->visitorObj->userSQLData,
		// ]);
		$this->visitorObj = new User(cookie('user_id'),cookie('login_key'));
		$keyWords=input("param.key");
		$encode = mb_detect_encoding($keyWords, array("ASCII","UTF-8","GB2312","GBK","BIG5")); 
		if($encode != "UTF-8")
		{
			$keyWords= iconv($encode,"UTF-8",$keyWords);
		}
		if(empty($keyWords)){
			$list = Db::name('shares')
				->where('type',"public")
				->order('view_num DESC')
				->paginate(18);
		}
		else
		{
			$list = Db::name('shares')
				->where('type',"public")
				->where('origin_name',"like","%".$keyWords."%")
				->order('view_num DESC')
				->paginate(18);
		}
		
		$listData = $list->all();
		foreach ($listData as $key => $value) {
			if($value["source_type"]=="file"){
				$listData[$key]["fileData"] = $value["origin_name"];

			}else{
				$pathDir = explode("/",$value["source_name"]);
				$listData[$key]["fileData"] = end($pathDir);
			}
		}
		return view("result",[
			"options" => $this->siteOptions,
			'loginStatus' => $this->visitorObj->loginStatus,
			'userData' => $this->visitorObj->userSQLData,
			'list' => $listData,
			'listOrigin' => $list,
			'keyWords' => $keyWords,
		]);
	}

	public function S(){
		echo "xx";
	}

}
