<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_GET["order-ref"])){
    	$status = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["order-status"])));
    	$reference = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["order-ref"])));
    	$statusArray = array(1, 2);
    	if(is_numeric($status)){
    		if(in_array($status, $statusArray)){
    			$select_payment_order = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_submitted_payments WHERE reference='".$reference."'");
    			if(mysqli_num_rows($select_payment_order) == 1){
    				$get_payment_order = mysqli_fetch_array($select_payment_order);
					$get_vendors_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE id='".$get_payment_order["vendor_id"]."'"));
					if(isset($get_vendors_details["id"])){
						$verified_vendors_details = $get_vendors_details;
					}else{
						$verified_vendors_details = "User Not Exists";
					}
                    		
    				if($status == 1){
    					$update_payment_status = mysqli_query($connection_server, "UPDATE sas_super_admin_submitted_payments SET status='3' WHERE reference='".$reference."'");
    					$json_response_array = array("desc" => ucwords($verified_vendors_details["email"]." Order with N".toDecimal($get_payment_order["discounted_amount"],2)." rejected successfully"));
    					$json_response_encode = json_encode($json_response_array,true);
    				}
    				
    				if($status == 2){
						if(in_array($get_payment_order["status"], array("2","3"))){
							$purchase_method = "web";
							$purchase_method = strtoupper($purchase_method);
							$user = $verified_vendors_details["email"];
							$type = "credit";
							$amount = $get_payment_order["amount"];
							$discounted_amount = $get_payment_order["discounted_amount"];
							$type_alternative = ucwords("wallet ".$type);
							$reference_2 = substr(str_shuffle("12345678901234567890"), 0, 15);
							$description = ucwords("account ".$type."ed by admin ( payment order )");
							$transType = $type;

							$credit_other_user = chargeOtherVendor($user, $transType, $user, ucwords("wallet ".$type), $reference_2, $amount, $discounted_amount, $description, $_SERVER["HTTP_HOST"], "1");
							if(in_array($credit_other_user, array("success"))){
								$update_payment_status = mysqli_query($connection_server, "UPDATE sas_super_admin_submitted_payments SET status='1' WHERE reference='".$reference."'");
								$json_response_array = array("desc" => ucwords($verified_vendors_details["email"]." Credited with N".toDecimal($get_payment_order["discounted_amount"],2)." successfully"));
								$json_response_encode = json_encode($json_response_array,true);
							}
							
							if($credit_other_user == "failed"){
								$json_response_array = array("desc" => "Cannot Proceed Processing Transaction");
								$json_response_encode = json_encode($json_response_array,true);
							}		
							
						}else{
							if(in_array($get_payment_order["status"], array("1"))){
								//Order Amount Had Already Been Deposited To User Account
								$json_response_array = array("desc" => "Order Amount Had Already Been Deposited To User Account");
								$json_response_encode = json_encode($json_response_array,true);
							}
						}
    				}
    			}else{
    				if(mysqli_num_rows($select_payment_order) > 1){
    					//Duplicated Orders
    					$json_response_array = array("desc" => "Duplicated Orders");
    					$json_response_encode = json_encode($json_response_array,true);
    				}else{
    					//Order Not Exists
    					$json_response_array = array("desc" => "Order Not Exists");
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
    	header("Location: /bc-spadmin/PaymentOrders.php");
    }
?>
<!DOCTYPE html>
<head>
    <title>Payment Orders</title>
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
        <?php
            
            if(!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
            	$page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
            	$offset_statement = " OFFSET ".((10 * $page_num) - 10);
            }else{
            	$offset_statement = "";
            }
            
            if(isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))){
                $search_statement = " && (reference LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR description LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR amount LIKE '%".trim(strip_tags($_GET["searchq"]))."%')";
                $search_parameter = "searchq=".trim(strip_tags($_GET["searchq"]))."&&";
            }else{
                $search_statement = "";
                $search_parameter = "";
            }
            $get_user_pending_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_submitted_payments WHERE status='2' $search_statement ORDER BY date DESC LIMIT 10 $offset_statement");
            $get_user_successful_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_submitted_payments WHERE status='1' $search_statement ORDER BY date DESC LIMIT 10 $offset_statement");
            $get_user_failed_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_submitted_payments WHERE status='3' $search_statement ORDER BY date DESC LIMIT 10 $offset_statement");
            
        ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">PAYMENT ORDER</span><br>
                <form method="get" action="PaymentOrders.php" class="m-margin-tp-1 s-margin-tp-1">
                    <input style="user-select: auto;" name="searchq" type="text" value="<?php echo trim(strip_tags($_GET["searchq"])); ?>" placeholder="Reference No, Email, amount e.t.c" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                    <button style="user-select: auto;" type="submit" class="button-box a-cursor color-2 bg-4 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                        <img src="<?php echo $web_http_host; ?>/asset/white-search.png" class="m-width-50 s-width-50 m-height-100 s-height-100" />
                    </button>
                </form>
            </div>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-1 s-margin-bm-1">PENDING REQUEST</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                    	<th>S/N</th><th>Reference</th><th>Vendor ID</th><th style="">Description</th><th>Amount</th><th>Amount Paid</th><th>Mode</th><th>Status</th><th>Date</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_user_pending_transaction_details) >= 1){
                    	while($user_transaction = mysqli_fetch_assoc($get_user_pending_transaction_details)){
							$get_vendors_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE id='".$user_transaction["vendor_id"]."'"));
							if(isset($get_vendors_details["id"])){
								$verified_vendors_details = $get_vendors_details;
							}else{
								$verified_vendors_details = "User Not Exists";
							}
                    		$transaction_type = ucwords($user_transaction["type_alternative"]);
                    		$countTransaction += 1;
                    		$reject_payment_order = '<span onclick="superAdminPaymentOrderStatus(`1`,`'.$user_transaction["reference"].'`,`'.$verified_vendors_details["email"].'`);" style="text-decoration: underline; color: red;" class="a-cursor">Reject Payment</span>';
                    		$accept_payment_order = '<span onclick="superAdminPaymentOrderStatus(`2`,`'.$user_transaction["reference"].'`,`'.$verified_vendors_details["email"].'`);" style="text-decoration: underline; color: green;" class="a-cursor">Accept Payment</span>';
                    		$all_payment_order_action = $reject_payment_order." | ".$accept_payment_order;
							
                    		echo 
                    		'<tr>
                    			<td>'.$countTransaction.'</td><td style="user-select: auto;">'.$user_transaction["reference"].'</td><td>'.ucwords($verified_vendors_details["email"]).'</td><td>Request Sent</td><td>'.toDecimal($user_transaction["amount"], 2).'</td><td>'.toDecimal($user_transaction["discounted_amount"], 2).'</td><td>'.$user_transaction["mode"].'</td><td>'.tranStatus($user_transaction["status"]).'</td><td>'.formDate($user_transaction["date"]).'</td><td>'.$all_payment_order_action.'</td>
                    		</tr>';
                    	}
                    }
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">SUCCESSFUL REQUEST</span><br>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                    	<th>S/N</th><th>Reference</th><th>Vendor ID</th><th style="">Description</th><th>Amount</th><th>Amount Paid</th><th>Mode</th><th>Status</th><th>Date</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_user_successful_transaction_details) >= 1){
                    	while($user_transaction = mysqli_fetch_assoc($get_user_successful_transaction_details)){
                    		$get_vendors_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE id='".$user_transaction["vendor_id"]."'"));
							if(isset($get_vendors_details["id"])){
								$verified_vendors_details = $get_vendors_details;
							}else{
								$verified_vendors_details = "User Not Exists";
							}
							$transaction_type = ucwords($user_transaction["type_alternative"]);
                    		$countTransaction += 1;
                    		$all_payment_order_action = "Successful";
							
                    		echo 
                    		'<tr>
                    			<td>'.$countTransaction.'</td><td style="user-select: auto;">'.$user_transaction["reference"].'</td><td>'.ucwords($verified_vendors_details["email"]).'</td><td>Request Sent</td><td>'.toDecimal($user_transaction["amount"], 2).'</td><td>'.toDecimal($user_transaction["discounted_amount"], 2).'</td><td>'.$user_transaction["mode"].'</td><td>'.tranStatus($user_transaction["status"]).'</td><td>'.formDate($user_transaction["date"]).'</td><td>'.$all_payment_order_action.'</td>
                    		</tr>';
                    	}
                    }
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">REJECTED REQUEST</span><br>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                    	<th>S/N</th><th>Reference</th><th>Vendor ID</th><th style="">Description</th><th>Amount</th><th>Amount Paid</th><th>Mode</th><th>Status</th><th>Date</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_user_failed_transaction_details) >= 1){
                    	while($user_transaction = mysqli_fetch_assoc($get_user_failed_transaction_details)){
							$get_vendors_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE id='".$user_transaction["vendor_id"]."'"));
							if(isset($get_vendors_details["id"])){
								$verified_vendors_details = $get_vendors_details;
							}else{
								$verified_vendors_details = "User Not Exists";
							}
                    		$transaction_type = ucwords($user_transaction["type_alternative"]);
                    		$countTransaction += 1;
                    		$accept_payment_order = '<span onclick="superAdminPaymentOrderStatus(`2`,`'.$user_transaction["reference"].'`,`'.$verified_vendors_details["email"].'`);" style="text-decoration: underline; color: green;" class="a-cursor">Accept Payment</span>';
                    		$all_payment_order_action = $accept_payment_order;
							
                    		echo 
                    		'<tr>
                    			<td>'.$countTransaction.'</td><td style="user-select: auto;">'.$user_transaction["reference"].'</td><td>'.ucwords($verified_vendors_details["email"]).'</td><td>Request Sent</td><td>'.toDecimal($user_transaction["amount"], 2).'</td><td>'.toDecimal($user_transaction["discounted_amount"], 2).'</td><td>'.$user_transaction["mode"].'</td><td>'.tranStatus($user_transaction["status"]).'</td><td>'.formDate($user_transaction["date"]).'</td><td>'.$all_payment_order_action.'</td>
                    		</tr>';
                    	}
                    }
                    ?>
                </table>
            </div><br/>

            <div class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
                <?php if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)){ ?>
                <a href="PaymentOrders.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
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
                <a href="PaymentOrders.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
                </a>
            </div>
        </div>

		
	<?php include("../func/bc-spadmin-footer.php"); ?>
	
</body>
</html>