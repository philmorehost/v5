<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    $product_type_array = array("airtime", "shared-data", "sme-data", "cg-data", "dd-data", "datacard", "rechargecard", "electric", "cable", "exam", "bulk-sms");
    $api_status_array = array(1 => "Public", 2 => "Private");

    $api_id_number = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9]+/", "", trim(strip_tags($_GET["apiID"]))));
    $select_api = mysqli_query($connection_server, "SELECT * FROM sas_api_marketplace_listings WHERE id='$api_id_number'");
    if(mysqli_num_rows($select_api) > 0){
        $get_api_details = mysqli_fetch_array($select_api);
    }

    if(isset($_POST["update-profile"])){
        $type = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["type"]))));
        $status = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["status"])));
        $desc = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["desc"])));
        $price = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9.]+/","",trim(strip_tags(strtolower($_POST["price"])))));
        $unrefined_website_url = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["website-url"]))));
        $refined_website_url = trim(str_replace(["https","http",":/","/","www."," "],"",$unrefined_website_url));
        $website_url = $refined_website_url;
        if(!empty($type) && in_array($type, $product_type_array) && !empty($status) && in_array($status, array_keys($api_status_array)) && !empty($price) && is_numeric($price) && !empty($website_url)){
            $check_api_details = mysqli_query($connection_server, "SELECT * FROM sas_api_marketplace_listings WHERE id='$api_id_number'");

            if(mysqli_num_rows($check_api_details) == 1){
                mysqli_query($connection_server, "UPDATE sas_api_marketplace_listings SET api_type='$type', status='$status', description='$desc', price='$price', api_website='$website_url' WHERE id='".$api_id_number."'");
                //API Information Updated Successfully
                $json_response_array = array("desc" => "API Information Updated Successfully");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(mysqli_num_rows($check_api_details) == 0){
                    //Api Not Exists
                    $json_response_array = array("desc" => "Api Not Exists");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(mysqli_num_rows($check_api_details) > 1){
                        //Duplicated Details, Contact Admin
                        $json_response_array = array("desc" => "Duplicated Details, Contact Admin");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
            }
        }else{
            if(empty($type)){
                //API Type Field Empty
                $json_response_array = array("desc" => "API Type Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(!in_array($type, $product_type_array)){
                    //Invalid API Type
                    $json_response_array = array("desc" => "Invalid API Type");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(empty($status)){
                        //API Status Field Empty
                        $json_response_array = array("desc" => "API Status Field Empty");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(!in_array($status, array_keys($api_status_array))){
                            //Invalid API Status
                            $json_response_array = array("desc" => "Invalid API Status");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            if(empty($price)){
                                //Price Field Empty
                                $json_response_array = array("desc" => "Price Field Empty");
                                $json_response_encode = json_encode($json_response_array,true);
                            }else{
                                if(!is_numeric($price)){
                                    //Non-numeric Price
                                    $json_response_array = array("desc" => "Non-numeric Price");
                                    $json_response_encode = json_encode($json_response_array,true);
                                }else{
                                    if(empty($website_url)){
                                        //Website Url Field Empty
                                        $json_response_array = array("desc" => "Website Url Field Empty");
                                        $json_response_encode = json_encode($json_response_array,true);
                                    }
                                }
                            }
                        }
                    }
                }
            }
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
    <?php if(!empty($get_api_details['id'])){ ?>
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">API INFO</span><br>
            <form method="post" enctype="multipart/form-data" action="">
                <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-20 s-width-15">
                    <img src="<?php echo $web_http_host; ?>/asset/developer-icon.png" class="a-cursor m-width-100 s-width-100" style="pointer-events: none; user-select: auto;"/>
                </div><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor" style="user-select: auto;">API INFORMATION</span>
                </div><br/>
                <select style="text-align: center;" id="" name="type" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose API Type</option>
                    <?php
                        foreach($product_type_array as $type){
                            if(($get_api_details["api_type"] == $type) && in_array($get_api_details["api_type"], $product_type_array)){
                                echo '<option value="'.strtolower(trim($type)).'" selected>'.str_replace(["_","-"]," ",strtoupper($type)).'</option>';
                            }else{
                                echo '<option value="'.strtolower(trim($type)).'">'.str_replace(["_","-"]," ",strtoupper($type)).'</option>';
                            }
                        }
                    ?>
                </select><br/>
                <select style="text-align: center;" id="" name="status" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose API Status</option>
                    <?php
                        foreach($api_status_array as $status_code => $status_text){
                            if(($get_api_details["status"] == $status_code) && in_array($get_api_details["status"], array_keys($api_status_array))){
                                echo '<option value="'.strtolower(trim($status_code)).'" selected>'.strtoupper($status_text).'</option>';
                            }else{
                                echo '<option value="'.strtolower(trim($status_code)).'">'.strtoupper($status_text).'</option>';
                            }
                        }
                    ?>
                </select><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor" style="user-select: auto;">API PRICE</span>
                </div><br/>
                <input style="text-align: center;" name="price" type="number" value="<?php echo $get_api_details['price']; ?>" placeholder="Price" step="0.0001" min="1" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor" style="user-select: auto;">WEBSITE URL</span>
                </div><br/>
                <input style="text-align: center;" name="website-url" type="url" value="https://<?php echo $get_api_details['api_website']; ?>" placeholder="Website Url" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor" style="user-select: auto;">DESCRIPTION</span>
                </div><br/>
                <textarea style="text-align: center; resize: none;" id="" name="desc" onkeyup="" placeholder="Description (Optional)" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-height-5rem s-height-5rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" ><?php echo $get_api_details["description"]; ?></textarea><br/>
                
                <button name="update-profile" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE API
                </button><br>
            </form>
        </div><br/>
        
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <div style="text-align: center;" class="color-10 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
            	<span id="admin-status-span" class="a-cursor" style="user-select: auto;">NB: Contact Admin For Further Assistance!!!</span>
            </div><br/>
        </div>
    <?php }else{ ?>
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">API INFO</span><br>
            <img src="<?php echo $web_http_host; ?>/asset/ooops.gif" class="a-cursor m-width-60 s-width-50" style="user-select: auto;"/><br/>
            <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45">
                <span id="api-status-span" class="a-cursor m-font-size-35 s-font-size-45" style="user-select: auto;">Ooops</span><br/>
                <span id="api-status-span" class="a-cursor m-font-size-18 s-font-size-20" style="user-select: auto;">Api Not Exists or has been deleted</span>
            </div><br/>
        </div>
    <?php } ?>
    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>