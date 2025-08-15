<?php session_start();
    include("../func/bc-config.php");

    if(isset($_POST["buy-cable"])){
        $purchase_method = "web";
        $action_function = 1;
		include_once("func/cable.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        unset($_SESSION["iuc_number"]);
        unset($_SESSION["cable_provider"]);
        unset($_SESSION["cable_package"]);
        unset($_SESSION["cable_name"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["verify-cable"])){
        $purchase_method = "web";
        $action_function = 3;
		include_once("func/cable.php");
        $json_response_decode = json_decode($json_response_encode,true);
        if($json_response_decode["status"] == "success"){
            $_SESSION["iuc_number"] = $iuc_no;
            $_SESSION["cable_provider"] = $isp;
            $_SESSION["cable_package"] = $quantity;
            $_SESSION["cable_name"] = $json_response_decode["desc"];
        }

        if($json_response_decode["status"] == "failed"){
            $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        }
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["reset-cable"])){
        unset($_SESSION["iuc_number"]);
        unset($_SESSION["cable_provider"]);
        unset($_SESSION["cable_package"]);
        unset($_SESSION["cable_name"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

?>
<!DOCTYPE html>
<head>
    <title>Cable | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BUY CABLE</span><br>
            <form method="post" action="">
                <?php if(!isset($_SESSION["cable_name"])){ ?>
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <img alt="Startimes" id="startimes-lg" product-status="enabled" src="/asset/startimes.jpg" onclick="tickCableCarrier('startimes');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                    <img alt="DSTV" id="dstv-lg" product-status="enabled" src="/asset/dstv.jpg" onclick="tickCableCarrier('dstv');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="GOTV" id="gotv-lg" product-status="enabled" src="/asset/gotv.jpg" onclick="tickCableCarrier('gotv');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                </div><br/>
                <input id="isprovider" name="isp" type="text" placeholder="Isp" hidden readonly required/>
                <input style="text-align: center;" id="iuc-number" name="iuc-number" onkeyup="tickCableCarrier(); resetCableQuantity();" type="text" value="" placeholder="Decoder IUC No." pattern="[0-9]{10,}" title="Charater must be atleast 10 digit long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <select style="text-align: center;" id="product-amount" name="quantity" onchange="tickCableCarrier();" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
			<option product-category="" value="" default hidden selected>Cable Quantity</option>
                    <?php
                        $account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                        if($account_level_table_name_arrays[$get_logged_user_details["account_level"]] == true){
                            $acc_level_table_name = $account_level_table_name_arrays[$get_logged_user_details["account_level"]];
                            $product_name_array = array("startimes", "dstv", "gotv");
							$cable_type_table_name_arrays = array("startimes"=>"sas_cable_status", "dstv"=>"sas_cable_status", "gotv"=>"sas_cable_status");

							//Startimes
                            $get_startimes_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$cable_type_table_name_arrays[$product_name_array[0]]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_startimes_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_startimes_status_details["api_id"]."' && api_type='cable' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_startimes_lists) == 1){
                                $get_api_enabled_startimes_lists = mysqli_fetch_array($get_api_enabled_startimes_lists);
                                $product_table_startimes_cable = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_startimes_cable["status"] == 1){
					$product_discount_table_startimes_cable = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_startimes_lists["id"]."' && product_id='".$product_table_startimes_cable["id"]."'");
					if(mysqli_num_rows($product_discount_table_startimes_cable) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_startimes_cable)){
						echo '<option product-category="startimes-cable" value="'.$product_details["val_1"].'" hidden>Startimes '.ucwords(trim(str_replace(["-", "_"], " ", $product_details["val_1"]))).' N'.$product_details["val_2"].'</option>';
					}
					}
                                }
                            }

                            //Dstv
                            $get_dstv_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$cable_type_table_name_arrays[$product_name_array[1]]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_dstv_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_dstv_status_details["api_id"]."' && api_type='cable' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_dstv_lists) == 1){
                                $get_api_enabled_dstv_lists = mysqli_fetch_array($get_api_enabled_dstv_lists);
                                $product_table_dstv_cable = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_dstv_cable["status"] == 1){
					$product_discount_table_dstv_cable = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_dstv_lists["id"]."' && product_id='".$product_table_dstv_cable["id"]."'");
					if(mysqli_num_rows($product_discount_table_dstv_cable) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_dstv_cable)){
						echo '<option product-category="dstv-cable" value="'.$product_details["val_1"].'" hidden>Dstv '.ucwords(trim(str_replace(["-", "_"], " ", $product_details["val_1"]))).' N'.$product_details["val_2"].'</option>';
					}
					}
                                }
                            }

                            //Gotv
                            $get_gotv_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$cable_type_table_name_arrays[$product_name_array[2]]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_gotv_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_gotv_status_details["api_id"]."' && api_type='cable' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_gotv_lists) == 1){
                                $get_api_enabled_gotv_lists = mysqli_fetch_array($get_api_enabled_gotv_lists);
                                $product_table_gotv_cable = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_gotv_cable["status"] == 1){
					$product_discount_table_gotv_cable = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_gotv_lists["id"]."' && product_id='".$product_table_gotv_cable["id"]."'");
					if(mysqli_num_rows($product_discount_table_gotv_cable) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_gotv_cable)){
						echo '<option product-category="gotv-cable" value="'.$product_details["val_1"].'" hidden>Gotv '.ucwords(trim(str_replace(["-", "_"], " ", $product_details["val_1"]))).' N'.$product_details["val_2"].'</option>';
					}
					}
                                }
                            }

                        }
                    ?>
                </select><br/>
                <?php }else{ ?>
                <img alt="<?php echo $_SESSION['cable_provider']; ?>" id="<?php echo $_SESSION['cable_provider']; ?>-lg" src="/asset/<?php echo $_SESSION['cable_provider']; ?>.jpg" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-50 s-width-25 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/><br/>
                <div style="text-align: left;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-16 s-font-size-18 m-width-60 s-width-45">
                    <span class="" style="user-select: auto;">Full-Name: <span class="color-8 text-bold-600"><?php echo strtoupper($_SESSION['cable_name']); ?></span></span><br/>
                    <span class="" style="user-select: none">IUC Number: <span class="color-8 text-bold-600"><?php echo $_SESSION['iuc_number']; ?></span></span><br/>
                    <span class="" style="user-select: auto;">Package: <span class="color-8 text-bold-600"><?php echo ucwords(trim(str_replace(["-", "_"], " ", strtoupper($_SESSION['cable_package'])))); ?></span></span><br/>
                </div><br/>
                <?php } ?>
                <?php if(!isset($_SESSION["cable_name"])){ ?>
                <button id="proceedBtn" name="verify-cable" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    VERIFY CABLE
                </button><br>
                <?php }else{ ?>
                <button id="" name="buy-cable" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    BUY CABLE
                </button><br>
                <button id="" name="reset-cable" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    RESET CABLE DETAILS
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