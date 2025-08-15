<?php session_start([
	'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
include("../func/bc-config.php");

$select_user_vendor_status_message = mysqli_query($connection_server, "SELECT * FROM sas_vendor_status_messages WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "'");
if (mysqli_num_rows($select_user_vendor_status_message) == 1) {
	$get_user_vendor_status_message = mysqli_fetch_array($select_user_vendor_status_message);
	if (!isset($_SESSION["product_purchase_response"]) && isset($_SESSION["user_session"])) {
		$user_vendor_status_message_template_encoded_text_array = array("{username}" => $get_logged_user_details["username"]);
		foreach ($user_vendor_status_message_template_encoded_text_array as $array_key => $array_val) {
			$user_vendor_status_message_template_text = str_replace($array_key, $array_val, $get_user_vendor_status_message["message"]);
		}
		$_SESSION["product_purchase_response"] = str_replace("\n", "<br/>", $user_vendor_status_message_template_text);
	}
}
if (isset($_POST["upgrade-user"])) {
	$account_level_upgrade_array = array("smart" => 1, "agent" => 2);
	$purchase_method = "web";
	$purchase_method = strtoupper($purchase_method);
	$purchase_method_array = array("WEB");

	if (in_array($purchase_method, $purchase_method_array)) {
		if ($purchase_method === "WEB") {
			$upgrade_type = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["upgrade-type"]))));
		}

		if ($account_level_upgrade_array[$upgrade_type] == true) {
			if ($account_level_upgrade_array[$upgrade_type] > $get_logged_user_details["account_level"]) {
				$get_upgrade_price = mysqli_query($connection_server, "SELECT * FROM sas_user_upgrade_price WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && account_type='" . $account_level_upgrade_array[$upgrade_type] . "'");
				if (mysqli_num_rows($get_upgrade_price) == 1) {
					$upgrade_price = mysqli_fetch_array($get_upgrade_price);
					if (!empty($upgrade_price["price"]) && is_numeric($upgrade_price["price"]) && ($upgrade_price["price"] > 0)) {
						if (!empty(userBalance(1)) && is_numeric(userBalance(1)) && (userBalance(1) > 0)) {
							$amount = $upgrade_price["price"];
							$discounted_amount = $amount;
							$type_alternative = ucwords("Account Upgrade");
							$reference = substr(str_shuffle("12345678901234567890"), 0, 15);
							$description = ucwords(accountLevel($account_level_upgrade_array[$upgrade_type]) . " Upgrade charges");
							$status = 1;

							$debit_user = chargeUser("debit", accountLevel($account_level_upgrade_array[$upgrade_type]), $type_alternative, $reference, "", $amount, $discounted_amount, $description, $purchase_method, $_SERVER["HTTP_HOST"], $status);
							if ($debit_user === "success") {
								$user_logged_name = $get_logged_user_details["username"];
								$account_upgrade_id = $account_level_upgrade_array[$upgrade_type];
								$alter_user_details = alterUser($user_logged_name, "account_level", $account_upgrade_id);
								if ($alter_user_details == "success") {
									// Email Beginning
									$upgrade_template_encoded_text_array = array("{firstname}" => $get_logged_user_details["firstname"], "{lastname}" => $get_logged_user_details["lastname"], "{account_level}" => $upgrade_type . " level");
									$raw_upgrade_template_subject = getUserEmailTemplate('user-upgrade', 'subject');
									$raw_upgrade_template_body = getUserEmailTemplate('user-upgrade', 'body');
									foreach ($upgrade_template_encoded_text_array as $array_key => $array_val) {
										$raw_upgrade_template_subject = str_replace($array_key, $array_val, $raw_upgrade_template_subject);
										$raw_upgrade_template_body = str_replace($array_key, $array_val, $raw_upgrade_template_body);
									}
									sendVendorEmail($get_logged_user_details["email"], $raw_upgrade_template_subject, $raw_upgrade_template_body);
									// Email End

									//Referral Function
									if (!empty($get_logged_user_details["referral_id"])) {
										$check_user_referral_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && id='" . $get_logged_user_details["referral_id"] . "'");
										if (mysqli_num_rows($check_user_referral_details) == 1) {
											$get_referral_details = mysqli_fetch_array($check_user_referral_details);
											$select_referral_percentage_details = mysqli_query($connection_server, "SELECT * FROM sas_referral_percents WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && account_level='" . $account_level_upgrade_array[$upgrade_type] . "'");
											if (mysqli_num_rows($select_referral_percentage_details) == 1) {
												$reference_3 = substr(str_shuffle("12345678901234567890"), 0, 15);
												$get_referral_percentage = mysqli_fetch_array($select_referral_percentage_details);
												$referral_commission = ($discounted_amount * ($get_referral_percentage["percentage"] / 100));
												$discounted_referral_commission = $referral_commission;
												chargeOtherUser($get_referral_details["username"], "credit", accountLevel($account_level_upgrade_array[$upgrade_type]) . " Referral Commission", "Referral Commission", $reference_3, "", $referral_commission, $discounted_referral_commission, "Referral Upgrade Commission from " . accountLevel($get_logged_user_details["account_level"]) . " to " . accountLevel($account_level_upgrade_array[$upgrade_type]) . " for user: " . $get_logged_user_details["username"], $purchase_method, $_SERVER["HTTP_HOST"], "1");
												// Email Beginning
												$referral_template_encoded_text_array = array("{firstname}" => $get_referral_details["firstname"], "{lastname}" => $get_referral_details["lastname"], "{referral_commission}" => toDecimal($discounted_referral_commission, 2), "{referree}" => $get_logged_user_details["username"], "{account_level}" => $upgrade_type . " level");
												$raw_referral_template_subject = getUserEmailTemplate('user-referral-commission', 'subject');
												$raw_referral_template_body = getUserEmailTemplate('user-referral-commission', 'body');
												foreach ($referral_template_encoded_text_array as $array_key => $array_val) {
													$raw_referral_template_subject = str_replace($array_key, $array_val, $raw_referral_template_subject);
													$raw_referral_template_body = str_replace($array_key, $array_val, $raw_referral_template_body);
												}
												sendVendorEmail($get_referral_details["email"], $raw_referral_template_subject, $raw_referral_template_body);
												// Email End
											}
										}
									}

									//Account Upgraded Successfully
									$json_response_array = array("desc" => "Account Upgraded Successfully");
									$json_response_encode = json_encode($json_response_array, true);
								} else {
									$reference_2 = substr(str_shuffle("12345678901234567890"), 0, 15);
									chargeUser("credit", accountLevel($account_level_upgrade_array[$upgrade_type]), "Refund", $reference_2, "", $amount, $discounted_amount, "Refund for Ref:<i>'$reference'</i>", $purchase_method, $_SERVER["HTTP_HOST"], "1");
									//Upgrade Failed, Contact Admin
									$json_response_array = array("desc" => "Upgrade Failed, Contact Admin");
									$json_response_encode = json_encode($json_response_array, true);
								}
							} else {
								//Insufficient Fund
								$json_response_array = array("desc" => "Insufficient Fund");
								$json_response_encode = json_encode($json_response_array, true);
							}
						} else {
							//Balance is LOW
							$json_response_array = array("desc" => "Balance is LOW");
							$json_response_encode = json_encode($json_response_array, true);
						}
					} else {
						//Pricing Error, Contact Admin
						$json_response_array = array("desc" => "Pricing Error, Contact Admin");
						$json_response_encode = json_encode($json_response_array, true);
					}
				} else {
					//Error: Pricing Not Available, Contact Admin
					$json_response_array = array("desc" => "Error: Pricing Not Available, Contact Admin");
					$json_response_encode = json_encode($json_response_array, true);
				}
			} else {
				//Error: Account Cannot Be Downgraded, Contact Admin
				$json_response_array = array("desc" => "Error: Account Cannot Be Downgraded, Contact Admin");
				$json_response_encode = json_encode($json_response_array, true);
			}
		} else {
			//Invalid Upgrade Type
			$json_response_array = array("desc" => "Invalid Upgrade Type");
			$json_response_encode = json_encode($json_response_array, true);
		}
	}
	$json_response_decode = json_decode($json_response_encode, true);
	$_SESSION["product_purchase_response"] = $json_response_decode["desc"];
	header("Location: " . $_SERVER["REQUEST_URI"]);
}

