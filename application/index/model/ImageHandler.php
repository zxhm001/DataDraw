<?php
namespace app\index\model;

use think\Model;
use think\Db;
use Qiniu\Auth;
use \app\index\model\FileManage;
use \app\index\model\Task;

class ImageHandler extends Model
{

	public $policyId;
	public $policyContent;
	public $upyunPolicy;
	public $s3Policy;
	public $userId;

	public function __construct($id, $uid)
	{
		$this->policyId = $id;
		$this->userId = $uid;
		$this->policyContent = Db::name('policy')->where('id', $id)->find();
	}

	/**
	 * 保存图片
	 * @param file  $file 图片
	 * @param string $item 图片所属文件地址
	 * @param Request $info 请求信息
	 */
	public function fileReceive($file, $info)
	{

		$graphFileObj = new FileManage($info['item'],$this->userId);
		$graphFileID = $graphFileObj->fileData['id'];

		$allowedExt = self::getAllowedExt(json_decode($this->policyContent["filetype"], true));
		$filter = array('size' => (int)$this->policyContent["max_size"]);
		if (!empty($allowedExt)) {
			$filter = array_merge($filter, array("ext" => $allowedExt));
		}
		if (!FileManage::sotrageCheck($this->userId, $file->getInfo('size'))) {
			$this->setError("空间容量不足", false);
		}
		if ($this->policyContent['autoname']) {
			$fileName = $this->getObjName($this->policyContent['namerule'], "local", $file->getInfo('name'));
		} else {
			$fileName = $file->getInfo('name');
		}
		$generatePath = $this->getDirName($this->policyContent['dirrule']);
		$savePath = ROOT_PATH . 'public/uploads/' . $generatePath;
		$Uploadinfo = $file->validate($filter)->move($savePath, $fileName, false);
		if ($Uploadinfo) {
			$jsonData = array(
				"path" => $info["path"],
				"fname" => $info["name"],
				"objname" => $generatePath . "/" . $Uploadinfo->getSaveName(),
				"fsize" => $Uploadinfo->getSize(),
				"type" => "image",
				"graphID"=>$graphFileID
			);
			@list($width, $height, $type, $attr) = getimagesize(rtrim($savePath, DS) . DS . $Uploadinfo->getSaveName());
			$picInfo = empty($width) ? " " : $width . "," . $height;

			//处理Onedrive等非直传
			if ($this->policyContent['policy_type'] == "onedrive") {
				$task = new Task();
				$task->taskName = "Upload" .  $info["name"] . " to Onedrive";
				$task->taskType = "uploadSingleToOnedrive";
				$task->taskContent = json_encode([
					"path" => $info["path"],
					"fname" => $info["name"],
					"objname" => $Uploadinfo->getSaveName(),
					"savePath" =>  $generatePath,
					"fsize" => $Uploadinfo->getSize(),
					"picInfo" => $picInfo,
					"policyId" => $this->policyContent['id'],
					"type" => "image",
					"graphID"=>$graphFileID
				]);
				$task->userId = $this->userId;

				$task->saveTask();

				echo json_encode(array("key" => $info["name"]));
				FileManage::storageCheckOut($this->userId, $jsonData["fsize"], $Uploadinfo->getInfo('size'));
				return;
			}
			$dir = "/".str_replace(",","/",$jsonData['path']);
			$fname = $jsonData['fname'];
			if(FileManage::isExist($dir,$fname,$this->userId))
			{
				FileManage::DeleteHandler([$dir.$fname],$this->userId);
			}

			//向数据库中添加文件记录
			$addAction = FileManage::addFile($jsonData, $this->policyContent, $this->userId, $picInfo);
			if (!$addAction[0]) {
				$tmpFileName = $Uploadinfo->getSaveName();
				unset($Uploadinfo);
				$this->setError($addAction[1], true, $tmpFileName, $savePath);
			}

			//扣除容量
			FileManage::storageCheckOut($this->userId, $jsonData["fsize"], $Uploadinfo->getInfo('size'));
			echo json_encode(array("key" => $info["name"]));
		} else {
			header("HTTP/1.1 401 Unauthorized");
			echo json_encode(array("error" => $file->getError()));
		}
	}

	public function setError($text, $delete = false, $fname = "", $path = "")
	{
		header("HTTP/1.1 401 Unauthorized");
		if ($delete) {
			unlink(rtrim($path, DS) . DS . $fname);
		}
		die(json_encode(["error" => $text]));
	}

