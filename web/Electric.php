<?php session_start([
    'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
    include("../func/bc-config.php");
        
    if(isset($_POST["buy-electric"])){
        $purchase_method = "web";
        $action_function = 1;
		include_once("func/electric.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        unset($_SESSION["meter_amount"]);
        unset($_SESSION["meter_number"]);
        unset($_SESSION["meter_provider"]);
        unset($_SESSION["meter_type"]);
        unset($_SESSION["meter_name"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["verify-meter"])){
        $purchase_method = "web";
        $action_function = 3;
		include_once("func/electric.php");
        $json_response_decode = json_decode($json_response_encode,true);
        if($json_response_decode["status"] == "success"){
            $_SESSION["meter_amount"] = $amount;
            $_SESSION["meter_number"] = $meter_number;
            $_SESSION["meter_provider"] = $epp;
            $_SESSION["meter_type"] = $type;
            $_SESSION["meter_name"] = $json_response_decode["desc"];
        }

        if($json_response_decode["status"] == "failed"){
            $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        }
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["reset-electric"])){
        unset($_SESSION["meter_amount"]);
        unset($_SESSION["meter_number"]);
        unset($_SESSION["meter_provider"]);
        unset($_SESSION["meter_type"]);
        unset($_SESSION["meter_name"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
    
?>
<!DOCTYPE html>
<head>
    <title>Utility Bills | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BUY ELECTRIC</span><br>
            <form method="post" action="">
                <?php if(!isset($_SESSION["meter_name"])){ ?>
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <img alt="ekedc" id="ekedc-lg" product-status="enabled" src="/asset/ekedc.jpg" onclick="tickElectricCarrier('ekedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                    <img alt="eedc" id="eedc-lg" product-status="enabled" src="/asset/eedc.jpg" onclick="tickElectricCarrier('eedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="ikedc" id="ikedc-lg" product-status="enabled" src="/asset/ikedc.jpg" onclick="tickElectricCarrier('ikedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="jedc" id="jedc-lg" product-status="enabled" src="/asset/jedc.jpg" onclick="tickElectricCarrier('jedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="kedco" id="kedco-lg" product-status="enabled" src="/asset/kedco.jpg" onclick="tickElectricCarrier('kedco'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="ibedc" id="ibedc-lg" product-status="enabled" src="/asset/ibedc.jpg" onclick="tickElectricCarrier('ibedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="phed" id="phed-lg" product-status="enabled" src="/asset/phed.jpg" onclick="tickElectricCarrier('phed'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="aedc" id="aedc-lg" product-status="enabled" src="/asset/aedc.jpg" onclick="tickElectricCarrier('aedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                	<img alt="yedc" id="yedc-lg" product-status="enabled" src="/asset/yedc.jpg" onclick="tickElectricCarrier('yedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="bedc" id="bedc-lg" product-status="enabled" src="/asset/bedc.jpg" onclick="tickElectricCarrier('bedc'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="aple" id="aple-lg" product-status="enabled" src="/asset/aple.jpg" onclick="tickElectricCarrier('aple'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="kaedco" id="kaedco-lg" product-status="enabled" src="/asset/kaedco.jpg" onclick="tickElectricCarrier('kaedco'); resetElectricQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                </div><br/>
                <input id="electricname" name="epp" type="text" placeholder="electric Name" hidden readonly required/>
                <select style="text-align: center;" id="meter-type" name="type" onchange="pickElectricQty();" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Meter Type</option>
                    <option value="prepaid">Prepaid</option>
                    <option value="postpaid">Postpaid</option>
                </select><br/>
                <input style="text-align: center;" id="meter-number" name="meter-number" onkeyup="pickElectricQty();" type="text" placeholder="Meter Number" pattern="[0-9]{10,}" title="Charater must be atleast 10 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <input style="text-align: center;" id="product-amount" name="amount" onkeyup="pickElectricQty();" type="text" placeholder="Amount" pattern="[0-9]{3,}" title="Charater must be atleast 3 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <?php }else{ ?>
                <img alt="<?php echo $_SESSION['meter_provider']; ?>" id="<?php echo $_SESSION['meter_provider']; ?>-lg" src="/asset/<?php echo $_SESSION['meter_provider']; ?>.jpg" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-50 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/><br/>
                <div style="text-align: left;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-16 s-font-size-18 m-width-60 s-width-45">
                    <span class="" style="user-select: auto;">Full-Name: <span class="color-8 text-bold-600"><?php echo strtoupper($_SESSION['meter_name']); ?></span></span><br/>
                    <span class="" style="user-select: none">Meter Number: <span class="color-8 text-bold-600"><?php echo $_SESSION['meter_number']; ?></span></span><br/>
                    <span class="" style="user-select: auto;">Meter Type: <span class="color-8 text-bold-600"><?php echo strtoupper($_SESSION['meter_type']); ?></span></span><br/>
                    <span class="" style="user-select: auto;">Amount To Pay: <span class="color-8 text-bold-600">N<?php echo $_SESSION['meter_amount']; ?></span></span>
                </div><br/>
                <?php } ?>

                <?php if(!isset($_SESSION["meter_name"])){ ?>
                <button id="proceedBtn" name="verify-meter" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    VERIFY METER
                </button><br>
                <?php }else{ ?>
                <button id="" name="buy-electric" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    BUY ELECTRIC
                </button><br>
                <button id="" name="reset-electric" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    RESET METER DETAILS
                </button><br>
                <?php } ?>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

		<?php include("../func/short-trans.php"); ?>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
</html>