<?php session_start();
    include("../func/bc-config.php");
        
    if(isset($_POST["send-sms"])){
        $purchase_method = "web";
		include_once("func/sms.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        //echo '<script>alert("'.$json_response_decode["status"].': '.$json_response_decode["desc"].'");</script>';
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
    
?>
<!DOCTYPE html>
<head>
<title>Bulk SMS | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BULK SMS</span><br>
            <form method="post" action="">
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <img alt="Airtel" id="airtel-lg" product-status="enabled" src="/asset/airtel.png" onclick="tickBulkSMSCarrier('airtel');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                    <img alt="MTN" id="mtn-lg" product-status="enabled" src="/asset/mtn.png" onclick="tickBulkSMSCarrier('mtn');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="Glo" id="glo-lg" product-status="enabled" src="/asset/glo.png" onclick="tickBulkSMSCarrier('glo');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="9mobile" id="9mobile-lg" product-status="enabled" src="/asset/9mobile.png" onclick="tickBulkSMSCarrier('9mobile');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                </div><br/>
                <input id="isprovider" name="isp" type="text" placeholder="Isp" hidden readonly required/>
                <input id="filtered-phone-numbers" name="filtered-phone-numbers" type="text" hidden readonly required/>
                <select style="text-align: center;" onchange="tickBulkSMSCarrier('');" id="" name="sender-id" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                	<option value="" default hidden selected>Sender ID</option>
                    <?php
                        $get_sms_sender_id_lists = mysqli_query($connection_server, "SELECT * FROM sas_bulk_sms_sender_id WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && username='".$get_logged_user_details["username"]."'");
                        if(mysqli_num_rows($get_sms_sender_id_lists) > 0){
                            while($sender_id_details = mysqli_fetch_assoc($get_sms_sender_id_lists)){
                                if($sender_id_details["status"] == 1){
                                    echo '<option value="'.$sender_id_details["sender_id"].'" >'.$sender_id_details["sender_id"].'</option>';
                                }else{
                                    if($sender_id_details["status"] == 2){
                                        echo '<option value="" disabled>'.$sender_id_details["sender_id"].' ( Disabled )</option>';
                                    }
                                }
                            }
                        }
                    ?>
                </select><br/><p>Register Sender ID: <a href="../web/SubmitSenderID.php">CLICK HERE</a></p><br/>
                <textarea style="text-align: center; resize: none;" id="phone-numbers" name="" onkeyup="filterBulkSMSPhoneNumbers();" placeholder="Phone numbers seperated by commas" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-height-5rem s-height-5rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required></textarea><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                    <span id="phone-numbers-span" class="a-cursor" style="user-select: auto;">Phone Number Count: 0</span>
                </div><br/>
                <textarea style="text-align: center; resize: none;" id="text-message" name="text-message" onkeyup="filterBulkSMSMessage(); tickBulkSMSCarrier('');" placeholder="Message here" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-height-5rem s-height-5rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required></textarea><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                    <span id="text-message-span" class="a-cursor" style="user-select: auto;">Word Count: 0/480</span>
                </div><br/>
                <select style="text-align: center;" id="sms-type" name="sms-type" onchange="tickBulkSMSCarrier('');" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Standard SMS</option>

                </select><br/>
                <div style="text-align: left;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                    <div class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-80 s-width-80 m-margin-lt-1 s-margin-lt-1">
                        <label onclick="restructureBulkSMSPhoneNumbers();" for="restructure-phone-numbers" class="a-cursor" style="user-select: auto;">
                            Format Phone Numbers (Click Me)
                        </label>
                    </div>
                </div><br>
                <div style="text-align: left;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                    <input id="phone-bypass" onclick="bypassBulkSMSPhoneNumbers();" checked type="checkbox" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none m-width-auto s-width-auto m-margin-bm-1 s-margin-bm-1" />
                    <div class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-80 s-width-80 m-margin-lt-1 s-margin-lt-1">
                        <label for="phone-bypass" class="a-cursor" style="user-select: auto;">
                            Bypass Phone Verification
                        </label>
                    </div>
                </div><br>
                <button id="proceedBtn" name="send-sms" type="button" style="pointer-events: unone; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    SEND SMS
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

		<?php include("../func/short-trans.php"); ?>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
</html>