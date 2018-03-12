<?php
include_once('common.php');
page_protect();
if(!isset($_SESSION['user_id']))
{
	logout();
}
$error = array();
$transactionList = array();
$user_session = $_SESSION['user_session'];
$user_current_balance = 0;
if(isset($_GET['nad']))
{
	$new_address = $_GET['nad'];
}
$client = "";
if(_LIVE_)
{
	$client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
	if(isset($client))
	{
		$transactionList = $client->getTransactionList($user_session);
		$user_current_balance = $client->getBalance($user_session) - $fee;
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

		<link href="<?php echo $favicon;?>" rel="shortcut icon" type="image/x-icon">
		 <link href="css/material-design-iconic-font.css" rel="stylesheet" type="text/css">
		<link href="css/icon.css" rel="stylesheet" type="text/css">
		<link href="css/font-awesome.css" rel="stylesheet" type="text/css">
		<!--Import materialize.css-->
		<link href="css/main.css" rel="stylesheet" type="text/css">
		<link href="css/mystyle.css" rel="stylesheet" type="text/css">
		<!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
		<link href="css/jquery.css" rel="stylesheet" type="text/css">
		<link href="css/sitemaster.css" rel="stylesheet" type="text/css">
		<link href="css/custom.css" rel="stylesheet">
		<script type="text/javascript" async="" src="files/atrk.js"></script>
		<script src="js/cbgapi.loaded_0" async=""></script>
		<script src="js/llqrcode.js"></script>
		<script src="js/plusone.js" gapi_processed="true"></script>
		<script src="js/socket.js"></script>
		<script src="js/webqr.js"></script>
		
	</head>
	<body>
		<div class="wrapper vertical-sidebar" id="full-page">
			<header id="header">
				<div class="navbar">
					<nav style="position:fixed!important;z-index:999;">
						<a onclick="openNav()" class="button-collapse top-nav full waves-effect waves-light"><span id="openbtn" style="font-size:30px;cursor:pointer" >&#9776;</span></a>

								<a onclick="closeNav()" class="button-collapse top-nav full waves-effect waves-light"><span id="closebtn" style="font-size:30px;cursor:pointer">&times;</span></a>
						<div class="nav-wrapper" >


							<ul class="left">
									<a href="myaddress.php" class="brand-logo">
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
					if($_SESSION['user_admin'] == 1)
					{
					?>
						<a href="admin_user.php"><!--<i class="zmdi zmdi-help-outline iconFAQ" style=""></i>-->User list</a>
					<?php

					}
					?>

			</div>
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
			<aside class="sidebar-left">
				<ul class="side-nav fixed clearfix left" id="nav-mobile" style="transform: translateX(0px);">
					<li>
						<ul class="vm1 collapsible" data-collapsible="accordion" style="margin-top: 30px;">

							<li id="ms2" class="active"><a href="transactions.php" style="color: #fddd72;" class="collapsible-header"><!--<i class="zmdi zmdi-swap-vertical icontransaction" style="font-size:30px;"></i>-->Transactions</a></li>
							<li id="ms3"><a href="myaddress.php" class="collapsible-header"><!--<i class="fa fa-btc iconaddress" aria-hidden="true" style=""></i>-->My Addresses</a></li>
							<li id="ms4" style="position:relative;">
								<a href="securitycenter.php" class="collapsible-header">
									<!--<i>
										<img src="image/smalllock.png" id="SecurityCenterimg">
									</i>-->Security Center
									<span style="position: absolute; width: 20px;">
										<!--<i class="fa fa-circle fa-stack-2x signsbg" style="color: rgb(255, 171, 0);"></i>
										<i class="fa fa-stack-1x fa-inverse signs fa-exclamation"></i>-->
									</span>
								</a>
							</li>
							<li id="ms5"><a href="contactus.php" class="collapsible-header"><!--<i class="zmdi zmdi-help-outline iconFAQ" style=""></i>-->Contact Us</a></li>
                            <?php
                            if($_SESSION['user_admin'] == 1)
                            {
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
    			<link href="css/font-awesome.css" rel="stylesheet" type="text/css">
        		

    			<form action="transactions.php" method="post">
    				<main id="content" class="topmg transactiontop main2-content">
    					<div id="page-content">
    						<div class="row" style="margin-bottom:0px;">
    							<div class="col l12 m12 s12 tabmenu">
    								<div class="above-prod-table pctrns">
    									<div class="col s7 left prod-title-div no-padding" id="ace">
    										<a href="transactions.php">
    											<span class="btn btn-default" style="background-color: transparent!important;color: #a2212c;font-size: 14px; #ececec;box-shadow:none!important;padding: 0px 10px;font-family:'Open Sans', sans-serif; font-weight:bold;text-decoration:underline" id="Type_All">All</span>
    										</a>
    										<a href="sent.php">
    											<span class="btn btn-default" style="background-color: transparent!important;color: #a2212c;font-size: 14px; #ececec;box-shadow:none!important;padding: 0px 10px;font-family:'Open Sans', sans-serif;" id="Type_Sent">Sent</span>
    											</a>
    										<a href="recieved.php">
    											<span class="btn btn-default" style="background-color: transparent!important;color: #a2212c;font-size: 14px; #ececec;box-shadow:none!important;padding: 0px 10px;font-family:'Open Sans', sans-serif;" id="Type_Receive">Received</span>
    												</a>

    									</div>

    								</div>
    								<div class="above-prod-table mobtrns">

    									<div class="col m12 s12 no18">
    										<div class="input-field">
    											<div class="row" style="margin-left: 20px">
    												<div class="col-sm-3">
    													<a href="transactions.php"><span id="Type_All" style="display:block;">
    													<strong style="font-weight:bold">All</strong></span></a>
    												</div>
    												<div class="col-sm-3">
    													<a href="sent.php"><span id="Type_Sent" style="display:block;">Sent</span></a>
    												</div>
    												<div class="col-sm-3">
    													<a href="recieved.php"><span id="Type_Receive" style="display:block;">Received</span></a>
    												</div>
    											</div>


    										</div>
    										<div class="input-field">
    											<input class="validate srh1" placeholder="Search" style="position:relative;" id="searchsmall" type="text">
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    						<div class="row" style="margin-left: 20px">
    							<table class="table2excel" data-tablename="Test Table 1">
    								<thead>
    									<tr>
    										<td><strong>Date</strong></td>
    										<td><strong>Address</strong></td>
    										<td><strong>Type</strong></td>
    										<td><strong>Amount</strong></td>
    										<td><strong>Confirmations</strong></td>
    										<td colspan="3"><strong>TX</strong></td>
    									</tr>
    								</thead>
    								<tbody>
                                    <?php
        						  		$bold_txxs = "";
        							   if(count($transactionList)>0)
        								{
        								   foreach($transactionList as $transaction) {
        									  if($transaction['category']=="send") { $tx_type = '<b style="color: #FF0000;">Sent</b>'; } else { $tx_type = '<b style="color: #01DF01;">Received</b>'; }
        									  echo '<tr>
        											   <td>'.date('n/j/Y h:i a',$transaction['time']).'</td>
        											   <td>'.$transaction['address'].'</td>
        											   <td>'.$tx_type.'</td>
        											   <td>'.abs($transaction['amount']).'</td>
        											   <td>'.$transaction['confirmations'].'</td>
        											   <td colspan=\"3\"><a href="' . $blockchain_url,  $transaction['txid'] . '" target="_blank">Info</a></td>
        											</tr>';
        								   }
        								}
        								else if((count($transactionList)== 0))
        								{
        									echo "<tr><td colspan=\"3\">There is no Transaction exists</td></tr>";
        								}
                                        ?>
    								</tbody>
    							</table>
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
	</body>
</html>
