<?php session_start();
    include("../func/bc-admin-config.php");

    $telecoms_array = array("mtn", "airtel", "glo", "9mobile");
    $cable_array = array("startimes", "dstv", "gotv");
    $exam_array = array("waec", "neco", "nabteb", "jamb");
    $electric_array = array("ekedc","eedc","ikedc","jedc","kedco","ibedc","phed","aedc","yedc","bedc","aple","kaedco","bedc","aple","kaedco");
    $products_array = array_merge($telecoms_array, $cable_array, $exam_array, $electric_array);
    
    if(isset($_POST["install-all-product"])){
        $all_product_status = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["all-product-status"])));
        foreach ($products_array as $product_name) {
            if(is_numeric($all_product_status) && in_array($all_product_status, array("0", "1"))){
                $product_status = $all_product_status;
            }else{
                $product_status = 1;
            }
            
            $select_product_lists = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");        
            if(mysqli_num_rows($select_product_lists) == 0){
                mysqli_query($connection_server, "INSERT INTO sas_products (vendor_id, product_name, status) VALUES ('".$get_logged_admin_details["id"]."', '$product_name', '$product_status')");
            }else{
                mysqli_query($connection_server, "UPDATE sas_products SET status='$product_status' WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");
            } 
        }
        //All Product Installed Successfully
        $json_response_array = array("desc" => "All Product Installed Successfully");
        $_SESSION["product_purchase_response"] = $json_response_array["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

        
    if(isset($_POST["update-product"])){
        $product_status = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["product-status"])));
        $product_name = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["product-name"]))));
        $account_level_table_name_arrays = array("sas_smart_parameter_values", "sas_agent_parameter_values", "sas_api_parameter_values");
        $product_variety = array();
        if(!empty($product_name)){
        	if(in_array($product_name, $products_array)){
    	        if(is_numeric($product_status) && in_array($product_status, array("0", "1"))){
                	$select_product_lists = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");        
   					if(mysqli_num_rows($select_product_lists) == 0){
                     	mysqli_query($connection_server, "INSERT INTO sas_products (vendor_id, product_name, status) VALUES ('".$get_logged_admin_details["id"]."', '$product_name', '$product_status')");
                 	}else{
                 		mysqli_query($connection_server, "UPDATE sas_products SET status='$product_status' WHERE vendor_id='".$get_logged_admin_details["id"]."' && product_name='$product_name'");
					}    
					//Product Status Updated Successfully
					$json_response_array = array("desc" => "Product Status Updated Successfully");
					$json_response_encode = json_encode($json_response_array,true);
				}else{
					//Invalid Product Status
					$json_response_array = array("desc" => "Invalid Product Status");
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
        
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
?>
<!DOCTYPE html>
<head>
    <title>Product Function | <?php echo $get_all_super_admin_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-25 s-font-size-30 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">PRODUCT SETTINGS</span><br>
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-width-20 s-width-15">
            	<img src="<?php echo $web_http_host; ?>/asset/installation-icon.png" class="a-cursor m-width-100 s-width-100" style="pointer-events: none; user-select: auto;"/>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">PRE INSTALL ALL PRODUCTS</span><br>
            <form method="post" action="">
                <select style="text-align: center;" id="" name="all-product-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose All Product Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <button id="install-all-product" name="install-all-product" onclick="javascript: if(confirm('Want to Pre-Install all product?')){this.type='submit';}" type="button" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    SAVE CHANGES
                </button><br>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">TELECOMS PRODUCT STATUS</span><br>
            <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                <img alt="Airtel" id="airtel-lg" product-name-array="mtn,airtel,glo,9mobile" src="/asset/airtel.png" onclick="tickProduct(this, 'airtel', 'api-telecoms-name', 'install-telecoms', 'png');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                <img alt="MTN" id="mtn-lg" product-name-array="mtn,airtel,glo,9mobile" src="/asset/mtn.png" onclick="tickProduct(this, 'mtn', 'api-telecoms-name', 'install-telecoms', 'png');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="Glo" id="glo-lg" product-name-array="mtn,airtel,glo,9mobile" src="/asset/glo.png" onclick="tickProduct(this, 'glo', 'api-telecoms-name', 'install-telecoms', 'png');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="9mobile" id="9mobile-lg" product-name-array="mtn,airtel,glo,9mobile" src="/asset/9mobile.png" onclick="tickProduct(this, '9mobile', 'api-telecoms-name', 'install-telecoms', 'png');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            </div><br/>
            <form method="post" action="">
                <input id="api-telecoms-name" name="product-name" type="text" placeholder="Product Name" hidden readonly required/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">ALL PRODUCT STATUS</span>
                </div><br/>
                <select style="text-align: center;" id="" name="product-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose Product Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <button id="install-telecoms" name="update-product" type="submit" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE STATUS
                </button><br>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">INSTALLED TELECOMS STATUS</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
                <table style="width: 100% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>Product Name</th><th>Status</th>
                    </tr>
                    <?php
                        function telecomFunc(){
                            global $connection_server;
                            global $get_logged_admin_details;
                            $product_name_array = array("mtn", "airtel", "glo", "9mobile");
                            foreach($product_name_array as $products){
                                $products_statement .= "product_name='$products' ";
                            }
                            $products_statement = trim($products_statement);
                            $products_statement = str_replace(" ", " OR ", $products_statement);
                            $select_product_lists = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && ($products_statement)");
                            if(mysqli_num_rows($select_product_lists) >= 1){
                                while($list_details = mysqli_fetch_assoc($select_product_lists)){
                                    if(strtolower(itemStatus($list_details["status"])) == "enabled"){
                                        $item_status = '<span style="color: green;">'.itemStatus($list_details["status"]).'</span>';
                                    }else{
                                        $item_status = '<span style="color: grey;">'.itemStatus($list_details["status"]).'</span>';
                                    }

                                    $product_tr_return .= 
                                    '<tr>
                                        <td>'.strtoupper(str_replace(["-","_"], " ", $list_details["product_name"])).'</td><td>'.$item_status.'</td>
                                    </tr>';
                                }
                            }
                            return $product_tr_return;
                        }

                        echo telecomFunc();
                    ?>
                </table>
            </div><br/>
			
            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">CABLE PRODUCT STATUS</span><br>
            <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                <img alt="Startimes" id="startimes-lg" product-name-array="startimes,dstv,gotv" src="/asset/startimes.jpg" onclick="tickProduct(this, 'startimes', 'api-cable-name', 'install-cable', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                <img alt="Dstv" id="dstv-lg" product-name-array="startimes,dstv,gotv" src="/asset/dstv.jpg" onclick="tickProduct(this, 'dstv', 'api-cable-name', 'install-cable', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="Gotv" id="gotv-lg" product-name-array="startimes,dstv,gotv" src="/asset/gotv.jpg" onclick="tickProduct(this, 'gotv', 'api-cable-name', 'install-cable', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            </div><br/>
            <form method="post" action="">
                <input id="api-cable-name" name="product-name" type="text" placeholder="Product Name" hidden readonly required/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">ALL PRODUCT STATUS</span>
                </div><br/>
                <select style="text-align: center;" id="" name="product-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose Product Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <button id="install-cable" name="update-product" type="submit" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE STATUS
                </button><br>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">INSTALLED CABLE STATUS</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
                <table style="width: 100% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>Product Name</th><th>Status</th>
                    </tr>
                    <?php
                        function cableFunc(){
                            global $connection_server;
                            global $get_logged_admin_details;
                            $product_name_array = array("startimes", "dstv", "gotv");
                            foreach($product_name_array as $products){
                                $products_statement .= "product_name='$products' ";
                            }
                            $products_statement = trim($products_statement);
                            $products_statement = str_replace(" ", " OR ", $products_statement);
                            $select_product_lists = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && ($products_statement)");
                            if(mysqli_num_rows($select_product_lists) >= 1){
                                while($list_details = mysqli_fetch_assoc($select_product_lists)){
                                    if(strtolower(itemStatus($list_details["status"])) == "enabled"){
                                        $item_status = '<span style="color: green;">'.itemStatus($list_details["status"]).'</span>';
                                    }else{
                                        $item_status = '<span style="color: grey;">'.itemStatus($list_details["status"]).'</span>';
                                    }

                                    $product_tr_return .= 
                                    '<tr>
                                        <td>'.strtoupper(str_replace(["-","_"], " ", $list_details["product_name"])).'</td><td>'.$item_status.'</td>
                                    </tr>';
                                }
                            }
                            return $product_tr_return;
                        }

                        echo cableFunc();
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">EXAM PRODUCT STATUS</span><br>
            <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                <img alt="Waec" id="waec-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/waec.jpg" onclick="tickProduct(this, 'waec', 'api-exam-name', 'install-exam', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                <img alt="Neco" id="neco-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/neco.jpg" onclick="tickProduct(this, 'neco', 'api-exam-name', 'install-exam', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="Nabteb" id="nabteb-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/nabteb.jpg" onclick="tickProduct(this, 'nabteb', 'api-exam-name', 'install-exam', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	<img alt="Jamb" id="jamb-lg" product-name-array="waec,neco,nabteb,jamb" src="/asset/jamb.jpg" onclick="tickProduct(this, 'jamb', 'api-exam-name', 'install-exam', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            </div><br/>
            <form method="post" action="">
                <input id="api-exam-name" name="product-name" type="text" placeholder="Product Name" hidden readonly required/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">ALL PRODUCT STATUS</span>
                </div><br/>
                <select style="text-align: center;" id="" name="product-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose Product Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <button id="install-exam" name="update-product" type="submit" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE STATUS
                </button><br>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">INSTALLED EXAM STATUS</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
                <table style="width: 100% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>Product Name</th><th>Status</th>
                    </tr>
                    <?php
                        function examFunc(){
                            global $connection_server;
                            global $get_logged_admin_details;
                            $product_name_array = array("waec", "neco", "nabteb", "jamb");
                            foreach($product_name_array as $products){
                                $products_statement .= "product_name='$products' ";
                            }
                            $products_statement = trim($products_statement);
                            $products_statement = str_replace(" ", " OR ", $products_statement);
                            $select_product_lists = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && ($products_statement)");
                            if(mysqli_num_rows($select_product_lists) >= 1){
                                while($list_details = mysqli_fetch_assoc($select_product_lists)){
                                    if(strtolower(itemStatus($list_details["status"])) == "enabled"){
                                        $item_status = '<span style="color: green;">'.itemStatus($list_details["status"]).'</span>';
                                    }else{
                                        $item_status = '<span style="color: grey;">'.itemStatus($list_details["status"]).'</span>';
                                    }

                                    $product_tr_return .= 
                                    '<tr>
                                        <td>'.strtoupper(str_replace(["-","_"], " ", $list_details["product_name"])).'</td><td>'.$item_status.'</td>
                                    </tr>';
                                }
                            }
                            return $product_tr_return;
                        }

                        echo examFunc();
                    ?>
                </table>
            </div><br/>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">ELECTRIC PRODUCT STATUS</span><br>
            <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                <img alt="ekedc" id="ekedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/ekedc.jpg" onclick="tickProduct(this, 'ekedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 "/>
                <img alt="eedc" id="eedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/eedc.jpg" onclick="tickProduct(this, 'eedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="ikedc" id="ikedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/ikedc.jpg" onclick="tickProduct(this, 'ikedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="jedc" id="jedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/jedc.jpg" onclick="tickProduct(this, 'jedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="kedco" id="kedco-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco,bedc,aple,kaedco" src="/asset/kedco.jpg" onclick="tickProduct(this, 'kedco', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="ibedc" id="ibedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/ibedc.jpg" onclick="tickProduct(this, 'ibedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="phed" id="phed-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/phed.jpg" onclick="tickProduct(this, 'phed', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
                <img alt="aedc" id="aedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/aedc.jpg" onclick="tickProduct(this, 'aedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	<img alt="yedc" id="yedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/yedc.jpg" onclick="tickProduct(this, 'yedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	<img alt="bedc" id="bedc-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/bedc.jpg" onclick="tickProduct(this, 'bedc', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	<img alt="aple" id="aple-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/aple.jpg" onclick="tickProduct(this, 'aple', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	<img alt="kaedco" id="kaedco-lg" product-name-array="ekedc,eedc,ikedc,jedc,kedco,ibedc,phed,aedc,yedc,bedc,aple,kaedco" src="/asset/kaedco.jpg" onclick="tickProduct(this, 'kaedco', 'api-electric-name', 'install-electric', 'jpg');" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1"/>
            	
            </div><br/>
            <form method="post" action="">
                <input id="api-electric-name" name="product-name" type="text" placeholder="Product Name" hidden readonly required/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">ALL PRODUCT STATUS</span>
                </div><br/>
                <select style="text-align: center;" id="" name="product-status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose Product Status</option>
                    <option value="1" >Enabled</option>
                    <option value="0" >Disabled</option>
                </select><br/>
                <button id="install-electric" name="update-product" type="submit" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE STATUS
                </button><br>
            </form>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-tp-2 s-margin-tp-2 m-margin-bm-1 s-margin-bm-1">INSTALLED ELECTRIC STATUS</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-90 s-width-47 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-0 s-margin-lt-0">
                <table style="width: 100% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>Product Name</th><th>Status</th>
                    </tr>
                    <?php
                        function electricFunc(){
                            global $connection_server;
                            global $get_logged_admin_details;
                            $product_name_array = array("ekedc","eedc","ikedc","jedc","kedco","ibedc","phed","aedc","yedc","bedc","aple","kaedco");
                            foreach($product_name_array as $products){
                                $products_statement .= "product_name='$products' ";
                            }
                            $products_statement = trim($products_statement);
                            $products_statement = str_replace(" ", " OR ", $products_statement);
                            $select_product_lists = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_admin_details["id"]."' && ($products_statement)");
                            if(mysqli_num_rows($select_product_lists) >= 1){
                                while($list_details = mysqli_fetch_assoc($select_product_lists)){
                                    if(strtolower(itemStatus($list_details["status"])) == "enabled"){
                                        $item_status = '<span style="color: green;">'.itemStatus($list_details["status"]).'</span>';
                                    }else{
                                        $item_status = '<span style="color: grey;">'.itemStatus($list_details["status"]).'</span>';
                                    }

                                    $product_tr_return .= 
                                    '<tr>
                                        <td>'.strtoupper(str_replace(["-","_"], " ", $list_details["product_name"])).'</td><td>'.$item_status.'</td>
                                    </tr>';
                                }
                            }
                            return $product_tr_return;
                        }

                        echo electricFunc();
                    ?>
                </table>
            </div><br/>

        </div>
	<?php include("../func/bc-admin-footer.php"); ?>
	
</body>
</html>