if ((!empty($select_vendor_table["bank_code"]) && is_numeric($select_vendor_table["bank_code"]) && !empty($select_vendor_table["bvn"]) && is_numeric($select_vendor_table["bvn"]) && strlen($select_vendor_table["bvn"]) == 11) || (!empty($get_logged_user_details["bank_code"]) && is_numeric($get_logged_user_details["bank_code"]) && !empty($get_logged_user_details["nin"]) && is_numeric($get_logged_user_details["nin"]) && strlen($get_logged_user_details["nin"]) == 11)) {
	$virtual_account_vaccount_err = "";
	//User BVN/NIN
	// if((!empty($get_logged_user_details["bvn"]) && is_numeric($get_logged_user_details["bvn"]) && strlen($get_logged_user_details["bvn"]) == 11) && (!empty($get_logged_user_details["nin"]) && is_numeric($get_logged_user_details["nin"]) && strlen($get_logged_user_details["nin"]) == 11)){
	// 	$verification_type = 1;
	// 	$bvn_nin_monnify_account_creation = '"bvn" => $get_logged_user_details["bvn"], "nin" => $get_logged_user_details["nin"]';
	// 	$bvn_nin_payvessel_account_creation = '"bvn" => $get_logged_user_details["bvn"]';
	// }else{
	// 	if((!empty($get_logged_user_details["bvn"]) && is_numeric($get_logged_user_details["bvn"]) && strlen($get_logged_user_details["bvn"]) == 11)){
	// 		$verification_type = 1;
	// 		$bvn_nin_monnify_account_creation = '"bvn" => $get_logged_user_details["bvn"]';
	// 		$bvn_nin_payvessel_account_creation = '"bvn" => $get_logged_user_details["bvn"]';
	// 	}else{
	// 		if((!empty($get_logged_user_details["nin"]) && is_numeric($get_logged_user_details["nin"]) && strlen($get_logged_user_details["nin"]) == 11)){
	// 			$verification_type = 2;
	// 			$bvn_nin_monnify_account_creation = '"nin" => $get_logged_user_details["nin"]';
	// 		}
	// 	}
	// }

	//Admin BVN/NIN
	if ((!empty($select_vendor_table["bvn"]) && is_numeric($select_vendor_table["bvn"]) && strlen($select_vendor_table["bvn"]) == 11) && (!empty($select_vendor_table["nin"]) && is_numeric($select_vendor_table["nin"]) && strlen($select_vendor_table["nin"]) == 11)) {
		$verification_type = 1;
		$select_vendor_table_bvn = $select_vendor_table["bvn"];
		$select_vendor_table_nin = $select_vendor_table["nin"];
	} else {
		if ((!empty($select_vendor_table["bvn"]) && is_numeric($select_vendor_table["bvn"]) && strlen($select_vendor_table["bvn"]) == 11)) {
			$verification_type = 1;
			$select_vendor_table_bvn = $select_vendor_table["bvn"];
		} else {
			if ((!empty($select_vendor_table["nin"]) && is_numeric($select_vendor_table["nin"]) && strlen($select_vendor_table["nin"]) == 11)) {
				$verification_type = 2;
				$select_vendor_table_nin = $select_vendor_table["nin"];
			}
		}
	}

	$registered_virtual_bank_arr = array();
	$virtual_bank_code_arr = array("232", "035", "50515", "120001", "100039", "110072");
	if (is_array(getUserVirtualBank()) == true) {
		foreach (getUserVirtualBank() as $bank_json) {
			$bank_json = json_decode($bank_json, true);
			array_push($registered_virtual_bank_arr, $bank_json["bank_code"]);
		}
	}
	if ((getUserVirtualBank() == false) || ((is_array(getUserVirtualBank()) == true) && (!empty(array_diff($virtual_bank_code_arr, $registered_virtual_bank_arr))))) {
		//Monnify
		$get_monnify_access_token = json_decode(getUserMonnifyAccessToken(), true);
		if ($get_monnify_access_token["status"] == "success") {

			//Check If Monnify Virtual Account Exists
			$user_monnify_account_reference = md5($_SERVER["HTTP_HOST"] . "-" . $get_logged_user_details["vendor_id"] . "-" . $get_logged_user_details["username"]);
			$get_monnify_reserve_account = json_decode(makeMonnifyRequest("get", $get_monnify_access_token["token"], "api/v2/bank-transfer/reserved-accounts/" . $user_monnify_account_reference, ""), true);
			if ($get_monnify_reserve_account["status"] == "success") {
				$monnify_reserve_account_response = json_decode($get_monnify_reserve_account["json_result"], true);
				foreach ($monnify_reserve_account_response["responseBody"]["accounts"] as $monnify_accounts_json) {
					if (in_array($monnify_accounts_json["bankCode"], array("232", "035", "50515", "058"))) {
						$virtual_account_vaccount_err .=
							'<div style="text-align: center;" class="bg-4 box-shadow m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-25 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Name: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . strtoupper($monnify_reserve_account_response["responseBody"]["accountName"]) . '</span><br>
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Bank Name: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . strtoupper(array_filter(explode(" ", $monnify_accounts_json["bankName"]))[0]) . '</span><br>
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Number: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . $monnify_accounts_json["accountNumber"] . ' <span onclick="copyText(`Account number copied successfully`,`' . $monnify_accounts_json["accountNumber"] . '`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy Account Number" src="' . $web_http_host . '/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-8 m-margin-lt-1 s-margin-lt-1" /></span></span>
						</div>';
						addUserVirtualBank($user_monnify_account_reference, $monnify_accounts_json["bankCode"], $monnify_accounts_json["bankName"], $monnify_accounts_json["accountNumber"], $monnify_reserve_account_response["responseBody"]["accountName"]);
					}
				}
			} else {
				$select_monnify_gateway_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_payment_gateways WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && gateway_name='monnify' LIMIT 1"));
				$monnify_create_reserve_account_array = array("accountReference" => $user_monnify_account_reference, "accountName" => $get_logged_user_details["firstname"] . " " . $get_logged_user_details["lastname"] . " " . $get_logged_user_details["othername"], "currencyCode" => "NGN", "contractCode" => $select_monnify_gateway_details["encrypt_key"], "customerEmail" => $get_logged_user_details["email"], "getAllAvailableBanks" => false, "preferredBanks" => ["232", "035", "50515", "058"]);
				if (strlen($select_vendor_table_bvn) === 11) {
					$monnify_create_reserve_account_array["bvn"] = $select_vendor_table_bvn;
				}
				if (strlen($select_vendor_table_nin) === 11) {
					$monnify_create_reserve_account_array["nin"] = $select_vendor_table_nin;
				}
				makeMonnifyRequest("post", $get_monnify_access_token["token"], "api/v2/bank-transfer/reserved-accounts", $monnify_create_reserve_account_array);
				//$virtual_account_vaccount_err .= '<span class="color-4">Virtual Account Created Successfully</span>';
			}
		} else {
			if ($get_monnify_access_token["status"] == "failed") {
				//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_monnify_access_token["message"].'</span>';
				
			}
			
		}


		//Payvessel Admin/User BVN Virtual Account Generation
		if ((!empty($select_vendor_table["bvn"]) && is_numeric($select_vendor_table["bvn"]) && strlen($select_vendor_table["bvn"]) == 11) || (!empty($get_logged_user_details["bvn"]) && is_numeric($get_logged_user_details["bvn"]) && strlen($get_logged_user_details["bvn"]) == 11)) {
			$get_payvessel_access_token = json_decode(getUserPayvesselAccessToken(), true);

			if ($get_payvessel_access_token["status"] == "success") {
				$select_payvessel_gateway_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_payment_gateways WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && gateway_name='payvessel' LIMIT 1"));
				$user_payvessel_account_reference = str_replace([".", "-", ":"], "", $_SERVER["HTTP_HOST"]) . "-" . $get_logged_user_details["username"] . "-" . $get_logged_user_details["email"];
				$payvessel_create_reserve_account_array = array("email" => $user_payvessel_account_reference, "name" => trim($get_logged_user_details["firstname"] . " " . $get_logged_user_details["lastname"] . " " . $get_logged_user_details["othername"]), "phoneNumber" => $get_logged_user_details["phone_number"], "businessid" => $select_payvessel_gateway_details["encrypt_key"], "bankcode" => ["101", "120001"], "account_type" => "STATIC");
				if (strlen($select_vendor_table_bvn) === 11) {
					$payvessel_create_reserve_account_array["bvn"] = $select_vendor_table_bvn;
				}
				if (strlen($select_vendor_table_nin) === 11) {
					$payvessel_create_reserve_account_array["nin"] = $select_vendor_table_nin;
				}
				$get_payvessel_reserve_account = json_decode(makePayvesselRequest("post", $get_payvessel_access_token["token"], "api/external/request/customerReservedAccount/", $payvessel_create_reserve_account_array), true);

				if ($get_payvessel_reserve_account["status"] == "success") {
					$payvessel_reserve_account_response = json_decode($get_payvessel_reserve_account["json_result"], true);

					foreach ($payvessel_reserve_account_response["banks"] as $payvessel_accounts_json) {
						$virtual_account_vaccount_err .=
							'<div style="text-align: center;" class="bg-4 box-shadow m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-25 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Name: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . strtoupper($payvessel_accounts_json["accountName"]) . '</span><br>
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Bank Name: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . $payvessel_accounts_json["bankName"] . '</span><br>
							<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Number: </span>
							<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . $payvessel_accounts_json["accountNumber"] . ' <span onclick="copyText(`Account number copied successfully`,`' . $payvessel_accounts_json["accountNumber"] . '`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy Account Number" src="' . $web_http_host . '/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-8 m-margin-lt-1 s-margin-lt-1" /></span></span>
							
						</div>';
						addUserVirtualBank($payvessel_accounts_json["trackingReference"], $payvessel_accounts_json["bankCode"], $payvessel_accounts_json["bankName"], $payvessel_accounts_json["accountNumber"], $payvessel_accounts_json["accountName"]);
						
					}
					//$virtual_account_vaccount_err .= '<span class="color-4">Virtual Account Created Successfully</span>';
				}

				if ($payvessel_reserve_account_response["status"] == "failed") {
					//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_payvessel_access_token["message"].'</span>';
				}
			} else {
				if ($get_payvessel_access_token["status"] == "failed") {
					//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_payvessel_access_token["message"].'</span>';
				}
			}
		}

		//Beewave Admin/User BVN Virtual Account Generation
		if ((!empty($select_vendor_table["bvn"]) && is_numeric($select_vendor_table["bvn"]) && strlen($select_vendor_table["bvn"]) == 11) || (!empty($get_logged_user_details["bvn"]) && is_numeric($get_logged_user_details["bvn"]) && strlen($get_logged_user_details["bvn"]) == 11)) {
			$select_beewave_gateway_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_payment_gateways WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && gateway_name='beewave' LIMIT 1"));
			$user_beewave_account_reference = str_replace([".", "-", ":"], "", $_SERVER["HTTP_HOST"]) . "-" . $get_logged_user_details["username"] . "-" . $get_logged_user_details["email"];
			$beewave_create_reserve_account_array = array("email" => $user_beewave_account_reference, "name" => trim($get_logged_user_details["firstname"] . " " . $get_logged_user_details["lastname"] . " " . $get_logged_user_details["othername"]), "phone" => $get_logged_user_details["phone_number"], "access_key" => $select_beewave_gateway_details["secret_key"], "bank_code" => ["100039", "110072"]);
			if (strlen($select_vendor_table_bvn) === 11) {
				$beewave_create_reserve_account_array["bvn"] = $select_vendor_table_bvn;
			}
			if (strlen($select_vendor_table_nin) === 11) {
				$beewave_create_reserve_account_array["nin"] = $select_vendor_table_nin;
			}
			$get_beewave_reserve_account = json_decode(makeBeewaveRequest("post", "s", "api/v1/bank-transfer/virtual-account-numbers", $beewave_create_reserve_account_array), true);
			
			if ($get_beewave_reserve_account["status"] == "success") {
				$beewave_reserve_account_response = json_decode($get_beewave_reserve_account["json_result"], true);

				foreach ($beewave_reserve_account_response["virtual_accounts"] as $beewave_accounts_json) {
					$virtual_account_vaccount_err .=
						'<div style="text-align: center;" class="bg-4 box-shadow m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-25 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
                            <span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Name: </span>
                            <span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . strtoupper($beewave_accounts_json["account_name"]) . '</span><br>
                            <span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Bank Name: </span>
                            <span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . $beewave_accounts_json["bank_name"] . '</span><br>
                            <span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Number: </span>
                            <span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . $beewave_accounts_json["account_number"] . ' <span onclick="copyText(`Account number copied successfully`,`' . $beewave_accounts_json["account_number"] . '`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy Account Number" src="' . $web_http_host . '/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-8 m-margin-lt-1 s-margin-lt-1" /></span></span>
                        </div>';
					addUserVirtualBank($beewave_accounts_json["tracking_ref"], $beewave_accounts_json["bank_code"], $beewave_accounts_json["bank_name"], $beewave_accounts_json["account_number"], $beewave_accounts_json["account_name"]);
				}
				//$virtual_account_vaccount_err .= '<span class="color-4">Virtual Account Created Successfully</span>';
			}

			if ($beewave_reserve_account_response["status"] == "failed") {
				//$virtual_account_vaccount_err .= '<span class="color-4">'.$get_beewave_access_token["message"].'</span>';
			}
		}

	} else {
		foreach (getUserVirtualBank() as $monnify_accounts_json) {
			$monnify_accounts_json = json_decode($monnify_accounts_json, true);
			if (in_array($monnify_accounts_json["bank_code"], array("232", "035", "50515", "058", "101", "120001"))) {
				$virtual_account_vaccount_err .=
					'<div style="text-align: center;" class="bg-4 box-shadow m-inline-block-dp s-inline-block-dp br-radius-5px m-width-94 s-width-25 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-1 m-margin-bm-2 s-margin-bm-1">
						<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Name: </span>
						<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . strtoupper($monnify_accounts_json["account_name"]) . '</span><br>
						<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Bank Name: </span>
						<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . strtoupper($monnify_accounts_json["bank_name"]) . '</span><br>
						<span style="user-select: auto;" class="color-2 text-bold-500 m-font-size-15 s-font-size-16">Account Number: </span>
						<span style="user-select: auto;" class="color-8 text-bold-500 m-font-size-20 s-font-size-20">' . $monnify_accounts_json["account_number"] . ' <span onclick="copyText(`Account number copied successfully`,`' . $monnify_accounts_json["account_number"] . '`);" style="text-decoration: underline; color: red;" class="a-cursor"><img title="Copy Account Number" src="' . $web_http_host . '/asset/copy-icon.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-8 m-margin-lt-1 s-margin-lt-1" /></span></span>
					</div>';
			}
		}
	}
} else {
	if (empty($get_logged_user_details["bank_code"])) {
		$virtual_account_vaccount_err .= '<span class="color-4">Incomplete Bank Details, Update Your Bank Details In Account Settings</span><br/>';
	} else {
		if (!is_numeric($get_logged_user_details["bank_code"])) {
			$virtual_account_vaccount_err .= '<span class="color-4">Non-numeric Bank Code</span><br/>';
		} else {
			if (empty($get_logged_user_details["bvn"])) {
				$virtual_account_vaccount_err .= '<span class="color-4">Update BVN if neccessary</span><br/>';
			} else {
				if (!is_numeric($get_logged_user_details["bvn"])) {
					$virtual_account_vaccount_err .= '<span class="color-4">Non-numeric BVN</span><br/>';
				} else {
					if (strlen($get_logged_user_details["bvn"]) !== 11) {
						$virtual_account_vaccount_err .= '<span class="color-4">BVN must be 11 digit long</span><br/>';
					} else {
						if (empty($get_logged_user_details["nin"])) {
							$virtual_account_vaccount_err .= '<span class="color-4">Update NIN if neccessary</span><br/>';
						} else {
							if (!is_numeric($get_logged_user_details["nin"])) {
								$virtual_account_vaccount_err .= '<span class="color-4">Non-numeric NIN</span><br/>';
							} else {
								if (strlen($get_logged_user_details["nin"]) !== 11) {
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
	<title>Dashboard | <?php echo $get_all_site_details["site_title"]; ?></title>
	<meta charset="UTF-8" />
	<meta name="description" content="<?php echo substr($get_all_site_details["site_desc"], 0, 160); ?>" />
	<meta http-equiv="Content-Type" content="text/html; " />
	<meta name="theme-color" content="black" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
	<link rel="stylesheet" href="/cssfile/bc-style.css">
	<meta name="author" content="BeeCodes Titan">
	<meta name="dc.creator" content="BeeCodes Titan">
</head>

<body>
	<?php include("../func/bc-header.php"); ?>
	<div style="user-select: auto;"
		class="bg-3 m-flex-column-dp s-flex-row-dp m-width-100 s-width-100 m-height-30rem s-height-65 m-margin-bm-2 s-margin-bm-2">
		<div style=""
			class="bg-3 m-block-dp s-inline-block-dp m-position-rel s-position-rel m-width-96 s-width-48 m-height-100 s-height-100 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-0">
			<div style=""
				class="color-4 bg-10 br-radius-5px box-shadow m-inline-block-dp s-inline-block-dp m-width-100 s-width-100 m-height-100 s-height-100">
				<div style="text-align: left;"
					class="bg-3 text-bold-300 m-font-size-16 s-font-size-20 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-95 s-width-95 m-height-auto s-height-auto m-position-rel s-position-rel m-margin-tp-2 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
					Welcome,
					<?php echo strtoupper($get_logged_user_details["firstname"] . " " . $get_logged_user_details["lastname"]) . checkIfEmpty(ucwords($get_logged_user_details["othername"]), ", ", ""); ?>
				</div>
				<div style="text-align: left;"
					class="bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px m-block-dp s-block-dp m-scroll-none s-scroll-none m-width-94 s-width-94 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-2 m-padding-bm-1 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
					<span class="m-font-size-14 s-font-size-18">BALANCE: <span
							class="color-10 text-bold-600"><?php echo toDecimal($get_logged_user_details["balance"], "2"); ?></span>,
						ACCOUNT LEVEL: <span
							class="color-10 text-bold-600"><?php echo accountLevel($get_logged_user_details["account_level"]); ?></span></span>
				</div>
				<div style="text-align: left;"
					class="bg-3 m-block-dp s-block-dp m-scroll-none s-scroll-none m-flex-column-dp s-flex-row-dp m-width-97 s-width-96 m-height-31 s-height-31 m-margin-lt-2 s-margin-lt-2 m-padding-lt-0 s-padding-lt-0 m-padding-rt-0 s-padding-rt-0 m-padding-tp-1 s-padding-tp-3 m-padding-bm-1 s-padding-bm-3 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-0">
					<a href="<?php echo $web_http_host; ?>/web/Fund.php" class="">
						<div style="text-align: center;"
							class="a-cursor color-4 bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px box-shadow onhover-bg-color-10 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-flex-row-dp s-flex-row-dp m-width-48 s-width-48 m-height-48 s-height-49 m-margin-rt-1 s-margin-rt-1 m-margin-bm-1 s-margin-bm-1">
							<img src="<?php echo $web_http_host; ?>/asset/pay.png"
								style="pointer-events: none; object-fit: contain; object-position: center;"
								class="bg-10 br-radius-5px box-shadow m-inline-block-dp s-inline-block-dp m-width-10 s-width-10 m-height-100 s-height-50 m-margin-tp-0 s-margin-tp-5 m-margin-bm-1 s-margin-bm-0" />
							<div style="text-align: left;"
								class="bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-inline-block-dp s-inline-block-dp m-width-75 s-width-75 m-height-auto s-height-auto m-margin-tp-0 s-margin-tp-0 m-margin-bm-6 s-margin-bm-0 m-margin-lt-3 s-margin-lt-3">
								FUND WALLET
							</div>
						</div>
					</a>

					<a href="<?php echo $web_http_host; ?>/web/ShareFund.php" class="">
						<div style="text-align: center;"
							class="a-cursor color-4 bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px box-shadow onhover-bg-color-10 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-flex-row-dp s-flex-row-dp m-width-48 s-width-48 m-height-48 s-height-49 m-margin-lt-1 s-margin-lt-1 m-margin-bm-1 s-margin-bm-1">
							<img src="<?php echo $web_http_host; ?>/asset/share-icon.svg"
								style="pointer-events: none; object-fit: contain; object-position: center;"
								class="bg-10 br-radius-5px box-shadow m-inline-block-dp s-inline-block-dp m-width-10 s-width-10 m-height-100 s-height-50 m-margin-tp-0 s-margin-tp-5 m-margin-bm-1 s-margin-bm-0" />
							<div style="text-align: left;"
								class="bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-inline-block-dp s-inline-block-dp m-width-75 s-width-75 m-height-auto s-height-auto m-margin-tp-0 s-margin-tp-0 m-margin-bm-6 s-margin-bm-0 m-margin-lt-3 s-margin-lt-3">
								SHARE FUND
							</div>
						</div>
					</a>

					<a href="<?php echo $web_http_host; ?>/web/SubmitPayment.php" class="">
						<div style="text-align: center;"
							class="a-cursor color-4 bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px box-shadow onhover-bg-color-10 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-flex-row-dp s-flex-row-dp m-width-48 s-width-48 m-height-48 s-height-49 m-margin-rt-1 s-margin-rt-1 m-margin-bm-1 s-margin-bm-1">
							<img src="<?php echo $web_http_host; ?>/asset/cart-icon.png"
								style="pointer-events: none; object-fit: contain; object-position: center;"
								class="bg-10 br-radius-5px box-shadow m-inline-block-dp s-inline-block-dp m-width-10 s-width-10 m-height-100 s-height-50 m-margin-tp-0 s-margin-tp-5 m-margin-bm-1 s-margin-bm-0" />
							<div style="text-align: left;"
								class="bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-inline-block-dp s-inline-block-dp m-width-75 s-width-75 m-height-auto s-height-auto m-margin-tp-0 s-margin-tp-0 m-margin-bm-6 s-margin-bm-0 m-margin-lt-3 s-margin-lt-3">
								SUBMIT PAYMENT
							</div>
						</div>
					</a>

					<a href="<?php echo $web_http_host; ?>/web/Transactions.php" class="">
						<div style="text-align: center;"
							class="a-cursor color-4 bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px box-shadow onhover-bg-color-10 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-flex-row-dp s-flex-row-dp m-width-48 s-width-48 m-height-48 s-height-49 m-margin-lt-1 s-margin-lt-1 m-margin-bm-1 s-margin-bm-1">
							<img src="<?php echo $web_http_host; ?>/asset/transactions.png"
								style="pointer-events: none; object-fit: contain; object-position: center;"
								class="bg-10 br-radius-5px box-shadow m-inline-block-dp s-inline-block-dp m-width-10 s-width-10 m-height-100 s-height-50 m-margin-tp-0 s-margin-tp-5 m-margin-bm-1 s-margin-bm-0" />
							<div style="text-align: left;"
								class="bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-inline-block-dp s-inline-block-dp m-width-75 s-width-75 m-height-auto s-height-auto m-margin-tp-0 s-margin-tp-0 m-margin-bm-6 s-margin-bm-0 m-margin-lt-3 s-margin-lt-3">
								TRANSACTIONS
							</div>
						</div>
					</a>
				</div>
				<div style="text-align: left;"
					class="bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-44 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-3 m-padding-bm-1 s-padding-bm-3 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
					<span class="color-4 m-font-size-14 s-font-size-16">Total Fund:
						<span class="color-10 text-bold-600">
							<?php
							$get_all_user_credit_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && (type_alternative LIKE '%credit%' OR type_alternative LIKE '%received%' OR type_alternative LIKE '%commission%')");
							if (mysqli_num_rows($get_all_user_credit_transaction_details) >= 1) {
								while ($transaction_record = mysqli_fetch_assoc($get_all_user_credit_transaction_details)) {
									$all_user_credit_transaction += $transaction_record["discounted_amount"];
								}
								echo toDecimal($all_user_credit_transaction, 2);
							} else {
								echo toDecimal(0, 2);
							}
							?>
						</span>
					</span>
				</div>
				<div style="text-align: left;"
					class="bg-2 br-color-2 br-style-bm-1 br-width-3 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-44 s-width-44 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-3 m-padding-bm-1 s-padding-bm-3 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
					<span class="color-4 m-font-size-14 s-font-size-16">Total Spent:
						<span class="color-10 text-bold-600">
							<?php
							$get_all_user_debit_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && (type_alternative NOT LIKE '%credit%' && type_alternative NOT LIKE '%refund%' && type_alternative NOT LIKE '%received%' && type_alternative NOT LIKE '%commission%' && status NOT LIKE '%3%')");
							if (mysqli_num_rows($get_all_user_debit_transaction_details) >= 1) {
								while ($transaction_record = mysqli_fetch_assoc($get_all_user_debit_transaction_details)) {
									$all_user_debit_transaction += $transaction_record["discounted_amount"];
								}
								echo toDecimal($all_user_debit_transaction, 2);
							} else {
								echo toDecimal(0, 2);
							}
							?>
						</span>
					</span>
				</div>
				<div style="text-align: left;"
					class="bg-3 br-radius-5px m-block-dp s-block-dp m-scroll-none s-scroll-none m-width-94 s-width-94 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
					<span class="color-4 m-font-size-14 s-font-size-16">Refer and Earn Big, copy referral link <span
							class="a-cursor color-4 text-bold-600 br-radius-5px br-width-3 br-style-bm-5 br-color-10 onhover-bg-color-10 m-font-size-18 s-font-size-20"
							onclick="copyReferLink();" title="Click To Copy">Copy Link</span></span>
				</div>
				<script>
					let ReferLink = '<?php echo $web_http_host . "/web/Register.php?referral=" . $get_logged_user_details["username"]; ?>';
					const copyReferLink = async () => {
						try {
							await navigator.clipboard.writeText(ReferLink);
							alert('Content copied to clipboard');
						} catch (err) {
							alert('Failed to copy: ' + err);
						}
					}
				</script>
			</div>
		</div>
		<div style="text-align: center;"
			class="color-2 bg-3 m-block-dp s-inline-block-dp m-position-rel s-position-rel m-width-96 s-width-48 m-height-100 s-height-100 m-margin-lt-2 s-margin-lt-0 m-margin-rt-0 s-margin-rt-2 m-margin-bm-2 s-margin-bm-0">
			<a href="<?php echo $web_http_host; ?>/web/Airtime.php" class="">
				<div style="text-align: center;"
					class="a-cursor color-2 bg-10 br-radius-5px box-shadow onhover-bg-color-4 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-width-48 s-width-48 m-height-48 s-height-48 m-margin-rt-1 s-margin-rt-1 m-margin-bm-1 s-margin-bm-1">
					<img src="<?php echo $web_http_host; ?>/asset/airtime.svg"
						style="pointer-events: none; object-fit: contain; object-position: center;"
						class="m-width-25 s-width-35 m-height-60 s-height-60 m-margin-tp-6 s-margin-tp-6 m-margin-bm-1 s-margin-bm-1" />
					<div
						class="color-4 bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-block-dp s-inline-block-dp m-width-100 s-width-100">
						BUY AIRTIME
					</div>
				</div>
			</a>

			<a href="<?php echo $web_http_host; ?>/web/Data.php" class="">
				<div style="text-align: center;"
					class="a-cursor color-2 bg-10 br-radius-5px box-shadow onhover-bg-color-4 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-width-48 s-width-48 m-height-48 s-height-48 m-margin-lt-1 s-margin-lt-1 m-margin-bm-1 s-margin-bm-1">
					<img src="<?php echo $web_http_host; ?>/asset/data.png"
						style="pointer-events: none; object-fit: contain; object-position: center;"
						class="m-width-25 s-width-35 m-height-60 s-height-60 m-margin-tp-6 s-margin-tp-6 m-margin-bm-1 s-margin-bm-1" />
					<div
						class="color-4 bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-block-dp s-inline-block-dp m-width-100 s-width-100">
						BUY DATA
					</div>
				</div>
			</a>

			<a href="<?php echo $web_http_host; ?>/web/Cable.php" class="">
				<div style="text-align: center;"
					class="a-cursor color-2 bg-10 br-radius-5px box-shadow onhover-bg-color-4 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-width-48 s-width-48 m-height-48 s-height-48 m-margin-rt-1 s-margin-rt-1 m-margin-tp-1 s-margin-tp-1">
					<img src="<?php echo $web_http_host; ?>/asset/cable.png"
						style="pointer-events: none; object-fit: contain; object-position: center;"
						class="m-width-25 s-width-35 m-height-60 s-height-60 m-margin-tp-6 s-margin-tp-6 m-margin-bm-1 s-margin-bm-1" />
					<div
						class="color-4 bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-block-dp s-inline-block-dp m-width-100 s-width-100">
						BUY CABLE
					</div>
				</div>
			</a>

			<a href="<?php echo $web_http_host; ?>/web/Electric.php" class="">
				<div style="text-align: center;"
					class="a-cursor color-2 bg-10 br-radius-5px box-shadow onhover-bg-color-4 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-scroll-initial s-scroll-initial m-width-48 s-width-48 m-height-48 s-height-48 m-margin-lt-1 s-margin-lt-1 m-margin-tp-1 s-margin-tp-1">
					<img src="<?php echo $web_http_host; ?>/asset/utility.png"
						style="pointer-events: none; object-fit: contain; object-position: center;"
						class="m-width-25 s-width-35 m-height-60 s-height-60 m-margin-tp-6 s-margin-tp-6 m-margin-bm-1 s-margin-bm-1" />
					<div
						class="color-4 bg-3 text-bold-500 m-font-size-12 s-font-size-14 m-scroll-x s-scroll-x m-block-dp s-inline-block-dp m-width-100 s-width-100">
						BUY ELECTRIC
					</div>
				</div>
			</a>
		</div>
	</div>

	<div style="text-align: center;"
		class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25">UPGRADE
			ACCOUNT</span><br>
		<form method="post" action="">
			<select style="text-align: center;" id="" name="upgrade-type" onchange=""
				class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1"
				required />
			<option value="" default hidden selected>Choose Account Level</option>
			<?php
			if (!empty($get_logged_user_details["account_level"])) {
				$account_level_upgrade_array = array(1 => "smart", 2 => "agent");
				foreach ($account_level_upgrade_array as $index => $account_levels) {
					if ($index > $get_logged_user_details["account_level"]) {
						$get_upgrade_price = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_user_upgrade_price WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && account_type='" . $index . "' LIMIT 1"));
						echo '<option value="' . $account_levels . '">' . accountLevel($index) . ' @ N' . toDecimal($get_upgrade_price["price"], 2) . '</option>';
					}
				}
			}
			?>
			</select><br />
			<button id="" name="upgrade-user" type="submit" style="user-select: auto;"
				class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
				Upgrade Account
			</button>
		</form>
	</div>

	<div style="text-align: center;"
		class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel m-scroll-x s-scroll-x br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25">INSTANT FUNDING VIA BANK TRANSFER
			</span><br>
		<?php
		echo $virtual_account_vaccount_err;
		?>
		
	</div> 
	<?php
	// $vpay_access = json_decode(getUserVpayAccessToken());
	// $curl_url = "https://services2.vpay.africa/api/service/v1/query/bank/list/show";
	// $curl_request = curl_init($curl_url);
	// curl_setopt($curl_request, CURLOPT_HTTPGET, true);
	
	// // $post_field_array = array("username" => $vpay_merchant_username, "password" => $vpay_merchant_password);
	// // curl_setopt($curl_request, CURLOPT_POSTFIELDS, json_encode($post_field_array, true));
	// curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
	
	// $header_field_array = array("b-access-token: ".$vpay_access["token"], "publicKey: 9ddfe4b6-3d00-4ace-86d0-393568a306b6", "Content-Type: application/json");
	// curl_setopt($curl_request, CURLOPT_HTTPHEADER, $header_field_array);
	// curl_setopt($curl_request, CURLOPT_TIMEOUT, 60);
	
	// curl_setopt($curl_request, CURLOPT_SSL_VERIFYHOST, false);
	// curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
	// $curl_result = curl_exec($curl_request);
	// $curl_json_result = json_decode($curl_result, true);
	// var_dump($curl_json_result);
	?>
	<?php include("../func/short-trans.php"); ?>
	<?php include("../func/bc-footer.php"); ?>

</body>

</html>