<?php session_start();
    include("../func/bc-config.php");
        
    if(isset($_POST["share-fund"])){
        $purchase_method = "web";
        $purchase_method = strtoupper($purchase_method);
    	$purchase_method_array = array("WEB");
    	if(in_array($purchase_method, $purchase_method_array)){
        if($purchase_method === "WEB"){
            $user = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["user"]))));
            $amount = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9.]+/","",trim(strip_tags($_POST["amount"]))));
        }

        $discounted_amount = $amount;
        $type_alternative = ucwords("shared fund");
        $reference = substr(str_shuffle("12345678901234567890"), 0, 15);
        $description = "Fund Sharing";
        if(!empty(userBalance(1)) && is_numeric(userBalance(1)) && (userBalance(1) > 0)){
            if(!empty($user) && !empty($amount) && is_numeric($amount)){
                if(userBalance(1) >= $amount){
                	if($get_logged_user_details["username"] !== $user){
						$debit_user = chargeUser("debit", $user, $type_alternative, $reference, "", $amount, $discounted_amount, $description, $purchase_method, $_SERVER["HTTP_HOST"], "1");
                        if($debit_user === "success"){
                            $reference_2 = substr(str_shuffle("12345678901234567890"), 0, 15);
                            $api_response_text = strtolower($api_response_text);
                            $api_response_description = "Shared Fund To User: ".ucwords($user);
                            
                            $create_submitted_share_fund_table = mysqli_query($connection_server, "INSERT INTO sas_fund_transfer_requests (vendor_id, username, recipient_username, reference, amount, discounted_amount, description, mode, api_website, status) VALUES ('".$get_logged_user_details["vendor_id"]."', '".$get_logged_user_details["username"]."', '$user', '$reference', '$amount', '$discounted_amount', '$description', '$purchase_method', '".$_SERVER["HTTP_HOST"]."', '2')");
                            if($create_submitted_share_fund_table == true){
                                alterTransaction($reference, "status", "2");
                                alterTransaction($reference, "description", $api_response_description);
                                alterTransaction($reference, "api_website", $_SERVER["HTTP_HOST"]);
                                //Fund Submitted Successfully
                                $json_response_array = array("desc" => "Fund Submitted Successfully");
                                $json_response_encode = json_encode($json_response_array,true);
                            }else{
                                $reference_3 = substr(str_shuffle("12345678901234567890"), 0, 15);
                                alterTransaction($reference, "description", $api_response_description);
                                chargeUser("credit", $user, "Refund", $reference_3, "", $amount, $discounted_amount, "Refund for Ref:<i>'$reference'</i>", $purchase_method, $_SERVER["HTTP_HOST"], "1");
                                
                                //Request Initiation Failed
                                $json_response_array = array("desc" => "Request Initiation Failed");
                                $json_response_encode = json_encode($json_response_array,true);
                            }
                        }else{
                            //Unable to proceed with charges
                            $json_response_array = array("desc" => "Unable to proceed with charges");
                            $json_response_encode = json_encode($json_response_array,true);
                        }
					}else{
						//Cannot share fund
						$json_response_array = array("desc" => "Cannot share fund");
						$json_response_encode = json_encode($json_response_array,true);
					}
                }else{
                    //Insufficient Wallet Balance
                    $json_response_array = array("desc" => "Insufficient Wallet Balance");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
            	//Incomplete Parameters
            	$json_response_array = array("desc" => "Incomplete Parameters");
            	$json_response_encode = json_encode($json_response_array,true);
            }
        }else{
        	//Balance is LOW
        	$json_response_array = array("desc" => "Balance is LOW");
        	$json_response_encode = json_encode($json_response_array,true);
        }
    }else{
        //Purchase Method Not specified
        $json_response_array = array("desc" => "Purchase Method Not specified");
        $json_response_encode = json_encode($json_response_array,true);
    }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
?>
<!DOCTYPE html>
<head>
    <title>Share Fund | <?php echo $get_all_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
</head>
<body>
	<?php include("../func/bc-header.php"); ?>	
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-1 s-padding-bm-1 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">FUND TRANSFER REQUEST (C2C)</span><br>
            <form method="post" action="">
                <input style="text-align: center;" id="share-fund-user" name="user" onkeyup="confirmUser();" type="text" value="" placeholder="Username" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">Enter User ID</span>
                </div><br/>
                <input style="text-align: center;" id="share-fund-amount" name="amount" onkeyup="confirmUser();" pattern="[0-9]{2, 5}" title="Digit must be around 10 to 99999 naira" value="" placeholder="Amount" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <button id="proceedBtn" name="share-fund" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    SHARE FUND
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

		<?php include("../func/short-fund-transfer-request.php"); ?>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
</html>