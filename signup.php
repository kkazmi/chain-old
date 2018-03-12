<?php
include_once('common.php');
$allowed = array(".", "-", "_");
$email_id = "";
$password = "";
$confirmpassword = "";
$spendingpassword = "";
$confirmspendingpassword = "";

$error = array();
if(isset($_POST['btnsignup']))
{
//	var_dump($_POST);
	$email_id = $_POST['txtEmailID'];
	$password = $_POST['signuppassword'];
	$confirmpassword = $_POST['confirmpassword'];
	$spendingpassword = $_POST['spendingpassword'];
	$confirmspendingpassword = $_POST['confirmspendingpassword'];

	if (empty($email_id))
	{
		$error['emailError'] = "Please Provide valid email id";
	}
	if(empty($password))
	{
		$error['passwordError'] = "Please Provide valid Password";
	}
	if(empty($confirmpassword))
	{
		$error['confirmpasswordError'] = "Please Provide valid Password";
	}
	else if($confirmpassword != $password)
	{
		$error['confirmpasswordError'] = "Password and Confirm Password Must be same";
	}
	if(empty($spendingpassword))
	{
		$error['spendingpasswordError'] = "Please Provide valid Spending Password";
	}
	if(empty($confirmspendingpassword))
	{
		$error['confirmspendingpasswordError'] = "Please Provide valid Spending Password";
	}
	else if($confirmspendingpassword != $spendingpassword)
	{
		$error['confirmpasswordError'] = "Spending Password and Confirm Password Must be same";
	}

	if (!isEmail($email_id))
	{
		$error['emailError'] = "Please Provide valid email id";
	}

	$email_id = $mysqli->real_escape_string(strip_tags($email_id));
	$password_value = hash('sha256',addslashes(strip_tags($password)));
	$qstring = "select coalesce(id,0) as id
				from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";

	$result	= $mysqli->query($qstring);
	$user = $result->fetch_assoc();
	//var_dump($user);
	if ($user['id']> 0)
	{
		$error['emailError'] = "User with email id $email_id already exist.";
	}

	if(empty($error))
	{
		$email_id = $mysqli->real_escape_string(strip_tags($email_id));
		$password_value = hash('sha256',addslashes(strip_tags($password)));
		$spendingpassword_value = hash('sha256',addslashes(strip_tags($spendingpassword)));

		$qstring = "insert into `users`( `date`, `ip`, `username`,
		`encrypt_username`, `password`, `transcation_password`,
		`email`) values (";
		$qstring .= "now(), ";
		$qstring .= "'".$_SERVER['REMOTE_ADDR']."', ";
		$qstring .= "'".$email_id."', ";
		$qstring .= "'".hash('sha256',$email_id)."', ";
		$qstring .= "'".$password_value."', ";
		$qstring .= "'".$spendingpassword_value."', ";
		$qstring .= "'".$email_id."') ";

		$result2	= $mysqli->query($qstring);
		// echo $result2;

		// die;
		if ($result2)
		{
			//	$user2 = $result2->fetch_assoc();
			// var_dump($result2);
			// die;
			//	header("Location:login.php");
			$email_id = "";
			$password = "";
			$confirmpassword = "";
			$spendingpassword = "";
			$confirmspendingpassword = "";
			$error['emailError2'] = "Your Account has successfully register. Please Login to continue";
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Wallets | <?php echo $coin_fullname;?>(<?php echo $coin_short;?>)</title>
		<meta name="description" content="<?php echo $coin_fullname;?>(<?php echo $coin_short;?>)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="./img/favicon.png" rel="shortcut icon" type="image/x-icon">
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
					<a class="navbar-brand" href=""><img src="<?php echo $logo;?>" ></a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="login.php" >Home</a></li>
						<li><a href="<?php echo $explorer;?>" target="_blank">Explorer</a></li>
						<li><a href="login.php">Sign In</a></li>
						<li><a href="signup.php">Sign Up</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div style="height:85%;margin-top:35px" class="MyMainDiv">
			<form  method="post" action="signup.php">
				<div class="form-horizontal white signUpContainer center">
					<div class="flex-center flex-justify flex-column login-box-container">
						<div ui-view="contents" class="login-box mhs">
							<div id="login" data-preflight-tag="Login">
								<header>
									<hgroup>
										<div class="flex-between flex-center flex-wrap">
                                <h2 class="em-300 mtn">Create your Wallet</h2>
										</div>
									</hgroup>
								</header>
								<div name="loginForm" role="form" autocomplete="off" novalidate=""
									class="ptl form-horizontal clearfix ng-pristine ng-invalid ng-invalid-required">
									
										<div class="form-group" style="margin-top:0px!important;">
											<label style="float:left">Email ID</label>
											<input id="txtEmailID" name="txtEmailID" class="form-control" style="border:2px solid #33212c ;" type="text"
											value="<?php echo $email_id;?>">
											</div>
<?php if(isset($error['emailError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['emailError']."</small>";  }?>
            <?php if(isset($error['emailError2'])) { echo "<br/><small class=\"messageClass2 text-success\">".$error['emailError2']."</small>";  }?>
										<div class="row">
											<div class="form-group col-md-6" id="half1">
												<label style="float:left">Password</label>
												<input id="signuppassword" name="signuppassword" autocomplete="off" class="form-control" type="password" value="<?php echo $password;?>">
<?php if(isset($error['passwordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['passwordError']."</small>";  }?>

												<span id="result" style="float:left"></span>
											</div>
											<div class="form-group col-md-6" id="half2">
												<label style="float:left">Confirm Password</label>
												<input id="confirmpassword" name="confirmpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $confirmpassword;?>">
<?php if(isset($error['confirmpasswordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['confirmpasswordError']."</small>";  }?>

											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-6" id="half3">
												<label style="float:left">Spending Password</label>
												<input id="spendingpassword" name="spendingpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $spendingpassword;?>">
<?php if(isset($error['spendingpasswordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['spendingpasswordError']."</small>";  }?>


												<span id="spendingresult" style="float:left"></span>
											</div>

											<div class="form-group col-md-6" id="half4">
												<label style="float:left">Confirm Spending Password</label>
												<input id="confirmspendingpassword" name="confirmspendingpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $confirmspendingpassword;?>">
<?php if(isset($error['confirmspendingpasswordError'])) { echo "<br/><small class=\"messageClass text-danger\">".$error['confirmspendingpasswordError']."</small>";  }?>

											</div>
										</div>
										<div style="clear:both"></div>
										<div class="flex-center flex-end mtm mbl">
											<input id="agreement_accept" name="agreement" ng-model="fields.acceptedAgreement"
											required="" class="pull-right ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required" type="checkbox">
											<label translate="ACCEPT_TOS" class="em-300 mbn mls right-align">I have read and agree to the <a>Terms of Service</a></label>
										</div>
										<div class="mtl flex-center flex-end">
											<input type="submit" class="button Lockerblue ladda-button" id="btnsignup" name="btnsignup" value="Sign Up"/>
											<span class="button Lockerblue ladda-button" id="btnLoading" style="display:none">
												<span style="position:relative;">
													<span class="loader"></span>
												</span>
												<span class="val1">Loading</span>
											</span>
										</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
<script type="text/javascript">

    function validateEmail(emailField) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(emailField);
    }

function checkStrength(password)
{
    var strength = 0
    if (password.length < 6)
	{
        $('#result').removeClass()
        $('#result').addClass('short')
        return 'Weak'
    }
    if (password.length > 7) strength += 1
    // If password contains both lower and uppercase characters, increase strength value.
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
    // If it has numbers and characters, increase strength value.
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
    // If it has one special character, increase strength value.
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
    // If it has two special characters, increase strength value.
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
    // Calculated strength value, we can return messages
    // If value is less than 2
    if (strength < 2)
	{
        $('#result').removeClass()
        $('#result').addClass('weak')
        return 'Regular'
    }
	else if (strength == 2)
	{
        $('#result').removeClass()
        $('#result').addClass('good')
        return 'Normal'
    }
	else
	{
        $('#result').removeClass()
        $('#result').addClass('strong')
        return 'Strong'
    }
}
</script>
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
