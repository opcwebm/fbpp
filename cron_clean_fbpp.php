<?php
	require_once("your_db_connect_for_this_cron.php");
 	@mysql_query("DELETE FROM fbpp WHERE access>=3 OR expires < NOW()");
?>