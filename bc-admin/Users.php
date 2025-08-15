<?php session_start();
    include("../func/bc-admin-config.php");
    
    if(isset($_GET["account-status"])){
        $status = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-status"])));
        $account_user = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-username"])));
        $statusArray = array(1, 2, 3);
        if(is_numeric($status)){
            if(in_array($status, $statusArray)){
            	$send_mail_to_user = false;
            	$get_user_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && username='$account_user' LIMIT 1"));
            	
                if($status == 1){
                    $alter_user_account_details = alterUser($account_user, "status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_user = true;
                        $json_response_array = array("desc" => ucwords($account_user." account activated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($get_payment_order["username"]." account cannot be activated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($status == 2){
                    $alter_user_account_details = alterUser($account_user, "status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_user = true;
                        $json_response_array = array("desc" => ucwords($account_user." account deactivated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($get_payment_order["username"]." account cannot be deactivated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }

                if($status == 3){
                    $alter_user_account_details = alterUser($account_user, "status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_user = true;
                        $json_response_array = array("desc" => ucwords($account_user." account deleted successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($get_payment_order["username"]." account cannot be deleted"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($send_mail_to_user == true){
                	// Email Beginning
                	$log_template_encoded_text_array = array("{firstname}" => $get_user_details["firstname"], "{lastname}" => $get_user_details["lastname"], "{account_status}" => accountStatus($status));
                	$raw_log_template_subject = getUserEmailTemplate('user-account-status','subject');
                	$raw_log_template_body = getUserEmailTemplate('user-account-status','body');
                	foreach($log_template_encoded_text_array as $array_key => $array_val){
                		$raw_log_template_subject = str_replace($array_key, $array_val, $raw_log_template_subject);
                		$raw_log_template_body = str_replace($array_key, $array_val, $raw_log_template_body);
                	}
                	sendVendorEmail($get_user_details["email"], $raw_log_template_subject, $raw_log_template_body);
                	// Email End
                }
            }else{
                //Invalid Status Code
                $json_response_array = array("desc" => "Invalid Status Code");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Non-numeric string
            $json_response_array = array("desc" => "Non-numeric string");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: /bc-admin/Users.php");
    }

    if(isset($_GET["account-api-status"])){
        $status = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-api-status"])));
        $account_user = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-username"])));
        $statusArray = array(1, 2);
        $statusArrayValue = array(1 => "Activated", 2 => "Deactivated");
        
        if(is_numeric($status)){
            if(in_array($status, $statusArray)){
            	$send_mail_to_user = false;
            	$get_user_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && username='$account_user' LIMIT 1"));
            	
                if($status == 1){
                    $alter_user_account_details = alterUser($account_user, "api_status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_user = true;
                        $json_response_array = array("desc" => ucwords($account_user." account status activated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($get_payment_order["username"]." account status cannot be activated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($status == 2){
                    $alter_user_account_details = alterUser($account_user, "api_status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_user = true;
                        $json_response_array = array("desc" => ucwords($account_user." account status deactivated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($get_payment_order["username"]." account status cannot be deactivated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($send_mail_to_user == true){
                	// Email Beginning
                	$log_template_encoded_text_array = array("{firstname}" => $get_user_details["firstname"], "{lastname}" => $get_user_details["lastname"], "{api_status}" => $statusArrayValue[$status]);
                	$raw_log_template_subject = getUserEmailTemplate('user-api-status','subject');
                	$raw_log_template_body = getUserEmailTemplate('user-api-status','body');
                	foreach($log_template_encoded_text_array as $array_key => $array_val){
                		$raw_log_template_subject = str_replace($array_key, $array_val, $raw_log_template_subject);
                		$raw_log_template_body = str_replace($array_key, $array_val, $raw_log_template_body);
                	}
                	sendVendorEmail($get_user_details["email"], $raw_log_template_subject, $raw_log_template_body);
                	// Email End
                }
            }else{
                //Invalid Status Code
                $json_response_array = array("desc" => "Invalid Status Code");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Non-numeric string
            $json_response_array = array("desc" => "Non-numeric string");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: /bc-admin/Users.php");
    }

    if(isset($_GET["account-log"])){
        $account_log = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-log"])));
        if(is_numeric($account_log)){
            if($account_log >= 1){
			    $get_logged_user_query = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$account_log'");
                if(mysqli_num_rows($get_logged_user_query) == 1){
                    $get_user_info = mysqli_fetch_array($get_logged_user_query);
                    $_SESSION["user_session"] = $get_user_info["username"];
                    $_SESSION["admin_to_user_redirect"] = true;
                }else{
                    if(mysqli_num_rows($get_logged_user_query) < 1){
                        $json_response_array = array("desc" => "Error: User not Exists");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(mysqli_num_rows($get_logged_user_query) > 1){
                            $json_response_array = array("desc" => "Error: Duplicate User Accounts");
                            $json_response_encode = json_encode($json_response_array,true);
                        }
                    }
                }
            }else{
                //Invalid Account ID
                $json_response_array = array("desc" => "Invalid Account ID");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Non-numeric string
            $json_response_array = array("desc" => "Non-numeric string");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: /bc-admin/Users.php");
    }
    
?>
<!DOCTYPE html>
<head>
    <title>Users | <?php echo $get_all_super_admin_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_super_admin_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
    <?php
    	//Redirect To User Page
    	if(isset($_SESSION["admin_to_user_redirect"]) && ($_SESSION["admin_to_user_redirect"] == true)){
    		echo '<script>	window.onload = function(){	window.open("'.$web_http_host.'/web/Dashboard.php","_blank");	}	</script>';
    	}
    ?>
</head>
<body>
    <?php include("../func/bc-admin-header.php"); ?>
        <?php
            
            if(!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
                $page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
                $offset_statement = " OFFSET ".((10 * $page_num) - 10);
            }else{
                $offset_statement = "";
            }
            
            if(isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))){
                $search_statement = " && (email LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR phone_number LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR username LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR firstname LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR lastname LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR othername LIKE '%".trim(strip_tags($_GET["searchq"]))."%')";
                $search_parameter = "searchq=".trim(strip_tags($_GET["searchq"]))."&&";
            }else{
                $search_statement = "";
                $search_parameter = "";
            }
            $get_active_user_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && status='1' $search_statement ORDER BY reg_date DESC LIMIT 50 $offset_statement");
            $get_inactive_user_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && status='2' $search_statement ORDER BY reg_date DESC LIMIT 10 $offset_statement");
            $get_deleted_user_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && status='3' $search_statement ORDER BY reg_date DESC LIMIT 10 $offset_statement");
            
        ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">USERS</span><br>
                <form method="get" action="Users.php" class="m-margin-tp-1 s-margin-tp-1">
                    <input style="user-select: auto;" name="searchq" type="text" value="<?php echo trim(strip_tags($_GET["searchq"])); ?>" placeholder="Email, Username, Phone number, Firstname e.t.c" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                    <button style="user-select: auto;" type="submit" class="button-box a-cursor color-2 bg-4 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                        <img src="<?php echo $web_http_host; ?>/asset/white-search.png" class="m-width-50 s-width-50 m-height-100 s-height-100" />
                    </button>
                </form>
            </div>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-1 s-margin-bm-1">ACTIVE ACCOUNT (<?php echo mysqli_num_rows($get_active_user_details); ?>)</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>S/N</th><th>Fullname</th><th>Username ID</th><th>Level</th><th>Balance</th><th>Phone number</th><th>Address</th><th>Referral</th><th>API Status</th><th>APIKey</th><th>Security Answer</th><th>Reg Date</th><th>Last Login</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_active_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_active_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $block_user_account = '<span onclick="updateUserAccountStatus(`2`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Block Account" src="'.$web_http_host.'/asset/fa-close.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $delete_user_account = '<span onclick="updateUserAccountStatus(`3`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Delete Account" src="'.$web_http_host.'/asset/fa-delete.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $login_user_account = '<span onclick="loginUserAccount(`'.$user_details["id"].'`, `'.$user_details["username"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Login Account" src="'.$web_http_host.'/asset/fa-login.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $all_user_account_action = $block_user_account." ".$delete_user_account." ".$login_user_account;
							
							//$user_bvn = '<span onclick="copyText(`BVN copied successfully`,`'.$user_details["bvn"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy BVN" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
							//$user_nin = '<span onclick="copyText(`NIN copied successfully`,`'.$user_details["nin"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy NIN" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
							$user_apikey = '<span onclick="copyText(`APIkey copied successfully`,`'.$user_details["api_key"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy APIkey" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
							
                            $username_with_link = ucwords($user_details["username"]).' <span onclick="customJsRedirect(`/bc-admin/UserEdit.php?userID='.$user_details["id"].'`, `Are you sure you want to edit '.strtoupper($user_details["username"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $user_level_with_upgrade_link = accountLevel($user_details["account_level"]).' <span onclick="customJsRedirect(`/bc-admin/UserUpgrade.php?userID='.$user_details["id"].'`, `Are you sure you want to Upgrade '.strtoupper($user_details["username"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Upgrade Account" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            
                            if(!empty($user_details["referral_id"]) && is_numeric($user_details["referral_id"])){
                                $get_user_referral_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$user_details["referral_id"]."'"));
                            }else{
                                $get_user_referral_details = array("username" => "Not Referred");
                            }

                            $account_api_status = array(1 => "enabled", 2 => "disabled");
                            if(in_array($user_details["api_status"], array_keys($account_api_status))){
                                if($account_api_status[$user_details["api_status"]] == "enabled"){
                                    $api_status_details = ucwords($account_api_status[$user_details["api_status"]]).' <span onclick="updateUserAccountAPIStatus(`2`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: red;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Disable]</sup><span>';
                                }else{
                                    $api_status_details = ucwords($account_api_status[$user_details["api_status"]]).' <span onclick="updateUserAccountAPIStatus(`1`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: green;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Enable]</sup><span>';
                                }
                            }else{
                                $api_status_details = '<span style="color: red;">Invalid Status Code</span>';
                            }

                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$user_details["firstname"]." ".$user_details["lastname"].checkIfEmpty(ucwords($user_details["othername"]),", ", "").'</td><td style="user-select: auto;">'.$username_with_link.'</td><td>'.$user_level_with_upgrade_link.'</td><td>'.toDecimal($user_details["balance"], 2).'</td><td>'.$user_details["phone_number"].'</td><td>'.$user_details["home_address"].'</td><td style="user-select: auto;">'.$get_user_referral_details["username"].'</td><td>'.$api_status_details.'</td><td style="user-select: auto;">'.$user_apikey.'</td><td style="user-select: auto;">'.$user_details["security_answer"].'</td><td>'.formDate($user_details["reg_date"]).'</td><td>'.formDate($user_details["last_login"]).'</td><td class="m-width-15 s-width-15">'.$all_user_account_action.'</td>
                            </tr>';
                        }
                    }
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-1 s-margin-bm-1">BLOCKED ACCOUNT (<?php echo mysqli_num_rows($get_inactive_user_details); ?>)</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>S/N</th><th>Fullname</th><th>Username ID</th><th>Level</th><th>Balance</th><th>Phone number</th><th>Address</th><th>Referral</th><th>API Status</th><th>APIKey</th><th>Security Answer</th><th>Reg Date</th><th>Last Login</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_inactive_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_inactive_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $activate_user_account = '<span onclick="updateUserAccountStatus(`1`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Re-activate Account" src="'.$web_http_host.'/asset/fa-approve.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $delete_user_account = '<span onclick="updateUserAccountStatus(`3`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Delete Account" src="'.$web_http_host.'/asset/fa-delete.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $login_user_account = '<span onclick="loginUserAccount(`'.$user_details["id"].'`, `'.$user_details["username"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Login Account" src="'.$web_http_host.'/asset/fa-login.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $all_user_account_action = $activate_user_account." ".$delete_user_account." ".$login_user_account;
                            $user_apikey = '<span onclick="copyText(`APIkey copied successfully`,`'.$user_details["api_key"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy APIkey" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
							
                            $username_with_link = ucwords($user_details["username"]).' <span onclick="customJsRedirect(`/bc-admin/UserEdit.php?userID='.$user_details["id"].'`, `Are you sure you want to edit '.strtoupper($user_details["username"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $user_level_with_upgrade_link = accountLevel($user_details["account_level"]).' <span onclick="customJsRedirect(`/bc-admin/UserUpgrade.php?userID='.$user_details["id"].'`, `Are you sure you want to Upgrade '.strtoupper($user_details["username"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Upgrade Account" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            
                            if(!empty($user_details["referral_id"]) && is_numeric($user_details["referral_id"])){
                                $get_user_referral_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$user_details["referral_id"]."'"));
                            }else{
                                $get_user_referral_details = array("username" => "Not Referred");
                            }

                            $account_api_status = array(1 => "enabled", 2 => "disabled");
                            if(in_array($user_details["api_status"], array_keys($account_api_status))){
                                if($account_api_status[$user_details["api_status"]] == "enabled"){
                                    $api_status_details = ucwords($account_api_status[$user_details["api_status"]]).' <span onclick="updateUserAccountAPIStatus(`2`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: red;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Disable]</sup><span>';
                                }else{
                                    $api_status_details = ucwords($account_api_status[$user_details["api_status"]]).' <span onclick="updateUserAccountAPIStatus(`1`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: green;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Enable]</sup><span>';
                                }
                            }else{
                                $api_status_details = '<span style="color: red;">Invalid Status Code</span>';
                            }

                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$user_details["firstname"]." ".$user_details["lastname"].checkIfEmpty(ucwords($user_details["othername"]),", ", "").'</td><td style="user-select: auto;">'.$username_with_link.'</td><td>'.$user_level_with_upgrade_link.'</td><td>'.toDecimal($user_details["balance"], 2).'</td><td>'.$user_details["phone_number"].'</td><td>'.$user_details["home_address"].'</td><td style="user-select: auto;">'.$get_user_referral_details["username"].'</td><td>'.$api_status_details.'</td><td style="user-select: auto;">'.$user_apikey.'</td><td style="user-select: auto;">'.$user_details["security_answer"].'</td><td>'.formDate($user_details["reg_date"]).'</td><td>'.formDate($user_details["last_login"]).'</td><td class="m-width-15 s-width-15">'.$all_user_account_action.'</td>
                            </tr>';
                        }
                    }
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-1 s-margin-bm-1">DELETED ACCOUNT (<?php echo mysqli_num_rows($get_deleted_user_details); ?>)</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>S/N</th><th>Fullname</th><th>Username ID</th><th>Level</th><th>Balance</th><th>Phone number</th><th>Address</th><th>Referral</th><th>API Status</th><th>APIKey</th><th>Security Answer</th><th>Reg Date</th><th>Last Login</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_deleted_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_deleted_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $activate_user_account = '<span onclick="updateUserAccountStatus(`1`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Re-activate Account" src="'.$web_http_host.'/asset/fa-approve.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $login_user_account = '<span onclick="loginUserAccount(`'.$user_details["id"].'`, `'.$user_details["username"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Login Account" src="'.$web_http_host.'/asset/fa-login.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $all_user_account_action = $activate_user_account." ".$login_user_account;
                            $user_apikey = '<span onclick="copyText(`APIkey copied successfully`,`'.$user_details["api_key"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy APIkey" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
							
                            $username_with_link = ucwords($user_details["username"]).' <span onclick="customJsRedirect(`/bc-admin/UserEdit.php?userID='.$user_details["id"].'`, `Are you sure you want to edit '.strtoupper($user_details["username"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $user_level_with_upgrade_link = accountLevel($user_details["account_level"]).' <span onclick="customJsRedirect(`/bc-admin/UserUpgrade.php?userID='.$user_details["id"].'`, `Are you sure you want to Upgrade '.strtoupper($user_details["username"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Upgrade Account" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            
                            if(!empty($user_details["referral_id"]) && is_numeric($user_details["referral_id"])){
                                $get_user_referral_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$user_details["referral_id"]."'"));
                            }else{
                                $get_user_referral_details = array("username" => "Not Referred");
                            }

                            $account_api_status = array(1 => "enabled", 2 => "disabled");
                            if(in_array($user_details["api_status"], array_keys($account_api_status))){
                                if($account_api_status[$user_details["api_status"]] == "enabled"){
                                    $api_status_details = ucwords($account_api_status[$user_details["api_status"]]).' <span onclick="updateUserAccountAPIStatus(`2`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: red;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Disable]</sup><span>';
                                }else{
                                    $api_status_details = ucwords($account_api_status[$user_details["api_status"]]).' <span onclick="updateUserAccountAPIStatus(`1`,`'.$user_details["username"].'`);" style="text-decoration: underline; color: green;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Enable]</sup><span>';
                                }
                            }else{
                                $api_status_details = '<span style="color: red;">Invalid Status Code</span>';
                            }

                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$user_details["firstname"]." ".$user_details["lastname"].checkIfEmpty(ucwords($user_details["othername"]),", ", "").'</td><td style="user-select: auto;">'.$username_with_link.'</td><td>'.$user_level_with_upgrade_link.'</td><td>'.toDecimal($user_details["balance"], 2).'</td><td>'.$user_details["phone_number"].'</td><td>'.$user_details["home_address"].'</td><td style="user-select: auto;">'.$get_user_referral_details["username"].'</td><td>'.$api_status_details.'</td><td style="user-select: auto;">'.$user_apikey.'</td><td style="user-select: auto;">'.$user_details["security_answer"].'</td><td>'.formDate($user_details["reg_date"]).'</td><td>'.formDate($user_details["last_login"]).'</td><td class="m-width-15 s-width-15">'.$all_user_account_action.'</td>
                            </tr>';
                        }
                    }
                    ?>
                </table>
            </div><br/>

            <div class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
                <?php if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)){ ?>
                <a href="Users.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-lt s-float-lt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Prev</button>
                </a>
                <?php } ?>
                <?php
                    if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
                        $trans_next = (trim(strip_tags($_GET["page"])) +1);
                    }else{
                        $trans_next = 2;
                    }
                ?>
                <a href="Users.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
                </a>
            </div>
        </div>

    <?php include("../func/bc-admin-footer.php"); ?>
    
</body>
</html>