	public function getToken($item){
		$graphFileObj = new FileManage($item,$this->userId);
		$graphFileID = $graphFileObj->fileData['id'];
		switch ($this->policyContent['policy_type']) {
			case 'qiniu':
				return $this->getQiniuToken($graphFileID);
				break;
			case 'local':
				return $this->getLocalToken();
				break;
			case 'onedrive':
				return 'nazGTT91tboaLWBC549$:tHSsNyTBxoV4HDfELJeKH1EUmEY=:eyJjYWxsYmFja0JvZHkiOiJ7XCJwYXRoXCI6XCJcIn0iLCJjYWxsYmFja0JvZHlUeXBlIjoiYXBwbGljYXRpb25cL2pzb24iLCJzY29wZSI6ImMxNjMyMTc3LTQ4NGEtNGU1OS1hZDBhLWUwNDc4ZjZhY2NjZSIsImRlYWRsaW5lIjoxNTM2ODMxOTEwfQ==';
				break;
			case 'oss':
				return $this->getOssToken();
				break;
			case 'upyun':
				return $this->getUpyunToken();
				break;
			case 's3':
				return $this->getS3Token();
				break;
			case 'remote':
				return $this->getRemoteToken();
				break;
			default:
				# code...
				break;
		}
	}

	public function getObjName($expression,$type = "qiniu",$origin = ""){
		$policy = array(
			'{date}' =>date("Ymd"),
			'{datetime}' =>date("YmdHis"), 
			'{uid}' =>$this->userId,
			'{timestamp}' =>time(),
			'{randomkey16}' =>self::getRandomKey(16),
			'{randomkey8}' =>self::getRandomKey(8),
			);
		if($type == "qiniu"){
			$policy = array_merge($policy,array("{originname}" => "$(fname)"));
		}else if($type == "local"){
			$policy = array_merge($policy,array("{originname}" => $origin));
		}else if ($type="oss"){
			$policy = array_merge($policy,array("{originname}" => '${filename}'));
		}else if ($type="upyun"){
			$policy = array_merge($policy,array("{originname}" => '{filename}{.suffix}'));
		}
		return strtr($expression,$policy);
	}

	public function getDirName($expression){
		$policy = array(
			'{date}' =>date("Ymd"),
			'{datetime}' =>date("YmdHis"), 
			'{uid}' =>$this->userId,
			'{timestamp}' =>time(),
			'{randomkey16}' =>self::getRandomKey(16),
			'{randomkey8}' =>self::getRandomKey(8),
			);
		return trim(strtr($expression,$policy),"/");
	}

	public function getQiniuToken($graphFileID){
		$callbackKey = $this->getRandomKey();
		$sqlData = [
		'callback_key' => $callbackKey,
		'pid' => $this->policyId,
		'uid' => $this->userId
		];
		Db::name('callback')->insert($sqlData);
		$auth = new Auth($this->policyContent['ak'], $this->policyContent['sk']);
		$fileRecord = Db::name('files')->where('graph_id',$graphFileID)->find();
		if (empty($fileRecord)){
			$policy = array(
				'callbackUrl' =>Option::getValue("siteURL").'Callback/Qiniu',
				'callbackBody' => '{"fname":"$(fname)","objname":"$(key)","fsize":"$(fsize)","callbackkey":"'.$callbackKey.'","type":"image","graphID":"'.$graphFileID.'","path":"$(x:path)","picinfo":"$(imageInfo.width),$(imageInfo.height)"}',
				'callbackBodyType' => 'application/json',
				'fsizeLimit' => (int)$this->policyContent['max_size'],
			);
			$dirName = $this->getObjName($this->policyContent['dirrule']);
			if($this->policyContent["autoname"]){
				$policy = array_merge($policy,array("saveKey" => $dirName.(empty($dirName)?"":"/").$this->getObjName($this->policyContent['namerule'])));
			}else{
				$policy = array_merge($policy,array("saveKey" => $dirName.(empty($dirName)?"":"/")."$(fname)"));
			}
			if(!empty($this->policyContent['mimetype'])){
				$policy = array_merge($policy,array("mimeLimit" => $this->policyContent['mimetype']));
			}
			$token = $auth->uploadToken($this->policyContent['bucketname'], null, 3600, $policy);
		}
		else
		{
			$policy = array(
				'callbackUrl' =>Option::getValue("siteURL").'Callback/Qiniu',
				'callbackBody' => '{"fname":"'.$fileRecord['orign_name'].'","objname":"$(key)","fsize":"$(fsize)","callbackkey":"'.$callbackKey.'","type":"update","graphID":"'.$graphFileID.'","path":"$(x:path)","picinfo":"$(imageInfo.width),$(imageInfo.height)"}',
				'callbackBodyType' => 'application/json',
				'fsizeLimit' => (int)$this->policyContent['max_size'],
			);
			if(!empty($this->policyContent['mimetype'])){
				$policy = array_merge($policy,array("mimeLimit" => $this->policyContent['mimetype']));
			}
			$token = $auth->uploadToken($this->policyContent['bucketname'],$fileRecord['pre_name'], 3600, $policy);
		}
		return $token;
	}

