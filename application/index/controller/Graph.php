<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use \app\index\model\Option;
use \app\index\model\User;
use \app\index\model\FileManage;
use \app\index\model\ShareHandler;
use think\Session;
use MxGraph\Reader\mxGraphViewImageReader;
use MxGraph\Util\mxUtils;

class Graph extends Controller{
    public $userObj;

	/**
	 * [_initialize description]
	 * @return [type] [description]
	 */
	public function _initialize(){
		$this->userObj = new User(cookie('user_id'),cookie('login_key'));
		if(!$this->userObj->loginStatus){
			echo "Bad request";
			exit();
		}
    }

    public function StoreImage()
    {
        $reqPath = json_decode(file_get_contents("php://input"),true)['item'];
        $imageStr = json_decode(file_get_contents("php://input"),true)['image'];
        $fileObj = new FileManage($reqPath,$this->userObj->uid);
        $fileID = $fileObj->fileData['id'];
        $image = Db::name('images')->where('file_id',$fileID)->find();
        if($image==null)
        {
            $jsondata = [
                'file_id'=>$fileID,
                'image'=>$imageStr
            ];
            if(Db::name('images')->insert($jsondata))
            {
                return [true,"上传成功"];
            }
            else
            {
                return [false,"上传失败"];
            }
        }
        else
        {
            $result = Db::name('images')->where('file_id',$fileID)->setField('image',$imageStr);
            return [true,"上传成功"];
        }
    }
    
    public function Export(){
        $format = $_POST["format"];
        $xml = $_POST["xml"];
        $bg = $_POST["bg"];
        $filename = $_POST["filename"];
        $h = intval($_POST["h"]);
        $w = intval($_POST["w"]);
        if (isset($xml))
        {
            if (isset($format))
            {
                $xml = urldecode($xml);
                header("Content-Disposition: attachment; filename=\"diagram.$format\"");
                header("Content-Type: image/$format");
                $viewReader = new mxGraphViewImageReader($bg,$w,$h);
                $viewReader->read($xml);
                $image = $viewReader->canvas->getImage();
                echo mxUtils::encodeImage($image, $format);
            }
        }
    }
}