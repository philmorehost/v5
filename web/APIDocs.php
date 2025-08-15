<?php session_start();
    include("../func/bc-config.php");

    if(isset($_POST["regenerate"])){
        $api_key = substr(str_shuffle("abdcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345678901234567890"), 0, 50);
		mysqli_query($connection_server, "UPDATE sas_users SET api_key='$api_key' WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && username='".$get_logged_user_details["username"]."'");
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
?>
<!DOCTYPE html>
<head>
<title>API Documentation | <?php echo $get_all_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
	<script>
		function slideDocs(sliderDivID){
			var sliderDiv = document.getElementById(sliderDivID);
			var apiDocsDiv = document.getElementsByClassName("api_docs_div");
			
			for(x = 0; x < apiDocsDiv.length; x++){
				apiDocsDiv[x].classList.remove("m-inline-block-dp");
				apiDocsDiv[x].classList.remove("s-inline-block-dp");
				apiDocsDiv[x].classList.add("m-none-dp");
				apiDocsDiv[x].classList.add("s-none-dp");
				apiDocsDiv[x].classList.remove("m-height-auto");
				apiDocsDiv[x].classList.remove("s-height-auto");
				apiDocsDiv[x].classList.add("m-height-0");
				apiDocsDiv[x].classList.add("s-height-0");
				apiDocsDiv[x].style.marginTop = "-0.4%";
			}

			if(sliderDiv.classList.contains("m-none-dp")){
				sliderDiv.classList.remove("m-none-dp");
				sliderDiv.classList.remove("s-none-dp");
				sliderDiv.classList.add("m-inline-block-dp");
				sliderDiv.classList.add("s-inline-block-dp");
				sliderDiv.classList.remove("m-height-0");
				sliderDiv.classList.remove("s-height-0");
				sliderDiv.classList.add("m-height-auto");
				sliderDiv.classList.add("s-height-auto");
				sliderDiv.style.marginTop = "-0.4%";
			}else{
				sliderDiv.classList.remove("m-inline-block-dp");
				sliderDiv.classList.remove("s-inline-block-dp");
				sliderDiv.classList.add("m-none-dp");
				sliderDiv.classList.add("s-none-dp");
				sliderDiv.classList.remove("m-height-auto");
				sliderDiv.classList.remove("s-height-auto");
				sliderDiv.classList.add("m-height-0");
				sliderDiv.classList.add("s-height-0");
				sliderDiv.style.marginTop = "0.4%";
			}
		}
	</script>
</head>
<?php if($get_logged_user_details["api_status"] == "1"){ ?>
<body onload="slideDocs('airtime-docs');">
	<?php include("../func/bc-header.php"); ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
				<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25">API DOCUMENTATION</span><br>
                <form method="post" action="" class="m-margin-tp-1 s-margin-tp-1">
                    <input style="user-select: auto; text-align: center;" name="" type="text" value="<?php echo $get_logged_user_details["api_key"]; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-97 s-width-34 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" readonly/>
                    <button style="user-select: auto;" name="regenerate" type="submit" class="button-box a-cursor color-4 bg-10 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-float-rt m-clr-float-both s-float-none s-clr-float-none m-width-auto s-width-auto m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                        Regenerate Key
                    </button>
                </form>
            </div>
        </div>
		<a href="#airtime-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('airtime-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				AIRTIMEVTU API
			</button>
		</a>
		
		<a href="#data-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('data-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				INTERNET DATA API
			</button>
		</a>

		<a href="#cable-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('cable-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				CABLE SUBSCRIPTION API
			</button>
		</a>
		
		<a href="#exam-pin-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('exam-pin-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				EXAM PIN API
			</button>
		</a>
		
		<a href="#electric-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('electric-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				ELECTRIC API
			</button>
		</a>
		
		<a href="#card-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('card-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				CARD API
			</button>
		</a>

		<a href="#bulk-sms-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('bulk-sms-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				BULK SMS API
			</button>
		</a>

		<a href="#requery-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('requery-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				REQUERY TRANSACTION
			</button>
		</a>

		<a href="#status-code-docs" style="text-decoration: none;">
			<button style="user-select: auto;" onclick="slideDocs('status-code-docs');" class="button-box br-none a-cursor color-4 bg-10 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-width-96 s-width-47 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				RESPONSE STATUS CODE
			</button>
		</a>

		<div id="airtime-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating AirtimeVTU API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">AIRTIMEVTU BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/airtime.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* network: <span class="text-bold-400">This is the service you are paying for e.g mtn et.c network code(Product Code) is on the table below</span><br/>
					* phone_number: <span class="text-bold-400">This is the recipient phone number</span><br/>
					* amount: <span class="text-bold-400">This is the amount of airtime to purchase</span>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful airtime transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"You have successfully purchased 100 naira airtime for 08124232128"}
				</span>

			</div>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
				<table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
				<tr>
					<th>Product</th><th>Network</th><th>Product Code</th><th>Smart User (%)</th><th>Agent Vendor (%)</th><th>API Vendor (%)</th>
				</tr>
				<?php
					function airtimeAPIDoc(){
						global $connection_server;
						global $get_logged_user_details;
						$account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
						$product_name_arrays = array(1 => "mtn", 2 => "airtel", 3 => "9mobile", 4 => "glo");
						$acc_smart_level_table_name = $account_level_table_name_arrays[1];
						$acc_agent_level_table_name = $account_level_table_name_arrays[2];
						$acc_api_level_table_name = $account_level_table_name_arrays[3];
						
						//PRODUCT NAME
						$mtn_product_name = $product_name_arrays[1];
						$airtel_product_name = $product_name_arrays[2];
						$etisalat_product_name = $product_name_arrays[3];
						$glo_product_name = $product_name_arrays[4];
						
						//MTN INFORMATION
						$mtn_airtime_api_id_array = [];
						$mtn_airtime_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='airtime'");
						if(mysqli_num_rows($mtn_airtime_api_lists) > 0){
							while($mtn_airtime_detail = mysqli_fetch_assoc($mtn_airtime_api_lists)){
								$mtn_airtime_api_id_array[] = $mtn_airtime_detail["id"];
							}
						}
						foreach($mtn_airtime_api_id_array as $mtn_api_id){
							$mtn_api_id_statement .= "api_id='$mtn_api_id'" . "\n";
						}
						$mtn_api_id_statement = "(".str_replace("\n", " OR ", trim($mtn_api_id_statement)).")";
						$mtn_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$mtn_product_name."' LIMIT 1"));
						if(!empty($mtn_product_table["id"])){
							$mtn_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
						}

						//AIRTEL INFORMATION
						$airtel_airtime_api_id_array = [];
						$airtel_airtime_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='airtime'");
						if(mysqli_num_rows($airtel_airtime_api_lists) > 0){
							while($airtel_airtime_detail = mysqli_fetch_assoc($airtel_airtime_api_lists)){
								$airtel_airtime_api_id_array[] = $airtel_airtime_detail["id"];
							}
						}
						foreach($airtel_airtime_api_id_array as $airtel_api_id){
							$airtel_api_id_statement .= "api_id='$airtel_api_id'" . "\n";
						}
						$airtel_api_id_statement = "(".str_replace("\n", " OR ", trim($airtel_api_id_statement)).")";
						$airtel_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$airtel_product_name."' LIMIT 1"));
						if(!empty($airtel_product_table["id"])){
							$airtel_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
						}
						
						//ETISALAT INFORMATION
						$etisalat_airtime_api_id_array = [];
						$etisalat_airtime_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='airtime'");
						if(mysqli_num_rows($etisalat_airtime_api_lists) > 0){
							while($etisalat_airtime_detail = mysqli_fetch_assoc($etisalat_airtime_api_lists)){
								$etisalat_airtime_api_id_array[] = $etisalat_airtime_detail["id"];
							}
						}
						foreach($etisalat_airtime_api_id_array as $etisalat_api_id){
							$etisalat_api_id_statement .= "api_id='$etisalat_api_id'" . "\n";
						}
						$etisalat_api_id_statement = "(".str_replace("\n", " OR ", trim($etisalat_api_id_statement)).")";
						$etisalat_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$etisalat_product_name."' LIMIT 1"));
						if(!empty($etisalat_product_table["id"])){
							$etisalat_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
						}

						//GLO INFORMATION
						$glo_airtime_api_id_array = [];
						$glo_airtime_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='airtime'");
						if(mysqli_num_rows($glo_airtime_api_lists) > 0){
							while($glo_airtime_detail = mysqli_fetch_assoc($glo_airtime_api_lists)){
								$glo_airtime_api_id_array[] = $glo_airtime_detail["id"];
							}
						}
						foreach($glo_airtime_api_id_array as $glo_api_id){
							$glo_api_id_statement .= "api_id='$glo_api_id'" . "\n";
						}
						$glo_api_id_statement = "(".str_replace("\n", " OR ", trim($glo_api_id_statement)).")";
						$glo_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$glo_product_name."' LIMIT 1"));
						if(!empty($glo_product_table["id"])){
							$glo_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
						}

						//MTN PRICE LISTING
						if(isset($mtn_smart_product_discount_table) && (mysqli_num_rows($mtn_smart_product_discount_table) > 0)){
							while(($mtn_smart_details = mysqli_fetch_assoc($mtn_smart_product_discount_table)) && ($mtn_agent_details = mysqli_fetch_assoc($mtn_agent_product_discount_table)) && ($mtn_api_details = mysqli_fetch_assoc($mtn_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Airtime</td><td>MTN</td><td>'.$mtn_product_table["product_name"].'</td><td>'.toDecimal($mtn_smart_details["val_1"], 2).'</td><td>'.toDecimal($mtn_agent_details["val_1"], 2).'</td><td>'.toDecimal($mtn_api_details["val_1"], 2).'</td>
									</tr>';
							}
						}
						
						//AIRTEL PRICE LISTING
						if(isset($airtel_smart_product_discount_table) && (mysqli_num_rows($airtel_smart_product_discount_table) > 0)){
							while(($airtel_smart_details = mysqli_fetch_assoc($airtel_smart_product_discount_table)) && ($airtel_agent_details = mysqli_fetch_assoc($airtel_agent_product_discount_table)) && ($airtel_api_details = mysqli_fetch_assoc($airtel_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Airtime</td><td>Airtel</td><td>'.$airtel_product_table["product_name"].'</td><td>'.toDecimal($airtel_smart_details["val_1"], 2).'</td><td>'.toDecimal($airtel_agent_details["val_1"], 2).'</td><td>'.toDecimal($airtel_api_details["val_1"], 2).'</td>
									</tr>';
							}
						}
						
						//ETISALAT PRICE LISTING
						if(isset($airtel_smart_product_discount_table) && (mysqli_num_rows($etisalat_smart_product_discount_table) > 0)){
							while(($etisalat_smart_details = mysqli_fetch_assoc($etisalat_smart_product_discount_table)) && ($etisalat_agent_details = mysqli_fetch_assoc($etisalat_agent_product_discount_table)) && ($etisalat_api_details = mysqli_fetch_assoc($etisalat_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Airtime</td><td>9mobile</td><td>'.$etisalat_product_table["product_name"].'</td><td>'.toDecimal($etisalat_smart_details["val_1"], 2).'</td><td>'.toDecimal($etisalat_agent_details["val_1"], 2).'</td><td>'.toDecimal($etisalat_api_details["val_1"], 2).'</td>
									</tr>';
							}
						}
						
						//GLO PRICE LISTING
						if(isset($glo_smart_product_discount_table) && (mysqli_num_rows($glo_smart_product_discount_table) > 0)){
							while(($glo_smart_details = mysqli_fetch_assoc($glo_smart_product_discount_table)) && ($glo_agent_details = mysqli_fetch_assoc($glo_agent_product_discount_table)) && ($glo_api_details = mysqli_fetch_assoc($glo_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Airtime</td><td>GLO</td><td>'.$glo_product_table["product_name"].'</td><td>'.toDecimal($glo_smart_details["val_1"], 2).'</td><td>'.toDecimal($glo_agent_details["val_1"], 2).'</td><td>'.toDecimal($glo_api_details["val_1"], 2).'</td>
									</tr>';
							}
						}
						return $product_tr_list;
					}
					echo airtimeAPIDoc();
				?>
				</table>
			</div>
		</div>
		<div id="data-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating Data API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">DATA BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/data.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* network: <span class="text-bold-400">This is the service you are paying for e.g mtn et.c network code(Product Code) is on the table below</span><br/>
					* phone_number: <span class="text-bold-400">This is the recipient phone number</span><br/>
					* type: <span class="text-bold-400">This is the type of data e.g sme-data, cg-data, dd-data</span><br/>
					* quantity: <span class="text-bold-400">This is the data size e.g 1gb, 2gb e.t.c</span>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful data transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"You have successfully shared 500mb data to 08124232128"}
				</span>

			</div>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
				<table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
				<tr>
					<th>Product</th><th>Network</th><th>Network Code</th><th>Type</th><th>Data Code(Qty)</th><th>Smart User (N)</th><th>Agent Vendor (N)</th><th>API Vendor (N)</th>
				</tr>
				<?php
					function dataAPIDoc(){
						global $connection_server;
						global $get_logged_user_details;
						$account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
						$product_name_arrays = array(1 => "mtn", 2 => "airtel", 3 => "9mobile", 4 => "glo");
						$acc_smart_level_table_name = $account_level_table_name_arrays[1];
						$acc_agent_level_table_name = $account_level_table_name_arrays[2];
						$acc_api_level_table_name = $account_level_table_name_arrays[3];
						
						//PRODUCT NAME
						$mtn_product_name = $product_name_arrays[1];
						$airtel_product_name = $product_name_arrays[2];
						$etisalat_product_name = $product_name_arrays[3];
						$glo_product_name = $product_name_arrays[4];
						
						//MTN INFORMATION
						$mtn_data_api_id_array = [];
						$mtn_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='sme-data' OR api_type='cg-data' OR api_type='dd-data')");
						if(mysqli_num_rows($mtn_data_api_lists) > 0){
							while($mtn_data_detail = mysqli_fetch_assoc($mtn_data_api_lists)){
								$mtn_data_api_id_array[] = $mtn_data_detail["id"];
							}
						}
						foreach($mtn_data_api_id_array as $mtn_api_id){
							$mtn_api_id_statement .= "api_id='$mtn_api_id'" . "\n";
						}
						if(!empty($mtn_api_id_statement)){
							$mtn_api_id_statement = "(".str_replace("\n", " OR ", trim($mtn_api_id_statement)).") ";
						}else{
							$mtn_api_id_statement = " api_id='' ";
						}
						$mtn_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$mtn_product_name."' LIMIT 1"));
						if(!empty($mtn_product_table["id"])){
							$mtn_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
						}

						//AIRTEL INFORMATION
						$airtel_data_api_id_array = [];
						$airtel_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='sme-data' OR api_type='cg-data' OR api_type='dd-data')");
						if(mysqli_num_rows($airtel_data_api_lists) > 0){
							while($airtel_data_detail = mysqli_fetch_assoc($airtel_data_api_lists)){
								$airtel_data_api_id_array[] = $airtel_data_detail["id"];
							}
						}
						foreach($airtel_data_api_id_array as $airtel_api_id){
							$airtel_api_id_statement .= "api_id='$airtel_api_id'" . "\n";
						}
						if(!empty($airtel_api_id_statement)){
							$airtel_api_id_statement = "(".str_replace("\n", " OR ", trim($airtel_api_id_statement)).") && ";
						}else{
							$airtel_api_id_statement = " api_id='' ";
						}
						$airtel_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$airtel_product_name."' LIMIT 1"));
						if(!empty($airtel_product_table["id"])){
							$airtel_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
						}

						//ETISALAT INFORMATION
						$etisalat_data_api_id_array = [];
						$etisalat_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='sme-data' OR api_type='cg-data' OR api_type='dd-data')");
						if(mysqli_num_rows($etisalat_data_api_lists) > 0){
							while($etisalat_data_detail = mysqli_fetch_assoc($etisalat_data_api_lists)){
								$etisalat_data_api_id_array[] = $etisalat_data_detail["id"];
							}
						}
						foreach($etisalat_data_api_id_array as $etisalat_api_id){
							$etisalat_api_id_statement .= "api_id='$etisalat_api_id'" . "\n";
						}
						if(!empty($etisalat_api_id_statement)){
							$etisalat_api_id_statement = "(".str_replace("\n", " OR ", trim($etisalat_api_id_statement)).")";
						}else{
							$etisalat_api_id_statement = " api_id='' ";
						}
						$etisalat_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$etisalat_product_name."' LIMIT 1"));
						if(!empty($etisalat_product_table["id"])){
							$etisalat_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
						}

						//GLO INFORMATION
						$glo_data_api_id_array = [];
						$glo_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='sme-data' OR api_type='cg-data' OR api_type='dd-data')");
						if(mysqli_num_rows($glo_data_api_lists) > 0){
							while($glo_data_detail = mysqli_fetch_assoc($glo_data_api_lists)){
								$glo_data_api_id_array[] = $glo_data_detail["id"];
							}
						}
						foreach($glo_data_api_id_array as $glo_api_id){
							$glo_api_id_statement .= "api_id='$glo_api_id'" . "\n";
						}
						if(!empty($glo_api_id_statement)){
							$glo_api_id_statement = "(".str_replace("\n", " OR ", trim($glo_api_id_statement)).")";
						}else{
							$glo_api_id_statement = " api_id='' ";
						}
						$glo_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$glo_product_name."' LIMIT 1"));
						if(!empty($glo_product_table["id"])){
							$glo_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
						}

						//MTN PRICE LISTING
						if(isset($mtn_smart_product_discount_table) && (mysqli_num_rows($mtn_smart_product_discount_table) > 0)){
							while(($mtn_smart_details = mysqli_fetch_assoc($mtn_smart_product_discount_table)) && ($mtn_agent_details = mysqli_fetch_assoc($mtn_agent_product_discount_table)) && ($mtn_api_details = mysqli_fetch_assoc($mtn_api_product_discount_table))){
								$data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$mtn_smart_details["api_id"]."' LIMIT 1"));
								$product_tr_list .= '<tr>
										<td>Internet Data</td><td>MTN</td><td>'.$mtn_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$mtn_smart_details["val_1"].'</td><td>'.toDecimal($mtn_smart_details["val_2"], 2).'</td><td>'.toDecimal($mtn_agent_details["val_2"], 2).'</td><td>'.toDecimal($mtn_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//AIRTEL PRICE LISTING
						if(isset($airtel_smart_product_discount_table) && (mysqli_num_rows($airtel_smart_product_discount_table) > 0)){
							while(($airtel_smart_details = mysqli_fetch_assoc($airtel_smart_product_discount_table)) && ($airtel_agent_details = mysqli_fetch_assoc($airtel_agent_product_discount_table)) && ($airtel_api_details = mysqli_fetch_assoc($airtel_api_product_discount_table))){
								$data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$airtel_smart_details["api_id"]."' LIMIT 1"));
								$product_tr_list .= '<tr>
										<td>Internet Data</td><td>Airtel</td><td>'.$airtel_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$airtel_smart_details["val_1"].'</td><td>'.toDecimal($airtel_smart_details["val_2"], 2).'</td><td>'.toDecimal($airtel_agent_details["val_2"], 2).'</td><td>'.toDecimal($airtel_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//ETISALAT PRICE LISTING
						if(isset($etisalat_smart_product_discount_table) && (mysqli_num_rows($etisalat_smart_product_discount_table) > 0)){
							while(($etisalat_smart_details = mysqli_fetch_assoc($etisalat_smart_product_discount_table)) && ($etisalat_agent_details = mysqli_fetch_assoc($etisalat_agent_product_discount_table)) && ($etisalat_api_details = mysqli_fetch_assoc($etisalat_api_product_discount_table))){
								$data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$etisalat_smart_details["api_id"]."' LIMIT 1"));
								$product_tr_list .= '<tr>
										<td>Internet Data</td><td>9mobile</td><td>'.$etisalat_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$etisalat_smart_details["val_1"].'</td><td>'.toDecimal($etisalat_smart_details["val_2"], 2).'</td><td>'.toDecimal($etisalat_agent_details["val_2"], 2).'</td><td>'.toDecimal($etisalat_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//GLO PRICE LISTING
						if(isset($glo_smart_product_discount_table) && (mysqli_num_rows($glo_smart_product_discount_table) > 0)){
							while(($glo_smart_details = mysqli_fetch_assoc($glo_smart_product_discount_table)) && ($glo_agent_details = mysqli_fetch_assoc($glo_agent_product_discount_table)) && ($glo_api_details = mysqli_fetch_assoc($glo_api_product_discount_table))){
								$data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$glo_smart_details["api_id"]."' LIMIT 1"));
								$product_tr_list .= '<tr>
										<td>Internet Data</td><td>GLO</td><td>'.$glo_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$glo_smart_details["val_1"].'</td><td>'.toDecimal($glo_smart_details["val_2"], 2).'</td><td>'.toDecimal($glo_agent_details["val_2"], 2).'</td><td>'.toDecimal($glo_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						return $product_tr_list;
					}
					echo dataAPIDoc();
                ?>
				</table>
			</div>
		</div>
		<div id="cable-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating Cable API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">CABLE BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/cable.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* type: <span class="text-bold-400">This is the service you are paying for e.g startimes et.c Type is on the table below</span><br/>
					* iuc_number: <span class="text-bold-400">This is the recipient IUC number</span><br/>
					* package: <span class="text-bold-400">This is the selected cable package to subscribe e.g nova, jolli, padi et.c</span><br/>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful cable transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"You have successfully purchased startime nova package for 02145XXXXXX"}
				</span><br/>
				
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">VERIFY CABLE BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/verify-cable.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* type: <span class="text-bold-400">This is the service you are paying for e.g startimes et.c Type is on the table below</span><br/>
					* iuc_number: <span class="text-bold-400">This is the recipient IUC number</span><br/>
					* package: <span class="text-bold-400">This is the selected cable package to subscribe e.g nova, jolli, padi et.c</span><br/>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful cable transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"status": "success", "desc":"Abdulrahaman Habeebullahi"}
				</span>
						
			</div>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
				<table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
				<tr>
					<th>Product</th><th>Cable</th><th>Type</th><th>Package</th><th>Smart User (N)</th><th>Agent Vendor (N)</th><th>API Vendor (N)</th>
				</tr>
				<?php
					function cableAPIDoc(){
						global $connection_server;
						global $get_logged_user_details;
						$account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
						$product_name_arrays = array(1 => "startimes", 2 => "dstv", 3 => "gotv", 4 => "glo");
						$acc_smart_level_table_name = $account_level_table_name_arrays[1];
						$acc_agent_level_table_name = $account_level_table_name_arrays[2];
						$acc_api_level_table_name = $account_level_table_name_arrays[3];
						
						//PRODUCT NAME
						$startimes_product_name = $product_name_arrays[1];
						$dstv_product_name = $product_name_arrays[2];
						$gotv_product_name = $product_name_arrays[3];
						
						//STARTIMES INFORMATION
						$startimes_cable_api_id_array = [];
						$startimes_cable_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='cable'");
						if(mysqli_num_rows($startimes_cable_api_lists) > 0){
							while($startimes_cable_detail = mysqli_fetch_assoc($startimes_cable_api_lists)){
								$startimes_cable_api_id_array[] = $startimes_cable_detail["id"];
							}
						}
						foreach($startimes_cable_api_id_array as $startimes_api_id){
							$startimes_api_id_statement .= "api_id='$startimes_api_id'" . "\n";
						}
						$startimes_api_id_statement = "(".str_replace("\n", " OR ", trim($startimes_api_id_statement)).")";
						$startimes_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$startimes_product_name."' LIMIT 1"));
						if(!empty($startimes_product_table["id"])){
							$startimes_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $startimes_api_id_statement && product_id='".$startimes_product_table["id"]."'");
							$startimes_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $startimes_api_id_statement && product_id='".$startimes_product_table["id"]."'");
							$startimes_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $startimes_api_id_statement && product_id='".$startimes_product_table["id"]."'");
						}

						//DSTV INFORMATION
						$dstv_cable_api_id_array = [];
						$dstv_cable_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='cable'");
						if(mysqli_num_rows($dstv_cable_api_lists) > 0){
							while($dstv_cable_detail = mysqli_fetch_assoc($dstv_cable_api_lists)){
								$dstv_cable_api_id_array[] = $dstv_cable_detail["id"];
							}
						}
						foreach($dstv_cable_api_id_array as $dstv_api_id){
							$dstv_api_id_statement .= "api_id='$dstv_api_id'" . "\n";
						}
						$dstv_api_id_statement = "(".str_replace("\n", " OR ", trim($dstv_api_id_statement)).")";
						$dstv_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$dstv_product_name."' LIMIT 1"));
						if(!empty($dstv_product_table["id"])){
							$dstv_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $dstv_api_id_statement && product_id='".$dstv_product_table["id"]."'");
							$dstv_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $dstv_api_id_statement && product_id='".$dstv_product_table["id"]."'");
							$dstv_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $dstv_api_id_statement && product_id='".$dstv_product_table["id"]."'");
						}
						//GOTV INFORMATION
						$gotv_cable_api_id_array = [];
						$gotv_cable_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='cable'");
						if(mysqli_num_rows($gotv_cable_api_lists) > 0){
							while($gotv_cable_detail = mysqli_fetch_assoc($gotv_cable_api_lists)){
								$gotv_cable_api_id_array[] = $gotv_cable_detail["id"];
							}
						}
						foreach($gotv_cable_api_id_array as $gotv_api_id){
							$gotv_api_id_statement .= "api_id='$gotv_api_id'" . "\n";
						}
						$gotv_api_id_statement = "(".str_replace("\n", " OR ", trim($gotv_api_id_statement)).")";
						$gotv_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$gotv_product_name."' LIMIT 1"));
						if(!empty($gotv_product_table["id"])){
							$gotv_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $gotv_api_id_statement && product_id='".$gotv_product_table["id"]."'");
							$gotv_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $gotv_api_id_statement && product_id='".$gotv_product_table["id"]."'");
							$gotv_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $gotv_api_id_statement && product_id='".$gotv_product_table["id"]."'");
						}

						//STARTIMES PRICE LISTING
						if(isset($startimes_smart_product_discount_table) && (mysqli_num_rows($startimes_smart_product_discount_table) > 0)){
							while(($startimes_smart_details = mysqli_fetch_assoc($startimes_smart_product_discount_table)) && ($startimes_agent_details = mysqli_fetch_assoc($startimes_agent_product_discount_table)) && ($startimes_api_details = mysqli_fetch_assoc($startimes_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Cable</td><td>Startimes</td><td>'.$startimes_product_table["product_name"] .'</td><td>'.$startimes_smart_details["val_1"].'</td><td>'.toDecimal($startimes_smart_details["val_2"], 2).'</td><td>'.toDecimal($startimes_agent_details["val_2"], 2).'</td><td>'.toDecimal($startimes_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//DSTV PRICE LISTING
						if(isset($dstv_smart_product_discount_table) && (mysqli_num_rows($dstv_smart_product_discount_table) > 0)){
							while(($dstv_smart_details = mysqli_fetch_assoc($dstv_smart_product_discount_table)) && ($dstv_agent_details = mysqli_fetch_assoc($dstv_agent_product_discount_table)) && ($dstv_api_details = mysqli_fetch_assoc($dstv_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Cable</td><td>DSTV</td><td>'.$dstv_product_table["product_name"].'</td><td>'.$dstv_smart_details["val_1"].'</td><td>'.toDecimal($dstv_smart_details["val_2"], 2).'</td><td>'.toDecimal($dstv_agent_details["val_2"], 2).'</td><td>'.toDecimal($dstv_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//GOTV PRICE LISTING
						if(isset($gotv_smart_product_discount_table) && (mysqli_num_rows($gotv_smart_product_discount_table) > 0)){
							while(($gotv_smart_details = mysqli_fetch_assoc($gotv_smart_product_discount_table)) && ($gotv_agent_details = mysqli_fetch_assoc($gotv_agent_product_discount_table)) && ($gotv_api_details = mysqli_fetch_assoc($gotv_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Cable</td><td>GOTV</td><td>'.$gotv_product_table["product_name"].'</td><td>'.$gotv_smart_details["val_1"].'</td><td>'.toDecimal($gotv_smart_details["val_2"], 2).'</td><td>'.toDecimal($gotv_agent_details["val_2"], 2).'</td><td>'.toDecimal($gotv_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						return $product_tr_list;
					}
					echo cableAPIDoc();
				?>
				</table>
			</div>
		</div>
		
		<div id="exam-pin-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating Exam API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">EXAM BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/exam.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* type: <span class="text-bold-400">This is the service you are paying for e.g waec et.c Type is on the table below</span><br/>
					* quantity: <span class="text-bold-400">This is the quantity. Qty in the table</span>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful exam transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"Waec Pin: 47754668347974836473"}
				</span>
		
			</div>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
				<table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
				<tr>
					<th>Product</th><th>Exam</th><th>Type</th><th>Qty</th><th>Smart User (N)</th><th>Agent Vendor (N)</th><th>API Vendor (N)</th>
				</tr>
				<?php
					function examAPIDoc(){
						global $connection_server;
						global $get_logged_user_details;
						$account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
						$product_name_arrays = array(1 => "waec", 2 => "neco", 3 => "nabteb", 4 => "jamb");
						$acc_smart_level_table_name = $account_level_table_name_arrays[1];
						$acc_agent_level_table_name = $account_level_table_name_arrays[2];
						$acc_api_level_table_name = $account_level_table_name_arrays[3];
						
						//PRODUCT NAME
						$waec_product_name = $product_name_arrays[1];
						$neco_product_name = $product_name_arrays[2];
						$nabteb_product_name = $product_name_arrays[3];
						$jamb_product_name = $product_name_arrays[4];
						
						//WAEC INFORMATION
						$waec_exam_api_id_array = [];
						$waec_exam_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='exam'");
						if(mysqli_num_rows($waec_exam_api_lists) > 0){
							while($waec_exam_detail = mysqli_fetch_assoc($waec_exam_api_lists)){
								$waec_exam_api_id_array[] = $waec_exam_detail["id"];
							}
						}
						foreach($waec_exam_api_id_array as $waec_api_id){
							$waec_api_id_statement .= "api_id='$waec_api_id'" . "\n";
						}
						$waec_api_id_statement = "(".str_replace("\n", " OR ", trim($waec_api_id_statement)).")";
						$waec_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$waec_product_name."' LIMIT 1"));
						if(!empty($waec_product_table["id"])){
							$waec_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $waec_api_id_statement && product_id='".$waec_product_table["id"]."'");
							$waec_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $waec_api_id_statement && product_id='".$waec_product_table["id"]."'");
							$waec_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $waec_api_id_statement && product_id='".$waec_product_table["id"]."'");
						}

						//NECO INFORMATION
						$neco_exam_api_id_array = [];
						$neco_exam_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='exam'");
						if(mysqli_num_rows($neco_exam_api_lists) > 0){
							while($neco_exam_detail = mysqli_fetch_assoc($neco_exam_api_lists)){
								$neco_exam_api_id_array[] = $neco_exam_detail["id"];
							}
						}
						foreach($neco_exam_api_id_array as $neco_api_id){
							$neco_api_id_statement .= "api_id='$neco_api_id'" . "\n";
						}
						$neco_api_id_statement = "(".str_replace("\n", " OR ", trim($neco_api_id_statement)).")";
						$neco_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$neco_product_name."' LIMIT 1"));
						if(!empty($neco_product_table["id"])){
							$neco_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $neco_api_id_statement && product_id='".$neco_product_table["id"]."'");
							$neco_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $neco_api_id_statement && product_id='".$neco_product_table["id"]."'");
							$neco_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $neco_api_id_statement && product_id='".$neco_product_table["id"]."'");
						}

						//NABTEB INFORMATION
						$nabteb_exam_api_id_array = [];
						$nabteb_exam_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='exam'");
						if(mysqli_num_rows($nabteb_exam_api_lists) > 0){
							while($nabteb_exam_detail = mysqli_fetch_assoc($nabteb_exam_api_lists)){
								$nabteb_exam_api_id_array[] = $nabteb_exam_detail["id"];
							}
						}
						foreach($nabteb_exam_api_id_array as $nabteb_api_id){
							$nabteb_api_id_statement .= "api_id='$nabteb_api_id'" . "\n";
						}
						$nabteb_api_id_statement = "(".str_replace("\n", " OR ", trim($nabteb_api_id_statement)).")";
						$nabteb_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$nabteb_product_name."' LIMIT 1"));
						if(!empty($nabteb_product_table["id"])){
							$nabteb_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $nabteb_api_id_statement && product_id='".$nabteb_product_table["id"]."'");
							$nabteb_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $nabteb_api_id_statement && product_id='".$nabteb_product_table["id"]."'");
							$nabteb_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $nabteb_api_id_statement && product_id='".$nabteb_product_table["id"]."'");
						}

						//JAMB INFORMATION
						$jamb_exam_api_id_array = [];
						$jamb_exam_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='exam'");
						if(mysqli_num_rows($jamb_exam_api_lists) > 0){
							while($jamb_exam_detail = mysqli_fetch_assoc($jamb_exam_api_lists)){
								$jamb_exam_api_id_array[] = $jamb_exam_detail["id"];
							}
						}
						foreach($jamb_exam_api_id_array as $jamb_api_id){
							$jamb_api_id_statement .= "api_id='$jamb_api_id'" . "\n";
						}
						$jamb_api_id_statement = "(".str_replace("\n", " OR ", trim($jamb_api_id_statement)).")";
						$jamb_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$jamb_product_name."' LIMIT 1"));
						if(!empty($jamb_product_table["id"])){
							$jamb_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $jamb_api_id_statement && product_id='".$jamb_product_table["id"]."'");
							$jamb_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $jamb_api_id_statement && product_id='".$jamb_product_table["id"]."'");
							$jamb_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $jamb_api_id_statement && product_id='".$jamb_product_table["id"]."'");
						}

						//WAEC PRICE LISTING
						if(isset($waec_smart_product_discount_table) && (mysqli_num_rows($waec_smart_product_discount_table) > 0)){
							while(($waec_smart_details = mysqli_fetch_assoc($waec_smart_product_discount_table)) && ($waec_agent_details = mysqli_fetch_assoc($waec_agent_product_discount_table)) && ($waec_api_details = mysqli_fetch_assoc($waec_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Exam PIN</td><td>WAEC</td><td>'.$waec_product_table["product_name"] .'</td><td>'.$waec_smart_details["val_1"].'</td><td>'.toDecimal($waec_smart_details["val_2"], 2).'</td><td>'.toDecimal($waec_agent_details["val_2"], 2).'</td><td>'.toDecimal($waec_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//NECO PRICE LISTING
						if(isset($neco_smart_product_discount_table) && (mysqli_num_rows($neco_smart_product_discount_table) > 0)){
							while(($neco_smart_details = mysqli_fetch_assoc($neco_smart_product_discount_table)) && ($neco_agent_details = mysqli_fetch_assoc($neco_agent_product_discount_table)) && ($neco_api_details = mysqli_fetch_assoc($neco_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Exam PIN</td><td>NECO</td><td>'.$neco_product_table["product_name"].'</td><td>'.$neco_smart_details["val_1"].'</td><td>'.toDecimal($neco_smart_details["val_2"], 2).'</td><td>'.toDecimal($neco_agent_details["val_2"], 2).'</td><td>'.toDecimal($neco_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//NABTEB PRICE LISTING
						if(isset($nabteb_smart_product_discount_table) && (mysqli_num_rows($nabteb_smart_product_discount_table) > 0)){
							while(($nabteb_smart_details = mysqli_fetch_assoc($nabteb_smart_product_discount_table)) && ($nabteb_agent_details = mysqli_fetch_assoc($nabteb_agent_product_discount_table)) && ($nabteb_api_details = mysqli_fetch_assoc($nabteb_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Exam PIN</td><td>NABTEB</td><td>'.$nabteb_product_table["product_name"].'</td><td>'.$nabteb_smart_details["val_1"].'</td><td>'.toDecimal($nabteb_smart_details["val_2"], 2).'</td><td>'.toDecimal($nabteb_agent_details["val_2"], 2).'</td><td>'.toDecimal($nabteb_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						//JAMB PRICE LISTING
						if(isset($jamb_smart_product_discount_table) && (mysqli_num_rows($jamb_smart_product_discount_table) > 0)){
							while(($jamb_smart_details = mysqli_fetch_assoc($jamb_smart_product_discount_table)) && ($jamb_agent_details = mysqli_fetch_assoc($jamb_agent_product_discount_table)) && ($jamb_api_details = mysqli_fetch_assoc($jamb_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Exam PIN</td><td>JAMB</td><td>'.$jamb_product_table["product_name"].'</td><td>'.$jamb_smart_details["val_1"].'</td><td>'.toDecimal($jamb_smart_details["val_2"], 2).'</td><td>'.toDecimal($jamb_agent_details["val_2"], 2).'</td><td>'.toDecimal($jamb_api_details["val_2"], 2).'</td>
									</tr>';
							}
						}
						
						return $product_tr_list;
					}
					echo examAPIDoc();
				?>
				</table>
			</div>
		</div>
		
		<div id="electric-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating Electric API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">ELECTRIC BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/electric.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* type: <span class="text-bold-400">This is the service you are paying for e.g prepaid or postpaid et.c</span><br/>
					* meter_number: <span class="text-bold-400">This is the recipient Meter number</span><br/>
					* provider: <span class="text-bold-400">This is the selected cable package to subscribe e.g ikedc, jedc, ibedc et.c</span><br/>
					* amount: <span class="text-bold-400">This is the amount of electricity unit to purchase</span>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful electric transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"Transaction Successful | Meter Number: 0987545678 | Meter Token: 98765435678976765"}
				</span><br/>
		
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">VERIFY ELECTRIC BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/verify-electric.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* type: <span class="text-bold-400">This is the service you are paying for e.g prepaid or postpaid et.c</span><br/>
					* meter_number: <span class="text-bold-400">This is the recipient Meter number</span><br/>
					* provider: <span class="text-bold-400">This is the selected cable package to subscribe e.g ikedc, jedc, ibedc et.c</span><br/>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful electric verification, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"status": "success", "desc":"Abdulrahaman Habeebullahi"}
				</span>
			</div>
			<div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
				<table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
				<tr>
					<th>Product</th><th>Electric</th><th>Provider</th><th>Type</th><th>Smart User (%)</th><th>Agent Vendor (%)</th><th>API Vendor (%)</th>
				</tr>
				<?php
					function electricAPIDoc(){
						global $connection_server;
						global $get_logged_user_details;
						$account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                        $product_name_arrays = array(1 => "ekedc",2 => "eedc",3 => "ikedc",4 => "jedc",5 => "kedco",6 => "ibedc",7 => "phed",8 => "aedc", 9 => "yedc");
                        $acc_smart_level_table_name = $account_level_table_name_arrays[1];
                        $acc_agent_level_table_name = $account_level_table_name_arrays[2];
                        $acc_api_level_table_name = $account_level_table_name_arrays[3];
                        
                        //PRODUCT NAME
                        $ekedc_product_name = $product_name_arrays[1];
                        $eedc_product_name = $product_name_arrays[2];
                        $ikedc_product_name = $product_name_arrays[3];
                        $jedc_product_name = $product_name_arrays[4];
                        $kedco_product_name = $product_name_arrays[5];
                        $ibedc_product_name = $product_name_arrays[6];
                        $phed_product_name = $product_name_arrays[7];
                        $aedc_product_name = $product_name_arrays[8];
                        $yedc_product_name = $product_name_arrays[9];
                        
                        //EKEDC INFORMATION
                        $ekedc_electric_api_id_array = [];
                        $ekedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($ekedc_electric_api_lists) > 0){
                            while($ekedc_electric_detail = mysqli_fetch_assoc($ekedc_electric_api_lists)){
                                $ekedc_electric_api_id_array[] = $ekedc_electric_detail["id"];
                            }
                        }
                        foreach($ekedc_electric_api_id_array as $ekedc_api_id){
                            $ekedc_api_id_statement .= "api_id='$ekedc_api_id'" . "\n";
                        }
                        $ekedc_api_id_statement = "(".str_replace("\n", " OR ", trim($ekedc_api_id_statement)).")";
                        $ekedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$ekedc_product_name."' LIMIT 1"));
						if(!empty($ekedc_product_table["id"])){
							$ekedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ekedc_api_id_statement && product_id='".$ekedc_product_table["id"]."'");
							$ekedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ekedc_api_id_statement && product_id='".$ekedc_product_table["id"]."'");
							$ekedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ekedc_api_id_statement && product_id='".$ekedc_product_table["id"]."'");
						}

                        //EEDC INFORMATION
                        $eedc_electric_api_id_array = [];
                        $eedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($eedc_electric_api_lists) > 0){
                            while($eedc_electric_detail = mysqli_fetch_assoc($eedc_electric_api_lists)){
                                $eedc_electric_api_id_array[] = $eedc_electric_detail["id"];
                            }
                        }
                        foreach($eedc_electric_api_id_array as $eedc_api_id){
                            $eedc_api_id_statement .= "api_id='$eedc_api_id'" . "\n";
                        }
                        $eedc_api_id_statement = "(".str_replace("\n", " OR ", trim($eedc_api_id_statement)).")";
                        $eedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$eedc_product_name."' LIMIT 1"));
						if(!empty($eedc_product_table["id"])){
							$eedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $eedc_api_id_statement && product_id='".$eedc_product_table["id"]."'");
							$eedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $eedc_api_id_statement && product_id='".$eedc_product_table["id"]."'");
							$eedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $eedc_api_id_statement && product_id='".$eedc_product_table["id"]."'");
						}

                        //IKEDC INFORMATION
                        $ikedc_electric_api_id_array = [];
                        $ikedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($ikedc_electric_api_lists) > 0){
                            while($ikedc_electric_detail = mysqli_fetch_assoc($ikedc_electric_api_lists)){
                                $ikedc_electric_api_id_array[] = $ikedc_electric_detail["id"];
                            }
                        }
                        foreach($ikedc_electric_api_id_array as $ikedc_api_id){
                            $ikedc_api_id_statement .= "api_id='$ikedc_api_id'" . "\n";
                        }
                        $ikedc_api_id_statement = "(".str_replace("\n", " OR ", trim($ikedc_api_id_statement)).")";
                        $ikedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$ikedc_product_name."' LIMIT 1"));
						if(!empty($ikedc_product_table["id"])){
							$ikedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ikedc_api_id_statement && product_id='".$ikedc_product_table["id"]."'");
							$ikedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ikedc_api_id_statement && product_id='".$ikedc_product_table["id"]."'");
							$ikedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ikedc_api_id_statement && product_id='".$ikedc_product_table["id"]."'");
						}

						//JEDC INFORMATION
                        $jedc_electric_api_id_array = [];
                        $jedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($jedc_electric_api_lists) > 0){
                            while($jedc_electric_detail = mysqli_fetch_assoc($jedc_electric_api_lists)){
                                $jedc_electric_api_id_array[] = $jedc_electric_detail["id"];
                            }
                        }
                        foreach($jedc_electric_api_id_array as $jedc_api_id){
                            $jedc_api_id_statement .= "api_id='$jedc_api_id'" . "\n";
                        }
                        $jedc_api_id_statement = "(".str_replace("\n", " OR ", trim($jedc_api_id_statement)).")";
                        $jedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$jedc_product_name."' LIMIT 1"));
						if(!empty($jedc_product_table["id"])){
							$jedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $jedc_api_id_statement && product_id='".$jedc_product_table["id"]."'");
							$jedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $jedc_api_id_statement && product_id='".$jedc_product_table["id"]."'");
							$jedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $jedc_api_id_statement && product_id='".$jedc_product_table["id"]."'");
						}

                        //KEDCO INFORMATION
                        $kedco_electric_api_id_array = [];
                        $kedco_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($kedco_electric_api_lists) > 0){
                            while($kedco_electric_detail = mysqli_fetch_assoc($kedco_electric_api_lists)){
                                $kedco_electric_api_id_array[] = $kedco_electric_detail["id"];
                            }
                        }
                        foreach($kedco_electric_api_id_array as $kedco_api_id){
                            $kedco_api_id_statement .= "api_id='$kedco_api_id'" . "\n";
                        }
                        $kedco_api_id_statement = "(".str_replace("\n", " OR ", trim($kedco_api_id_statement)).")";
                        $kedco_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$kedco_product_name."' LIMIT 1"));
						if(!empty($kedco_product_table["id"])){
							$kedco_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $kedco_api_id_statement && product_id='".$kedco_product_table["id"]."'");
							$kedco_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $kedco_api_id_statement && product_id='".$kedco_product_table["id"]."'");
							$kedco_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $kedco_api_id_statement && product_id='".$kedco_product_table["id"]."'");
						}

                        //IBEDC INFORMATION
                        $ibedc_electric_api_id_array = [];
                        $ibedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($ibedc_electric_api_lists) > 0){
                            while($ibedc_electric_detail = mysqli_fetch_assoc($ibedc_electric_api_lists)){
                                $ibedc_electric_api_id_array[] = $ibedc_electric_detail["id"];
                            }
                        }
                        foreach($ibedc_electric_api_id_array as $ibedc_api_id){
                            $ibedc_api_id_statement .= "api_id='$ibedc_api_id'" . "\n";
                        }
                        $ibedc_api_id_statement = "(".str_replace("\n", " OR ", trim($ibedc_api_id_statement)).")";
                        $ibedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$ibedc_product_name."' LIMIT 1"));
						if(!empty($ibedc_product_table["id"])){
							$ibedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ibedc_api_id_statement && product_id='".$ibedc_product_table["id"]."'");
							$ibedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ibedc_api_id_statement && product_id='".$ibedc_product_table["id"]."'");
							$ibedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $ibedc_api_id_statement && product_id='".$ibedc_product_table["id"]."'");
						}

						//PHED INFORMATION
                        $phed_electric_api_id_array = [];
                        $phed_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($phed_electric_api_lists) > 0){
                            while($phed_electric_detail = mysqli_fetch_assoc($phed_electric_api_lists)){
                                $phed_electric_api_id_array[] = $phed_electric_detail["id"];
                            }
                        }
                        foreach($phed_electric_api_id_array as $phed_api_id){
                            $phed_api_id_statement .= "api_id='$phed_api_id'" . "\n";
                        }
                        $phed_api_id_statement = "(".str_replace("\n", " OR ", trim($phed_api_id_statement)).")";
                        $phed_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$phed_product_name."' LIMIT 1"));
						if(!empty($phed_product_table["id"])){
							$phed_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $phed_api_id_statement && product_id='".$phed_product_table["id"]."'");
							$phed_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $phed_api_id_statement && product_id='".$phed_product_table["id"]."'");
							$phed_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $phed_api_id_statement && product_id='".$phed_product_table["id"]."'");
						}

                        //AEDC INFORMATION
                        $aedc_electric_api_id_array = [];
                        $aedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
                        if(mysqli_num_rows($aedc_electric_api_lists) > 0){
                            while($aedc_electric_detail = mysqli_fetch_assoc($aedc_electric_api_lists)){
                                $aedc_electric_api_id_array[] = $aedc_electric_detail["id"];
                            }
                        }
                        foreach($aedc_electric_api_id_array as $aedc_api_id){
                            $aedc_api_id_statement .= "api_id='$aedc_api_id'" . "\n";
                        }
                        $aedc_api_id_statement = "(".str_replace("\n", " OR ", trim($aedc_api_id_statement)).")";
                        $aedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$aedc_product_name."' LIMIT 1"));
						if(!empty($aedc_product_table["id"])){
							$aedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $aedc_api_id_statement && product_id='".$aedc_product_table["id"]."'");
							$aedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $aedc_api_id_statement && product_id='".$aedc_product_table["id"]."'");
							$aedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $aedc_api_id_statement && product_id='".$aedc_product_table["id"]."'");
						}
						
						//YEDC INFORMATION
						$yedc_electric_api_id_array = [];
						$yedc_electric_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='electric'");
						if(mysqli_num_rows($yedc_electric_api_lists) > 0){
							while($yedc_electric_detail = mysqli_fetch_assoc($yedc_electric_api_lists)){
								$yedc_electric_api_id_array[] = $yedc_electric_detail["id"];
							}
						}
						foreach($yedc_electric_api_id_array as $yedc_api_id){
							$yedc_api_id_statement .= "api_id='$yedc_api_id'" . "\n";
						}
						$yedc_api_id_statement = "(".str_replace("\n", " OR ", trim($yedc_api_id_statement)).")";
						$yedc_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$yedc_product_name."' LIMIT 1"));
						if(!empty($yedc_product_table["id"])){
							$yedc_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $yedc_api_id_statement && product_id='".$yedc_product_table["id"]."'");
							$yedc_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $yedc_api_id_statement && product_id='".$yedc_product_table["id"]."'");
							$yedc_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $yedc_api_id_statement && product_id='".$yedc_product_table["id"]."'");
						}
						
                        //EKEDC PRICE LISTING
                        if(isset($ekedc_smart_product_discount_table) && (mysqli_num_rows($ekedc_smart_product_discount_table) > 0)){
                            while(($ekedc_smart_details = mysqli_fetch_assoc($ekedc_smart_product_discount_table)) && ($ekedc_agent_details = mysqli_fetch_assoc($ekedc_agent_product_discount_table)) && ($ekedc_api_details = mysqli_fetch_assoc($ekedc_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>Ekedc</td><td>'.$ekedc_product_table["product_name"] .'</td><td>prepaid or postpaid</td><td>'.toDecimal($ekedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($ekedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($ekedc_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //EEDC PRICE LISTING
                        if(isset($eedc_smart_product_discount_table) && (mysqli_num_rows($eedc_smart_product_discount_table) > 0)){
                            while(($eedc_smart_details = mysqli_fetch_assoc($eedc_smart_product_discount_table)) && ($eedc_agent_details = mysqli_fetch_assoc($eedc_agent_product_discount_table)) && ($eedc_api_details = mysqli_fetch_assoc($eedc_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>EEDC</td><td>'.$eedc_product_table["product_name"].'</td><td>prepaid or postpaid</td><td>'.toDecimal($eedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($eedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($eedc_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //IKEDC PRICE LISTING
                        if(isset($ikedc_smart_product_discount_table) && (mysqli_num_rows($ikedc_smart_product_discount_table) > 0)){
                            while(($ikedc_smart_details = mysqli_fetch_assoc($ikedc_smart_product_discount_table)) && ($ikedc_agent_details = mysqli_fetch_assoc($ikedc_agent_product_discount_table)) && ($ikedc_api_details = mysqli_fetch_assoc($ikedc_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>IKEDC</td><td>'.$ikedc_product_table["product_name"].'</td><td>prepaid or postpaid</td><td>'.toDecimal($ikedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($ikedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($ikedc_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
						//JEDC PRICE LISTING
                        if(isset($jedc_smart_product_discount_table) && (mysqli_num_rows($jedc_smart_product_discount_table) > 0)){
                            while(($jedc_smart_details = mysqli_fetch_assoc($jedc_smart_product_discount_table)) && ($jedc_agent_details = mysqli_fetch_assoc($jedc_agent_product_discount_table)) && ($jedc_api_details = mysqli_fetch_assoc($jedc_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>Jedc</td><td>'.$jedc_product_table["product_name"] .'</td><td>prepaid or postpaid</td><td>'.toDecimal($jedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($jedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($jedc_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //KEDCO PRICE LISTING
                        if(isset($kedco_smart_product_discount_table) && (mysqli_num_rows($kedco_smart_product_discount_table) > 0)){
                            while(($kedco_smart_details = mysqli_fetch_assoc($kedco_smart_product_discount_table)) && ($kedco_agent_details = mysqli_fetch_assoc($kedco_agent_product_discount_table)) && ($kedco_api_details = mysqli_fetch_assoc($kedco_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>KEDCO</td><td>'.$kedco_product_table["product_name"].'</td><td>prepaid or postpaid</td><td>'.toDecimal($kedco_smart_details["val_1"], 2).'</td><td>'.toDecimal($kedco_agent_details["val_1"], 2).'</td><td>'.toDecimal($kedco_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //IBEDC PRICE LISTING
                        if(isset($ibedc_smart_product_discount_table) && (mysqli_num_rows($ibedc_smart_product_discount_table) > 0)){
                            while(($ibedc_smart_details = mysqli_fetch_assoc($ibedc_smart_product_discount_table)) && ($ibedc_agent_details = mysqli_fetch_assoc($ibedc_agent_product_discount_table)) && ($ibedc_api_details = mysqli_fetch_assoc($ibedc_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>IBEDC</td><td>'.$ibedc_product_table["product_name"].'</td><td>prepaid or postpaid</td><td>'.toDecimal($ibedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($ibedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($ibedc_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }

						//PHED PRICE LISTING
                        if(isset($phed_smart_product_discount_table) && (mysqli_num_rows($phed_smart_product_discount_table) > 0)){
                            while(($phed_smart_details = mysqli_fetch_assoc($phed_smart_product_discount_table)) && ($phed_agent_details = mysqli_fetch_assoc($phed_agent_product_discount_table)) && ($phed_api_details = mysqli_fetch_assoc($phed_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>Phed</td><td>'.$phed_product_table["product_name"] .'</td><td>prepaid or postpaid</td><td>'.toDecimal($phed_smart_details["val_1"], 2).'</td><td>'.toDecimal($phed_agent_details["val_1"], 2).'</td><td>'.toDecimal($phed_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //AEDC PRICE LISTING
                        if(isset($aedc_smart_product_discount_table) && (mysqli_num_rows($aedc_smart_product_discount_table) > 0)){
                            while(($aedc_smart_details = mysqli_fetch_assoc($aedc_smart_product_discount_table)) && ($aedc_agent_details = mysqli_fetch_assoc($aedc_agent_product_discount_table)) && ($aedc_api_details = mysqli_fetch_assoc($aedc_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Electric</td><td>AEDC</td><td>'.$aedc_product_table["product_name"].'</td><td>prepaid or postpaid</td><td>'.toDecimal($aedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($aedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($aedc_api_details["val_1"], 2).'</td>
                                    </tr>';
                            }
                        }
						
						//YEDC PRICE LISTING
						if(isset($yedc_smart_product_discount_table) && (mysqli_num_rows($yedc_smart_product_discount_table) > 0)){
							while(($yedc_smart_details = mysqli_fetch_assoc($yedc_smart_product_discount_table)) && ($yedc_agent_details = mysqli_fetch_assoc($yedc_agent_product_discount_table)) && ($yedc_api_details = mysqli_fetch_assoc($yedc_api_product_discount_table))){
								$product_tr_list .= '<tr>
										<td>Electric</td><td>yedc</td><td>'.$yedc_product_table["product_name"].'</td><td>prepaid or postpaid</td><td>'.toDecimal($yedc_smart_details["val_1"], 2).'</td><td>'.toDecimal($yedc_agent_details["val_1"], 2).'</td><td>'.toDecimal($yedc_api_details["val_1"], 2).'</td>
									</tr>';
							}
						}
						
                        return $product_tr_list;


					}
					echo electricAPIDoc();
				?>
				</table>
			</div>
		</div>
        <div id="card-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
            <div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating Card API</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">CARD BASE URL</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
                <input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/card.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
                <span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
                    * api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
                    * network: <span class="text-bold-400">This is the service you are paying for e.g mtn et.c network code(Product Code) is on the table below</span><br/>
                    * qty_number: <span class="text-bold-400">This is the number of card to purchase e.g 5</span><br/>
                    * type: <span class="text-bold-400">This is the type of card e.g datacard, rechargecard</span><br/>
                    * quantity: <span class="text-bold-400">This is the card size e.g 1gb, 2gb, 100, 200  e.t.c</span><br/>
                    * card_name: <span class="text-bold-400">This is the business name on card (Optional) e.g BeeTech Solution</span>
                </span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful data transaction, you get a json response e.g:</span><br/>
                <span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
                    {"ref":873387339873, "status": "success", "desc":"Transaction Successful", "cards":"344333222233322,9643468994422342,754466435799246", "response_desc" => "Transaction Successful"}
                </span>

            </div>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
                <table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                <tr>
                    <th>Product</th><th>Network</th><th>Network Code</th><th>Type</th><th>Product Code(Qty)</th><th>Smart User (%)</th><th>Agent Vendor (%)</th><th>API Vendor (%)</th>
                </tr>
                <?php
                    function dataRechargeAPIDoc(){
                        global $connection_server;
                        global $get_logged_user_details;
                        $account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                        $product_name_arrays = array(1 => "mtn", 2 => "airtel", 3 => "9mobile", 4 => "glo");
                        $acc_smart_level_table_name = $account_level_table_name_arrays[1];
                        $acc_agent_level_table_name = $account_level_table_name_arrays[2];
                        $acc_api_level_table_name = $account_level_table_name_arrays[3];
                        
                        //PRODUCT NAME
                        $mtn_product_name = $product_name_arrays[1];
                        $airtel_product_name = $product_name_arrays[2];
                        $etisalat_product_name = $product_name_arrays[3];
                        $glo_product_name = $product_name_arrays[4];
                        
                        //MTN INFORMATION
                        $mtn_data_api_id_array = [];
                        $mtn_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='datacard' OR api_type='rechargecard')");
                        if(mysqli_num_rows($mtn_data_api_lists) > 0){
                            while($mtn_data_detail = mysqli_fetch_assoc($mtn_data_api_lists)){
                                $mtn_data_api_id_array[] = $mtn_data_detail["id"];
                            }
                        }
                        foreach($mtn_data_api_id_array as $mtn_api_id){
                            $mtn_api_id_statement .= "api_id='$mtn_api_id'" . "\n";
                        }
                        $mtn_api_id_statement = "(".str_replace("\n", " OR ", trim($mtn_api_id_statement)).")";
                        $mtn_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$mtn_product_name."' LIMIT 1"));
						if(!empty($mtn_product_table["id"])){
							$mtn_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
						}

                        //AIRTEL INFORMATION
                        $airtel_data_api_id_array = [];
                        $airtel_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='datacard' OR api_type='rechargecard')");
                        if(mysqli_num_rows($airtel_data_api_lists) > 0){
                            while($airtel_data_detail = mysqli_fetch_assoc($airtel_data_api_lists)){
                                $airtel_data_api_id_array[] = $airtel_data_detail["id"];
                            }
                        }
                        foreach($airtel_data_api_id_array as $airtel_api_id){
                            $airtel_api_id_statement .= "api_id='$airtel_api_id'" . "\n";
                        }
                        $airtel_api_id_statement = "(".str_replace("\n", " OR ", trim($airtel_api_id_statement)).")";
                        $airtel_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$airtel_product_name."' LIMIT 1"));
						if(!empty($airtel_product_table["id"])){
							$airtel_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
						}

                        //ETISALAT INFORMATION
                        $etisalat_data_api_id_array = [];
                        $etisalat_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='datacard' OR api_type='rechargecard')");
                        if(mysqli_num_rows($etisalat_data_api_lists) > 0){
                            while($etisalat_data_detail = mysqli_fetch_assoc($etisalat_data_api_lists)){
                                $etisalat_data_api_id_array[] = $etisalat_data_detail["id"];
                            }
                        }
                        foreach($etisalat_data_api_id_array as $etisalat_api_id){
                            $etisalat_api_id_statement .= "api_id='$etisalat_api_id'" . "\n";
                        }
                        $etisalat_api_id_statement = "(".str_replace("\n", " OR ", trim($etisalat_api_id_statement)).")";
                        $etisalat_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$etisalat_product_name."' LIMIT 1"));
						if(!empty($etisalat_product_table["id"])){
							$etisalat_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
						}

                        //GLO INFORMATION
                        $glo_data_api_id_array = [];
                        $glo_data_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && (api_type='datacard' OR api_type='rechargecard')");
                        if(mysqli_num_rows($glo_data_api_lists) > 0){
                            while($glo_data_detail = mysqli_fetch_assoc($glo_data_api_lists)){
                                $glo_data_api_id_array[] = $glo_data_detail["id"];
                            }
                        }
                        foreach($glo_data_api_id_array as $glo_api_id){
                            $glo_api_id_statement .= "api_id='$glo_api_id'" . "\n";
                        }
                        $glo_api_id_statement = "(".str_replace("\n", " OR ", trim($glo_api_id_statement)).")";
                        $glo_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$glo_product_name."' LIMIT 1"));
						if(!empty($glo_product_table["id"])){
							$glo_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
						}

                        //MTN PRICE LISTING
                        if(isset($mtn_smart_product_discount_table) && (mysqli_num_rows($mtn_smart_product_discount_table) > 0)){
                            while(($mtn_smart_details = mysqli_fetch_assoc($mtn_smart_product_discount_table)) && ($mtn_agent_details = mysqli_fetch_assoc($mtn_agent_product_discount_table)) && ($mtn_api_details = mysqli_fetch_assoc($mtn_api_product_discount_table))){
                                $data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$mtn_smart_details["api_id"]."' LIMIT 1"));
                                $product_tr_list .= '<tr>
                                        <td>Card</td><td>MTN</td><td>'.$mtn_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$mtn_smart_details["val_1"].'</td><td>'.toDecimal($mtn_smart_details["val_2"], 2).'</td><td>'.toDecimal($mtn_agent_details["val_2"], 2).'</td><td>'.toDecimal($mtn_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //AIRTEL PRICE LISTING
                        if(isset($airtel_smart_product_discount_table) && (mysqli_num_rows($airtel_smart_product_discount_table) > 0)){
                            while(($airtel_smart_details = mysqli_fetch_assoc($airtel_smart_product_discount_table)) && ($airtel_agent_details = mysqli_fetch_assoc($airtel_agent_product_discount_table)) && ($airtel_api_details = mysqli_fetch_assoc($airtel_api_product_discount_table))){
                                $data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$airtel_smart_details["api_id"]."' LIMIT 1"));
                                $product_tr_list .= '<tr>
                                        <td>Card</td><td>Airtel</td><td>'.$airtel_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$airtel_smart_details["val_1"].'</td><td>'.toDecimal($airtel_smart_details["val_2"], 2).'</td><td>'.toDecimal($airtel_agent_details["val_2"], 2).'</td><td>'.toDecimal($airtel_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //ETISALAT PRICE LISTING
                        if(isset($etisalat_smart_product_discount_table) && (mysqli_num_rows($etisalat_smart_product_discount_table) > 0)){
                            while(($etisalat_smart_details = mysqli_fetch_assoc($etisalat_smart_product_discount_table)) && ($etisalat_agent_details = mysqli_fetch_assoc($etisalat_agent_product_discount_table)) && ($etisalat_api_details = mysqli_fetch_assoc($etisalat_api_product_discount_table))){
                                $data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$etisalat_smart_details["api_id"]."' LIMIT 1"));
                                $product_tr_list .= '<tr>
                                        <td>Card</td><td>9mobile</td><td>'.$etisalat_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$etisalat_smart_details["val_1"].'</td><td>'.toDecimal($etisalat_smart_details["val_2"], 2).'</td><td>'.toDecimal($etisalat_agent_details["val_2"], 2).'</td><td>'.toDecimal($etisalat_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //GLO PRICE LISTING
                        if(isset($glo_smart_product_discount_table) && (mysqli_num_rows($glo_smart_product_discount_table) > 0)){
                            while(($glo_smart_details = mysqli_fetch_assoc($glo_smart_product_discount_table)) && ($glo_agent_details = mysqli_fetch_assoc($glo_agent_product_discount_table)) && ($glo_api_details = mysqli_fetch_assoc($glo_api_product_discount_table))){
                                $data_type_api_list = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && id='".$glo_smart_details["api_id"]."' LIMIT 1"));
                                $product_tr_list .= '<tr>
                                        <td>Card</td><td>GLO</td><td>'.$glo_product_table["product_name"].'</td><td>'.$data_type_api_list["api_type"].'</td><td>'.$glo_smart_details["val_1"].'</td><td>'.toDecimal($glo_smart_details["val_2"], 2).'</td><td>'.toDecimal($glo_agent_details["val_2"], 2).'</td><td>'.toDecimal($glo_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        return $product_tr_list;
                    }
                    echo dataRechargeAPIDoc();
                ?>
                </table>
            </div>
        </div>

		<div id="bulk-sms-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
            <div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating BULK SMS API</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">BULK SMS BASE URL</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
                <input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/sms.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">STANDARD SMS & FLASH SMS:</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
                <span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
                    * api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
                    * network: <span class="text-bold-400">This is the service you are paying for e.g mtn et.c network code(Product Code) is on the table below</span><br/>
                    * phone_number: <span class="text-bold-400">This is the recipient phone numbers (comma seperated) e.g 09024089614, 08124232128</span><br/>
                    * sender_id: <span class="text-bold-400">This is the approved sms id name e.g beetech</span><br/>
                    * type: <span class="text-bold-400">This is the type of sms e.g standard_sms or flash_sms</span><br/>
                    * message: <span class="text-bold-400">This is the text message</span><br/>
					* date: <span class="text-bold-400">This is the scheldule date e.g YYYY-MM-DD HH:MM:SS (optional)</span>
                </span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful bulk-sms transaction, you get a json response e.g:</span><br/>
                <span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
                    {"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"SMS Sent successfully"}
                </span><br/>

				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">IN-APP OTP SMS:</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
                <span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
                    * api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
                    * network: <span class="text-bold-400">This is the service you are paying for e.g mtn et.c network code(Product Code) is on the table below</span><br/>
                    * phone_number: <span class="text-bold-400">This is the recipient phone number e.g 09024089614</span><br/>
                    * otp_type: <span class="text-bold-400">This is the type of PIN code that will be generated and sent as part of the OTP message. You can set OTP type to <b>numeric</b> or <b>alphanumeric</b></span><br/>
                    * type: <span class="text-bold-400">This is the type of sms e.g otp</span><br/>
                    * pin_attempts: <span class="text-bold-400">This is the number of times the PIN can be attempted before expiration. It has a minimum of one attempt</span><br/>
                    * expires: <span class="text-bold-400">This represents how long the PIN is valid before expiration. The time is in minutes. The minimum time value is 0 and the maximum time value is 60</span><br/>
					* pin_length: <span class="text-bold-400">This is the length of the PIN code. It has a minimum of 4 and maximum of 8.</span>
                </span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
                <span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful bulk-sms transaction, you get a json response e.g:</span><br/>
                <span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
                    {"ref":873387339873, "status": "success", "otp":"948562", "desc":"Transaction Successful", "response_desc":"Transaction Successful"}
                </span>

            </div>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-scroll-x s-scroll-x">
                <table style="width: 130% !important;" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                <tr>
                    <th>Product</th><th>Network</th><th>Product Code</th><th>Type</th><th>Smart User (%)</th><th>Agent Vendor (%)</th><th>API Vendor (%)</th>
                </tr>
                <?php
                    function bulksmsAPIDoc(){
                        global $connection_server;
                        global $get_logged_user_details;
                        $account_level_table_name_arrays = array(1 => "sas_smart_parameter_values", 2 => "sas_agent_parameter_values", 3 => "sas_api_parameter_values");
                        $product_name_arrays = array(1 => "mtn", 2 => "airtel", 3 => "9mobile", 4 => "glo");
                        $acc_smart_level_table_name = $account_level_table_name_arrays[1];
                        $acc_agent_level_table_name = $account_level_table_name_arrays[2];
                        $acc_api_level_table_name = $account_level_table_name_arrays[3];
                        
                        //PRODUCT NAME
                        $mtn_product_name = $product_name_arrays[1];
                        $airtel_product_name = $product_name_arrays[2];
                        $etisalat_product_name = $product_name_arrays[3];
                        $glo_product_name = $product_name_arrays[4];
                        
                        //MTN INFORMATION
                        $mtn_bulk_sms_api_id_array = [];
                        $mtn_bulk_sms_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='bulk-sms'");
                        if(mysqli_num_rows($mtn_bulk_sms_api_lists) > 0){
                            while($mtn_bulk_sms_detail = mysqli_fetch_assoc($mtn_bulk_sms_api_lists)){
                                $mtn_bulk_sms_api_id_array[] = $mtn_bulk_sms_detail["id"];
                            }
                        }
                        foreach($mtn_bulk_sms_api_id_array as $mtn_api_id){
                            $mtn_api_id_statement .= "api_id='$mtn_api_id'" . "\n";
                        }
                        $mtn_api_id_statement = "(".str_replace("\n", " OR ", trim($mtn_api_id_statement)).")";
                        $mtn_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$mtn_product_name."' LIMIT 1"));
						if(!empty($mtn_product_table["id"])){
							$mtn_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
							$mtn_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $mtn_api_id_statement && product_id='".$mtn_product_table["id"]."'");
						}

                        //AIRTEL INFORMATION
                        $airtel_bulk_sms_api_id_array = [];
                        $airtel_bulk_sms_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='bulk-sms'");
                        if(mysqli_num_rows($airtel_bulk_sms_api_lists) > 0){
                            while($airtel_bulk_sms_detail = mysqli_fetch_assoc($airtel_bulk_sms_api_lists)){
                                $airtel_bulk_sms_api_id_array[] = $airtel_bulk_sms_detail["id"];
                            }
                        }
                        foreach($airtel_bulk_sms_api_id_array as $airtel_api_id){
                            $airtel_api_id_statement .= "api_id='$airtel_api_id'" . "\n";
                        }
                        $airtel_api_id_statement = "(".str_replace("\n", " OR ", trim($airtel_api_id_statement)).")";
                        $airtel_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$airtel_product_name."' LIMIT 1"));
						if(!empty($airtel_product_table["id"])){
							$airtel_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
							$airtel_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $airtel_api_id_statement && product_id='".$airtel_product_table["id"]."'");
						}

                        //ETISALAT INFORMATION
                        $etisalat_bulk_sms_api_id_array = [];
                        $etisalat_bulk_sms_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='bulk-sms'");
                        if(mysqli_num_rows($etisalat_bulk_sms_api_lists) > 0){
                            while($etisalat_bulk_sms_detail = mysqli_fetch_assoc($etisalat_bulk_sms_api_lists)){
                                $etisalat_bulk_sms_api_id_array[] = $etisalat_bulk_sms_detail["id"];
                            }
                        }
                        foreach($etisalat_bulk_sms_api_id_array as $etisalat_api_id){
                            $etisalat_api_id_statement .= "api_id='$etisalat_api_id'" . "\n";
                        }
                        $etisalat_api_id_statement = "(".str_replace("\n", " OR ", trim($etisalat_api_id_statement)).")";
                        $etisalat_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$etisalat_product_name."' LIMIT 1"));
						if(!empty($etisalat_product_table["id"])){
							$etisalat_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
							$etisalat_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $etisalat_api_id_statement && product_id='".$etisalat_product_table["id"]."'");
						}

                        //GLO INFORMATION
                        $glo_bulk_sms_api_id_array = [];
                        $glo_bulk_sms_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && api_type='bulk-sms'");
                        if(mysqli_num_rows($glo_bulk_sms_api_lists) > 0){
                            while($glo_bulk_sms_detail = mysqli_fetch_assoc($glo_bulk_sms_api_lists)){
                                $glo_bulk_sms_api_id_array[] = $glo_bulk_sms_detail["id"];
                            }
                        }
                        foreach($glo_bulk_sms_api_id_array as $glo_api_id){
                            $glo_api_id_statement .= "api_id='$glo_api_id'" . "\n";
                        }
                        $glo_api_id_statement = "(".str_replace("\n", " OR ", trim($glo_api_id_statement)).")";
                        $glo_product_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && product_name='".$glo_product_name."' LIMIT 1"));
						if(!empty($glo_product_table["id"])){
							$glo_smart_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_smart_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_agent_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_agent_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
							$glo_api_product_discount_table = mysqli_query($connection_server, "SELECT * FROM $acc_api_level_table_name WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && $glo_api_id_statement && product_id='".$glo_product_table["id"]."'");
						}

                        //MTN PRICE LISTING
                        if(isset($mtn_smart_product_discount_table) && (mysqli_num_rows($mtn_smart_product_discount_table) > 0)){
                            while(($mtn_smart_details = mysqli_fetch_assoc($mtn_smart_product_discount_table)) && ($mtn_agent_details = mysqli_fetch_assoc($mtn_agent_product_discount_table)) && ($mtn_api_details = mysqli_fetch_assoc($mtn_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Bulk SMS</td><td>MTN</td><td>'.$mtn_product_table["product_name"].'</td><td>'.$mtn_smart_details["val_1"].'</td><td>'.toDecimal($mtn_smart_details["val_2"], 2).'</td><td>'.toDecimal($mtn_agent_details["val_2"], 2).'</td><td>'.toDecimal($mtn_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //AIRTEL PRICE LISTING
                        if(isset($airtel_smart_product_discount_table) && (mysqli_num_rows($airtel_smart_product_discount_table) > 0)){
                            while(($airtel_smart_details = mysqli_fetch_assoc($airtel_smart_product_discount_table)) && ($airtel_agent_details = mysqli_fetch_assoc($airtel_agent_product_discount_table)) && ($airtel_api_details = mysqli_fetch_assoc($airtel_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Bulk SMS</td><td>Airtel</td><td>'.$airtel_product_table["product_name"].'</td><td>'.$airtel_smart_details["val_1"].'</td><td>'.toDecimal($airtel_smart_details["val_2"], 2).'</td><td>'.toDecimal($airtel_agent_details["val_2"], 2).'</td><td>'.toDecimal($airtel_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //ETISALAT PRICE LISTING
                        if(isset($etisalat_smart_product_discount_table) && (mysqli_num_rows($etisalat_smart_product_discount_table) > 0)){
                            while(($etisalat_smart_details = mysqli_fetch_assoc($etisalat_smart_product_discount_table)) && ($etisalat_agent_details = mysqli_fetch_assoc($etisalat_agent_product_discount_table)) && ($etisalat_api_details = mysqli_fetch_assoc($etisalat_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Bulk SMS</td><td>9mobile</td><td>'.$etisalat_product_table["product_name"].'</td><td>'.$etisalat_smart_details["val_1"].'</td><td>'.toDecimal($etisalat_smart_details["val_2"], 2).'</td><td>'.toDecimal($etisalat_agent_details["val_2"], 2).'</td><td>'.toDecimal($etisalat_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        
                        //GLO PRICE LISTING
                        if(isset($glo_smart_product_discount_table) && (mysqli_num_rows($glo_smart_product_discount_table) > 0)){
                            while(($glo_smart_details = mysqli_fetch_assoc($glo_smart_product_discount_table)) && ($glo_agent_details = mysqli_fetch_assoc($glo_agent_product_discount_table)) && ($glo_api_details = mysqli_fetch_assoc($glo_api_product_discount_table))){
                                $product_tr_list .= '<tr>
                                        <td>Bulk SMS</td><td>GLO</td><td>'.$glo_product_table["product_name"].'</td><td>'.$glo_smart_details["val_1"].'</td><td>'.toDecimal($glo_smart_details["val_2"], 2).'</td><td>'.toDecimal($glo_agent_details["val_2"], 2).'</td><td>'.toDecimal($glo_api_details["val_2"], 2).'</td>
                                    </tr>';
                            }
                        }
                        return $product_tr_list;
                    }
                    echo bulksmsAPIDoc();
                ?>
                </table>
            </div>
        </div>
        
		<div id="requery-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Integrating Transaction Requery API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">REQUERY BASE URL</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Make HTTP POST request to this endpoint</span><br/>
				<input style="user-select: auto; text-align: left;" type="text" value="<?php echo $web_http_host."/web/api/requery.php"; ?>" placeholder="API Key" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-5 s-margin-bm-5" readonly/><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* api_key: <span class="text-bold-400">This is the generated API KEY on your user account</span><br/>
					* reference: <span class="text-bold-400">This is the transaction reference to requery</span><br/>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful airtime transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful", "response_desc":"All neccessary details"}
				</span>

			</div>
		</div>

		<div id="status-code-docs" class="api_docs_div color-4 bg-3 m-none-dp s-none-dp m-width-96 s-width-96 m-height-0 s-height-0 m-margin-lt-2 s-margin-lt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
			<div style="user-select: auto;" class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-scroll-none s-scroll-none m-padding-lt-2 s-padding-lt-2 m-padding-rt-2 s-padding-rt-2 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Responses Status Codes</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-5 s-margin-bm-5">This section contains all the step to integrate our RESTful API</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">Required Parameters:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-5 s-margin-bm-5">
					* success: <span class="text-bold-400">This indicates the transaction is SUCCESSFUL</span><br/>
					* pending: <span class="text-bold-400">This indicates the transaction is PENDING</span><br/>
					* failed: <span class="text-bold-400">This indicates the transaction is FAILED</span><br/>
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-18 s-font-size-20 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">Sample JSON Responses</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-16 s-font-size-18 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">API Response</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a successful transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "success", "desc":"Transaction Successful"}
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a pending transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "pending", "desc":"Transaction Pending"}
				</span><br/>
				<span style="user-select: auto;" class="color-4 text-bold-400 m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-bm-2 s-margin-bm-2">On a failed transaction, you get a json response e.g:</span><br/>
				<span style="user-select: auto; line-height: 20px;" class="color-4 text-bold-bold m-font-size-12 s-font-size-14 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-3 s-margin-bm-3">
					{"ref":873387339873, "status": "failed", "desc":"Transaction Failed"}
				</span>

			</div>
		</div>
	<?php include("../func/bc-footer.php"); ?>
</body>
<?php }else{ ?>
<body>
	<?php include("../func/bc-header.php"); ?>
	<img alt="Logo" src="<?php echo $web_http_host; ?>/asset/ooops.gif" style="user-select: auto; pointer-events: none; object-fit: contain; object-position: center;" class="m-position-rel s-position-rel m-inline-block-dp s-inline-block-dp m-width-60 s-width-50 m-height-auto s-height-auto m-margin-lt-20 s-margin-lt-20"/><br/>
	<center>
		<span style="user-select: auto;" id="" class="color-10 text-bold-600 m-position-rel s-position-rel m-font-size-20 s-font-size-22 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-2 s-margin-lt-2">Oooops!!! The API is yet to be active for your account.</span>
	</center>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
<?php } ?>

</html>