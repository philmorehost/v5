<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_GET["account-status"])){
        $status = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-status"])));
        $account_user = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-username"])));
        $account_id = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-id"])));
        $statusArray = array(1, 2, 3);
        if(is_numeric($status)){
            if(in_array($status, $statusArray)){
            	$send_mail_to_admin = false;
            	$get_admin_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE id='$account_id' LIMIT 1"));
            	
                if($status == 1){
                    $alter_user_account_details = alterVendor($account_id, "status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_admin = true;
                        $json_response_array = array("desc" => ucwords($account_user." account activated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($account_user." account cannot be activated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($status == 2){
                    $alter_user_account_details = alterVendor($account_id, "status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_admin = true;
                        $json_response_array = array("desc" => ucwords($account_user." account deactivated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($account_user." account cannot be deactivated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }

                if($status == 3){
                    $alter_user_account_details = alterVendor($account_id, "status", $status);
                    if($alter_user_account_details == "success"){
                    	$send_mail_to_admin = true;
                        $json_response_array = array("desc" => ucwords($account_user." account deleted successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($account_user." account cannot be deleted"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($send_mail_to_admin == true){
                	// Email Beginning
                	$log_template_encoded_text_array = array("{firstname}" => $get_admin_details["firstname"], "{lastname}" => $get_admin_details["lastname"], "{account_status}" => accountStatus($status));
                	$raw_log_template_subject = getSuperAdminEmailTemplate('vendor-account-status','subject');
                	$raw_log_template_body = getSuperAdminEmailTemplate('vendor-account-status','body');
                	foreach($log_template_encoded_text_array as $array_key => $array_val){
                		$raw_log_template_subject = str_replace($array_key, $array_val, $raw_log_template_subject);
                		$raw_log_template_body = str_replace($array_key, $array_val, $raw_log_template_body);
                	}
                	sendSuperAdminEmail($get_admin_details["email"], $raw_log_template_subject, $raw_log_template_body);
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
        header("Location: /bc-spadmin/Vendors.php");
    }

    
    if(isset($_GET["account-log"])){
        $account_log = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-log"])));
        $type = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["type"])));
        if(is_numeric($account_log)){
            if($account_log >= 1){
			    $get_logged_user_query = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE id='".$account_log."'");
                if(mysqli_num_rows($get_logged_user_query) == 1){
                    $get_user_info = mysqli_fetch_array($get_logged_user_query);
                    $_SESSION["vendor_email"] = $get_user_info["email"];
                    $_SESSION["vendor_pass"] = $get_user_info["password"];
                    $_SESSION["admin_to_vendor_redirect_hostname"] = $get_user_info["website_url"];
                }else{
                    if(mysqli_num_rows($get_logged_user_query) < 1){
                        $json_response_array = array("desc" => "Error: Vendor not Exists");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(mysqli_num_rows($get_logged_user_query) > 1){
                            $json_response_array = array("desc" => "Error: Duplicate Vendor Accounts");
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
        if(isset($_SESSION["admin_to_vendor_redirect_hostname"]) && ($_SESSION["admin_to_vendor_redirect_hostname"] == true)){
            $vendors_auth_email = $_SESSION["vendor_email"];
            $vendors_auth_pass = base64_encode($_SESSION["vendor_pass"]);
            $vendors_url = $_SESSION["admin_to_vendor_redirect_hostname"];
            $vendors_auth_text = base64_encode($vendors_auth_email.":".$vendors_auth_pass);
            //Unset Vendor Session
            unset($_SESSION["vendor_email"]);
            unset($_SESSION["vendor_pass"]);
            unset($_SESSION["admin_to_vendor_redirect_hostname"]);
            $type_manage_api = "";
            if($type == "manageapi"){
                $type_manage_api = "&&redirect=MarketPlace.php";
            }
            header("Location: /bc-spadmin/Vendors.php?vendorUrl=".$vendors_url."&&vendorLogAuth=".$vendors_auth_text.$type_manage_api);
        }else{
            header("Location: /bc-spadmin/Vendors.php");
        }
    }
    
    /*if(isset($_GET["api-status"])){
        $status = mysqli_real_escape_string($connection_server, trim($_GET["api-status"]));
        $api_id = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["api-id"])));
        $account_user = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-username"])));
        $account_id = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["account-id"])));
        $api_detail = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["api-detail"])));

        $statusArray = array(0, 1);
        if(is_numeric($status)){
            if(in_array($status, $statusArray)){
                if($status == 1){
                    $alter_user_account_details = alterAPI($account_id, $api_id, "status", $status);
                    if($alter_user_account_details == "success"){
                        $json_response_array = array("desc" => ucwords($account_user." ".$api_detail." api activated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($account_user." ".$api_detail." cannot be activated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
                
                if($status == 0){
                    $alter_user_account_details = alterAPI($account_id, $api_id, "status", $status);
                    if($alter_user_account_details == "success"){
                        $json_response_array = array("desc" => ucwords($account_user." ".$api_detail." deactivated successfully"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        $json_response_array = array("desc" => ucwords($account_user." ".$api_detail." cannot be deactivated"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
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
        header("Location: /bc-spadmin/Vendors.php");
    }*/

    
?>
<!DOCTYPE html>
<head>
    <title></title>
    <meta charset="UTF-8" />
    <meta name="description" content="" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
    <?php
    	//Redirect To Vendor Page
        $getVendorUrl = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["vendorUrl"])));
    	$getVendorLogAuth = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["vendorLogAuth"])));
        $getRedirectUrl = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["redirect"])));
    	
    	if(isset($_GET["vendorUrl"]) && !empty($getVendorUrl) && isset($_GET["vendorLogAuth"]) && !empty($getVendorLogAuth)){
            if(isset($_GET["redirect"]) && !empty($getRedirectUrl)){
                echo '<script>	window.onload = function(){	window.open("http://'.$getVendorUrl.'/bc-admin/Dashboard.php?logVendorAdmin='.$getVendorLogAuth.'&&redirectAdminTo='.$getRedirectUrl.'","_blank"); window.open("/bc-spadmin/Vendors.php","_self");	}	</script>';
            }else{
                echo '<script>	window.onload = function(){	window.open("http://'.$getVendorUrl.'/bc-admin/Dashboard.php?logVendorAdmin='.$getVendorLogAuth.'","_blank"); window.open("/bc-spadmin/Vendors.php","_self");	}	</script>';
            }
    	}
    ?>
</head>
<body>
    <?php include("../func/bc-spadmin-header.php"); ?>
        <?php
            
            if(!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
                $page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
                $offset_statement = " OFFSET ".((10 * $page_num) - 10);
            }else{
                $offset_statement = "";
            }
            
            if(isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))){
                $search_statement = " && (email LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR phone_number LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR firstname LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR lastname LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR website_url LIKE '%".trim(strip_tags($_GET["searchq"]))."%')";
                $search_parameter = "searchq=".trim(strip_tags($_GET["searchq"]))."&&";
            }else{
                $search_statement = "";
                $search_parameter = "";
            }
            $get_active_user_details = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE status='1' $search_statement ORDER BY reg_date DESC LIMIT 10 $offset_statement");
            $get_inactive_user_details = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE status='2' $search_statement ORDER BY reg_date DESC LIMIT 10 $offset_statement");
            $get_deleted_user_details = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE status='3' $search_statement ORDER BY reg_date DESC LIMIT 10 $offset_statement");
            
        ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDORS</span><br>
                <form method="get" action="Vendors.php" class="m-margin-tp-1 s-margin-tp-1">
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
                        <th>S/N</th><th>Fullname</th><th>Vendor ID</th><th>URL</th><th>APIs</th><th>Balance</th><th>Phone number</th><th>Address</th><th>BVN</th><th>NIN</th><th>Reg Date</th><th>Last Login</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_active_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_active_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $block_user_account = '<span onclick="updateVendorAccountStatus(`2`,`'.$user_details["id"].'`,`'.$user_details["email"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Block Account" src="'.$web_http_host.'/asset/fa-close.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $delete_user_account = '<span onclick="updateVendorAccountStatus(`3`,`'.$user_details["id"].'`,`'.$user_details["email"].'`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Delete Account" src="'.$web_http_host.'/asset/fa-delete.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $login_user_account = '<span onclick="loginVendorAccount(`'.$user_details["id"].'`, `'.$user_details["email"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Login Account" src="'.$web_http_host.'/asset/fa-login.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $manage_api_user_account = '<span onclick="manageVendorApi(`'.$user_details["id"].'`, `'.$user_details["email"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Manage API" src="'.$web_http_host.'/asset/fa-developer.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $all_user_account_action = $block_user_account." ".$delete_user_account." ".$login_user_account." ".$manage_api_user_account;
                            $user_bvn = '<span onclick="copyText(`BVN copied successfully`,`'.$user_details["bvn"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy BVN" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $user_nin = '<span onclick="copyText(`NIN copied successfully`,`'.$user_details["nin"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy NIN" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            
                            $username_with_link = ucwords($user_details["email"]).' <span onclick="customJsRedirect(`/bc-spadmin/VendorEdit.php?vendorID='.$user_details["id"].'`, `Are you sure you want to edit '.strtoupper($user_details["email"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $website_url_href = '<a title="Visit Website" style="text-decoration: underline; color: blue;" class="a-cursor text-bold-400" href="//'.$user_details["website_url"].'/bc-admin" target="_blank">'.$user_details["website_url"].'</a>';
                            //Vendor API List
                            $select_vendor_api_list = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$user_details["id"]."'");
                            if(mysqli_num_rows($select_vendor_api_list) >= 1){
                                while($api_details = mysqli_fetch_assoc($select_vendor_api_list)){
                                    $api_names = "[".str_replace(["_","-"]," ",$api_details["api_type"])."] ".$api_details["api_base_url"];
                                    if($api_details["status"] == 1){
                                        $vendor_each_api .= $api_names. ' <span onclick="customJsRedirect(`/bc-spadmin/Vendors.php?api-id='.$api_details["id"].'&&api-status=0&&account-username='.$user_details["email"].'&&account-id='.$user_details["id"].'&&api-detail='.strtoupper($api_details["api_type"]).' - '.strtoupper($api_details["api_base_url"]).'`, `Are you sure you want to disable '.$user_details["email"].' '.strtoupper($api_details["api_type"]).' - '.strtoupper($api_details["api_base_url"]).'`);" style="text-decoration: underline; color: green;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Disable]</sup></span>'. "<br/>";
                                    }else{
                                        $vendor_each_api .= $api_names. ' <span onclick="customJsRedirect(`/bc-spadmin/Vendors.php?api-id='.$api_details["id"].'&&api-status=1&&account-username='.$user_details["email"].'&&account-id='.$user_details["id"].'&&api-detail='.strtoupper($api_details["api_type"]).' - '.strtoupper($api_details["api_base_url"]).'`, `Are you sure you want to enable '.$user_details["email"].' '.strtoupper($api_details["api_type"]).' - '.strtoupper($api_details["api_base_url"]).'`);" style="text-decoration: underline; color: green;" class="a-cursor m-font-size-11 s-font-size-11"><sup>[Enable]</sup></span>'. "<br/>";
                                    }
                                }
                                $vendor_api_lists = $vendor_each_api;
                            }else{
                                $vendor_api_lists = "No API";
                            }
                            
                            $vendor_api_lists = '<details ><summary class="color-4 outline-none">VIEW API LIST</summary>'.$vendor_api_lists.'</details>';
                            
                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$user_details["firstname"]." ".$user_details["lastname"].checkIfEmpty(ucwords($user_details["othername"]),", ", "").'</td><td style="user-select: auto;">'.$username_with_link.'</td><td>'.$website_url_href.'</td><td class="m-width-30 s-width-20">'.$vendor_api_lists.'</td><td>'.toDecimal($user_details["balance"], 2).'</td><td>'.$user_details["phone_number"].'</td><td>'.$user_details["home_address"].'</td><td>'.$user_bvn.'</td><td>'.$user_nin.'</td><td>'.formDate($user_details["reg_date"]).'</td><td>'.formDate($user_details["last_login"]).'</td><td class="m-width-15 s-width-15">'.$all_user_account_action.'</td>
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
                        <th>S/N</th><th>Fullname</th><th>Vendor ID</th><th>URL</th><th>Balance</th><th>Phone number</th><th>Address</th><th>Reg Date</th><th>Last Login</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_inactive_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_inactive_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $activate_user_account = '<span onclick="updateVendorAccountStatus(`1`,`'.$user_details["id"].'`,`'.$user_details["email"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Re-activate Account" src="'.$web_http_host.'/asset/fa-approve.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $delete_user_account = '<span onclick="updateVendorAccountStatus(`3`,`'.$user_details["id"].'`,`'.$user_details["email"].'`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Delete Account" src="'.$web_http_host.'/asset/fa-delete.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $login_user_account = '<span onclick="loginVendorAccount(`'.$user_details["id"].'`, `'.$user_details["email"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Login Account" src="'.$web_http_host.'/asset/fa-login.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $all_user_account_action = $activate_user_account." ".$delete_user_account." ".$login_user_account;

                            $username_with_link = ucwords($user_details["email"]).' <span onclick="customJsRedirect(`/bc-spadmin/VendorEdit.php?vendorID='.$user_details["id"].'`, `Are you sure you want to edit '.strtoupper($user_details["email"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $website_url_href = '<a title="Visit Website" style="text-decoration: underline; color: blue;" class="a-cursor text-bold-400" href="//'.$user_details["website_url"].'/bc-admin" target="_blank">'.$user_details["website_url"].'</a>';
                            
                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$user_details["firstname"]." ".$user_details["lastname"].checkIfEmpty(ucwords($user_details["othername"]),", ", "").'</td><td style="user-select: auto;">'.$username_with_link.'</td><td>'.$website_url_href.'</td><td>'.toDecimal($user_details["balance"], 2).'</td><td>'.$user_details["phone_number"].'</td><td>'.$user_details["home_address"].'</td><td>'.formDate($user_details["reg_date"]).'</td><td>'.formDate($user_details["last_login"]).'</td><td class="m-width-15 s-width-10">'.$all_user_account_action.'</td>
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
                        <th>S/N</th><th>Fullname</th><th>Vendordi ID</th><th>URL</th><th>Balance</th><th>Phone number</th><th>Address</th><th>Reg Date</th><th>Last Login</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_deleted_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_deleted_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $activate_user_account = '<span onclick="updateVendorAccountStatus(`1`,`'.$user_details["id"].'`,`'.$user_details["email"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Re-activate Account" src="'.$web_http_host.'/asset/fa-approve.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $login_user_account = '<span onclick="loginVendorAccount(`'.$user_details["id"].'`, `'.$user_details["email"].'`);" style="text-decoration: underline; color: orange;" class="a-cursor"><img title="Login Account" src="'.$web_http_host.'/asset/fa-login.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $all_user_account_action = $activate_user_account." ".$login_user_account;

                            $username_with_link = ucwords($user_details["email"]).' <span onclick="customJsRedirect(`/bc-spadmin/VendorEdit.php?vendorID='.$user_details["id"].'`, `Are you sure you want to edit '.strtoupper($user_details["email"]).' account`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            $website_url_href = '<a title="Visit Website" style="text-decoration: underline; color: blue;" class="a-cursor text-bold-400" href="//'.$user_details["website_url"].'/bc-admin" target="_blank">'.$user_details["website_url"].'</a>';
                            
                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$user_details["firstname"]." ".$user_details["lastname"].checkIfEmpty(ucwords($user_details["othername"]),", ", "").'</td><td style="user-select: auto;">'.$username_with_link.'</td><td>'.$website_url_href.'</td><td>'.toDecimal($user_details["balance"], 2).'</td><td>'.$user_details["phone_number"].'</td><td>'.$user_details["home_address"].'</td><td>'.formDate($user_details["reg_date"]).'</td><td>'.formDate($user_details["last_login"]).'</td><td class="m-width-15 s-width-10">'.$all_user_account_action.'</td>
                            </tr>';
                        }
                    }
                    ?>
                </table>
            </div><br/>

            <div class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
                <?php if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)){ ?>
                <a href="Vendors.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
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
                <a href="Vendors.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
                </a>
            </div>
        </div>

    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>