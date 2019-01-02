<?php
/**
 * ALIPAY API: alipay.open.agent.mini.create request
 *
 * @author auto create
 * @since 1.0, 2018-07-12 18:50:00
 */
class AlipayOpenAgentMiniCreateRequest
{
	/** 
	 * 小程序应用类目，参数格式：一级类目_二级类目。
应用类目参考文档：https://docs.alipay.com/isv/10325
	 **/
	private $appCategoryIds;
	
	/** 
	 * 商家小程序描述信息，简要描述小程序主要功能（20-200个字），例：xx小程序提供了xx功能，主要解决了XX问题。
	 **/
	private $appDesc;
	
	/** 
	 * 小程序英文名称，长度3~20个字符
	 **/
	private $appEnglishName;
	
	/** 
	 * 商家小程序应用图标，最大256KB，LOGO不允许涉及政治敏感与色情；图片格式必须为：png、jpeg、jpg，建议上传像素为180*180，LOGO核心图形建议在白色160PX范围内
	 **/
	private $appLogo;
	
	/** 
	 * 代商家创建的小程序应用名称。名称可以由中文、数字、英文及下划线组成，长度在3-20个字符之间，一个中文字等于2个字符，更多名称规则见：https://docs.alipay.com/mini/operation/name
	 **/
	private $appName;
	
	/** 
	 * 代商家创建的小程序的简介，请用一句话简要描述小程序提供的服务；应用上架后一个自然月可修改5次（10~32个字符）
	 **/
	private $appSlogan;
	
	/** 
	 * ISV 代商家操作事务编号，通过事务开启接口alipay.open.agent.create调用返回。
	 **/
	private $batchNo;
	
	/** 
	 * 商家小程序客服邮箱
商家小程序客服电话和邮箱，可以二选一填写，但不能同时为空
	 **/
	private $serviceEmail;
	
	/** 
	 * 商家小程序的客服电话，推荐填写
商家小程序客服电话和邮箱，可以二选一填写，但不能同时为空
	 **/
	private $servicePhone;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	private $apiVersion="1.0";
	private $notifyUrl;
	private $returnUrl;
    private $needEncrypt=false;

	
	public function setAppCategoryIds($appCategoryIds)
	{
		$this->appCategoryIds = $appCategoryIds;
		$this->apiParas["app_category_ids"] = $appCategoryIds;
	}

	public function getAppCategoryIds()
	{
		return $this->appCategoryIds;
	}

	public function setAppDesc($appDesc)
	{
		$this->appDesc = $appDesc;
		$this->apiParas["app_desc"] = $appDesc;
	}

	public function getAppDesc()
	{
		return $this->appDesc;
	}

	public function setAppEnglishName($appEnglishName)
	{
		$this->appEnglishName = $appEnglishName;
		$this->apiParas["app_english_name"] = $appEnglishName;
	}

	public function getAppEnglishName()
	{
		return $this->appEnglishName;
	}

	public function setAppLogo($appLogo)
	{
		$this->appLogo = $appLogo;
		$this->apiParas["app_logo"] = $appLogo;
	}

	public function getAppLogo()
	{
		return $this->appLogo;
	}

	public function setAppName($appName)
	{
		$this->appName = $appName;
		$this->apiParas["app_name"] = $appName;
	}

	public function getAppName()
	{
		return $this->appName;
	}

	public function setAppSlogan($appSlogan)
	{
		$this->appSlogan = $appSlogan;
		$this->apiParas["app_slogan"] = $appSlogan;
	}

	public function getAppSlogan()
	{
		return $this->appSlogan;
	}

	public function setBatchNo($batchNo)
	{
		$this->batchNo = $batchNo;
		$this->apiParas["batch_no"] = $batchNo;
	}

	public function getBatchNo()
	{
		return $this->batchNo;
	}

	public function setServiceEmail($serviceEmail)
	{
		$this->serviceEmail = $serviceEmail;
		$this->apiParas["service_email"] = $serviceEmail;
	}

	public function getServiceEmail()
	{
		return $this->serviceEmail;
	}

	public function setServicePhone($servicePhone)
	{
		$this->servicePhone = $servicePhone;
		$this->apiParas["service_phone"] = $servicePhone;
	}

	public function getServicePhone()
	{
		return $this->servicePhone;
	}

	public function getApiMethodName()
	{
		return "alipay.open.agent.mini.create";
	}

	public function setNotifyUrl($notifyUrl)
	{
		$this->notifyUrl=$notifyUrl;
	}

	public function getNotifyUrl()
	{
		return $this->notifyUrl;
	}

	public function setReturnUrl($returnUrl)
	{
		$this->returnUrl=$returnUrl;
	}

	public function getReturnUrl()
	{
		return $this->returnUrl;
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

  public function setNeedEncrypt($needEncrypt)
  {

     $this->needEncrypt=$needEncrypt;

  }

  public function getNeedEncrypt()
  {
    return $this->needEncrypt;
  }

}
