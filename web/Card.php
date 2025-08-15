<?php session_start();
    include("../func/bc-config.php");
        
    if(isset($_POST["buy-card"])){
        $purchase_method = "web";
        include_once("func/card.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        //echo '<script>alert("'.$json_response_decode["status"].': '.$json_response_decode["desc"].'");</script>';
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
    
?>
<!DOCTYPE html>
<head>
    <title>Card Printing | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BUY CARD</span><br>
            <form method="post" action="">
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <img alt="Airtel" id="airtel-lg" product-status="enabled" src="/asset/airtel.png" onclick="tickDataRechargeCarrier('airtel'); resetDataQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                    <img alt="MTN" id="mtn-lg" product-status="enabled" src="/asset/mtn.png" onclick="tickDataRechargeCarrier('mtn'); resetDataQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="Glo" id="glo-lg" product-status="enabled" src="/asset/glo.png" onclick="tickDataRechargeCarrier('glo'); resetDataQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="9mobile" id="9mobile-lg" product-status="enabled" src="/asset/9mobile.png" onclick="tickDataRechargeCarrier('9mobile'); resetDataQuantity();" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                </div><br/>
                <input id="isprovider" name="isp" type="text" placeholder="Isp" hidden readonly required/>
                <select style="text-align: center;" id="internet-data-type" name="type" onchange="tickDataRechargeCarrier(); resetDataQuantity();" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Card Type</option>
                    <option value="datacard">Data Card</option>
                    <option value="rechargecard">Recharge Card</option>
                </select><br/>
                <select style="text-align: center;" id="product-amount" name="quantity" onchange="tickDataRechargeCarrier(); tickDataRechargeCarrier();" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option product-category="" value="" default hidden selected>Card Quantity</option>
                    <?php
                        $account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                        if($account_level_table_name_arrays[$get_logged_user_details["account_level"]] == true){
                            $acc_level_table_name = $account_level_table_name_arrays[$get_logged_user_details["account_level"]];
                            $product_name_array = array("mtn", "airtel", "glo", "9mobile");
                            $data_type_table_name_arrays = array("datacard"=>"sas_datacard_status", "rechargecard"=>"sas_rechargecard_status", "dd-data"=>"sas_dd_data_status");
                            
                            //MTN DATACARD
                            $get_mtn_datacard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["datacard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_datacard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_mtn_datacard_status_details["api_id"]."' && api_type='datacard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_datacard_lists) == 1){
                                $get_api_enabled_datacard_lists = mysqli_fetch_array($get_api_enabled_datacard_lists);
                                $product_table_mtn_datacard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_mtn_datacard["status"] == 1){
                                    $product_discount_table_mtn_datacard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_datacard_lists["id"]."' && product_id='".$product_table_mtn_datacard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_mtn_datacard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_mtn_datacard)){
                                            echo '<option product-category="mtn-datacard" value="'.$product_details["val_1"].'" hidden>MTN DATACARD '.$product_details["val_1"].' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                            //MTN RECHARGECARD
                            $get_mtn_rechargecard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["rechargecard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_rechargecard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_mtn_rechargecard_status_details["api_id"]."' && api_type='rechargecard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_rechargecard_lists) == 1){
                                $get_api_enabled_rechargecard_lists = mysqli_fetch_array($get_api_enabled_rechargecard_lists);
                                $product_table_mtn_rechargecard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_mtn_rechargecard["status"] == 1){
                                    $product_discount_table_mtn_rechargecard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_rechargecard_lists["id"]."' && product_id='".$product_table_mtn_rechargecard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_mtn_rechargecard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_mtn_rechargecard)){
                                            echo '<option product-category="mtn-rechargecard" value="'.$product_details["val_1"].'" hidden>MTN RECHARGECARD N'.$product_details["val_1"].' @ N'.$product_details["val_2"].'</option>';
                                        }
                                    }
                                }
                            }

                            //AIRTEL DATACARD
                            $get_airtel_datacard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["datacard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_datacard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_airtel_datacard_status_details["api_id"]."' && api_type='datacard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_datacard_lists) == 1){
                                $get_api_enabled_datacard_lists = mysqli_fetch_array($get_api_enabled_datacard_lists);
                                $product_table_airtel_datacard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_airtel_datacard["status"] == 1){
                                    $product_discount_table_airtel_datacard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_datacard_lists["id"]."' && product_id='".$product_table_airtel_datacard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_airtel_datacard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_airtel_datacard)){
                                            echo '<option product-category="airtel-datacard" value="'.$product_details["val_1"].'" hidden>AIRTEL DATACARD '.$product_details["val_1"].' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                            //AIRTEL RECHARGECARD
                            $get_airtel_rechargecard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["rechargecard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_rechargecard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_airtel_rechargecard_status_details["api_id"]."' && api_type='rechargecard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_rechargecard_lists) == 1){
                                $get_api_enabled_rechargecard_lists = mysqli_fetch_array($get_api_enabled_rechargecard_lists);
                                $product_table_airtel_rechargecard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_airtel_rechargecard["status"] == 1){
                                    $product_discount_table_airtel_rechargecard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_rechargecard_lists["id"]."' && product_id='".$product_table_airtel_rechargecard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_airtel_rechargecard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_airtel_rechargecard)){
                                            echo '<option product-category="airtel-rechargecard" value="'.$product_details["val_1"].'" hidden>AIRTEL RECHARGECARD N'.$product_details["val_1"].' @ N'.$product_details["val_2"].'</option>';
                                        }
                                    }
                                }
                            }

                            //GLO DATACARD
                            $get_glo_datacard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["datacard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_datacard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_glo_datacard_status_details["api_id"]."' && api_type='datacard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_datacard_lists) == 1){
                                $get_api_enabled_datacard_lists = mysqli_fetch_array($get_api_enabled_datacard_lists);
                                $product_table_glo_datacard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_glo_datacard["status"] == 1){
                                    $product_discount_table_glo_datacard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_datacard_lists["id"]."' && product_id='".$product_table_glo_datacard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_glo_datacard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_glo_datacard)){
                                            echo '<option product-category="glo-datacard" value="'.$product_details["val_1"].'" hidden>GLO DATACARD '.$product_details["val_1"].' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                            //GLO RECHARGECARD
                            $get_glo_rechargecard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["rechargecard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_rechargecard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_glo_rechargecard_status_details["api_id"]."' && api_type='rechargecard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_rechargecard_lists) == 1){
                                $get_api_enabled_rechargecard_lists = mysqli_fetch_array($get_api_enabled_rechargecard_lists);
                                $product_table_glo_rechargecard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_glo_rechargecard["status"] == 1){
                                    $product_discount_table_glo_rechargecard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_rechargecard_lists["id"]."' && product_id='".$product_table_glo_rechargecard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_glo_rechargecard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_glo_rechargecard)){
                                            echo '<option product-category="glo-rechargecard" value="'.$product_details["val_1"].'" hidden>GLO RECHARGECARD N'.$product_details["val_1"].' @ N'.$product_details["val_2"].'</option>';
                                        }
                                    }
                                }
                            }

                            //9MOBILE DATACARD
                            $get_9mobile_datacard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["datacard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."'"));
                            $get_api_enabled_datacard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_9mobile_datacard_status_details["api_id"]."' && api_type='datacard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_datacard_lists) == 1){
                                $get_api_enabled_datacard_lists = mysqli_fetch_array($get_api_enabled_datacard_lists);
                                $product_table_9mobile_datacard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."' LIMIT 1"));
                                if($product_table_9mobile_datacard["status"] == 1){
                                    $product_discount_table_9mobile_datacard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_datacard_lists["id"]."' && product_id='".$product_table_9mobile_datacard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_9mobile_datacard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_9mobile_datacard)){
                                            echo '<option product-category="9mobile-datacard" value="'.$product_details["val_1"].'" hidden>9MOBILE DATACARD '.$product_details["val_1"].' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                            //9MOBILE RECHARGECARD
                            $get_9mobile_rechargecard_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["rechargecard"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."'"));
                            $get_api_enabled_rechargecard_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_9mobile_rechargecard_status_details["api_id"]."' && api_type='rechargecard' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_rechargecard_lists) == 1){
                                $get_api_enabled_rechargecard_lists = mysqli_fetch_array($get_api_enabled_rechargecard_lists);
                                $product_table_9mobile_rechargecard = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."' LIMIT 1"));
                                if($product_table_9mobile_rechargecard["status"] == 1){
                                    $product_discount_table_9mobile_rechargecard = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_rechargecard_lists["id"]."' && product_id='".$product_table_9mobile_rechargecard["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_9mobile_rechargecard) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_9mobile_rechargecard)){
                                            echo '<option product-category="9mobile-rechargecard" value="'.$product_details["val_1"].'" hidden>9MOBILE RECHARGECARD N'.$product_details["val_1"].' @ N'.$product_details["val_2"].'</option>';
                                        }
                                    }
                                }
                            }

                        }
                    ?>
                </select><br/>
                <input style="text-align: center;" id="quantity" name="qty-number" onkeyup="tickDataRechargeCarrier();" type="text" value="" placeholder="Quantity e.g 1" pattern="[0-9]{1,}" title="Charater must be atleast 1 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <input style="text-align: center;" id="" name="card-name" onkeyup="" type="text" value="" placeholder="Card Name" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" /><br/>
                <button id="proceedBtn" name="buy-card" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    BUY CARD
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