	private function getRemoteToken(){
		$callbackKey = $this->getRandomKey();
		$sqlData = [
			'callback_key' => $callbackKey,
			'pid' => $this->policyId,
			'uid' => $this->userId
		];
		Db::name('callback')->insert($sqlData);
		$policy = array(
			'callbackUrl' =>Option::getValue("siteURL").'Callback/Remote',
			'callbackKey' => $callbackKey,
			'callbackBodyType' => 'application/json',
			'fsizeLimit' => (int)$this->policyContent['max_size'],
			'uid' => $this->userId,
		);
		$dirName = $this->getObjName($this->policyContent['dirrule']);
		if($this->policyContent["autoname"]){
			$policy = array_merge($policy,array("saveKey" => $dirName.(empty($dirName)?"":"/").$this->getObjName($this->policyContent['namerule'])));
		}else{
			$policy = array_merge($policy,array("saveKey" => $dirName.(empty($dirName)?"":"/")."$(fname)"));
		}
		if(!empty($this->policyContent['mimetype'])){
			$policy = array_merge($policy,array("mimeLimit" => $this->policyContent['mimetype']));
		}
		$signingKey = hash_hmac("sha256",json_encode($policy),"UPLOAD".$this->policyContent['sk']);
		$token = $signingKey. ":" .base64_encode(json_encode($policy));
		return $token;
	}

	static function upyunSign($key, $secret, $method, $uri, $date, $policy=null, $md5=null){
		$elems = array();
		foreach (array($method, $uri, $date, $policy, $md5) as $v){
			if ($v){
				$elems[] = $v;
			}
		}
		$value = implode('&', $elems);
		$sign = base64_encode(hash_hmac('sha1', $value, $secret, true));
		return 'UPYUN ' . $key . ':' . $sign;
	}


	public function getUpyunToken(){
		$callbackKey = $this->getRandomKey();
		$sqlData = [
		'callback_key' => $callbackKey,
		'pid' => $this->policyId,
		'uid' => $this->userId
		];
		Db::name('callback')->insert($sqlData);
		$options = Option::getValues(["oss","basic"]);
		$dateNow = gmdate('D, d M Y H:i:s \G\M\T');
		$policy=[
			"bucket" => $this->policyContent['bucketname'],
			"expiration" => time()+$options["timeout"],
			"notify-url" => $options["siteURL"]."Callback/Upyun",
			"content-length-range" =>"0,".$this->policyContent['max_size'],
			"date" => $dateNow,
			"ext-param"=>json_encode([
				"path"=>cookie("path"),
				"uid" => $this->userId,
				"pid" => $this->policyId,
				]),
		];
		$allowedExt = self::getAllowedExt(json_decode($this->policyContent["filetype"],true));
		if(!empty($allowedExt)){
			$policy = array_merge($policy,array("allow-file-type" => $allowedExt));
		}
		$dirName = $this->getObjName($this->policyContent['dirrule']);
		$policy = array_merge($policy,array("save-key" => $dirName.(empty($dirName)?"":"/").uniqid()."CLSUFF{filename}{.suffix}"));
		$this->upyunPolicy = base64_encode(json_encode($policy));
		return self::upyunSign($this->policyContent['op_name'], md5($this->policyContent['op_pwd']), "POST", "/".$this->policyContent['bucketname'],$dateNow,$this->upyunPolicy);
	}

	public function ossCallback(){
		$callbackKey = $this->getRandomKey();
		$sqlData = [
			'callback_key' => $callbackKey,
			'pid' => $this->policyId,
			'uid' => $this->userId
		];
		Db::name('callback')->insert($sqlData);
		$returnValue["callbackUrl"] = Option::getValue("siteUrl").'Callback/Oss';
		$returnValue["callbackBody"] = '{"fname":"${x:fname}","objname":"${object}","fsize":"${size}","callbackkey":"'.$callbackKey.'","path":"${x:path}","picinfo":"${imageInfo.width},${imageInfo.height}"}';
		$this->ossCallBack = base64_encode(json_encode($returnValue));
		return base64_encode(json_encode($returnValue));
	}

