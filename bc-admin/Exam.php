<?php session_start();
    include("../func/bc-admin-config.php");
        
    if(isset($_POST["update-key"])){
        $api_id = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["api-id"])));
        $apikey = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["api-key"])));
        $apistatus = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["api-status"])));
        
        if(!empty($api_id) && is_numeric($api_id)){
            if(!empty($apikey)){
                if(is_numeric($apistatus) && in_array($apistatus, array("0", "1"))){
                    $select_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$api_id' && api_type='exam'");
                    if(mysqli_num_rows($select_api_lists) == 1){
                        mysqli_query($connection_server, "UPDATE sas_apis SET api_key='$apikey', status='$apistatus' WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$api_id' && api_type='exam'");
                        //APIkey Updated Successfully
                        $json_response_array = array("desc" => "APIkey Updated Successfully");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        //API Doesnt Exists
                        $json_response_array = array("desc" => "API Doesnt Exists");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }else{
                    //Invalid API Status
                    $json_response_array = array("desc" => "Invalid API Status");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                //Apikey Field Empty
                $json_response_array = array("desc" => "Apikey Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Invalid Apikey Website
            $json_response_array = array("desc" => "Invalid Apikey Website");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["install-product"])){
        $api_id = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["api-id"])));
        $item_status = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["item-status"])));
        $product_name = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["product-name"]))));
        $products_array = array("waec", "neco", "nabteb", "jamb");
        $account_level_table_name_arrays = array("sas_smart_parameter_values", "sas_agent_parameter_values", "sas_api_parameter_values");
        $array_waec_product_variety = array("result_checker");
        $array_neco_product_variety = array("result_checker");
        $array_nabteb_product_variety = array("result_checker");
		$array_jamb_product_variety = array("direct_entry", "utme_with_mock", "utme_without_mock");
		
        if(!empty($api_id) && is_numeric($api_id)){
            if(!empty($product_name)){
                if(in_array($product_name, $products_array)){
                    if(is_numeric($item_status) && in_array($item_status, array("0", "1"))){
                        $select_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$api_id' && api_type='exam'");
                        $select_exam_status_lists = mysqli_query($connection_server, "SELECT * FROM sas_exam_status WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");
                        if(mysqli_num_rows($select_api_lists) == 1){    
                            if(mysqli_num_rows($select_exam_status_lists) == 0){
                                mysqli_query($connection_server, "INSERT INTO sas_exam_status (vendor_id, api_id, product_name, status) VALUES ('".$get_logged_admin_details["id"]."', '$api_id', '$product_name', '$item_status')");
                            }else{
                                mysqli_query($connection_server, "UPDATE sas_exam_status SET api_id='$api_id', status='$item_status' WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");
                            }
                                
                            foreach($account_level_table_name_arrays as $account_level_table_name){ 
                                $select_product_details = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");
                                if(mysqli_num_rows($select_product_details) == 1){
                                    $get_product_details = mysqli_fetch_array($select_product_details);
                                    $get_selected_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$api_id'"));
                                    $select_api_list_with_api_type = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id!='$api_id' && api_type='".$get_selected_api_list["api_type"]."' LIMIT 1");
                                    if(mysqli_num_rows($select_api_list_with_api_type) == 1){
                                        $get_api_list_with_api_type = mysqli_fetch_array($select_api_list_with_api_type);
                                        $select_api_list_product_pricing_table = mysqli_query($connection_server, "SELECT * FROM $account_level_table_name WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$get_api_list_with_api_type["id"]."' && product_id='".$get_product_details["id"]."'");                    		
                                        if(mysqli_num_rows($select_api_list_product_pricing_table) == 1){
                                            $get_api_list_product_pricing_table = mysqli_fetch_array($select_api_list_product_pricing_table);
                                            $pro_val_1 = $get_api_list_product_pricing_table["val_1"];
                                            $pro_val_2 = $get_api_list_product_pricing_table["val_2"];
                                            $pro_val_3 = $get_api_list_product_pricing_table["val_3"];
                                            $pro_val_4 = $get_api_list_product_pricing_table["val_4"];
                                            $pro_val_5 = $get_api_list_product_pricing_table["val_5"];
                                            $pro_val_6 = $get_api_list_product_pricing_table["val_6"];
                                            $pro_val_7 = $get_api_list_product_pricing_table["val_7"];
                                            $pro_val_8 = $get_api_list_product_pricing_table["val_8"];
                                            $pro_val_9 = $get_api_list_product_pricing_table["val_9"];
                                            $pro_val_10 = $get_api_list_product_pricing_table["val_10"];
                                        }else{
                                            $pro_val_1 = "0";
                                            $pro_val_2 = "0";
                                            $pro_val_3 = "0";
                                            $pro_val_4 = "0";
                                            $pro_val_5 = "0";
                                            $pro_val_6 = "0";
                                            $pro_val_7 = "0";
                                            $pro_val_8 = "0";
                                            $pro_val_9 = "0";
                                            $pro_val_10 = "0";
                                        }
                                    }else{
                                        $pro_val_1 = "0";
                                        $pro_val_2 = "0";
                                        $pro_val_3 = "0";
                                        $pro_val_4 = "0";
                                        $pro_val_5 = "0";
                                        $pro_val_6 = "0";
                                        $pro_val_7 = "0";
                                        $pro_val_8 = "0";
                                        $pro_val_9 = "0";
                                        $pro_val_10 = "0";
                                    }
                                    $select_all_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_type='exam'");
                                    $product_array_string_name = "array_".$product_name."_product_variety";
                                    $product_variety = $$product_array_string_name;
                                    $count_product_variety = count($product_variety);
                                    if($count_product_variety >= 1){
                                        foreach($product_variety as $product_val_1){
                                            $product_val_1 = trim($product_val_1);
                                            $product_pricing_table = mysqli_query($connection_server, "SELECT * FROM $account_level_table_name WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='$api_id' && product_id='".$get_product_details["id"]."' && val_1='$product_val_1'");                            
                                            if(mysqli_num_rows($product_pricing_table) == 0){
                                                mysqli_query($connection_server, "INSERT INTO $account_level_table_name (vendor_id, api_id, product_id, val_1, val_2) VALUES ('".$get_logged_admin_details["id"]."', '$api_id', '".$get_product_details["id"]."', '$product_val_1', '$pro_val_2')");
                                            }else{
                                                if(mysqli_num_rows($select_all_api_lists) >= 1){
                                                    while($api_details = mysqli_fetch_assoc($select_all_api_lists)){
                                                        if($api_details["id"] !== $api_id){
                                                            mysqli_query($connection_server, "DELETE FROM $account_level_table_name WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$api_details["id"]."' && product_id='".$get_product_details["id"]."' && val_1='$product_val_1'");
                                                        }else{
                                                            $check_product_pricing_row_exists = mysqli_query($connection_server, "SELECT * FROM $account_level_table_name WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='$api_id' && product_id='".$get_product_details["id"]."' && val_1='$product_val_1'");                         
                                                            if(mysqli_num_rows($check_product_pricing_row_exists) == 0){
                                                                mysqli_query($connection_server, "INSERT INTO $account_level_table_name (vendor_id, api_id, product_id, val_1, val_2) VALUES ('".$get_logged_admin_details["id"]."', '$api_id', '".$get_product_details["id"]."', '$product_val_1', '$pro_val_2')");
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }       
                            //Product Updated Successfully
                            $json_response_array = array("desc" => "Product Updated Successfully");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            //API Doesnt Exists
                            $json_response_array = array("desc" => "API Doesnt Exists");
                            $json_response_encode = json_encode($json_response_array,true);
                        }
                    }else{
                        //Invalid Exam Status
                        $json_response_array = array("desc" => "Invalid Exam Status");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }else{
                    //Invalid Product Name
                    $json_response_array = array("desc" => "Invalid Product Name");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                //Product Name Field Empty
                $json_response_array = array("desc" => "Product Name Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Invalid Apikey Website
            $json_response_array = array("desc" => "Invalid Apikey Website");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["update-price"])){
        $api_id_array = $_POST["api-id"];
        $product_id_array = $_POST["product-id"];
        $product_code_1_array = $_POST["product-code-1"];
        $smart_price_array = $_POST["smart-price"];
        $agent_price_array = $_POST["agent-price"];
        $api_price_array = $_POST["api-price"];
        $account_level_table_name_arrays = array("sas_smart_parameter_values", "sas_agent_parameter_values", "sas_api_parameter_values");
        if(count($api_id_array) == count($product_id_array)){
            foreach($api_id_array as $index => $api_id){
                $api_id = $api_id_array[$index];
                $product_id = $product_id_array[$index];
                $product_code_1 = $product_code_1_array[$index];
                $smart_price = $smart_price_array[$index];
                $agent_price = $agent_price_array[$index];
                $api_price = $api_price_array[$index];
                $get_selected_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='$api_id'"));
                $select_api_list_with_api_type = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_type='".$get_selected_api_list["api_type"]."'");
                if(mysqli_num_rows($select_api_list_with_api_type) > 0){
                    while($refined_api_id = mysqli_fetch_assoc($select_api_list_with_api_type)){
                        $smart_product_pricing_table = mysqli_query($connection_server, "SELECT * FROM ".$account_level_table_name_arrays[0]." WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$refined_api_id["id"]."' && product_id='$product_id' && val_1='$product_code_1'");                          
                        if(mysqli_num_rows($smart_product_pricing_table) == 0){
                            mysqli_query($connection_server, "INSERT INTO ".$account_level_table_name_arrays[0]." (vendor_id, api_id, product_id, val_1, val_2) VALUES ('".$get_logged_admin_details["id"]."', '".$refined_api_id["id"]."', '$product_id', '$product_code_1', '$smart_price')");
                        }else{
                            mysqli_query($connection_server, "UPDATE ".$account_level_table_name_arrays[0]." SET vendor_id='".$get_logged_admin_details["id"]."', api_id='".$refined_api_id["id"]."', product_id='$product_id', val_1='$product_code_1', val_2='$smart_price' WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$refined_api_id["id"]."' && product_id='$product_id' && val_1='$product_code_1'");
                        }
                        
                        $agent_product_pricing_table = mysqli_query($connection_server, "SELECT * FROM ".$account_level_table_name_arrays[1]." WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$refined_api_id["id"]."' && product_id='$product_id' && val_1='$product_code_1'");                          
                        if(mysqli_num_rows($agent_product_pricing_table) == 0){
                            mysqli_query($connection_server, "INSERT INTO ".$account_level_table_name_arrays[1]." (vendor_id, api_id, product_id, val_1, val_2) VALUES ('".$get_logged_admin_details["id"]."', '".$refined_api_id["id"]."', '$product_id', '$product_code_1', '$agent_price')");
                        }else{
                            mysqli_query($connection_server, "UPDATE ".$account_level_table_name_arrays[1]." SET vendor_id='".$get_logged_admin_details["id"]."', api_id='".$refined_api_id["id"]."', product_id='$product_id', val_1='$product_code_1', val_2='$agent_price' WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$refined_api_id["id"]."' && product_id='$product_id' && val_1='$product_code_1'");
                        }
                        
                        $api_product_pricing_table = mysqli_query($connection_server, "SELECT * FROM ".$account_level_table_name_arrays[2]." WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$refined_api_id["id"]."' && product_id='$product_id' && val_1='$product_code_1'");                            
                        if(mysqli_num_rows($api_product_pricing_table) == 0){
                            mysqli_query($connection_server, "INSERT INTO ".$account_level_table_name_arrays[2]." (vendor_id, api_id, product_id, val_1, val_2) VALUES ('".$get_logged_admin_details["id"]."', '".$refined_api_id["id"]."', '$product_id', '$product_code_1', '$api_price')");
                        }else{
                            mysqli_query($connection_server, "UPDATE ".$account_level_table_name_arrays[2]." SET vendor_id='".$get_logged_admin_details["id"]."', api_id='".$refined_api_id["id"]."', product_id='$product_id', val_1='$product_code_1', val_2='$api_price' WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$refined_api_id["id"]."' && product_id='$product_id' && val_1='$product_code_1'");
                        }
                    }
                }
            }
            //Price Updated Successfully
            $json_response_array = array("desc" => "Price Updated Successfully");
            $json_response_encode = json_encode($json_response_array,true);
        }else{
            //Product Connection Error
            $json_response_array = array("desc" => "Product Connection Error");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
    
    $csv_price_level_array = [];
    $csv_price_level_array[] = "product_name,smart_level,agent_level,api_level";
    
?>
<!DOCTYPE html>
<head>
    <title>Exam API | <?php echo $get_all_super_admin_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_super_admin_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
</head>
<body>
    <?php include("../func/bc-admin-header.php"); ?>    
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-25 s-font-size-30 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">EXAM SETTINGS</span><br>
            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">API SETTING</span><br>
            <form method="post" action="">
                <select style="text-align: center;" id="" name="api-id" onchange="getWebApikey(this);" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <?php
                        //All Exam API
                        $get_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_type='exam'");
                        if(mysqli_num_rows($get_api_lists) >= 1){
                            echo '<option value="" default hidden selected>Choose API</option>';
                            while($api_details = mysqli_fetch_assoc($get_api_lists)){
                                if(empty(trim($api_details["api_key"]))){
                                    $apikey_status = "( Empty Key )";
                                }else{
                                    $apikey_status = "";
                                }
                                
                                echo '<option value="'.$api_details["id"].'" api-key="'.$api_details["api_key"].'" api-status="'.$api_details["status"].'">'.strtoupper($api_details["api_base_url"]).' '.$apikey_status.'</option>';
                            }
                        }else{
                            echo '<option value="" default hidden selected>No API</option>';
                        }
                    ?>
                </select><br/>
                <select style="text-align: center;" id="web-apikey-status" name="api-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose API Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <input style="text-align: center;" id="web-apikey-input" name="api-key" onkeyup="" type="text" value="" placeholder="Api Key" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <button name="update-key" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE KEY
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div><br/>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">PRODUCT INSTALLATION</span><br>
            <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                <img alt="Waec" id="waec-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/waec.jpg" onclick="tickProduct(this, 'waec', 'api-product-name', 'install-product', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                <img alt="Neco" id="neco-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/neco.jpg" onclick="tickProduct(this, 'neco', 'api-product-name', 'install-product', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="Nabteb" id="nabteb-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/nabteb.jpg" onclick="tickProduct(this, 'nabteb', 'api-product-name', 'install-product', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	<img alt="Jamb" id="jamb-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/jamb.jpg" onclick="tickProduct(this, 'jamb', 'api-product-name', 'install-product', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            </div><br/>
            <form method="post" action="">
                <input id="api-product-name" name="product-name" type="text" placeholder="Product Name" hidden readonly required/>
                <select style="text-align: center;" id="" name="api-id" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <?php
                        //All Exam API
                        $get_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_type='exam'");
                        if(mysqli_num_rows($get_api_lists) >= 1){
                            echo '<option value="" default hidden selected>Choose API</option>';
                            while($api_details = mysqli_fetch_assoc($get_api_lists)){
                                if(empty(trim($api_details["api_key"]))){
                                    $apikey_status = "( Empty Key )";
                                }else{
                                    $apikey_status = "";
                                }
                                
                                echo '<option value="'.$api_details["id"].'">'.strtoupper($api_details["api_base_url"]).' '.$apikey_status.'</option>';
                            }
                        }else{
                            echo '<option value="" default hidden selected>No API</option>';
                        }
                    ?>
                </select><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">EXAM STATUS</span>
                </div><br/>
                <select style="text-align: center;" id="" name="item-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose Exam Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <button id="install-product" name="install-product" type="submit" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    INSTALL PRODUCT
                </button><br>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">INSTALLED EXAM STATUS</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
                <table style="width: 100% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>Product Name</th><th>API Route</th><th>Status</th>
                    </tr>
                    <?php
                        $item_name_array = array("waec", "neco", "nabteb", "jamb");
                        foreach($item_name_array as $products){
                        	$items_statement .= "product_name='$products' OR ";
                        }
                        $items_statement = "(".trim(rtrim($items_statement," OR ")).")";
                        $select_item_lists = mysqli_query($connection_server, "SELECT * FROM sas_exam_status WHERE vendor_id='".$get_logged_admin_details["id"]."' && $items_statement");
                        if(mysqli_num_rows($select_item_lists) >= 1){
                            while($list_details = mysqli_fetch_assoc($select_item_lists)){
                                $select_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$list_details["api_id"]."' && api_type='exam'");
                                if(mysqli_num_rows($select_api_lists) == 1){
                                    $api_details = mysqli_fetch_array($select_api_lists);
                                    $api_route_web = strtoupper($api_details["api_base_url"]);
                                }else{
                                    if(mysqli_num_rows($select_api_lists) == 0){
                                        $api_route_web = "Invalid API Website";
                                    }else{
                                        $api_route_web = "Duplicated API Website";
                                    }
                                }
                                if(strtolower(itemStatus($list_details["status"])) == "enabled"){
                                    $item_status = '<span style="color: green;">'.itemStatus($list_details["status"]).'</span>';
                                }else{
                                    $item_status = '<span style="color: grey;">'.itemStatus($list_details["status"]).'</span>';
                                }
                                
                                echo 
                                '<tr>
                                    <td>'.strtoupper(str_replace(["-","_"], " ", $list_details["product_name"])).'</td><td>'.$api_route_web.'</td><td>'.$item_status.'</td>
                                </tr>';
                            }
                        }
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">EXAM DISCOUNT</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
                <form method="post" action="">
                    <table style="width: 100% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                        <tr>
                            <th>Product Name</th><th>Smart Earner</th><th>Agent Vendor</th><th>API Vendor</th>
                        </tr>
                        
                        <?php
                            $item_name_array_2 = array("waec", "neco", "nabteb", "jamb");
                            foreach($item_name_array_2 as $products){
                                $get_item_status_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_exam_status WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$products'"));
                                $get_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && id='".$get_item_status_details["api_id"]."' && api_type='exam'");
                                $account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                                $product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$products' LIMIT 1"));
                                $product_smart_table = mysqli_query($connection_server, "SELECT * FROM ".$account_level_table_name_arrays[1]." WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$get_item_status_details["api_id"]."' && product_id='".$product_table["id"]."'");                         
                                $product_agent_table = mysqli_query($connection_server, "SELECT * FROM ".$account_level_table_name_arrays[2]." WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$get_item_status_details["api_id"]."' && product_id='".$product_table["id"]."'");                         
                                $product_api_table = mysqli_query($connection_server, "SELECT * FROM ".$account_level_table_name_arrays[3]." WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_id='".$get_item_status_details["api_id"]."' && product_id='".$product_table["id"]."'");                           
                                
                                if((mysqli_num_rows($get_api_lists) == 1) && (mysqli_num_rows($product_smart_table) > 0) && (mysqli_num_rows($product_agent_table) > 0) && (mysqli_num_rows($product_api_table) > 0)){
                                    while(($product_smart_details = mysqli_fetch_assoc($product_smart_table)) && ($product_agent_details = mysqli_fetch_assoc($product_agent_table)) && ($product_api_details = mysqli_fetch_assoc($product_api_table))){
                                        echo 
                                            '<tr style="background-color: transparent !important;">
                                                <td style="color: var(--color-2) !important;">
                                                    '.strtoupper($products." ".str_replace(["_","-"]," ",$product_smart_details["val_1"])).'
                                                    <input style="text-align: center;" name="api-id[]" type="text" value="'.$product_smart_details["api_id"].'" hidden readonly required/>
                                                    <input style="text-align: center;" name="product-id[]" type="text" value="'.$product_smart_details["product_id"].'" hidden readonly required/>
                                                    <input style="text-align: center;" name="product-code-1[]" type="text" value="'.$product_smart_details["val_1"].'" hidden readonly required/>
                                                </td>
                                                <td>
                                                    <input style="text-align: center;" id="'.strtolower(trim($products)).'_'.str_replace(["_","-"],"_",$product_smart_details["val_1"]).'_smart_level" name="smart-price[]" type="text" value="'.$product_smart_details["val_2"].'" placeholder="Price" pattern="[0-9.]{1,}" title="Amount Must Be A Digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-100 s-width-100 m-padding-tp-10 s-padding-tp-5 m-padding-bm-10 s-padding-bm-5 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" required/>
                                                </td>
                                                <td>
                                                    <input style="text-align: center;" id="'.strtolower(trim($products)).'_'.str_replace(["_","-"],"_",$product_smart_details["val_1"]).'_agent_level" name="agent-price[]" type="text" value="'.$product_agent_details["val_2"].'" placeholder="Price" pattern="[0-9.]{1,}" title="Amount Must Be A Digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-100 s-width-100 m-padding-tp-10 s-padding-tp-5 m-padding-bm-10 s-padding-bm-5 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" required/>
                                                </td>
                                                <td>
                                                    <input style="text-align: center;" id="'.strtolower(trim($products)).'_'.str_replace(["_","-"],"_",$product_smart_details["val_1"]).'_api_level" name="api-price[]" type="text" value="'.$product_api_details["val_2"].'" placeholder="Price" pattern="[0-9.]{1,}" title="Amount Must Be A Digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-100 s-width-100 m-padding-tp-10 s-padding-tp-5 m-padding-bm-10 s-padding-bm-5 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" required/>
                                                </td>
                                            </tr>'; 
                                            $csv_price_level_array[] = strtolower(trim($products)).'_'.str_replace(["_","-"],"_",$product_smart_details["val_1"]).",".$product_smart_details["val_2"].",".$product_agent_details["val_2"].",".$product_api_details["val_2"];
                                    }
                                }else{
                                    
                                }
                            }
                        ?>
                    </table>
                    <button id="" name="update-price" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-80 s-width-62 m-float-rt m-clr-float-both s-float-rt s-clr-float-both m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-rt-2 s-margin-rt-2 m-margin-bm-1 s-margin-bm-1" >
                        UPDATE PRICE
                    </button><br>
                </form>
            </div><br/>
            
            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">FILL PRICE TABLE USING CSV</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
            	<form method="post" enctype="multipart/form-data" action="">
            		<input style="text-align: center;" id="csv-chooser" type="file" accept="" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-95 s-width-95 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-2 s-margin-bm-2" required/><br/>
            		<button onclick="getCSVDetails('4');" type="button" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-97 s-width-97 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
            			PROCESS
            		</button>
            	</form>
            </div><br/>
            
            <a onclick='downloadFile(`<?php echo implode("\n",$csv_price_level_array); ?>`, "exam.csv");' style="text-decoration: underline; user-select: auto;" class="a-cursor color-2 outline-none text-bold-600">Download Price CSV</a>
            
        </div>
    <?php include("../func/bc-admin-footer.php"); ?>
    
</body>
</html>