<?php
	if(!isset($_SERVER['HTTP_REFERER']))
	{
		header('Location: https://www.yourwebsite.com');
		exit;
	}
	
	$url=( strpos($_SERVER['HTTP_REFERER'],"https://www.yourwebsite.com")!==false 
	&& strpos($_SERVER['HTTP_REFERER'],"https://www.yourwebsite.com")===0) 
	
	if(!$url)
	{
		header('Location: https://www.yourwebsite.com');
		exit;
	}
	
	include("your_database_connector.php");	
	
	$res="<h1>Oops! Illegal Request...</h1>";
	$key="";
	$input=$_REQUEST['_rfi'];

	if(strpos($input,"_") !== false)
	{
		$keytab=explode("_", $input);
		$key=$keytab[1];
	}

	if(isset($_GET['pass']) && !empty($_GET['pass']))
	{
		$sqlpass="SELECT * FROM fbpp WHERE hash='{$key}' AND wp='{$_GET['pass']}'";
		$qrypass=mysql_query($sqlpass);
		$fch = mysql_fetch_assoc($qrypass);
		if($fch !== false)
		{
			$res=$fch['data']."#".$key;

			//to avoid new call/flood
			$new_pass=bin2hex(openssl_random_pseudo_bytes(8));
			mysql_query("UPDATE fbpp SET wp='{$new_pass}' ,access=access+1 WHERE hash='{$key}'");
			
		}
		else
		{
			header('Location: https://www.yourwebsite.com');
			exit;

		}
	}

	echo $res;
?>