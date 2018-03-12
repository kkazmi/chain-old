<?php
include_once('common.php');
page_protect();
if (!isset($_SESSION['user_id'])) {
    logout();
}

$password = "";
$confirmpassword = "";
$spendingpassword = "";
$confirmspendingpassword = "";
$currentpassword = "";
$currentspendingpassword = "";

$user_session = $_SESSION['user_session'];
$user_current_balance = 0;

$error = array();
$error2 = array();
$client = "";
if (_LIVE_) {
    $client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
    if (isset($client)) {
        $user_current_balance = $client->getBalance($user_session) - $fee;
    }
}


if (isset($_POST['btnverify'])) {
    $new_password = rand(0, 99999999);
    $otp_value = hash('sha256', addslashes(strip_tags($new_password)));

    $sub =" Email Verification Mail";
    $message_body =" Dear User \n";
    $message_body .= " Your email verification OTP is $new_password \n\n";
    $message_body .= " \n\n";
    $message_body .= " Thanks \n";
    $message_body .= " Administrator";

    $qstring = "update users set `otp_value` ='".$otp_value."'";
    $qstring .= " WHERE ";
    //	$qstring .= " encrypt_username = '" . hash('sha256',$user_session) . "' and ";
    $qstring .= " id = ".$_SESSION['user_id'];
    //echo $new_password;
    $result2	= $mysqli->query($qstring);
    //	$user2 = $result2->fetch_assoc();

    sendpmail($user_session, $sub, $message_body);

    header("Location:verifyemail.php");
    exit();
}


if (isset($_POST['btnlogin'])) {
    //var_dump($_POST);


    $currentpassword = $_POST['currentpassword'];
    $password = $_POST['signuppassword'];
    $confirmpassword = $_POST['confirmpassword'];
    $spendingpassword = $_POST['spendingpassword'];
    $confirmspendingpassword = $_POST['confirmspendingpassword'];
    $currentspendingpassword = $_POST['currentspendingpassword'];

    if (empty($currentpassword)) {
        $error['currentpasswordError'] = "Please Provide your current login password";
    }
    if (empty($password)) {
        $error['passwordError'] = "Please Provide valid Password";
    }
    if (empty($confirmpassword)) {
        $error['confirmpasswordError'] = "Please Provide valid Confirm Password";
    } elseif ($confirmpassword != $password) {
        $error['confirmpasswordError'] = "Password and Confirm Password Must be same";
    }

    $password_value = hash('sha256', addslashes(strip_tags($currentpassword)));
    $qstring = "select coalesce(id,0) as id
	from users WHERE encrypt_username = '".hash('sha256', $user_session)."' and `password` = '" . $password_value . "'";


    $result	= $mysqli->query($qstring);
    $user = $result->fetch_assoc();

    if ($user['id'] <= 0) {
        $error['currentpasswordError'] = "Your current Login password is not match with our store password. Please provide valid one.";
    }

    if (empty($error)) {
        $password_value = hash('sha256', addslashes(strip_tags($password)));

        $qstring = "update `users`set ";
        $qstring .= "`password` = ";
        $qstring .= "'".$password_value."'";
        $qstring .= " where encrypt_username = '".hash('sha256', $user_session)."' and id = ".$user['id'];
        //echo $qstring;
        $result	= $mysqli->query($qstring);
        if ($result) {
            $error['currentpasswordError2'] = "Your  Login password has been successfully updated.";
            $password = "";
            $confirmpassword = "";
            $currentpassword = "";
        }
    }
}

