<?php session_start([
    'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
    include("../func/bc-admin-config.php");
    
    $select_vendor_super_admin_status_message = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_status_messages");
    if(mysqli_num_rows($select_vendor_super_admin_status_message) == 1){
    	$get_vendor_super_admin_status_message = mysqli_fetch_array($select_vendor_super_admin_status_message);
    	if(!isset($_SESSION["product_purchase_response"]) && isset($_SESSION["admin_session"])){
    		$vendor_super_admin_status_message_template_encoded_text_array = array("{firstname}" => $get_logged_admin_details["firstname"]);
    		foreach($vendor_super_admin_status_message_template_encoded_text_array as $array_key => $array_val){
    			$vendor_super_admin_status_message_template_text = str_replace($array_key, $array_val, $get_vendor_super_admin_status_message["message"]);
    		}
    		$_SESSION["product_purchase_response"] = str_replace("\n","<br/>",$vendor_super_admin_status_message_template_text);
    	}
    }
    
    if(isset($_POST["pay-bill"])){
        $purchase_method = "web";
        $purchase_method = strtoupper($purchase_method);
        $purchase_method_array = array("WEB");
        
        if(in_array($purchase_method, $purchase_method_array)){
            if($purchase_method === "WEB"){
                $bill_id = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["bill-id"]))));
            }
            
            if(!empty($bill_id)){
                if(is_numeric($bill_id)){
                    $get_bill_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_billings WHERE id='".$bill_id."'");
                    if(mysqli_num_rows($get_bill_details) == 1){
                    	$check_if_bill_is_paid = mysqli_query($connection_server, "SELECT * FROM sas_vendor_paid_bills WHERE vendor_id='".$get_logged_admin_details["id"]."' && bill_id='".$bill_id."'");
                    	if(mysqli_num_rows($check_if_bill_is_paid) == 0){
                        	$bill_amount = mysqli_fetch_array($get_bill_details);
                        	if(!empty($bill_amount["amount"]) && is_numeric($bill_amount["amount"]) && ($bill_amount["amount"] > 0)){
                            	if(!empty(vendorBalance(1)) && is_numeric(vendorBalance(1)) && (vendorBalance(1) > 0)){
                                	$amount = $bill_amount["amount"];
                                	$discounted_amount = $amount;
                                	$type_alternative = ucwords($bill_amount["bill_type"]);
                                	$reference = substr(str_shuffle("12345678901234567890"), 0, 15);
                                	$description = ucwords(checkTextEmpty($bill_amount["description"])." - Bill charges");
                                	$status = 1;
                                
                                	$debit_vendor = chargeVendor("debit", $bill_amount["bill_type"], $type_alternative, $reference, $amount, $discounted_amount, $description, $_SERVER["HTTP_HOST"], $status);
                                	if($debit_vendor === "success"){
                                    	$add_vendor_paid_bill_details = mysqli_query($connection_server, "INSERT INTO sas_vendor_paid_bills (vendor_id, bill_id, bill_type, description, amount, starting_date, ending_date) VALUES ('".$get_logged_admin_details["id"]."', '".$bill_amount["id"]."', '".$bill_amount["bill_type"]."', '".$bill_amount["description"]."', '$amount', '".$bill_amount["starting_date"]."','".$bill_amount["ending_date"]."')");
                                    	if($add_vendor_paid_bill_details == true){
                                        	//Account ... Bill Successfully
                                        	$json_response_array = array("desc" => "Account ".ucwords($bill_amount["bill_type"])." Bill Successfully");
                                        	$json_response_encode = json_encode($json_response_array,true);
                                    	}else{
                                        	$reference_2 = substr(str_shuffle("12345678901234567890"), 0, 15);
                                        	chargeVendor("credit", $bill_amount["bill_type"], "Refund", $reference_2, $amount, $discounted_amount, "Refund for Ref:<i>'$reference'</i>", $_SERVER["HTTP_HOST"], "1");
                                        	//Bill Failed, Contact Admin
                                        	$json_response_array = array("desc" => "Bill Failed, Contact Admin");
                                        	$json_response_encode = json_encode($json_response_array,true);
                                    	}
                                	}else{
                                    	//Insufficient Fund
                                    	$json_response_array = array("desc" => "Insufficient Fund");
                                    	$json_response_encode = json_encode($json_response_array,true);
                                	}
                            	}else{
                                	//Balance is LOW
                                	$json_response_array = array("desc" => "Balance is LOW");
                                	$json_response_encode = json_encode($json_response_array,true);
                            	}
                        	}else{
                            	//Pricing Error, Contact Admin
                            	$json_response_array = array("desc" => "Pricing Error, Contact Admin");
                            	$json_response_encode = json_encode($json_response_array,true);
                        	}
                        }else{
                        	//Bill Has Already Been Paid
                        	$json_response_array = array("desc" => "Bill Has Already Been Paid");
                        	$json_response_encode = json_encode($json_response_array,true);
                        }
                    }else{
                        //Error: Billing Details Not Exists, Contact Admin
                        $json_response_array = array("desc" => "Error: Billing Details Not Exists, Contact Admin");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }else{
                    //Non-numeric Bill ID
                    $json_response_array = array("desc" => "Non-numeric Bill ID");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                //Bill Field Empty
                $json_response_array = array("desc" => "Bill Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
	

	if((!empty($get_logged_admin_details["bank_code"]) && is_numeric($get_logged_admin_details["bank_code"]) && !empty($get_logged_admin_details["bvn"]) && is_numeric($get_logged_admin_details["bvn"]) && strlen($get_logged_admin_details["bvn"]) == 11) || (!empty($get_logged_admin_details["bank_code"]) && is_numeric($get_logged_admin_details["bank_code"]) && !empty($get_logged_admin_details["nin"]) && is_numeric($get_logged_admin_details["nin"]) && strlen($get_logged_admin_details["nin"]) == 11)){
		$virtual_account_vaccount_err = "";
		if((!empty($get_logged_admin_details["bvn"]) && is_numeric($get_logged_admin_details["bvn"]) && strlen($get_logged_admin_details["bvn"]) == 11) && (!empty($get_logged_admin_details["nin"]) && is_numeric($get_logged_admin_details["nin"]) && strlen($get_logged_admin_details["nin"]) == 11)){
			$verification_type = 1;
			$bvn_nin_monnify_account_creation = '"bvn" => $get_logged_admin_details["bvn"], "nin" => $get_logged_admin_details["nin"]';
			$bvn_nin_payvessel_account_creation = '"bvn" => $get_logged_admin_details["bvn"]';
		}else{
			if((!empty($get_logged_admin_details["bvn"]) && is_numeric($get_logged_admin_details["bvn"]) && strlen($get_logged_admin_details["bvn"]) == 11)){
				$verification_type = 1;
				$bvn_nin_monnify_account_creation = '"bvn" => $get_logged_admin_details["bvn"]';
				$bvn_nin_payvessel_account_creation = '"bvn" => $get_logged_admin_details["bvn"]';
			}else{
				if((!empty($get_logged_admin_details["nin"]) && is_numeric($get_logged_admin_details["nin"]) && strlen($get_logged_admin_details["nin"]) == 11)){
					$verification_type = 2;
					$bvn_nin_monnify_account_creation = '"nin" => $get_logged_admin_details["nin"]';
				}
			}
		}
		
		$registered_virtual_bank_arr = array();
		$virtual_bank_code_arr = array("232", "035", "50515", "120001");
		if(is_array(getVendorVirtualBank()) == true){
			foreach(getVendorVirtualBank() as $bank_json){
				$bank_json = json_decode($bank_json, true);
				array_push($registered_virtual_bank_arr, $bank_json["bank_code"]);
			}
		}
		if((getVendorVirtualBank() == false) || ((is_array(getVendorVirtualBank()) == true) && (!empty(array_diff($virtual_bank_code_arr, $registered_virtual_bank_arr))))){
		//Monnify
		$get_monnify_access_token = json_decode(getVendorMonnifyAccessToken(), true);
		if($get_monnify_access_token["status"] == "success"){

			//Check If Monnify Virtual Account Exists
			$admin_monnify_account_reference = md5($_SERVER["HTTP_HOST"]."-".$get_logged_admin_details["id"]."-".$get_logged_admin_details["email"]);
			$get_monnify_reserve_account = json_decode(makeMonnifyRequest("get", $get_monnify_access_token["token"], "api/v2/bank-transfer/reserved-accounts/".$admin_monnify_account_reference, ""), true);
			if($get_monnify_reserve_account["status"] == "success"){
				$monnify_reserve_account_response = json_decode($get_monnify_reserve_account["json_result"], true);
				foreach($monnify_reserve_account_response["responseBody"]["accounts"] as $monnify_accounts_json){
					if(in_array($monnify_accounts_json["bankCode"], array("232", "035", "50515"))){
						$virtual_account_vaccount_err .= 
						'<div style="text-align: center;" class="bg-1 m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-48 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
                            <span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">ACCOUNT NAME: </span>
                            <span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.strtoupper($monnify_reserve_account_response["responseBody"]["accountName"]).'</span><br>
                            <span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">BANK NAME: </span>
                            <span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.strtoupper(array_filter(explode(" ",$monnify_accounts_json["bankName"]))[0]).'</span><br>
                            <span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">ACCOUNT NUMBER: </span>
                            <span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.$monnify_accounts_json["accountNumber"].' <span onclick="copyText(`Account number copied successfully`,`'.$monnify_accounts_json["accountNumber"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy Account Number" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span></span>
                        </div>';
                        addVendorVirtualBank($admin_monnify_account_reference, $monnify_accounts_json["bankCode"], $monnify_accounts_json["bankName"], $monnify_accounts_json["accountNumber"], $monnify_reserve_account_response["responseBody"]["accountName"]);
					}
				}
			}else{
				$select_monnify_gateway_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_super_admin_payment_gateways WHERE gateway_name='monnify' LIMIT 1"));
				$monnify_create_reserve_account_array = array("accountReference" => $admin_monnify_account_reference, "accountName" => $get_logged_admin_details["firstname"]." ".$get_logged_admin_details["lastname"]." ".$get_logged_admin_details["othername"], "currencyCode" => "NGN", "contractCode" => $select_monnify_gateway_details["encrypt_key"], "customerEmail" => $get_logged_admin_details["email"], $bvn_nin_monnify_account_creation, "getAllAvailableBanks" => false, "preferredBanks" => ["232", "035", "50515", "058"]);
				makeMonnifyRequest("post", $get_monnify_access_token["token"], "api/v2/bank-transfer/reserved-accounts", $monnify_create_reserve_account_array);
				//$virtual_account_vaccount_err .= '<span class="color-4">Virtual Account Created Successfully</span>';
			}
		}else{
			if($get_monnify_access_token["status"] == "failed"){
				//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_monnify_access_token["message"].'</span>';
			}
		}
		
		//Payvessel
		if((!empty($get_logged_admin_details["bvn"]) && is_numeric($get_logged_admin_details["bvn"]) && strlen($get_logged_admin_details["bvn"]) == 11)){
		$get_payvessel_access_token = json_decode(getVendorPayvesselAccessToken(), true);
		if($get_payvessel_access_token["status"] == "success"){
			$select_payvessel_gateway_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_super_admin_payment_gateways WHERE gateway_name='payvessel' LIMIT 1"));
			$admin_payvessel_account_reference = str_replace([".","-",":"], "", $_SERVER["HTTP_HOST"])."-".$get_logged_admin_details["id"]."-".$get_logged_admin_details["email"];
			$payvessel_create_reserve_account_array = array("email" => $admin_payvessel_account_reference, "name" => $get_logged_admin_details["firstname"]." ".$get_logged_admin_details["lastname"], "phoneNumber" => $get_logged_admin_details["phone_number"], $bvn_nin_payvessel_account_creation, "businessid" => $select_payvessel_gateway_details["encrypt_key"], "bankcode" => ["101", "120001"], "account_type" => "STATIC");
			$get_payvessel_reserve_account = json_decode(makePayvesselRequest("post", $get_payvessel_access_token["token"], "api/external/request/customerReservedAccount/", $payvessel_create_reserve_account_array), true);
			
			if($get_payvessel_reserve_account["status"] == "success"){
				$payvessel_reserve_account_response = json_decode($get_payvessel_reserve_account["json_result"], true);
				
				foreach($payvessel_reserve_account_response["banks"] as $payvessel_accounts_json){
						$virtual_account_vaccount_err .= 
						'<div style="text-align: center;" class="bg-1 m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-25 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">ACCOUNT NAME: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.strtoupper($payvessel_accounts_json["accountName"]).'</span><br>
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">BANK NAME: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.$payvessel_accounts_json["bankName"].'</span><br>
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">ACCOUNT NUMBER: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.$payvessel_accounts_json["accountNumber"].' <span onclick="copyText(`Account number copied successfully`,`'.$payvessel_accounts_json["accountNumber"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy APIkey" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span></span>
						</div>';
					addVendorVirtualBank($payvessel_accounts_json["trackingReference"], $payvessel_accounts_json["bankCode"], $payvessel_accounts_json["bankName"], $payvessel_accounts_json["accountNumber"], $payvessel_accounts_json["accountName"]);
				}
				//$virtual_account_vaccount_err .= '<span class="color-4">Virtual Account Created Successfully</span>';
			}
			
			if($payvessel_reserve_account_response["status"] == "failed"){
				//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_payvessel_access_token["message"].'</span>';
			}
		}else{
			if($get_payvessel_access_token["status"] == "failed"){
				//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_payvessel_access_token["message"].'</span>';
			}
		}
		}
		}else{
			foreach(getVendorVirtualBank() as $monnify_accounts_json){
				$monnify_accounts_json = json_decode($monnify_accounts_json, true);
				if(in_array($monnify_accounts_json["bank_code"], array("232", "035", "50515", "058", "101", "120001"))){
					$virtual_account_vaccount_err .= 
					'<div style="text-align: center;" class="bg-10 m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-48 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
						<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">ACCOUNT NAME: </span>
						<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.strtoupper($monnify_accounts_json["account_name"]).'</span><br>
						<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">BANK NAME: </span>
						<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.strtoupper(array_filter(explode(" ",$monnify_accounts_json["bank_name"]))[0]).'</span><br>
						<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">ACCOUNT NUMBER: </span>
						<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">'.$monnify_accounts_json["account_number"].' <span onclick="copyText(`Account number copied successfully`,`'.$monnify_accounts_json["account_number"].'`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy Account Number" src="'.$web_http_host.'/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span></span>
					</div>';
				}
			}
		}
	}else{
		if(empty($get_logged_admin_details["bank_code"])){
			$virtual_account_vaccount_err .= '<span class="color-4">Incomplete Bank Details, Update Your Bank Details In Account Settings</span><br/>';
		}else{
			if(!is_numeric($get_logged_admin_details["bank_code"])){
				$virtual_account_vaccount_err .= '<span class="color-4">Non-numeric Bank Code</span><br/>';
			}else{
				if(empty($get_logged_admin_details["bvn"])){
					$virtual_account_vaccount_err .= '<span class="color-4">Update BVN if neccessary</span><br/>';
				}else{
					if(!is_numeric($get_logged_admin_details["bvn"])){
						$virtual_account_vaccount_err .= '<span class="color-4">Non-numeric BVN</span><br/>';
					}else{
						if(strlen($get_logged_admin_details["bvn"]) !== 11){
							$virtual_account_vaccount_err .= '<span class="color-4">BVN must be 11 digit long</span><br/>';
						}else{
							if(empty($get_logged_admin_details["nin"])){
								$virtual_account_vaccount_err .= '<span class="color-4">Update NIN if neccessary</span><br/>';
							}else{
								if(!is_numeric($get_logged_admin_details["nin"])){
									$virtual_account_vaccount_err .= '<span class="color-4">Non-numeric NIN</span><br/>';
								}else{
									if(strlen($get_logged_admin_details["nin"]) !== 11){
										$virtual_account_vaccount_err .= '<span class="color-4">NIN must be 11 digit long</span>';
									}
								}
							}
						}
					}
				}
			}
		}
		
	}
?>
<!DOCTYPE html>
<head>
    <title>Admin Dashboard | <?php echo $get_all_super_admin_site_details["site_title"]; ?></title>
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
        <div style="text-align: center;" class="bg-10 box-shadow m-block-dp s-block-dp m-position-rel s-position-rel m-scroll-x s-scroll-x br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <div style="" class="color-4 bg-10 br-radius-5px m-inline-block-dp s-inline-block-dp m-width-100 s-width-100 m-height-100 s-height-100">
                <div style="text-align: left;" class="color-4 bg-3 text-bold-300 m-font-size-16 s-font-size-20 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-95 s-width-95 m-height-auto s-height-auto m-position-rel s-position-rel m-margin-tp-2 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">Welcome, <?php echo strtoupper($get_logged_admin_details["firstname"]." ".$get_logged_admin_details["lastname"]).checkIfEmpty(ucwords($get_logged_admin_details["othername"]),", ", ""); ?></div>
                <div style="text-align: left;" class="bg-2 br-color-4 br-style-bm-1 br-width-3 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-44 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-0 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <span class="color-4 m-font-size-14 s-font-size-18">BALANCE: <span class="color-10 text-bold-600"><?php echo toDecimal($get_logged_admin_details["balance"], "2"); ?></span> STATUS: <span class="color-10 text-bold-600"><?php echo accountStatus($get_logged_admin_details["status"]); ?></span></span>
                </div>
                <div style="text-align: left;" class="bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-45 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
					<span class="color-4 m-font-size-14 s-font-size-16">Users Manual Funding: 
                        <span class="color-4 text-bold-600">
                            <?php
                                $get_all_user_manual_credit_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='".$get_logged_admin_details["id"]."' && (type_alternative LIKE '%credit%' && description LIKE '%credit%' && description LIKE '%admin%')");
                                if(mysqli_num_rows($get_all_user_manual_credit_transaction_details) >= 1){
                                    while($transaction_record = mysqli_fetch_assoc($get_all_user_manual_credit_transaction_details)){
                                        $all_user_manual_credit_transaction += $transaction_record["discounted_amount"];
                                    }
                                    echo toDecimal($all_user_manual_credit_transaction, 2);
                                }else{
                                    echo toDecimal(0, 2);
                                }
                            ?>
                        </span>
                    </span>
				</div>
				<div style="text-align: left;" class="bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-44 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-0 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <span class="color-4 m-font-size-14 s-font-size-16">Total Fund: 
                        <span class="color-4 text-bold-600">
                            <?php
                                $get_all_admin_credit_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_transactions WHERE vendor_id='".$get_logged_admin_details["id"]."' && (type_alternative LIKE '%credit%' OR type_alternative LIKE '%received%' OR type_alternative LIKE '%commission%')");
                                if(mysqli_num_rows($get_all_admin_credit_transaction_details) >= 1){
                                    while($transaction_record = mysqli_fetch_assoc($get_all_admin_credit_transaction_details)){
                                        $all_admin_credit_transaction += $transaction_record["discounted_amount"];
                                    }
                                    echo toDecimal($all_admin_credit_transaction, 2);
                                }else{
                                    echo toDecimal(0, 2);
                                }
                            ?>
                        </span>
                    </span>
                </div>
                <div style="text-align: left;" class="bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-45 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <span class="color-4 m-font-size-14 s-font-size-16">Total Spent: 
                        <span class="color-4 text-bold-600">
                            <?php
                                $get_all_admin_debit_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_transactions WHERE vendor_id='".$get_logged_admin_details["id"]."' && (type_alternative NOT LIKE '%credit%' && type_alternative NOT LIKE '%refund%' && type_alternative NOT LIKE '%received%' && type_alternative NOT LIKE '%commission%' && status NOT LIKE '%3%')");
                                if(mysqli_num_rows($get_all_admin_debit_transaction_details) >= 1){
                                    while($transaction_record = mysqli_fetch_assoc($get_all_admin_debit_transaction_details)){
                                        $all_admin_debit_transaction += $transaction_record["discounted_amount"];
                                    }
                                    echo toDecimal($all_admin_debit_transaction, 2);
                                }else{
                                    echo toDecimal(0, 2);
                                }
                            ?>
                        </span>
                    </span>
                </div>
                
                
                <div style="text-align: center;" class="bg-1 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-1 s-margin-lt-0 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/add-fund.svg" class="m-width-20" /><br>
                    <span class="color-2 text-bold-500 m-font-size-14 s-font-size-18">Payment Gateway Setup</span><br/>
                    <a title="Payment Gateway" href="<?php echo $web_http_host; ?>/bc-admin/PaymentGateway.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
        
        <div style="text-align: center;" class="bg-1 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-1 s-margin-lt-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/trans-icon.png" class="m-width-20" /><br>
                    <span class="color-2 text-bold-500 m-font-size-14 s-font-size-18">Pay Orders | Transactions</span><br/>
                    <a title="View Payment Order" href="<?php echo $web_http_host; ?>/bc-admin/PaymentOrders.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="m-width-20 s-width-20" />
                                      		
                    </a>
                    <a title="View Transactions" href="<?php echo $web_http_host; ?>/bc-admin/Transactions.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
		
		
		
		<div style="text-align: center;" class="bg-1 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-0 s-margin-lt-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/mail-icon.png" class="m-width-20 s-width-20" /><br>
                    <span class="color-2 text-bold-500 m-font-size-14 s-font-size-18">Mail</span><br/>
                    <a title="Send Mail" href="<?php echo $web_http_host; ?>/bc-admin/SendMail.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="m-width-20 s-width-20" />
                  		
                    </a>
                    <a title="Email Template" href="<?php echo $web_http_host; ?>/bc-admin/EmailTemplates.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="m-width-20 s-width-20" />
                                      		
                    </a>
                </div>        
        
                
                
            </div>
        </div>
		
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        	<span style="admin-select: none;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25">BILL PAYMENT</span><br>
        	<form method="post" action="">
        		<select style="text-align: center;" id="" name="bill-id" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
        			<option value="" default hidden selected>Choose Bill</option>
        			<?php
        				$get_active_billing_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_billings WHERE date >= '".$get_logged_admin_details["reg_date"]."' ORDER BY date DESC");
        				
        				if(mysqli_num_rows($get_active_billing_details) >= 1){
        					while($active_billing = mysqli_fetch_assoc($get_active_billing_details)){
        						$get_paid_bill_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_paid_bills WHERE vendor_id='".$get_logged_admin_details["id"]."' && bill_id='".$active_billing["id"]."'");
        						if(mysqli_num_rows($get_paid_bill_details) == 0){
        							echo '<option value="'.$active_billing["id"].'">'.$active_billing["bill_type"].' @ N'.toDecimal($active_billing["amount"], 2).' (Starts: '.formDateWithoutTime($active_billing["starting_date"]).', Ends: '.formDateWithoutTime($active_billing["ending_date"]).')</option>';
        						}
        					}
        				}
        			?>
        		</select><br/>
        		<button id="" name="pay-bill" type="submit" style="admin-select: none;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
        			Pay Bill
        		</button>
        </form>
        </div>
        
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel m-scroll-x s-scroll-x br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        	<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25">VIRTUAL ACCOUNT</span><br>
        	<?php
        		echo $virtual_account_vaccount_err;
        	?>
        </div>
        
        <?php include("../func/admin-short-trans.php"); ?>
    <?php include("../func/bc-admin-footer.php"); ?>
    
</body>
</html>