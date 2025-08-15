<?php session_start();
    include("../func/bc-admin-config.php");
    
    $user_id_number = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9]+/", "", trim(strip_tags($_GET["userID"]))));
    $select_user = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$user_id_number'");
    if(mysqli_num_rows($select_user) > 0){
        $get_user_details = mysqli_fetch_array($select_user);
    }

    if(isset($_POST["upgrade-level"])){
    	$account_level_upgrade_array = array("smart" => 1, "agent" => 2, "api" => 3);
    	
        $upgrade_type = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["upgrade-type"])));
        
        if(!empty($upgrade_type) && in_array($upgrade_type, array_keys($account_level_upgrade_array))){
            $check_user_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$user_id_number."'");
            if(mysqli_num_rows($check_user_details) == 1){
                $upgrade_signal = false;
                $get_user_info = mysqli_fetch_array($check_user_details);
                
                if($get_user_info["account_level"] == $account_level_upgrade_array[$upgrade_type]){
                    $upgrade_signal = true;
                }else{
                    if($get_user_info["account_level"] > $account_level_upgrade_array[$upgrade_type]){
                        $upgrade_signal = true;
                    }else{
                        if($get_user_info["account_level"] < $account_level_upgrade_array[$upgrade_type]){
                            $get_upgrade_price = mysqli_query($connection_server, "SELECT * FROM sas_user_upgrade_price WHERE vendor_id='".$get_logged_admin_details["id"]."' && account_type='".$account_level_upgrade_array[$upgrade_type]."'");
                            if(mysqli_num_rows($get_upgrade_price) == 1){
                                $upgrade_price = mysqli_fetch_array($get_upgrade_price);
                                if(!empty($upgrade_price["price"]) && is_numeric($upgrade_price["price"]) && ($upgrade_price["price"] > 0)){
                                    $transType = "debit";
                                    $userID = strtolower($get_user_info["username"]);
                                    $purchase_method = "WEB";
                                    $amount = $upgrade_price["price"];
                                    $discounted_amount = $amount;
                                    $type_alternative = ucwords("Account Upgrade");
                                    $reference = substr(str_shuffle("12345678901234567890"), 0, 15);
                                    $description = ucwords(accountLevel($account_level_upgrade_array[$upgrade_type])." Upgrade charges By Admin");
                                    $status = 1;
                                    
                                    $debit_other_user = chargeOtherUser($userID, $transType, ucwords(accountLevel($account_level_upgrade_array[$upgrade_type])), $type_alternative, $reference, "", $amount, $discounted_amount, $description, $purchase_method, $_SERVER["HTTP_HOST"], $status);
                                    if(in_array($debit_other_user, array("success"))){
                                        $upgrade_signal = true;
                                        $json_response_array = array("desc" => ucwords($get_user_info["username"])."`s Account Upgraded Successfully");
                                        $json_response_encode = json_encode($json_response_array,true);
                                    }
                                                                        
                                    if($debit_other_user == "failed"){
                                        $upgrade_signal = false;
                                        $json_response_array = array("desc" => "Upgrade Failed, Insufficient User Fund");
                                        $json_response_encode = json_encode($json_response_array,true);
                                    }		
                                }else{
                                    //Pricing Error, Contact Admin
                                    $json_response_array = array("desc" => "Pricing Error, Contact Admin");
                                    $json_response_encode = json_encode($json_response_array,true);
                                }
                            }else{
                                //Error: Pricing Not Available, Contact Admin
                                $json_response_array = array("desc" => "Error: Pricing Not Available, Contact Admin");
                                $json_response_encode = json_encode($json_response_array,true);
                            }
                        }
                    }
                }

                if($upgrade_signal == true){
                    mysqli_query($connection_server, "UPDATE sas_users SET account_level='".$account_level_upgrade_array[$upgrade_type]."' WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$user_id_number."'");
                    //Account Upgraded To ... Level Successfully
                    $json_response_array = array("desc" => "Account Upgraded To ".accountLevel($account_level_upgrade_array[$upgrade_type])." Successfully");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                if(mysqli_num_rows($check_user_details) == 0){
                    //User Not Exists
                    $json_response_array = array("desc" => "User Not Exists");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(mysqli_num_rows($check_user_details) > 1){
                        //Duplicated Details, Contact Admin
                        $json_response_array = array("desc" => "Duplicated Details, Contact Admin");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
            }
        }else{
            if(empty($upgrade_type)){
                //Upgrade Field Empty
                $json_response_array = array("desc" => "Upgrade Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(!in_array($upgrade_type, array_keys($account_level_upgrade_array))){
                    //Invalid Upgrade Level Code
                    $json_response_array = array("desc" => "Invalid Upgrade Level Code");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }
        }
    
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }    
?>
<!DOCTYPE html>
<head>
    <title>Upgrade User | <?php echo $get_all_super_admin_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_super_admin_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
</head>
<body>
    <?php include("../func/bc-admin-header.php"); ?>    
    <?php if(!empty($get_user_details['id'])){ ?>
    	<div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">USER UPGRADE</span><br>
    		<form method="post" enctype="multipart/form-data" action="">
                <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-20 s-width-15">
                	<img src="<?php echo $web_http_host; ?>/asset/user-icon.png" class="a-cursor m-width-100 s-width-100" style="pointer-events: none; user-select: auto;"/>
                </div><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
            		<span id="user-status-span" class="a-cursor" style="user-select: auto;"><?php echo strtoupper($get_user_details['username']); ?> ACCOUNT UPGRADE</span>
            	</div><br/>
                <select style="text-align: center;" id="" name="upgrade-type" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
					<option value="" default hidden selected>Choose Account Level</option>
					<?php
						$account_level_upgrade_array = array(1 => "smart", 2 => "agent", 3 => "api");
                        foreach($account_level_upgrade_array as $index => $account_levels){
                            $get_upgrade_price = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_user_upgrade_price WHERE vendor_id='".$get_logged_admin_details["id"]."' && account_type='".$index."' LIMIT 1"));
                            if($index == $get_user_details['account_level']){
                                echo '<option value="'.$account_levels.'" selected>'.accountLevel($index).' @ N'.toDecimal($get_upgrade_price["price"], 2).'</option>';
                            }else{
                                echo '<option value="'.$account_levels.'">'.accountLevel($index).' @ N'.toDecimal($get_upgrade_price["price"], 2).'</option>';
                            }
                        }
					?>
				</select><br/>
                
                <button name="upgrade-level" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPGRADE LEVEL
                </button><br>
    		</form>
    	</div><br/>

        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <div style="text-align: center;" class="color-10 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="admin-status-span" class="a-cursor" style="user-select: auto;">NB: Contact Admin For Further Assistance!!!</span>
                </div><br/>
            </form>
        </div>
    <?php }else{ ?>
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">USER INFO</span><br>
    		<img src="<?php echo $web_http_host; ?>/asset/ooops.gif" class="a-cursor m-width-60 s-width-50" style="user-select: auto;"/><br/>
            <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45">
                <span id="user-status-span" class="a-cursor m-font-size-35 s-font-size-45" style="user-select: auto;">Ooops</span><br/>
                <span id="user-status-span" class="a-cursor m-font-size-18 s-font-size-20" style="user-select: auto;">User Account Not Exists</span>
            </div><br/>
        </div>
    <?php } ?>
    <?php include("../func/bc-admin-footer.php"); ?>
    
</body>
</html>