if (isset($_POST['btnSpending'])) {
    $currentpassword = $_POST['currentpassword'];
    $password = $_POST['signuppassword'];
    $confirmpassword = $_POST['confirmpassword'];
    $spendingpassword = $_POST['spendingpassword'];
    $confirmspendingpassword = $_POST['confirmspendingpassword'];
    $currentspendingpassword = $_POST['currentspendingpassword'];


    if (empty($currentspendingpassword)) {
        $error2['currentspendingpasswordError'] = "Please Provide your current Spending Password";
    }
    if (empty($spendingpassword)) {
        $error2['spendingpasswordError'] = "Please Provide valid Spending Password";
    }
    if (empty($confirmspendingpassword)) {
        $error2['confirmspendingpasswordError'] = "Please Provide valid Confirm Spending Password";
    } elseif ($confirmspendingpassword != $spendingpassword) {
        $error2['confirmpasswordError'] = "Spending Password and Confirm Password Spending Must be same";
    }

    $spendingpassword_value = hash('sha256', addslashes(strip_tags($currentspendingpassword)));
    $qstring = "select coalesce(id,0) as id
	from users where encrypt_username = '".hash('sha256', $user_session)."' and `transcation_password` = '" . $spendingpassword_value . "'";

    $result2 = $mysqli->query($qstring);
    $user2 = $result2->fetch_assoc();
    //var_dump($user);
    if ($user2['id'] <= 0) {
        $error2['currentspendingpasswordError'] = "Your current spending password is not match with our store password. Please provide valid one.";
    }

    if (empty($error2)) {
        $spendingpassword_value = hash('sha256', addslashes(strip_tags($spendingpassword)));

        $qstring = "update `users`set ";
        $qstring .= "`transcation_password` = ";
        $qstring .= "'".$spendingpassword_value."' ";
        $qstring .= " where encrypt_username = '".hash('sha256', $user_session)."' and id = ".$user2['id'];
        $result3 = $mysqli->query($qstring);
        if ($result3) {
            $error2['currentspendingpasswordError2'] = "Your  Spending Password has been successfully updated.";
            $spendingpassword = "";
            $confirmspendingpassword = "";
            $currentspendingpassword = "";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Wallets | <?php echo $coin_fullname;?>(<?php echo $coin_short;?>)</title>
	<meta name="description" content="<?php echo $coin_fullname;?>(<?php echo $coin_short;?>)">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="./img/favicon.png" rel="shortcut icon" type="image/x-icon">
	<link href="css/material-design-iconic-font.css" rel="stylesheet" type="text/css">
	<link href="css/icon.css" rel="stylesheet" type="text/css">
	<link href="css/font-awesome.css" rel="stylesheet" type="text/css">
	<!--Import materialize.css-->
	<link href="css/main.css" rel="stylesheet" type="text/css">
	<link href="css/mystyle.css" rel="stylesheet" type="text/css">
	<!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
	<link href="css/sitemaster.css" rel="stylesheet" type="text/css">
	<link href="css/custom.css" rel="stylesheet">
	<script type="text/javascript" async="" src="files/atrk.js"></script>
	<script src="js/cbgapi.loaded_0" async=""></script>
	<script src="js/llqrcode.js"></script>
	<script src="js/plusone.js" gapi_processed="true"></script>
	<script src="js/socket.js"></script>
	<script src="js/webqr.js"></script>
	<script type="text/javascript" async="" src="js/atrk.js"></script>
	<script src="js/modernizr-2.js"></script>


</head>
<body>
	<div class="wrapper vertical-sidebar" id="full-page">
		<header id="header">
			<div class="navbar">
				<nav style="position:fixed!important;z-index:999;">
					<a onclick="openNav()" class="button-collapse top-nav full waves-effect waves-light"><span id="openbtn" style="font-size:30px;cursor:pointer" >&#9776;</span></a>

					<a onclick="closeNav()" class="button-collapse top-nav full waves-effect waves-light"><span id="closebtn" style="font-size:30px;cursor:pointer">&times;</span></a>
					<div class="nav-wrapper">


						<ul class="left">


							<a href="./myaddress.php" class="brand-logo">
								<img src="<?php echo $logo;?>" >
							</a>

						</ul>
						<ul class="right hide-on-med-and-down">
							<li class="b1"> <a href="#"><span style="font-size:15px"></span>
								<span id="lblliveusd" style="padding-left:2px;font-size:15px;"></span></a></li>
								<li id="topmenu">
								</li><li>
								<a id="logout" href="logout.php">
									<img src="image/sign-out.png" style="width: 30px; vertical-align: middle;">
								</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<div id="mySidenav" class="sidenav-mobile">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

			<a href="transactions.php">Transactions</a>
			<a href="myaddress.php">Addresses</a>
			<a href="securitycenter.php">Security center</a>
			<a href="contactus.php">Contact Us</a>
			<?php
            if ($_SESSION['user_admin'] == 1) {
                ?>
				<a href="admin_user.php"><!--<i class="zmdi zmdi-help-outline iconFAQ" style=""></i>-->User list</a>
				<?php
            }
            ?>

		</div>

		<aside class="sidebar-left">
			<ul class="side-nav fixed clearfix left" id="nav-mobile" style="transform: translateX(0px);">
				<li>
					<ul class="vm1 collapsible" data-collapsible="accordion" style="margin-top: 30px;">

						<li id="ms2"><a href="transactions.php" class="collapsible-header">Transactions</a></li>
						<li id="ms3" class="active"><a  href="myaddress.php" class="collapsible-header">My Addresses</a></li>
						<li id="ms4"><a href="securitycenter.php" style="color: #fddd72;" class="collapsible-header">Security Center</a>
						</li>
						<li id="ms5">
							<a href="contactus.php" class="collapsible-header">Contact Us</a>
						</li>
						<?php
                        if ($_SESSION['user_admin'] == 1) {
                            ?>
							<li id="ms6"><a href="admin_user.php" class="collapsible-header"><!--<i class="zmdi zmdi-help-outline iconFAQ" style=""></i>-->User list</a></li>
							<?php
                        }
                        ?>

					</ul>
				</li>
			</ul>
		</aside>
		<main id="content" style="position:fixed;width:100%;z-index:990;">
			<div id="page-content">
				<div class="row section-header">
					<div class="col l12" id="topvalues">
						<div style="overflow:hidden;cursor:pointer;"><h5 id="lblbtcbalancesmall" class="topbtc"><?php echo $user_current_balance." " . $coin_short;?></h5></div>
						<div style="overflow:hidden;cursor:pointer;"><h6 id="lblusdbalancesmall" class="topusd"></h6></div>
						<div style="overflow:hidden;cursor:pointer;"><h5 id="lblusdbalance2small" class="topbtc" style="display: none;"></h5></div>
						<div style="overflow:hidden;cursor:pointer;"><h6 id="lblbtcbalance2small" class="topusd" style="display: none;"><?php echo $user_current_balance." " . $coin_short;?></h6></div>
					</div>
					<div class="col m6 l6" id="sidetopbuttons">
						<a href="send.php" id="btnsend" class="btn btn-default"><!--<i class="zmdi zmdi-long-arrow-up zmdi-hc-fw"></i>-->Send</a>
						<a href="recievecoin.php" id="btnreceived" class="btn btn-default"><!--<i class="zmdi zmdi-long-arrow-down zmdi-hc-fw"></i>-->Receive</a>

					</div>
					<div class="col m6 l6" id="sidetopvalues">
						<div style="overflow:hidden;cursor:pointer;"><h5 id="lblbtcbalance" class="topbtc"><?php echo $user_current_balance." " . $coin_short;?></h5></div>
						<div style="overflow:hidden;cursor:pointer;"><h6 id="lblusdbalance" class="topusd"></h6></div>
						<div style="overflow:hidden;cursor:pointer;"><h5 id="lblusdbalance2" class="topbtc" style="display: none;"></h5></div>
						<div style="overflow:hidden;cursor:pointer;"><h6 id="lblbtcbalance2" class="topusd" style="display: none;"><?php echo $user_current_balance." " . $coin_short;?></h6></div>
					</div>
				</div>
			</div>
		</main>
		<div>

		<form action="securitycenter.php" method="post">
			<main id="content" class="topmg transactiontop main2-content">
				<div id="page-content">
					<div class="modal-content">
						<div id="send1ststep">
							<div class="modal-head">
								<div class="col l8 text-center">
									<h5><!--<i class="zmdi zmdi-long-arrow-up zmdi-hc-fw"></i>-->Security Center</h5>
									<p class="text-center">Please update your password regularly</p>
								</div>
								<div class="col l4 right-align">
									<!--<i class="zmdi zmdi-close-circle-o modal-close"></i>-->
								</div>
							</div>
							<div class="form-horizontal white signUpContainer center" style="width:100%;">
								<fieldset>
									<div class="row" style="border-bottom:1px solid black;">
										<div class="form-group col-md-3 col-sm-3" id="half1" style="margin-bottom:5px">
											<label style="float:left;margin-left:5px; padding:0px">Verify Your Email ID</label>
										</div>
										<div class="form-group col-md-9 col-sm-9" id="half1" style="margin-bottom:5px">
											<div class="form-group col-md-6 col-sm-12" id="half1" style="margin-bottom:5px">
												<label style="float:left;margin-left:15px; padding:0px"><?php if ($_SESSION['is_email_verify'] == 1) {
                            echo "<span class=\"messageClass2\">verified" ;
                        } else {
                            echo "<span class=\"messageClass\">Not Verified" ;
                        } ?></label>
											</div>
											<div class="form-group col-md-6 col-sm-12" style="margin-left:5px; width:40%; margin-right:4%; font-weight:bold;font-size:1.3em;
											float:right;margin-bottom:5px;">
											<?php if ($_SESSION['is_email_verify'] == 0) {
                            echo "<input type=\"submit\" class=\"btn Lockerblue font-10\" id=\"btnverify\" name=\"btnverify\" value=\"Verify Email\">";
                        }?>
											</div>

										</div>
									</div>

									<div class="row">
										<div class="form-group col-md-6 col-sm-12" id="half1" style="margin-bottom:5px">
											
											<input id="currentpassword" name="currentpassword" autocomplete="off" class="form-control" type="password" value="<?php echo $currentpassword;?>">
											<?php if (isset($error['currentpasswordError'])) {
                            echo "<br/><span class=\"messageClass\">".$error['currentpasswordError']."</span>";
                        }?>
											<?php if (isset($error['currentpasswordError2'])) {
                            echo "<br/><span class=\"messageClass2\">".$error['currentpasswordError2']."</span>";
                        }?>								<label style="float:left">Current Password</label>
											
											<input id="signuppassword" name="signuppassword" autocomplete="off" class="form-control" type="password" value="<?php echo $password;?>">
											<?php if (isset($error['passwordError'])) {
                            echo "<br/><span class=\"messageClass\">".$error['passwordError']."</span>";
                        }?>								<label style="float:left">New Login Password</label>

											
											<input id="confirmpassword" name="confirmpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $confirmpassword;?>">
											<?php if (isset($error['confirmpasswordError'])) {
                            echo "<br/><span class=\"messageClass\">".$error['confirmpasswordError']."</span>";
                        }?>								<label style="float:left">Confirm Login Password</label><br><br><br>
												<div class="row">
													<div class="form-group col-md-6 col-sm-12" style="margin-left:5px;margin-bottom:10px; width:50%;font-weight:bold;font-size:1.3em">
														<input type="submit" class="btn Lockerblue font-10" id="btnlogin" name="btnlogin" value="Update Login Password"/>
													</div>
												</div>
										</div>

										<div class="form-group col-md-6 col-sm-12" id="half3">
											
											<input id="currentspendingpassword" name="currentspendingpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $currentspendingpassword;?>">
											<?php if (isset($error2['currentspendingpasswordError'])) {
                            echo "<br/><span class=\"messageClass\">".$error2['currentspendingpasswordError']."</span>";
                        }?>
											<?php if (isset($error2['currentspendingpasswordError2'])) {
                            echo "<br/><span class=\"messageClass2\">".$error2['currentspendingpasswordError2']."</span>";
                        }?>
											<label style="float:left">Current Spending Password</label>
											
											<input id="spendingpassword" name="spendingpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $spendingpassword;?>">
											<?php if (isset($error2['spendingpasswordError'])) {
                            echo "<br/><span class=\"messageClass\">".$error2['spendingpasswordError']."</span>";
                        }?>

											<label style="float:left">New Spending Password</label>
											
											<input id="confirmspendingpassword" name="confirmspendingpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $confirmspendingpassword;?>">
											<?php if (isset($error2['confirmspendingpasswordError'])) {
                            echo "<br/><span class=\"messageClass\">".$error2['confirmspendingpasswordError']."</span>";
                        }?> 								<label style="float:left">Confirm Spending Password</label>
												<br><br><br><div class="row">

													<div class="form-group col-md-6 col-sm-12" style="width:50%;font-weight:bold;font-size:1.3em">
														<input type="submit" class="btn Lockerblue font-10" id="btnSpending" name="btnSpending" value="Update Spending Password"/>
													</div>

												</div>
										</div>
									</div>


								</fieldset>
							</div>
						</div>
					</div>
				</main>
			</form>
		</div>
	</div>
	<link href="css/alertify.css" rel="stylesheet">
	<script src="js/clipboard.js" gapi_processed="true"></script>
	<script src="js/jquery-2.js" type="text/javascript"></script>
	<script src="js/materialize.js" type="text/javascript"></script>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/mara_002.js" type="text/javascript"></script>
	<script src="js/mara.js" type="text/javascript"></script>
	<script src="js/amcharts.js" type="text/javascript"></script>
	<script src="js/serial.js" type="text/javascript"></script>
	<script src="js/light.js" type="text/javascript"></script>
	<script src="js/jquery_002.js" type="text/javascript"></script>
	<script src="js/highcharts.js" type="text/javascript"></script>
	<link href="css/keyboard.css" rel="stylesheet">
	<link href="css/jkeyboard.css" rel="stylesheet">
	<script src="js/jkeyboard.js"></script>
	<script src="js/jquery-qrcode-0.js"></script>
	<script>
			function openNav() {
				document.getElementById("mySidenav").style.width = "250px";
				document.getElementById("openbtn").style.display = "none";
				document.getElementById("closebtn").style.display = "block";
			}

			function closeNav() {
				document.getElementById("mySidenav").style.width = "0";
				document.getElementById("openbtn").style.display = "block";
				document.getElementById("closebtn").style.display = "none";
			}

		</script>
</body>
</html>
