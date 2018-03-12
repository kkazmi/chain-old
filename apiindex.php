<?php
include_once('common.php');
function formatData($header,$data,$format='json')
{
   if($format == 'json') 
	{
		//header('Content-type: application/json'); 
		header('Content-type: text/html'); 
		return json_encode(array($header=>$data));
	}
	else 
	{
		$response = '';
		header('Content-type: text/xml');
		$response .=  "<".$header.">";
		foreach($data as $index => $data) 
		{
			if(is_array($data)) 
			{
				foreach($data as $key => $value) 
				{
					//echo $value;
					$response .=  "<".$key.">";
					if(is_array($value)) 
					{
						foreach($value as $tag => $val) 
						{
							$response .=  "<".$tag.">".$val."</".$tag.">";
						}
					}
					$response .= "</".$key.">";
				}
			}
		}
		$response .= "</".$header.">";
	}
	return $response;
}

	function errorHandler($header,$data,$format='json')
	{
		if($format == 'json') 
			{
					header('Content-type: application/json'); 
					header('HTTP/1.0 412'); 
				return json_encode(($data));
			}
}

function formatData2($count,$header,$data,$format='json')
{
 //  print_r($data);
	if($format == 'json') 
	{
		header('Content-type: application/json'); 
		return json_encode(array("total_count"=>$count,$header=>$data));
	}
	else 
	{
		$response = '';
		header('Content-type: text/xml');
		$response .=  "<".$header.">";
		foreach($data as $index => $data) 
		{
			if(is_array($data)) 
			{
				foreach($data as $key => $value) 
				{
					//echo $value;
					$response .=  "<".$key.">";
					if(is_array($value)) 
					{
						foreach($value as $tag => $val) 
						{
							$response .=  "<".$tag.">".$val."</".$tag.">";
						}
					}
					$response .= "</".$key.">";
				}
			}
		}
		$response .= "</".$header.">";
	}
	return $response;
}


