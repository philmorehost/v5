<?php session_start([
    'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
    include("../func/bc-config.php");
    
    if(isset($_POST["buy-airtime"])){
        $purchase_method = "web";
		include_once("func/airtime.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        //echo '<script>alert("'.$json_response_decode["status"].': '.$json_response_decode["desc"].'");</script>';
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

?>
<!DOCTYPE html>
<head>
<title>Airtime | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BUY AIRTIME</span><br>
            <form method="post" action="">
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <img alt="Airtel" id="airtel-lg" product-status="enabled" src="/asset/airtel.png" onclick="tickAirtimeCarrier('airtel');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                    <img alt="MTN" id="mtn-lg" product-status="enabled" src="/asset/mtn.png" onclick="tickAirtimeCarrier('mtn');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="Glo" id="glo-lg" product-status="enabled" src="/asset/glo.png" onclick="tickAirtimeCarrier('glo');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="9mobile" id="9mobile-lg" product-status="enabled" src="/asset/9mobile.png" onclick="tickAirtimeCarrier('9mobile');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                </div><br/>
                <input id="isprovider" name="isp" type="text" placeholder="Isp" hidden readonly required/>
                <input style="text-align: center;" id="phone-number" name="phone-number" onkeyup="tickAirtimeCarrier();" type="text" value="" placeholder="Phone number e.g 08124232128" pattern="[0-9]{11}" title="Charater must be an 11 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <input style="text-align: center;" id="product-amount" name="amount" onkeyup="tickAirtimeCarrier();" type="text" value="" placeholder="Amount e.g 100" pattern="[0-9]{3,}" title="Charater must be atleast 3 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <div style="text-align: left;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                    <input id="phone-bypass" onclick="tickAirtimeCarrier('airtel');" type="checkbox" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none m-width-auto s-width-auto m-margin-bm-1 s-margin-bm-1" />
                    <div class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-80 s-width-80 m-margin-lt-1 s-margin-lt-1">
                        <label for="phone-bypass" class="a-cursor" style="user-select: auto;">
                            Bypass Phone Verification
                        </label>
                    </div>
                </div><br>
                <button id="proceedBtn" name="buy-airtime" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    BUY AIRTIME
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