<?php session_start();
    include("../func/bc-config.php");
	$get_admin_payment_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_admin_payments WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' LIMIT 1"));
	$get_admin_payment_order_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_admin_payment_orders WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' LIMIT 1"));
	
    if(isset($_POST["submit-payment"])){
        $purchase_method = "web";
        $purchase_method = strtoupper($purchase_method);
    	$purchase_method_array = array("WEB");
    	if(in_array($purchase_method, $purchase_method_array)){
            if($purchase_method === "WEB"){
                $amount = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9.]+/","",trim(strip_tags($_POST["amount"]))));
            }

            $discounted_amount = ($amount - $get_admin_payment_details["amount_charged"]);
            $reference = substr(str_shuffle("12345678901234567890"), 0, 15);
            $description = "Request Sent";
            if(!empty($amount) && is_numeric($amount)){
                if(($amount > $get_admin_payment_details["amount_charged"]) && ($amount > 0) && ($get_admin_payment_details["amount_charged"] == true) && ($get_admin_payment_details["amount_charged"] > 0)){
                    if(isset($get_admin_payment_order_details["min_amount"]) && isset($get_admin_payment_order_details["max_amount"]) && ($amount >= $get_admin_payment_order_details["min_amount"]) && ($amount <= $get_admin_payment_order_details["max_amount"])){
                        $create_submitted_payment_table = mysqli_query($connection_server, "INSERT INTO sas_submitted_payments (vendor_id, username, reference, amount, discounted_amount, description, mode, api_website, status) VALUES ('".$get_logged_user_details["vendor_id"]."', '".$get_logged_user_details["username"]."', '$reference', '$amount', '$discounted_amount', '$description', '$purchase_method', '".$_SERVER["HTTP_HOST"]."', '2')");
                        if($create_submitted_payment_table == true){
                            //Request Sent Successfully
                            $json_response_array = array("desc" => "Request Sent Successfully");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            //Request Initiation Failed
                            $json_response_array = array("desc" => "Request Initiation Failed");
                            $json_response_encode = json_encode($json_response_array,true);
                        }
                    }else{
                        if(!isset($get_admin_payment_order_details["min_amount"])){
                            //Minimum Amount Not Set
                            $json_response_array = array("desc" => "Minimum Amount Not Set");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            if(!isset($get_admin_payment_order_details["max_amount"])){
                                //Maximum Amount Not Set
                                $json_response_array = array("desc" => "Maximum Amount Not Set");
                                $json_response_encode = json_encode($json_response_array,true);
                            }else{
                                if(($amount < $get_admin_payment_order_details["min_amount"])){
                                    //Minimum Amount Is ...
                                    $json_response_array = array("desc" => "USE THE ONLINE AUTO-FUND BANK TRANSFER");
                                    $json_response_encode = json_encode($json_response_array,true);
                                }else{
                                    if(($amount > $get_admin_payment_order_details["max_amount"])){
                                        //Maximum Amount Is ...
                                        $json_response_array = array("desc" => "Maximum Amount Is N".$get_admin_payment_order_details["max_amount"]);
                                        $json_response_encode = json_encode($json_response_array,true);
                                    }
                                }
                            }
                        }
                    }
                }else{
                	//Amount Too LOW
                	$json_response_array = array("desc" => "Amount Too LOW");
                	$json_response_encode = json_encode($json_response_array,true);
                }
            }else{
            	//Incomplete Parameters
            	$json_response_array = array("desc" => "Incomplete Parameters");
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
    <title>Submit Payment | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">SUBMIT PAYMENT ORDER</span><br>
            <form method="post" action="">
                <div style="text-align: left;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-90 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <span id="user-status-span" style="user-select: auto; line-height: 25px;">
                        WE ACCEPT: Bank Transfer, ATM Payment to our Bank.<br/>
                        Send Money to the below Account:<br/>
                        <span class="m-inline-block-dp s-inline-block-dp m-margin-lt-10 s-margin-lt-5">
                            <span class="text-bold-600">Account Number:</span> <span style="user-select: auto;" class="color-10 text-bold-600"><?php echo $get_admin_payment_details["account_number"]; ?></span><br/>
                            <span class="text-bold-600">Account Name:</span> <span class="color-10 text-bold-600"><?php echo $get_admin_payment_details["account_name"]; ?></span><br>
                            <span class="text-bold-600">Bank Name:</span> <span class="color-10 text-bold-600"><?php echo $get_admin_payment_details["bank_name"]; ?></span>
                        </span><br/>
                        Make sure to send payment information like(Bank Account Name[The one use to make Payment], Amount, Order ID[Reference Number]) to <span style="user-select: auto;" class="color-10 text-bold-600"><?php echo $get_admin_payment_details["phone_number"]; ?></span> then your wallet will be fund within 15minutes after Payment Confirmation<br/>
                        <span class="text-bold-600">Minimum Amount:</span> <span class="color-10 text-bold-600">N<?php echo toDecimal($get_admin_payment_order_details["min_amount"], 2); ?></span>, 
                        <span class="text-bold-600">Maximum Amount:</span> <span class="color-10 text-bold-600">N<?php echo toDecimal($get_admin_payment_order_details["max_amount"], 2); ?></span><br/>
                        Note: <span class="color-10 text-bold-600">N<?php echo toDecimal($get_admin_payment_details["amount_charged"], "2"); ?></span> flat rate apply
                    </span>
                </div><br/>
                <input style="text-align: center;" name="amount" onkeyup="submitPayment(this);" pattern="[0-9]{2, }" title="Digit must be around 2 digit upward naira" value="" placeholder="Amount" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                <button id="proceedBtn" name="submit-payment" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-28 s-width-10 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    SUBMIT
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>
        
		<?php include("../func/short-payment-order.php"); ?>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
</html>