if($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    return header("HTTP/1.0 200");
}
else
{
    if (isset($_GET['method']))
    {
		
        $jsonData = file_get_contents('php://input');
		$method = strtolower($_GET['method']);
        $parser = (isset($_REQUEST['parser']) ? $_REQUEST['parser'] : 'json');
		$data = json_decode($jsonData, true);
		//var_dump($data);
	switch ($method) 
		{
			default:
				return header("HTTP/1.0 200");
			break;
			case "userregistration":
                /* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=userRegistration
                {
                    "email_id" : "",
                    "user_password" : "",
                    "confirm_password" : "",
					"spending_password" : "",                    
					"confirm_spending_password":""
                }
                */
				$user_password_value = addslashes(strip_tags(trim($data['user_password'])));
                $confirm_password_value = addslashes(strip_tags(trim($data['confirm_password'])));
                $email_value = addslashes(strip_tags(trim($data['email_id'])));
				$spending_password = addslashes(strip_tags(trim($data['spending_password'])));
				$confirm_spending_password = addslashes(strip_tags(trim($data['confirm_spending_password'])));
		  
                if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else if(strlen($user_password_value) == 0)
                {
                    echo formatData("response",array("error_message"=>"Invalid Password of length 8 to 35 characters"),"json");
                }
				else if(strpos($user_password_value, ' ') > 0)
				{
					echo formatData("response",array("error_message"=>"Invalid Password of length 8 to 35 characters"),"json");
				}
				
                else if(strcasecmp($user_password_value,$confirm_password_value) != 0)
                {
                    echo formatData("response",array("error_message"=>"Password and Confirm Password do not match"),"json");
                }
				else if(strlen($spending_password) == 0)
                {
                    echo formatData("response",array("error_message"=>"Invalid Spending Password of length 8 to 35 characters"),"json");
                }
				else if(strpos($spending_password, ' ') > 0)
				{
					echo formatData("response",array("error_message"=>"Invalid spending Password of length 8 to 35 characters"),"json");
				}
				
                else if(strcasecmp($spending_password,$confirm_spending_password) != 0)
                {
                    echo formatData("response",array("error_message"=>"Spending Password and Spending Confirm Password do not match"),"json");
                }
				else
                {
                    
					$email_id = $mysqli->real_escape_string(strip_tags($email_value));
					$password_value = hash('sha256',addslashes(strip_tags($user_password_value)));
					$qstring = "select coalesce(id,0) as id
								from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";
					
					$result	= $mysqli->query($qstring);
					$user = $result->fetch_assoc();
					//var_dump($user);
					if ($user['id']> 0)
					{
						echo formatData("response",array("error_message"=>"User name with email Id ".$email_value." already exist. Please try another one"),"json");
					}
					else
					{
						$email_id = $mysqli->real_escape_string(strip_tags($email_value));
						$password_value = hash('sha256',addslashes(strip_tags($user_password_value)));
						$spending_password_value = hash('sha256',addslashes(strip_tags($spending_password)));
						
						$qstring = "insert into `users`( `date`, `ip`, `username`, 
						`encrypt_username`, `password`, `transcation_password`, 
						`email`) values (";
						$qstring .= "now(), ";
						$qstring .= "'".$_SERVER['REMOTE_ADDR']."', ";
						$qstring .= "'".$email_id."', ";
						$qstring .= "'".hash('sha256',$email_id)."', ";
						$qstring .= "'".$password_value."', ";
						$qstring .= "'".$spending_password_value."', ";
						$qstring .= "'".$email_id."') ";
						//	echo $qstring;
						$result2	= $mysqli->query($qstring);
						if ($result2)
						{
							echo formatData("response",array("sucess_message"=>"User name with email Id ".$email_value." has been successfully registered"),"json");
						}
					}
				}
            break;
			case "login":
				/* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=Login
                {
                    "email_id" : "admin",
                    "password" : "password"
                }
                */
		//	var_dump($data);
				$user_name_value = addslashes(strip_tags(trim($data['email_id'])));
				$user_password_value = addslashes(strip_tags(trim($data['password'])));
				$userArray = array();
				
				if(strlen($user_name_value) == 0)
				{
					echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
				}
				else if(!isEmail($user_name_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				 else if(strlen($user_password_value) == 0)
				{
					echo formatData("response",array("error_message"=>"Invalid  Password"),"json");
				}
				 else
                {
                   
					$email_id = $mysqli->real_escape_string(strip_tags($user_name_value));
					$password_value = hash('sha256',addslashes(strip_tags($user_password_value)));
					$qstring = "select coalesce(id,0) as id, coalesce(username,'') as username,
								coalesce(password,'') as password,
								coalesce(email,'') as email_id,
								coalesce(admin,'') as admin,
								coalesce(locked,0) as locked,
								coalesce(supportpin,'') as supportpin,
								coalesce(is_email_verify,0) as is_email_verify,
								coalesce(secret,'') as secret,
								coalesce(authused,0) as authused
								from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";
					
					$result	= $mysqli->query($qstring);
					$user = $result->fetch_assoc();
					
                    $secret = $user['secret'];
					if (($user) && ($user['password'] == $password_value) && ($user['locked'] == 0) && ($user['authused'] == 0))
					{
						//	session_start();
						$userArray['user_id'] = $user['id'];
						$userArray['user_email_id'] = $user['email_id'];
						$userArray['useruserArray'] = $user['username'];
						$userArray['user_admin'] = $user['admin'];
						$userArray['user_supportpin'] = $user['supportpin'];
						$userArray['is_email_verify'] = $user['is_email_verify'];
						echo formatData("response",array("data"=>$userArray),"json");
						

					} 
					elseif (($user) && ($user['password'] == $password_value) && ($user['locked'] == 1))
					{
						$pin = $user['supportpin'];
						echo formatData("response",array("error_message"=>"Account is locked. Contact support for more information. $pin"),"json");
						
					}
					elseif (($user) && ($user['password'] == $password_value) && ($user['locked'] == 0) && ($user['authused'] == 1 && ($oneCode == $_POST['auth']))) 
					{
						//		session_start();
						session_regenerate_id (true); //prevent against session fixation attacks.
											
						$userArray['user_id'] = $user['id'];
						$userArray['user_email_id'] = $user['email_id'];
						$userArray['useruserArray'] = $user['username'];
						$userArray['user_admin'] = $user['admin'];
						$userArray['user_supportpin'] = $user['supportpin'];
						$userArray['is_email_verify'] = $user['is_email_verify'];
						
						echo formatData("response",array("data"=>$userArray),"json");
					}
					else
					{
						echo formatData("response",array("error_message"=>"email_id, password is incorrect"),"json");
					}
					
					
					
                }
			break;
					
			case "forgetpassword":
                /* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=forgetPassword
                {
                    "email_id" : "1"
                }
                */
				//var_dump($data);
                $email_value = addslashes(strip_tags(trim($data['email_id'])));
                if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
                else
                {
					$email_id = $mysqli->real_escape_string(strip_tags($email_value));
					$qstring = "select coalesce(id,0) as id, coalesce(username,'') as username
								from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";
					
					$result	= $mysqli->query($qstring);
					$user = $result->fetch_assoc();
					//var_dump($user);
					
					
					if (($user) && ($user['id'] > 0 ))
					{
						$new_password = rand(0,100000)."p".rand(0,100000);
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
						echo formatData("response",array("sucess_message"=>"An Email has been send to your email id."),"json");
						sendpmail($email_id,$sub,$message_body);
					}
					else
					{
						echo formatData("response",array("error_message"=>"the Provided email_id  is not registered with us"),"json");
					}
				  
				}
			break;
			case "updatespendingpassword":
                /* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=updateSpendingPassword
                {
                    "email_id" : "",
                    "spending_password" : "",
					"confirm_spending_password":""
                }
                */
				//var_dump($data);
            	$spending_password = addslashes(strip_tags(trim($data['spending_password'])));
                $confirm_spending_password = addslashes(strip_tags(trim($data['confirm_spending_password'])));
                $email_value = addslashes(strip_tags(trim($data['email_id'])));
			
                if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else if(strlen($spending_password) == 0)
                {
                    echo formatData("response",array("error_message"=>"Invalid Spending Password of length 8 to 35 characters"),"json");
                }
				else if(strpos($spending_password, ' ') > 0)
				{
					echo formatData("response",array("error_message"=>"Invalid Spending Password of length 8 to 35 characters"),"json");
				}
				
                else if(strcasecmp($spending_password,$confirm_spending_password) != 0)
                {
                    echo formatData("response",array("error_message"=>"spending Password and spending Confirm Password do not match"),"json");
                }
				else
                {
                    
					$email_id = $mysqli->real_escape_string(strip_tags($email_value));
					$password_value = hash('sha256',addslashes(strip_tags($spending_password)));
					$qstring = "select coalesce(id,0) as id
								from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";
					
					$result	= $mysqli->query($qstring);
					$user = $result->fetch_assoc();
					//var_dump($user);
					if ($user['id']< 0)
					{
						echo formatData("response",array("error_message"=>"User name with email Id ".$email_value." does not  exist. Please try another one"),"json");
					}
					else
					{
						$qstring = "update `users`set "; 
						$qstring .= "`transcation_password` = ";
						$qstring .= "'".$password_value."'";
						$qstring .= " where encrypt_username = '".hash('sha256',$email_id)."' and id = ".$user['id'];
						//echo $qstring;
						$result	= $mysqli->query($qstring);
						if($result)
						{
							echo formatData("response",array("sucess_message"=>"Your Spending Password has been successfully updated"),"json");

						}
					}
				}
            break;
			
			case "updateloginpassword":
                /* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=updateLoginPassword
                {
                    "email_id" : "",
                    "user_password" : "",
					"confirm_password":""
                }
                */
            	$user_password_value = addslashes(strip_tags(trim($data['user_password'])));
                $confirm_password_value = addslashes(strip_tags(trim($data['confirm_password'])));
                $email_value = addslashes(strip_tags(trim($data['email_id'])));
			
                if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else if(strlen($user_password_value) == 0)
                {
                    echo formatData("response",array("error_message"=>"Invalid Password of length 8 to 35 characters"),"json");
                }
				else if(strpos($user_password_value, ' ') > 0)
				{
					echo formatData("response",array("error_message"=>"Invalid Password of length 8 to 35 characters"),"json");
				}
				
                else if(strcasecmp($user_password_value,$confirm_password_value) != 0)
                {
                    echo formatData("response",array("error_message"=>"Password and Confirm Password do not match"),"json");
                }
				else
                {
                    
					$email_id = $mysqli->real_escape_string(strip_tags($email_value));
					$password_value = hash('sha256',addslashes(strip_tags($user_password_value)));
					$qstring = "select coalesce(id,0) as id
								from users WHERE encrypt_username = '" . hash('sha256',$email_id) . "'";
					
					$result	= $mysqli->query($qstring);
					$user = $result->fetch_assoc();
					//var_dump($user);
					if ($user['id']< 0)
					{
						echo formatData("response",array("error_message"=>"User name with email Id ".$email_value." does not  exist. Please try another one"),"json");
					}
					else
					{
						$qstring = "update `users`set "; 
						$qstring .= "`password` = ";
						$qstring .= "'".$password_value."'";
						$qstring .= " where encrypt_username = '".hash('sha256',$email_id)."' and id = ".$user['id'];
						//echo $qstring;
						$result	= $mysqli->query($qstring);
						if($result)
						{
							echo formatData("response",array("sucess_message"=>"Your Login password has been successfully updated"),"json");

						}
					}
				}
            break;
			
			case "getnewaddress":
				/* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=getnewaddress
                {
					"email_id" : "1"
                }
                */
				
				$email_value = addslashes(strip_tags(trim($data['email_id'])));
				if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else
                {
                    
					if(_LIVE_)
					{
						$client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
						if(isset($client))
						{
							$wallet_address  = $client->getnewaddress($email_value);
							echo formatData("response",array("wallet_address"=>$wallet_address),"json");
						}
					}
					
                }
			break;
			
			case "sendcoin":
                /* Api calling Json
                http://operacoinwallet.com/apiindex.php?method=sendCoin
                {
					"email_id" : "",
                    "wallet_address" : "",
                    "trans_amount" : "",
					"spending_password":""
					
                }
                */
			//var_dump($data);
                $reciever_address = addslashes(strip_tags(trim($data['wallet_address'])));
                $trans_amount_value = (double)addslashes(strip_tags(trim($data['trans_amount'])));
                $spendingpassword = addslashes(strip_tags(trim($data['spending_password'])));
                $txid_value = 0;
				$user_current_balance = 0;
				$email_value = addslashes(strip_tags(trim($data['email_id'])));
				
				if(_LIVE_)
				{
					$client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
					if(isset($client))
					{
						$user_current_balance = $client->getBalance($email_value) - $fee;
					}
				}
				if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else if (empty($reciever_address))
				{
					echo formatData("response",array("error_message"=>"Invalid To Wallet Address"),"json");
				}	
				else if(strlen($trans_amount_value) == 0)
				{
					echo formatData("response",array("error_message"=>"Invalid Trans Amount"),"json");
				}
				else if(empty($spendingpassword))
				{
					echo formatData("response",array("error_message"=>"Invalid Spending Password"),"json");
					
				}	
				else if ($trans_amount_value > $user_current_balance)
				{
					echo formatData("response",array("error_message"=>"Withdrawal amount exceeds your wallet balance"),"json");
				}

                
                else
                {
					$qstring = "select coalesce(id,0) as id,coalesce(transcation_password,'') as transcation_password ";
					$qstring .= "from users WHERE encrypt_username = '" . hash('sha256',$email_value) . "'";
					
					$spendingpassword_value = hash('sha256',addslashes(strip_tags($spendingpassword)));
				
					$result	= $mysqli->query($qstring);
					$user = $result->fetch_assoc();
					$transcation_password_v = $user['transcation_password'];
				
					if ($user['id']> 0 && ($transcation_password_v != $spendingpassword_value))
					{
						echo formatData("response",array("error_message"=>"Sepnding Password do not match, Please provide valid Spending Password."),"json");
					}
					else 
					{
						if(_LIVE_)
						{
							$withdraw_message = $client->withdraw($email_value, $reciever_address, (float)$trans_amount_value);
							//$withdraw_message = $client->payment($reciever_address,$coin_amount,'from $user_session');
							echo formatData("response",array("success_message"=>"Your transcation has been intiated with transcation id ".$withdraw_message),"json");
						}
					}
				}
            break;
			
			
			
			 case "myalltranscationlist":
                /*
                http://operacoinwallet.com/apiindex.php?method=MyAllTranscationList
                {
					"email_id":""
                }
                */
			    $email_value = addslashes(strip_tags(trim($data['email_id'])));
				$transactionList = array();
				if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else
				{
					if(_LIVE_)
					{
						$client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
						if(isset($client))
						{
							$transactionList = $client->getTransactionList($email_value);
						}
					}
					echo formatData("response",array("transcation_list"=>$transactionList),"json");
				}
				
            break;
			
			case "mywithdrwaltranscationlist":
                /* 
                http://operacoinwallet.com/apiindex.php?method=MyWithdrawlTranscationList
               {
					"email_id":""
                }
                */
			    $email_value = addslashes(strip_tags(trim($data['email_id'])));
				$transcaary = array();
				$transactionList = array();
				if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else
				{
					if(_LIVE_)
					{
						$client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
						if(isset($client))
						{
							$transactionList = $client->getTransactionList($email_value);
						}
					}
				//	var_dump($transactionList);
					foreach($transactionList as $transaction) 
					{
						if($transaction['category'] == "send")
						{								
							$transcaary['time'] = $transaction['time'];
							$transcaary['address'] = $transaction['address'];
							$transcaary['amount'] = $transaction['amount'];
							$transcaary['confirmations'] = $transaction['confirmations'];
							$transcaary['txid'] = $transaction['txid'];
						}
				   }
				   echo formatData("response",array("transcation_list"=>$transcaary),"json");
				}
            break;
			
			case "myrecievetranscationlist":
                /* 
                {
					"email_id":""
                }
                */
			    $email_value = addslashes(strip_tags(trim($data['email_id'])));
				$transcaary = array();
				$transactionList = array();
				if(!isEmail($email_value))
                {
                    echo formatData("response",array("error_message"=>"Invalid Email ID"),"json");
                }
				else
				{
					if(_LIVE_)
					{
						$client = new Client($rpc_host, $rpc_port, $rpc_user, $rpc_pass);
						if(isset($client))
						{
							$transactionList = $client->getTransactionList($email_value);
						}
					}
					
					foreach($transactionList as $transaction) 
					{
						if($transaction['category'] != "send")
						{								
							$transcaary['time'] = $transaction['time'];
							$transcaary['address'] = $transaction['address'];
							$transcaary['amount'] = $transaction['amount'];
							$transcaary['confirmations'] = $transaction['confirmations'];
							$transcaary['txid'] = $transaction['txid'];
						}
				   }
				   echo formatData("response",array("transcation_list"=>$transcaary),"json");
				}
            break;
		}
	}
	else
	{
		$json['success'] = false;
		$json['message'] = "Un Athorize Access 1";
		echo json_encode($json);
		exit;
		
	}
}
?>
