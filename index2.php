<?php
include_once('common.php');
page_protect();
if(!isset($_SESSION['user_id']))
{
	logout();
}
else
{
	header("Location:myaddress.php");
}
?>
<html>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
</head>
<body>
 
<div id="dialog" title="Wallet Under Maintenance">
  <p></p>
</div>

</body>
</html>
