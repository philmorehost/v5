<?php session_start([
    'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
    include("../func/bc-config.php");
    //alterTransaction("6176424889","status","2");
    if(isset($_POST["buy-data"])){
        $purchase_method = "web";
		include_once("func/data.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        //echo '<script>alert("'.$json_response_decode["status"].': '.$json_response_decode["desc"].'");</script>';
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

?>
<!DOCTYPE html>
<head>
    <title>Shared Data, SME Data, Direct Data, Corporate Data | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BUY DATA</span><br>
            <form method="post" action="">
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <img alt="Airtel" id="airtel-lg" product-status="enabled" src="/asset/airtel.png" onclick="tickDataCarrier('airtel');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                    <img alt="MTN" id="mtn-lg" product-status="enabled" src="/asset/mtn.png" onclick="tickDataCarrier('mtn');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="Glo" id="glo-lg" product-status="enabled" src="/asset/glo.png" onclick="tickDataCarrier('glo');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                    <img alt="9mobile" id="9mobile-lg" product-status="enabled" src="/asset/9mobile.png" onclick="tickDataCarrier('9mobile');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                </div><br/>
                <input id="isprovider" name="isp" type="text" placeholder="Isp" hidden readonly required/>
                <input style="text-align: center;" id="phone-number" name="phone-number" onkeyup="tickDataCarrier(); resetDataQuantity();" type="text" value="" placeholder="Phone number e.g 08124232128" pattern="[0-9]{11}" title="Charater must be an 11 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <select style="text-align: center;" id="internet-data-type" name="type" onchange="tickDataCarrier(); resetDataQuantity();" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Data Type</option>
			<option value="shared-data">Shared Data</option>
			<option value="sme-data">SME Data</option>
			<option value="cg-data">Corporate Gifting Data</option>
			<option value="dd-data">Direct Data</option>
                </select><br/>
                <select style="text-align: center;" id="product-amount" name="quantity" onchange="tickDataCarrier();" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
			<option product-category="" value="" default hidden selected>Data Quantity</option>
                    <?php
                        $account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                        if($account_level_table_name_arrays[$get_logged_user_details["account_level"]] == true){
                            $acc_level_table_name = $account_level_table_name_arrays[$get_logged_user_details["account_level"]];
                            $product_name_array = array("mtn", "airtel", "glo", "9mobile");
							$data_type_table_name_arrays = array("shared-data"=>"sas_shared_data_status", "sme-data"=>"sas_sme_data_status", "cg-data"=>"sas_cg_data_status", "dd-data"=>"sas_dd_data_status");

                            //MTN SHARED
                            $get_mtn_shared_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["shared-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_shared_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_mtn_shared_status_details["api_id"]."' && api_type='shared-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_shared_data_lists) == 1){
                                $get_api_enabled_shared_data_lists = mysqli_fetch_array($get_api_enabled_shared_data_lists);
                                $product_table_mtn_shared_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_mtn_shared_data["status"] == 1){
                                    $product_discount_table_mtn_shared_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_shared_data_lists["id"]."' && product_id='".$product_table_mtn_shared_data["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_mtn_shared_data) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_mtn_shared_data)){
                                            echo '<option product-category="mtn-shared-data" value="'.$product_details["val_1"].'" hidden>MTN SHARED '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                           //MTN SME
                            $get_mtn_sme_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["sme-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_sme_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_mtn_sme_status_details["api_id"]."' && api_type='sme-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_sme_data_lists) == 1){
                                $get_api_enabled_sme_data_lists = mysqli_fetch_array($get_api_enabled_sme_data_lists);
                                $product_table_mtn_sme_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_mtn_sme_data["status"] == 1){
					$product_discount_table_mtn_sme_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_sme_data_lists["id"]."' && product_id='".$product_table_mtn_sme_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_mtn_sme_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_mtn_sme_data)){
						echo '<option product-category="mtn-sme-data" value="'.$product_details["val_1"].'" hidden>MTN SME '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                      //MTN CG
                            $get_mtn_cg_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["cg-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_cg_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_mtn_cg_status_details["api_id"]."' && api_type='cg-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_cg_data_lists) == 1){
                                $get_api_enabled_cg_data_lists = mysqli_fetch_array($get_api_enabled_cg_data_lists);
                                $product_table_mtn_cg_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_mtn_cg_data["status"] == 1){
					$product_discount_table_mtn_cg_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_cg_data_lists["id"]."' && product_id='".$product_table_mtn_cg_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_mtn_cg_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_mtn_cg_data)){
						echo '<option product-category="mtn-cg-data" value="'.$product_details["val_1"].'" hidden>MTN CG '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //MTN DD
                            $get_mtn_dd_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["dd-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."'"));
                            $get_api_enabled_dd_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_mtn_dd_status_details["api_id"]."' && api_type='dd-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_dd_data_lists) == 1){
                                $get_api_enabled_dd_data_lists = mysqli_fetch_array($get_api_enabled_dd_data_lists);
                                $product_table_mtn_dd_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[0]."' LIMIT 1"));
                                if($product_table_mtn_dd_data["status"] == 1){
					$product_discount_table_mtn_dd_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_dd_data_lists["id"]."' && product_id='".$product_table_mtn_dd_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_mtn_dd_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_mtn_dd_data)){
						echo '<option product-category="mtn-dd-data" value="'.$product_details["val_1"].'" hidden>MTN DIRECT '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //AIRTEL SHARED
                            $get_airtel_shared_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["shared-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_shared_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_airtel_shared_status_details["api_id"]."' && api_type='shared-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_shared_data_lists) == 1){
                                $get_api_enabled_shared_data_lists = mysqli_fetch_array($get_api_enabled_shared_data_lists);
                                $product_table_airtel_shared_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_airtel_shared_data["status"] == 1){
                                    $product_discount_table_airtel_shared_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_shared_data_lists["id"]."' && product_id='".$product_table_airtel_shared_data["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_airtel_shared_data) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_airtel_shared_data)){
                                            echo '<option product-category="airtel-shared-data" value="'.$product_details["val_1"].'" hidden>AIRTEL SHARED '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                            //AIRTEL SME
                            $get_airtel_sme_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["sme-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_sme_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_airtel_sme_status_details["api_id"]."' && api_type='sme-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_sme_data_lists) == 1){
                                $get_api_enabled_sme_data_lists = mysqli_fetch_array($get_api_enabled_sme_data_lists);
                                $product_table_airtel_sme_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_airtel_sme_data["status"] == 1){
					$product_discount_table_airtel_sme_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_sme_data_lists["id"]."' && product_id='".$product_table_airtel_sme_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_airtel_sme_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_airtel_sme_data)){
						echo '<option product-category="airtel-sme-data" value="'.$product_details["val_1"].'" hidden>AIRTEL SME '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //AIRTEL CG
                            $get_airtel_cg_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["cg-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_cg_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_airtel_cg_status_details["api_id"]."' && api_type='cg-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_cg_data_lists) == 1){
                                $get_api_enabled_cg_data_lists = mysqli_fetch_array($get_api_enabled_cg_data_lists);
                                $product_table_airtel_cg_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_airtel_cg_data["status"] == 1){
					$product_discount_table_airtel_cg_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_cg_data_lists["id"]."' && product_id='".$product_table_airtel_cg_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_airtel_cg_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_airtel_cg_data)){
						echo '<option product-category="airtel-cg-data" value="'.$product_details["val_1"].'" hidden>AIRTEL CG '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //AIRTEL DD
                            $get_airtel_dd_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["dd-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."'"));
                            $get_api_enabled_dd_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_airtel_dd_status_details["api_id"]."' && api_type='dd-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_dd_data_lists) == 1){
                                $get_api_enabled_dd_data_lists = mysqli_fetch_array($get_api_enabled_dd_data_lists);
                                $product_table_airtel_dd_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[1]."' LIMIT 1"));
                                if($product_table_airtel_dd_data["status"] == 1){
					$product_discount_table_airtel_dd_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_dd_data_lists["id"]."' && product_id='".$product_table_airtel_dd_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_airtel_dd_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_airtel_dd_data)){
						echo '<option product-category="airtel-dd-data" value="'.$product_details["val_1"].'" hidden>AIRTEL DIRECT '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //GLO SHARED
                            $get_glo_shared_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["shared-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_shared_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_glo_shared_status_details["api_id"]."' && api_type='shared-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_shared_data_lists) == 1){
                                $get_api_enabled_shared_data_lists = mysqli_fetch_array($get_api_enabled_shared_data_lists);
                                $product_table_glo_shared_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_glo_shared_data["status"] == 1){
                                    $product_discount_table_glo_shared_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_shared_data_lists["id"]."' && product_id='".$product_table_glo_shared_data["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_glo_shared_data) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_glo_shared_data)){
                                            echo '<option product-category="glo-shared-data" value="'.$product_details["val_1"].'" hidden>GLO SHARED '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                            //GLO SME
                            $get_glo_sme_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["sme-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_sme_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_glo_sme_status_details["api_id"]."' && api_type='sme-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_sme_data_lists) == 1){
                                $get_api_enabled_sme_data_lists = mysqli_fetch_array($get_api_enabled_sme_data_lists);
                                $product_table_glo_sme_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_glo_sme_data["status"] == 1){
					$product_discount_table_glo_sme_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_sme_data_lists["id"]."' && product_id='".$product_table_glo_sme_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_glo_sme_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_glo_sme_data)){
						echo '<option product-category="glo-sme-data" value="'.$product_details["val_1"].'" hidden>GLO SME '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //GLO CG
                            $get_glo_cg_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["cg-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_cg_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_glo_cg_status_details["api_id"]."' && api_type='cg-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_cg_data_lists) == 1){
                                $get_api_enabled_cg_data_lists = mysqli_fetch_array($get_api_enabled_cg_data_lists);
                                $product_table_glo_cg_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_glo_cg_data["status"] == 1){
					$product_discount_table_glo_cg_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_cg_data_lists["id"]."' && product_id='".$product_table_glo_cg_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_glo_cg_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_glo_cg_data)){
						echo '<option product-category="glo-cg-data" value="'.$product_details["val_1"].'" hidden>GLO CG '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //GLO DD
                            $get_glo_dd_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["dd-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."'"));
                            $get_api_enabled_dd_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_glo_dd_status_details["api_id"]."' && api_type='dd-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_dd_data_lists) == 1){
                                $get_api_enabled_dd_data_lists = mysqli_fetch_array($get_api_enabled_dd_data_lists);
                                $product_table_glo_dd_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[2]."' LIMIT 1"));
                                if($product_table_glo_dd_data["status"] == 1){
					$product_discount_table_glo_dd_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_dd_data_lists["id"]."' && product_id='".$product_table_glo_dd_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_glo_dd_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_glo_dd_data)){
						echo '<option product-category="glo-dd-data" value="'.$product_details["val_1"].'" hidden>GLO DIRECT '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //9MOBILE SHARED
                            $get_9mobile_shared_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["shared-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."'"));
                            $get_api_enabled_shared_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_9mobile_shared_status_details["api_id"]."' && api_type='shared-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_shared_data_lists) == 1){
                                $get_api_enabled_shared_data_lists = mysqli_fetch_array($get_api_enabled_shared_data_lists);
                                $product_table_9mobile_shared_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."' LIMIT 1"));
                                if($product_table_9mobile_shared_data["status"] == 1){
                                    $product_discount_table_9mobile_shared_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_shared_data_lists["id"]."' && product_id='".$product_table_9mobile_shared_data["id"]."'");
                                    if(mysqli_num_rows($product_discount_table_9mobile_shared_data) > 0){
                                        while($product_details = mysqli_fetch_assoc($product_discount_table_9mobile_shared_data)){
                                            echo '<option product-category="9mobile-shared-data" value="'.$product_details["val_1"].'" hidden>9MOBILE SHARED '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
                                        }
                                    }
                                }
                            }

                        /*    //9MOBILE SME
                            $get_9mobile_sme_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["sme-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."'"));
                            $get_api_enabled_sme_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_9mobile_sme_status_details["api_id"]."' && api_type='sme-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_sme_data_lists) == 1){
                                $get_api_enabled_sme_data_lists = mysqli_fetch_array($get_api_enabled_sme_data_lists);
                                $product_table_9mobile_sme_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."' LIMIT 1"));
                                if($product_table_9mobile_sme_data["status"] == 1){
					$product_discount_table_9mobile_sme_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_sme_data_lists["id"]."' && product_id='".$product_table_9mobile_sme_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_9mobile_sme_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_9mobile_sme_data)){
						echo '<option product-category="9mobile-sme-data" value="'.$product_details["val_1"].'" hidden>9MOBILE SME '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }  */

                            //9MOBILE CG
                            $get_9mobile_cg_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["cg-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."'"));
                            $get_api_enabled_cg_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_9mobile_cg_status_details["api_id"]."' && api_type='cg-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_cg_data_lists) == 1){
                                $get_api_enabled_cg_data_lists = mysqli_fetch_array($get_api_enabled_cg_data_lists);
                                $product_table_9mobile_cg_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."' LIMIT 1"));
                                if($product_table_9mobile_cg_data["status"] == 1){
					$product_discount_table_9mobile_cg_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_cg_data_lists["id"]."' && product_id='".$product_table_9mobile_cg_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_9mobile_cg_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_9mobile_cg_data)){
						echo '<option product-category="9mobile-cg-data" value="'.$product_details["val_1"].'" hidden>9MOBILE CG '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                            //9MOBILE DD
                            $get_9mobile_dd_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM ".$data_type_table_name_arrays["dd-data"]." WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."'"));
                            $get_api_enabled_dd_data_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$get_9mobile_dd_status_details["api_id"]."' && api_type='dd-data' && status='1' LIMIT 1");
                            if(mysqli_num_rows($get_api_enabled_dd_data_lists) == 1){
                                $get_api_enabled_dd_data_lists = mysqli_fetch_array($get_api_enabled_dd_data_lists);
                                $product_table_9mobile_dd_data = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$product_name_array[3]."' LIMIT 1"));
                                if($product_table_9mobile_dd_data["status"] == 1){
					$product_discount_table_9mobile_dd_data = mysqli_query($connection_server, "SELECT * FROM $acc_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_id='".$get_api_enabled_dd_data_lists["id"]."' && product_id='".$product_table_9mobile_dd_data["id"]."'");
					if(mysqli_num_rows($product_discount_table_9mobile_dd_data) > 0){
					while($product_details = mysqli_fetch_assoc($product_discount_table_9mobile_dd_data)){
						echo '<option product-category="9mobile-dd-data" value="'.$product_details["val_1"].'" hidden>9MOBILE DIRECT '.str_replace("_"," ",$product_details["val_1"]).' @ N'.$product_details["val_2"].' (Validity '.$product_details["val_3"].'days)</option>';
					}
					}
                                }
                            }

                        }
                    ?>
                </select><br/>
                <div style="text-align: left;" id="phone-bypass-div" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                    <input id="phone-bypass" onclick="tickDataCarrier('airtel');" type="checkbox" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none m-width-auto s-width-auto m-margin-bm-1 s-margin-bm-1" />
                    <div class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-80 s-width-80 m-margin-lt-1 s-margin-lt-1">
                        <label for="phone-bypass" class="a-cursor" style="user-select: auto;">
                            Bypass Phone Verification
                        </label>
                    </div>
                </div><br>
                <button id="proceedBtn" name="buy-data" type="button" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    BUY DATA
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