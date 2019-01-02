<?php
namespace app\index\controller;

use think\Controller;
use think\Config;
use think\Db;
use \app\index\model\Option;
use \app\index\model\User;
use \app\index\model\AliPayContent;
use \app\index\model\AliPayService;
use think\Session;

class Pay extends Controller
{
    public function _initialize(){
		$this->userObj = new User(cookie('user_id'),cookie('login_key'));
        if (!defined("AOP_SDK_WORK_DIR"))
        {
            define("AOP_SDK_WORK_DIR", ROOT_PATH."tmp/");
        }
    }
    
    public function Alipay()
    {
        if(!$this->userObj->loginStatus){
			$this->redirect(url('/Login','',''));
			exit();
        }
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = '';
        $out_trade_no .= date('Y');
        $out_trade_no .= date('m');
        $out_trade_no .= date('d');
        $out_trade_no .= date('H');
        $out_trade_no .= date('i');
        $out_trade_no .= date('s');
        $out_trade_no .= (int)microtime(True);
        //订单名称，必填
        $subject = '数字绘终身会员';
        //付款金额，必填
        $total_amount = '99.00';
        //商品描述，可空
        $body = '数字绘在线制图软件，终身享受会员服务';
        $payRequest = new AliPayContent();
        $payRequest->setBody($body);
        $payRequest->setSubject($subject);
        $payRequest->setTotalAmount($total_amount);
        $payRequest->setOutTradeNo($out_trade_no);
        $config = Config::get('alipay');
        $aop = new AliPayService($config);
        $response = $aop->pagePay($payRequest,$config['return_url'],$config['notify_url']);
        $userInfo = $this->userObj->getInfo();
        //存入数据库
        $order = [
            'user_id'=>$userInfo['uid'],
            'app_id'=>$config['app_id'],
            'out_trade_no'=>$out_trade_no,
        ];
        Db::name('order')->insert($order);
        //输出表单
        var_dump($response);
    }

    public function Alipay_return()
    {
        if(!$this->userObj->loginStatus){
			$this->redirect(url('/Login','',''));
			exit();
        }
        $userInfo = $this->userObj->getInfo();
		$policyData = $this->userObj->getPolicy();
        $arr=$_GET;
        $config = Config::get('alipay');
        $alipaySevice = new AliPayService($config); 
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        $title='支付失败';
        $message='支付失败，您可以返回页面重新购买！';
        $btn='返回';
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            //商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
            $total_amount = htmlspecialchars($_GET['total_amount']);
            $app_id = htmlspecialchars($_GET['app_id']);
            $order = Db::name('order')->where('out_trade_no',$out_trade_no)->find();
            if ($total_amount=='99.00' && $app_id==$config['app_id'] && $order!=null && $order['user_id']==$userInfo['uid']) {
                $title='支付成功';
                $message='支付成功，您已成为本站终身会员，感谢您的支持，您可以返回查看会员QQ群号';
            }
            else {
                //验证失败
                $title='支付失败';
                $message='支付失败，已经成功付款，但其他信息不正确，请联系客服修正或者办理退款';
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            return view('payment_result', [
                'options'  => Option::getValues(['basic','upload']),
                'userInfo' => $userInfo,
                'title'=>$title,
                'message'=>$message,
                'btn'=>$btn,
            ]);
        }
    }

    public function Alipay_notify()
    {
        $arr=$_POST;
        $config = Config::get('alipay');
        $alipaySevice = new AliPayService($config); 
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            // 订单金额
            $total_amount = $_POST['total_amount'];
            $order = Db::name('order')->where('out_trade_no',$out_trade_no)->find();
            if ($trade_status == 'TRADE_SUCCESS' && $order!=null && $total_amount='99.00') {
                $updateData = [
                    'trade_no'=> $_POST['trade_no'],
                    'total_amount'=>$total_amount,
                    'buyer_id'=>$_POST['buyer_id'],
                    'trade_type'=>'支付宝',
                    'trade_status'=>$trade_status,
                    'create_time'=>$_POST['gmt_create'],
                    'pay_time'=> $_POST['gmt_payment'],
                ];
                Db::name('order')->where('id', $order['id'])->update($updateData);
                $vipgroup = Db::name('groups')->where('group_name','终身会员')->find();
                Db::name('users')->where('id', $order['user_id'])->update(['user_group' => $vipgroup['id']]);
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";
        }
    }


    public function WxPay()
    {

    }
}
