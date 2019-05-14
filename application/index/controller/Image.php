<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use \app\index\model\Option;
use \app\index\model\User;
use \app\index\model\ImageHandler;

class Image extends Controller
{
	public $userObj;

	/**
	 * [_initialize description]
	 * @return [type] [description]
	 */
	public function _initialize()
	{
		$this->userObj = new User(cookie('user_id'), cookie('login_key'));
		if (!$this->userObj->loginStatus) {
			echo "Bad request";
			exit();
		}
	}

	public function StoreImage()
	{
		ob_end_clean();
		$file = request()->file('file');
		$fileInfo = Request::instance()->request();
		$imageHandler = new ImageHandler($this->userObj->groupData['policy_name'], $this->userObj->uid);
		return $imageHandler->fileReceive($file, $fileInfo);
	}

	public function Token()
	{
		$item = request()->get('item');
		$uploadObj = new ImageHandler($this->userObj->groupData['policy_name'], $this->userObj->uid);
		$upToken = $uploadObj->getToken($item);
		if (!empty($uploadObj->upyunPolicy)) {
			return json([
				"token" => $upToken,
				"policy" => $uploadObj->upyunPolicy,
			]);
		}
		if (!empty($uploadObj->s3Policy)) {
			return json([
				"policy" => $uploadObj->s3Policy,
				"sign" =>  $uploadObj->s3Sign,
				"key" => $uploadObj->dirName,
				"credential" => $uploadObj->s3Credential,
				"x_amz_date" => $uploadObj->x_amz_date,
				"siteUrl" => $uploadObj->siteUrl,
				"callBackKey" => $uploadObj->callBackKey,
			]);
		}
		if (!$uploadObj->getToken($item)) {
			return json([
				"uptoken" => $uploadObj->ossToken,
				"sign" => $uploadObj->ossSign,
				"id" => $uploadObj->ossAccessId,
				"key" => $uploadObj->ossFileName,
				"callback" => $uploadObj->ossCallBack,
			]);
		}
		return json(["uptoken" => $upToken]);
	}
}
