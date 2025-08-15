<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_POST["create-profile"])){
        $first = mysqli_real_escape_string($connection_server, trim(strip_tags(ucwords($_POST["first"]))));
        $last = mysqli_real_escape_string($connection_server, trim(strip_tags(ucwords($_POST["last"]))));
        $address = mysqli_real_escape_string($connection_server, trim(strip_tags(ucwords($_POST["address"]))));
        $email = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["email"]))));
        $pass = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["pass"])));
        $phone = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["phone"]))));
        $bank_code = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["bank-code"])));
        $account_number = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["account-number"])));
        $bvn = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["bvn"]))));
        $nin = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["nin"]))));
        
        $unrefined_website_url = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["website-url"]))));
        $refined_website_url = trim(str_replace(["https","http",":/","/","www."," "],"",$unrefined_website_url));
        $website_url = $refined_website_url;
        
        if(!empty($first) && !empty($last) && !empty($address) && !empty($email) && !empty($pass) && !empty($phone) && !empty($website_url)){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $check_vendor_details_with_email = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE email='$email'");
                if(mysqli_num_rows($check_vendor_details_with_email) == 0){
                    $check_vendor_details_with_url = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE website_url='$website_url'");
                    if(mysqli_num_rows($check_vendor_details_with_url) == 0){
                        $md5_pass = md5($pass);
                        if(!empty($bank_code) && is_numeric($bank_code) && (strlen($bank_code) >= 1)){
                    		$refined_bank_code = $bank_code;
                    	}else{
                    		$refined_bank_code = "";
                    	}
                    	
                    	if(!empty($account_number) && is_numeric($account_number) && (strlen($account_number) == 10)){
                    		$refined_account_number = $account_number;
                    	}else{
                    		$refined_account_number = "";
                    	}

                        if(!empty($bvn) && is_numeric($bvn) && (strlen($bvn) == 11)){
                        	$refined_bvn = $bvn;
                        }else{
                        	$refined_bvn = "";
                        }
                        
                        if(!empty($nin) && is_numeric($nin) && (strlen($nin) == 11)){
                        	$refined_nin = $nin;
                        }else{
                        	$refined_nin = "";
                        }
                        
                        mysqli_query($connection_server, "INSERT INTO sas_vendors (website_url, email, password, firstname, lastname, phone_number, balance, home_address, bank_code, account_number, bvn, nin, status) VALUES ('$website_url', '$email', '$md5_pass', '$first', '$last', '$phone', '0', '$address', '$refined_bank_code', '$refined_account_number', '$refined_bvn', '$refined_nin', '1')");
                        // Email Beginning
                        $reg_template_encoded_text_array = array("{firstname}" => $first, "{lastname}" => $last, "{address}" => $address, "{email}" => $email, "{phone}" => $phone, "{website}" => $website_url);
                        $raw_reg_template_subject = getSuperAdminEmailTemplate('vendor-reg','subject');
                        $raw_reg_template_body = getSuperAdminEmailTemplate('vendor-reg','body');
                        foreach($reg_template_encoded_text_array as $array_key => $array_val){
                        	$raw_reg_template_subject = str_replace($array_key, $array_val, $raw_reg_template_subject);
                        	$raw_reg_template_body = str_replace($array_key, $array_val, $raw_reg_template_body);
                        }
                        sendVendorEmail($email, $raw_reg_template_subject, $raw_reg_template_body);
                        // Email End
                        //Vendor Profile Information Created Successfully
                        $json_response_array = array("desc" => "Vendor Profile Information Created Successfully");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(mysqli_num_rows($check_vendor_details_with_url) == 1){
                            //Vendor With Same Website Url Exists
                            $json_response_array = array("desc" => "Vendor With Same Website Url Exists");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            if(mysqli_num_rows($check_vendor_details_with_url) > 1){
                                //Duplicated Website Url Details, Contact Admin
                                $json_response_array = array("desc" => "Duplicated Website Url  Details, Contact Admin");
                                $json_response_encode = json_encode($json_response_array,true);
                            }
                        }
                    }
                }else{
                    if(mysqli_num_rows($check_vendor_details_with_email) == 1){
                        //Vendor With Same Email Exists
                        $json_response_array = array("desc" => "Vendor With Same Email Exists");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(mysqli_num_rows($check_vendor_details_with_email) > 1){
                            //Duplicated Vendors Email Details, Contact Admin
                            $json_response_array = array("desc" => "Duplicated Vendors Email Details, Contact Admin");
                            $json_response_encode = json_encode($json_response_array,true);
                        }
                    }
                }
            }else{
                //Invalid Email
                $json_response_array = array("desc" => "Invalid Email");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            if(empty($first)){
                //Firstname Field Empty
                $json_response_array = array("desc" => "Firstname Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(empty($last)){
                    //Lastname Field Empty
                    $json_response_array = array("desc" => "Lastname Field Empty");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(empty($address)){
                        //Home Address Field Empty
                        $json_response_array = array("desc" => "Home Address Field Empty");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(empty($email)){
                            //Email Field Empty
                            $json_response_array = array("desc" => "Email Field Empty");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            if(empty($pass)){
                                //Password Field Empty
                                $json_response_array = array("desc" => "Password Field Empty");
                                $json_response_encode = json_encode($json_response_array,true);
                            }else{
                                if(empty($phone)){
                                    //Phone Number Field Empty
                                    $json_response_array = array("desc" => "Phone Number Field Empty");
                                    $json_response_encode = json_encode($json_response_array,true);
                                }else{
                                    if(empty($website_url)){
                                        //Website Url Field Empty
                                        $json_response_array = array("desc" => "Website Url Field Empty");
                                        $json_response_encode = json_encode($json_response_array,true);
                                    }
                                }
                            }
                        }
                    }
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
</head>
<body>
    <?php include("../func/bc-spadmin-header.php"); ?>    
    <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR REGISTRATION</span><br>
        <form method="post" enctype="multipart/form-data" action="">
            <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-20 s-width-15">
                <img src="<?php echo $web_http_host; ?>/asset/user-icon.png" class="a-cursor m-width-100 s-width-100" style="pointer-events: none; user-select: auto;"/>
            </div><br/>
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="user-status-span" class="a-cursor" style="user-select: auto;">PERSONAL INFORMATION</span>
            </div><br/>
            <input style="text-align: center;" name="first" type="text" value="" placeholder="Firstname" pattern="[a-zA-Z ]{3,}" title="Firstname must be atleast 3 letters long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            <input style="text-align: center;" name="last" type="text" value="" placeholder="Lastname" pattern="[a-zA-Z ]{3,}" title="Lastname must be atleast 3 letters long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            <input style="text-align: center;" name="address" type="text" value="" placeholder="Home Address" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>

            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="user-status-span" class="a-cursor" style="user-select: auto;">CONTACT INFORMATION</span>
            </div><br/>
            <input style="text-align: center;" name="email" type="email" value="" placeholder="Email" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            <input style="text-align: center;" name="phone" type="text" value="" placeholder="Phone Number" pattern="[0-9]{11}" title="Phone number must be 11 digit long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="user-status-span" class="a-cursor" style="user-select: auto;">WEBSITE URL</span>
            </div><br/>
            <input style="text-align: center;" name="website-url" type="url" value="https://" placeholder="Website Url" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="user-status-span" class="a-cursor" style="user-select: auto;">VENDOR BANK INFORMATION</span>
            </div><br/>
            <select style="text-align: center;" id="" name="bank-code" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                <option value="" default hidden selected>Choose Bank</option>
                <?php

                    //Bank Lists
                    $get_monnify_access_token_2 = json_decode(getSuperAdminMonnifyAccessToken(), true);
                        
                    if($get_monnify_access_token_2["status"] == "success"){
                        $get_monnify_bank_lists = json_decode(getMonnifyBanks($get_monnify_access_token_2["token"]), true);
                        
                        if($get_monnify_bank_lists["status"] == "success"){
                            foreach($get_monnify_bank_lists["banks"] as $bank_json){
                                $decode_bank_json = $bank_json;
                                echo '<option value="'.$decode_bank_json["code"].'">'.$decode_bank_json["name"].'</option>';
                            }
                        }
                    }
                    
                ?>
            </select><br/>
            <input style="text-align: center;" name="account-number" type="text" value="" placeholder="Account Number (optional)" pattern="[0-9]{10}" title="Account number must be 10 digit long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" /><br/>
            
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
            	<span id="user-status-span" class="a-cursor" style="user-select: auto;">VENDOR VERIFICATION</span>
            </div><br/>
            <input style="text-align: center;" name="bvn" type="text" value="" placeholder="BVN (optional)" pattern="[0-9]{11}" title="BVN must be 11 digit long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" /><br/>
            <input style="text-align: center;" name="nin" type="text" value="" placeholder="NIN (optional)" pattern="[0-9]{11}" title="NIN must be 11 digit long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" /><br/>
            
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="user-status-span" class="a-cursor" style="user-select: auto;">PASSWORD</span>
            </div><br/>
            <input style="text-align: center;" name="pass" type="password" value="" placeholder="Password" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            
            <button name="create-profile" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                CREATE PROFILE
            </button><br>
        </form>
    </div><br/>

    <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        <div style="text-align: center;" class="color-10 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="admin-status-span" class="a-cursor" style="user-select: auto;">NB: Contact Admin For Further Assistance!!!</span>
            </div><br/>
        </form>
    </div>
    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>