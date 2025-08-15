<?php session_start();
    include("../func/bc-spadmin-config.php");
        
    if(isset($_POST["share-fund"])){
        $purchase_method = "web";
        $purchase_method = strtoupper($purchase_method);
        $purchase_method_array = array("WEB");
        if(in_array($purchase_method, $purchase_method_array)){
        if($purchase_method === "WEB"){
            $vendor = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["vendor"]))));
            $type = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["type"]))));
            $amount = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9.]+/","",trim(strip_tags($_POST["amount"]))));
        }

        $discounted_amount = $amount;
        $type_alternative = ucwords("wallet ".$type);
        $reference = substr(str_shuffle("12345678901234567890"), 0, 15);
        $description = ucwords("account ".$type."ed by admin");
        if(in_array($type, array("debit"))){
            $transType = "debit";
        }
        
        if(in_array($type, array("credit","refund"))){
            $transType = "credit";
        }
        $get_logged_vendor_query = mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE email='$vendor' LIMIT 1");
        if(in_array($type, array("debit","credit","refund"))){
            if(mysqli_num_rows($get_logged_vendor_query) == 1){
                $credit_other_vendor = chargeOtherVendor($vendor, $transType, $vendor, ucwords("wallet ".$type), $reference, $amount, $discounted_amount, $description, $_SERVER["HTTP_HOST"], "1");
                if(in_array($credit_other_vendor, array("success"))){
                    $json_response_array = array("reference" => $reference, "status" => "success", "desc" => ucwords($vendor." ".$type."ed with N".$amount." successfully"));
                    $json_response_encode = json_encode($json_response_array,true);
                }
                                                    
                if($credit_other_vendor !== "success"){
                    $json_response_array = array("desc" => "Transaction Failed");
                    $json_response_encode = json_encode($json_response_array,true);
                }       
            }else{
                //Vendor not exists
                $json_response_array = array("desc" => "Vendor not exists");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Invalid Transaction Type
            $json_response_array = array("desc" => "Invalid Transaction Type");
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
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-1 s-padding-bm-1 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">SHARE FUND (A2C)</span><br>
            <form method="post" action="">
                <input style="text-align: center;" id="share-fund-vendor" name="vendor" onkeyup="spAdminConfirmVendor();" type="email" value="" placeholder="Vendor Email" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="vendor-status-span" class="a-cursor" style="user-select: auto;">Enter Vendor ID</span>
                </div><br/>
                <select style="text-align: center;" id="" name="type" onchange="getWebApikey(this);" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" selected hidden default>Choose Transaction Type</option>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                    <option value="refund">Refund</option>
                </select><br/>
                <input style="text-align: center;" id="share-fund-amount" name="amount" onkeyup="spAdminConfirmVendor();" pattern="[0-9]{2, 7}" title="Digit must be around 10 to 1999999 naira" value="" placeholder="Amount" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <button id="proceedBtn" name="share-fund" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    SHARE FUND
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>