<?php
include_once('common.php');
page_protect();
if (!isset($_SESSION['user_id'])) {
    logout();
}
$error = array();
$transactionList = array();
$user_session = $_SESSION['user_session'];
$user_current_balance = 0;
$reciever_address= "";
$coin_amount = 0;
$trans_desc ="";
$spendingpassword = "";
$user_current_balance2 = 0;
$client = "";
if (_LIVE_) {
    $client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
    if (isset($client)) {
        $user_current_balance = $client->getBalance($user_session) - $fee;
        $user_current_balance2 = $user_current_balance;
    }
}

if (isset($_POST['btnlogin'])) {
    // var_dump($_POST);
    $coin_amount = $_POST['txtChar'];
    $reciever_address = $_POST['btcaddress'];
    $trans_desc = $_POST['discription'];
    $spendingpassword = $_POST['spendingpassword'];
    $user_current_balance = 0;

    if (_LIVE_) {
        $client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
        if (isset($client)) {
            $user_current_balance = $client->getBalance($user_session) - $fee;
        }
    }
    if (empty($reciever_address)) {
        $error['reciever_addressError'] = "Please Provide valid Address";
    }

    if (empty($coin_amount)) {
        $error['txtCharError'] = "Please Provide valid Amount";
    }
    if (empty($spendingpassword)) {
        $error['spendingpasswordError'] = "Please Provide valid Spending Password";
    }
    if ($coin_amount > $user_current_balance) {
        $error['txtCharError'] = "Withdrawal amount exceeds your wallet balance";
    }
    if (!empty($spendingpassword)) {
        $qstring = "select coalesce(id,0) as id,coalesce(transcation_password,'') as transcation_password ";
        $qstring .= "from users WHERE encrypt_username = '" . hash('sha256', $user_session) . "'";

        $spendingpassword_value = hash('sha256', addslashes(strip_tags($spendingpassword)));

        $result = $mysqli->query($qstring);
        $user = $result->fetch_assoc();
        $transcation_password_v = $user['transcation_password'];

        if ($user['id']> 0 && ($transcation_password_v != $spendingpassword_value)) {
            $error['spendingpasswordError'] = "Please provide valid Spending Password.";
        }
    }

    if (empty($error)) {
        $withdraw_message = 'ssss';
        if (_LIVE_) {
            $withdraw_message = $client->withdraw($user_session, $reciever_address, (float)$coin_amount);
            //$withdraw_message = $client->payment($reciever_address,$coin_amount,'from $user_session');
        }
        header("Location:sucecsssend.php?m=".$withdraw_message);
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
<script>
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31
      && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

</script>

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
<div style="overflow:hidden;cursor:pointer;"><h5 id="lblbtcbalancesmall" class="topbtc"><?php echo $user_current_balance2." " . $coin_short;?></h5></div>
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

<form action="send.php" method="post">
<main id="content" class="topmg transactiontop main2-content">
<div id="page-content">
<div class="modal-content">
<div id="send1ststep">
<div class="modal-head">
<div class="col l8">
<h5><!--<i class="zmdi zmdi-long-arrow-up zmdi-hc-fw"></i>-->Send</h5>

                        </div>
                        <div class="col l4 right-align">
                            <!--<i class="zmdi zmdi-close-circle-o modal-close"></i>-->
                        </div>
                    </div>
<div style="height:90%" class="MyMainDiv">
<div class="form-horizontal white center" style="margin:5%">
<div class="flex-center flex-justify ">
<div ui-view="contents" class="mhs">
<div id="login" style="width:100%">
<div name="loginForm" role="form" autocomplete="off" novalidate=""
class="ptl form-horizontal clearfix ng-pristine ng-invalid ng-invalid-required">
<fieldset>
<div  class="form-group">
<label style="float:left">To:</label>

<input id="btcaddress"  name ="btcaddress" class="validate mainaddress"
placeholder="Enter <?php echo $coin_short;?> Address" autocomplete="off" type="text"
value="<?php echo $reciever_address;?>">

<?php if (isset($error['reciever_addressError'])) {
    echo "<br/><span class=\"messageClass\">".$error['reciever_addressError']."</span>";
}?>
</div>
<div  class="form-group">
<label style="float:left">Amount <?php echo $coin_short;?></label>
<input id = "btcval" class="validate" placeholder="0" autocomplete="off"
onkeypress="return isNumberKey(event)"
name="txtChar" type="text" value ="<?php echo $coin_amount;?>">
<?php if (isset($error['txtCharError'])) {
    echo "<br/><span class=\"messageClass\">".$error['txtCharError']."</span>";
}?>
</div>

<div class="form-group">
<label style="float:left">Description</label>
<textarea id="discription" name ="discription" type="text" class="validate" placeholder="Description" style="position:relative;padding: 10px;height: 4rem;font-size:14px;resize: none;-ms-overflow-style: none;border:1px solid #9e9e9e;"><?php echo $trans_desc;?></textarea>
</div>
<div  class="form-group">
<label style="float:left">Spending Password</label>
<input id="spendingpassword" name="spendingpassword" class="form-control" autocomplete="off" type="password" value="<?php echo $spendingpassword;?>">
<?php if (isset($error['spendingpasswordError'])) {
    echo "<br/><span class=\"messageClass\">".$error['spendingpasswordError']."</span>";
}?>
</div>

<div class="mtl flex-center flex-end">
<input type="submit" class="btn Lockerblue" id="btnlogin" name="btnlogin" value="Send"/>
</div>
</fieldset>
</div>

</div>
</div>
</div>
</div>
</div>
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
