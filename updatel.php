<?php
	include_once('common.php');
	page_protect();
	if(!isset($_SESSION['user_id']))
	{
		logout();
	}
	$up = -1;
	$id = 0;
	$user_session = $_SESSION['user_session'];
	if(isset($_GET['m']))
	{
			$up = strip_tags($_GET['m']);
			$id = strip_tags($_GET['i']);
	}
	
	if($up > 0)
	{
		$qstring = "update `users`set "; 
		$qstring .= "`locked` = 1";
		$qstring .= " where id = ".$id;
	//echo $qstring;
	}
	else if($up == 0)
	{
		$qstring = "update `users`set "; 
		$qstring .= "`locked` = 0";
		$qstring .= " where id = ".$id;
	//echo $qstring;
	}
	echo $qstring;
	
	$result	= $mysqli->query($qstring);
	header("Location:admin_user.php");
	exit();
?> 