	public function getS3Token(){
		$dirName = $this->getDirName($this->policyContent['dirrule']);
		$longDate = gmdate('Ymd\THis\Z');
		$shortDate = gmdate('Ymd');
		$credential = $this->policyContent['ak'] . '/' . $shortDate . '/' . $this->policyContent['op_name'] . '/s3/aws4_request';
		$callbackKey = $this->getRandomKey();
		$sqlData = [
			'callback_key' => $callbackKey,
			'pid' => $this->policyId,
			'uid' => $this->userId
		];
		Db::name('callback')->insert($sqlData);
		$this->siteUrl = Option::getValue("siteUrl");
		$returnValue = [
			"expiration" => date("Y-m-d",time()+1800)."T".date("H:i:s",time()+1800).".000Z",
			"conditions" => [
				0 => ["bucket" => $this->policyContent['bucketname']],
				1 => ["starts-with",'$key', $dirName],
				2 => ["starts-with",'$success_action_redirect' ,$this->siteUrl."Callback/S3/key/".$callbackKey],
				3 => ["content-length-range",1,(int)$this->policyContent['max_size']],
				4 => ['x-amz-algorithm' => 'AWS4-HMAC-SHA256'],
				5 => ['x-amz-credential' => $credential],
				6 => ['x-amz-date' => $longDate],
				7 => ["starts-with", '$name', ""],
				8 => ["starts-with", '$Content-Type', ""],
			]
		];
		$this->s3Policy = base64_encode(json_encode($returnValue));
		$signingKey = hash_hmac("sha256",$shortDate,"AWS4".$this->policyContent['sk'],true);
		$signingKey = hash_hmac("sha256",$this->policyContent['op_name'],$signingKey,true);
		$signingKey = hash_hmac("sha256","s3",$signingKey,true);
		$signingKey = hash_hmac("sha256","aws4_request",$signingKey,true);
		$signingKey = hash_hmac("sha256",$this->s3Policy,$signingKey);
		$this->s3Sign = $signingKey;
		$this->dirName = $dirName;
		$this->s3Credential = $credential;
		$this->x_amz_date = $longDate;
		$this->callBackKey = $callbackKey;
	}

	public function getOssToken(){
		$dirName = $this->getObjName($this->policyContent['dirrule']);
		$returnValu["expiration"] = date("Y-m-d",time()+1800)."T".date("H:i:s",time()+1800).".000Z";
		$returnValu["conditions"][0]["bucket"] = $this->policyContent['bucketname'];
		$returnValu["conditions"][1][0]="starts-with";
		$returnValu["conditions"][1][1]='$key';
		if($this->policyContent["autoname"]){
			$this->ossFileName = $dirName.(empty($dirName)?"":"/").$this->getObjName($this->policyContent['namerule'],"oss");;
		}else{
			$this->ossFileName = $dirName.(empty($dirName)?"":"/").'${filename}';
		}
		$returnValu["conditions"][1][2]=$dirName.(empty($dirName)?"":"/");
		$returnValu["conditions"][2]=["content-length-range",1,(int)$this->policyContent['max_size']];
		$returnValu["conditions"][3]["callback"] = $this->ossCallback();
		$this->ossToken=base64_encode(json_encode($returnValu));
		$this->ossSignToken();
		$this->ossAccessId = $this->policyContent['ak'];
		return false;
	}

	public function ossSignToken(){
		$this->ossSign = base64_encode(hash_hmac("sha1", $this->ossToken, $this->policyContent['sk'],true));  
	}

	public function getLocalToken(){
		$auth = new Auth($this->policyContent['ak'], $this->policyContent['sk']);
		$policy = array(
				'callbackBody' => '{"path":"'.cookie('path').'"}',
				'callbackBodyType' => 'application/json',
		);
		$token = $auth->uploadToken($this->policyContent['bucketname'], null, 3600, $policy);
		return $token;
	}

	static function b64Decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	static function getAllowedExt($ext)
	{
		$returnValue = "";
		foreach ($ext as $key => $value) {
			$returnValue .= $value["ext"] . ",";
		}
		return rtrim($returnValue, ",");
	}

	static function getRandomKey($length = 16){
		$charTable = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$result = ""; 
		for ( $i = 0; $i < $length; $i++ ){ 
			$result .= $charTable[ mt_rand(0, strlen($charTable) - 1) ]; 
		} 
		return $result; 
	}
}
