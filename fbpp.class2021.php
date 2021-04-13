<?php 
/* */
/*FORM BYPASS PROTECTION*/
/* */
class FBPP
{
	private $HashInput;		//content HashInput from form [KNOWN]
	private $WhatPage;		//AJAX LINK FOR CALL [UNKNOWN+UNIQUE CALL] which fetchs code value
	private $HashData;		//code value after analysis
	private $analyze;		//Validation analysis return

	
	
	public function __construct()
	{
		$this->analyze = null;
		$multi_ip=0;
		if(strrpos($_SERVER['REMOTE_ADDR'],".") !== false)
		{
			$ip_pattern = substr($_SERVER['REMOTE_ADDR'],0,strrpos($_SERVER['REMOTE_ADDR'],".")+1)."%";
			$sql_multi_ip="SELECT *, count(*) AS 'nb' FROM fbpp WHERE ip LIKE '{$ip_pattern}'";
			$qry_multi_ip=mysql_query($sql_multi_ip) or die("Erreur SQL(".__LINE__."):" . $sql_multi_ip . "<br>" . mysql_error());
			$fch_multi_ip=mysql_fetch_assoc($qry_multi_ip);
			if($fch_multi_ip !== false) $multi_ip = $fch_multi_ip['nb'];
		}
		
		$sql_psid="SELECT *, count(*) AS 'nb' FROM fbpp WHERE sessionid='".self::getCookie()."' or ip LIKE '{$_SERVER['REMOTE_ADDR']}'";
		$qry_psid=mysql_query($sql_psid) or die("Erreur SQL(".__LINE__."):" . $sql_psid . "<br>" . mysql_error());
		$fch_psid=mysql_fetch_assoc($qry_psid);
		if($fch_psid === false)
		{
			$this->createInternalCredentials();
		}
		else
		{
			//Exception for access increasing when the same user changes of page of your site includint ANOTHER form
			if($fch_psid['sessionid']==self::getCookie() && $fch_psid['url']!=$_SERVER['SCRIPT_URI'])
			{
				$this->resetAccessForNewForm();
				$this->updateUrlinCredentials();
			}
			
			//Restrictions
			if($fch_psid['expires'] < date('Y-m-d H:i:s',strtotime("now")) || $fch_psid['access'] >= 3 || $fch_psid['nb'] > 5 || $multi_ip >=3)
			{
				self::destroyInternalCredentials();
				$this->createInternalCredentials();
			}
			else
			{
				//To call the same page (actual) thru POST process
				$this->HashInput = $fch_psid['hash'];
				$this->HashData  = $fch_psid['data'];
				$this->WhatPage  = $fch_psid['wp'];
				$this->updateUrlinCredentials();
			}

		}
	}	



	/* Readers */
	public function getHashInputForm()
	{
		return "FBPP_".$this->HashInput;
	}
	public function getHashInputKey()
	{
		return $this->HashInput;
	}
	public function getHashData()
	{
		return $this->HashData;
	}	
	public function getWp()
	{
		return $this->WhatPage;
	}


	/* Static Methods */
	public static function getCookie()
	{
		$psid=session_id();
		if($psid=="")
		{
			session_start();					
			header('Location: '.$_SERVER['PHP_SELF']);
		}
		return $psid;
	}
	
	public function createInternalCredentials()
	{
		$epid = (isset($_SESSION['ep_id']))? $_SESSION['ep_id'] : 0;
		$this->HashInput = self::genHashInput();					//KNOWN
		$this->HashData  = self::genHashData();						//UNKNOWN
		$this->WhatPage  = self::genHashInput();					//UNKNWON+UNIQUE USAGE
		$expires = date('Y-m-d H:i:s',strtotime("+10 minutes"));	//30 min max expires
			
			
		//	First creation of credentials to insert thru form
		//  
		$sql_insert="INSERT INTO `media_sante`.`fbpp` (`hash`, `wp`, `ip`, `sessionid`, `ep_id`, `url`, `data`, `access`, `expires`) 
						VALUES ('{$this->HashInput}', '{$this->WhatPage}', '{$_SERVER['REMOTE_ADDR']}', '".self::getCookie()."', {$epid}, '{$_SERVER['SCRIPT_URI']}'
						, '{$this->HashData}', 0, '{$expires}')";
		mysql_query($sql_insert) or die("Erreur SQL en ligne :[{$sql_insert}]" . __LINE__ . "<br>" . mysql_error());
	}
	
	public function resetAccessForNewForm()
	{
		@mysql_query("UPDATE fbpp SET access=-1 WHERE hash='{$this->HashInput}' or sessionid='".self::getCookie()."' or ip='{$_SERVER['REMOTE_ADDR']}'");
	}	
	
	public function updateUrlinCredentials()
	{
		@mysql_query("UPDATE fbpp SET url='{$_SERVER['SCRIPT_URI']}' WHERE hash='{$this->HashInput}' or sessionid='".self::getCookie()."' or ip='{$_SERVER['REMOTE_ADDR']}'");
	}
	
	public static function destroyInternalCredentials($key="")
	{
		@mysql_query("DELETE FROM fbpp WHERE hash='{$key}' or sessionid='".self::getCookie()."' or ip='{$_SERVER['REMOTE_ADDR']}'");
	}
	
	public static function genHashInput($size=8,$span_embed=false)
	{
		$ghi=bin2hex(openssl_random_pseudo_bytes($size));
		return ($span_embed)? "<span id=\"{$ghi}\"></span>\n":$ghi;
	}	
	
	public static function genHashData($universal_key=8)
	{
		$string = array();
		$string_html="";
		$user_ramdom_key ="ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz123456789-+/*.:,;?!%$|])}";
		srand((double)microtime()*time());

		for($i=0; $i< $universal_key; $i++) 
		{
			$string[]= $user_ramdom_key[rand()%strlen($user_ramdom_key)];
		}
		
		$string_html=implode("", $string);
		return $string_html;
	} 
}
?>