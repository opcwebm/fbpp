<?php
	if(!isset($_SERVER['HTTP_REFERER']))
	{
		header('Location: https://www.media-sante.com');
		exit;
	}
	
	$url=( strpos($_SERVER['HTTP_REFERER'],"https://www.media-sante.com")!==false 
	&& strpos($_SERVER['HTTP_REFERER'],"https://www.media-sante.com")===0) || (strpos($_SERVER['HTTP_REFERER'],"www.media-sante.lan")!==false);
	if(!$url)
	{
		header('Location: https://www.media-sante.com');
		exit;
	}
	
	include("_includes/inc_conn.php");	
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
			
			
			//pass doit être renouvelé pour éviter un flood/nouvel appel
			$new_pass=bin2hex(openssl_random_pseudo_bytes(8));
			mysql_query("UPDATE fbpp SET wp='{$new_pass}' ,access=access+1 WHERE hash='{$key}'");
			
		}
		else
		{
			header('Location: https://www.media-sante.com');
			exit;

		}
	}

	echo $res;
?>