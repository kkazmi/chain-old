<?php
include_once('common.php');
$allowed = array(".", "-", "_");
$email_id ="";

//echo "           =>>>>>>> ".hash('sha256',addslashes(strip_tags($email_id)));
//echo "</br>           =>>>>>>> ".hash('sha256',addslashes(strip_tags($password)));
$error = array();
if(isset($_POST['btnlogin']))
{
//	var_dump($_POST);
	$email_id = $_POST['txtEmailID'];
	if (empty($email_id))
	{
		$error['emailError'] = "Please Provide valid email id";
	}
	elseif (!isEmail($email_id))
	{
		$error['emailError'] = "Please Provide valid email id";
	}

	if(empty($error))
	{
		$email_id = $mysqli->real_escape_string(strip_tags($email_id));

		$qstring = "select coalesce(id,0) as id, coalesce(username,'') as username
					from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";

		$result	= $mysqli->query($qstring);
		$user = $result->fetch_assoc();
		//var_dump($user);


		if (($user) && ($user['id'] > 0 ))
		{
			$new_password = "s!w@".rand(0,100000);
			$password_value = hash('sha256',addslashes(strip_tags($new_password)));
			$sub =" Password Recovery Mail";
			$message_body =" Dear User \n";
			$message_body .= " Your recovery password is $new_password \n\n";
			$message_body .= " Please login and change it immediately\n\n";
			$message_body .= " Thanks \n";
			$message_body .= " Administrator";

			$qstring = "update users set `password` ='".$password_value."'";
			$qstring .= " WHERE encrypt_username = '" . hash('sha256',$email_id) . "' and id = ".$user['id'] ;

			$result2	= $mysqli->query($qstring);
	//		$user2 = $result2->fetch_assoc();

			$error['emailError2'] = "An Email has been send to your email id. ";

			sendpmail($email_id,$sub,$message_body);
		}
		else
		{
			$error['emailError'] = "the Provided email_id  is not registered with us";
		}
	}
}
//var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Wallets |<?php echo $coin_fullname;?>(<?php echo $coin_short;?>) </title>
		<meta name="description" content="<?php echo $coin_fullname;?>(<?php echo $coin_short;?>)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo $favicon;?>" rel="shortcut icon" type="image/x-icon">
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="css/css.css" rel="stylesheet" type="text/css">
		<link href="css/sitestyle.css" rel="stylesheet">
		<link href="css/font-awesome.css" rel="stylesheet" type="text/css">
		<link href="css/wallet.css" rel="stylesheet">
		<link href="css/add.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
 		<script type="text/javascript" async="" src="js/atrk.js"></script>
		<script src="js/modernizr-2.js"></script>

	</head>
	<body>
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./myaddress.php"> 
						<img src="<?php echo $logo;?>" >
					</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav navbar-right">

						<li><a href="login.php" >Home</a></li>
						<li><a <?php echo $explorer;?> target="_blank">Explorer</a></li>
						<li><a href="login.php">Sign In</a></li>
						<li><a href="signup.php">Sign Up</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div style="height:85%" class="MyMainDiv">
			<form  method="post" action="forget.php">
				<div class="form-horizontal white signUpContainer center">
					<div class="flex-center flex-justify flex-column login-box-container">
						<div ui-view="contents" class="login-box mhs">
							<div id="login" data-preflight-tag="Login">
								<header>
									<hgroup>
										<div class="flex-between flex-center flex-wrap">
											<h2 class="em-300 mtn">Forget Password</h2>
										</div>
									</hgroup>
								</header>
								<div name="loginForm" role="form" autocomplete="off" novalidate=""
									class="ptl form-horizontal clearfix ng-pristine ng-invalid ng-invalid-required">
									<fieldset>
										<div ng-class="{'has-error': errors.uid}" class="form-group">
											<label style="float:left">Registered Email ID</label>
											<input id="txtEmailID" name="txtEmailID" class="form-control" style="border:2px solid #33212c;" type="text"
											value="<?php echo $email_id;?>">
											<?php if(isset($error['emailError'])) { echo "<br/><span class=\"messageClass\">".$error['emailError']."</span>";  }?>
											<?php if(isset($error['emailError2'])) { echo "<br/><span class=\"messageClass2\">".$error['emailError2']."</span>";  }?>
										</div>
										<div class="mtl flex-center flex-end">
											<input type="submit" class="button Lockerblue ladda-button" id="btnlogin" name="btnlogin" value="Send"/>
											<span class="button Lockerblue ladda-button" id="btnLoading" style="display:none">
												<span style="position:relative;">
													<span class="loader"></span>
												</span>
												<span class="val1">Loading</span>
											</span>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div style="background:#2f2f2f;height:15%; display:none" class="minefooter">
			<div class="footer">
				<div class="row-fluid" style="margin-bottom:0px;">
					<div class="col -md-12">
						<div class="social">
							<ul class="social-link tt-animate ltr">
								<li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>

							</ul>
							<p class="footerp">2017 <?php echo $coin_fullname;?> All RIGHTS RESERVED.</p>
							<p class="footerp">

							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="js/jquery-1.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
