<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    $api_type_array = array(1 => "api", 2 => "requery", 3 => "verify");

    $api_id_number = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9]+/", "", trim(strip_tags($_GET["apiID"]))));
    $select_api = mysqli_query($connection_server, "SELECT * FROM sas_api_marketplace_listings WHERE id='$api_id_number'");
    if(mysqli_num_rows($select_api) > 0){
        $get_api_details = mysqli_fetch_array($select_api);
    }

    if(isset($_POST["update-api-file"])){
        $type = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["type"]))));
        $api_file = $_FILES["api-file"]["name"];
        $api_file_tmp = $_FILES["api-file"]["tmp_name"];
        $api_file_ext = pathinfo($api_file)["extension"];
        $acceptable_ext_array = array("php");
        if(!empty($type) && in_array($type, array_keys($api_type_array)) && !empty($api_file) && in_array($api_file_ext, $acceptable_ext_array)){
            $check_api_details = mysqli_query($connection_server, "SELECT * FROM sas_api_marketplace_listings WHERE id='$api_id_number'");

            if(mysqli_num_rows($check_api_details) == 1){
            	$api_details = mysqli_fetch_array($check_api_details);
            	$refined_website_url = trim(str_replace(["https","http",":/","/","www."," "],"",$api_details["api_website"]));
            	$api_name = strtolower(str_replace([" ","."], "-", trim($api_details["api_type"]."-".$refined_website_url)).".php");
                if($type == 1){
                	$api_folder_path = "../func/api-gateway/";
                }else{
                	if($type == 2){
                		$api_folder_path = "../func/api-gateway/requery/";
                	}else{
                		if($type == 3){
                			$api_folder_path = "../func/api-gateway/verify/";
                		}
                	}
                }
                
                if(isset($api_folder_path) && !empty(trim($api_folder_path)) && is_dir($api_folder_path)){
                	if(!file_exists($api_folder_path.$api_name)){
                		move_uploaded_file($api_file_tmp, $api_folder_path.$api_name);
                		//API File Uploaded Successfully
                		$json_response_array = array("desc" => "API File Uploaded Successfully");
                		$json_response_encode = json_encode($json_response_array,true);
                	}else{
                		//Api Already Exists, Delete Previous File and Try Again
                		$json_response_array = array("desc" => "Api Already Exists, Delete Previous File and Try Again");
                		$json_response_encode = json_encode($json_response_array,true);
                	}
                }else{
                	//API Path Not Set, Contact Developer
                	$json_response_array = array("desc" => "API Path Not Set, Contact Developer");
                	$json_response_encode = json_encode($json_response_array,true);
                }
                
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
                if(!in_array($type, array_keys($api_type_array))){
                    //Invalid API Type
                    $json_response_array = array("desc" => "Invalid API Type");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(empty($api_file)){
                        //API File Field Empty
                        $json_response_array = array("desc" => "API File Field Empty");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                    	if(!in_array($api_file_ext, $acceptable_ext_array)){
                    		//Error: File Extension must be ()
                    		$json_response_array = array("desc" => "Error: File Extension must be (".implode(", ", $acceptable_ext_array).")");
                    		$json_response_encode = json_encode($json_response_array,true);
                    	}
                    }
                }
            }
        }

        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
    
    
    if(isset($_GET["api-file-link"])){
    	$api_file_link = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["api-file-link"])));
    	if(file_exists($api_file_link)){
    		unlink($api_file_link);
    		//API File Deleted Successfully
    		$json_response_array = array("desc" => "API File Deleted Successfully");
    		$json_response_encode = json_encode($json_response_array,true);
    	}else{
    		//API File Not Exists
    		$json_response_array = array("desc" => "API File Not Exists");
    		$json_response_encode = json_encode($json_response_array,true);
    	}
    	
    	$json_response_decode = json_decode($json_response_encode,true);
    	$_SESSION["product_purchase_response"] = $json_response_decode["desc"];
    	header("Location: /bc-spadmin/ApiUpload.php?apiID=".$get_api_details["id"]);
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">UPLOAD API FILE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
                <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-20 s-width-15">
                    <img src="<?php echo $web_http_host; ?>/asset/upload-icon.png" class="a-cursor m-width-100 s-width-100" style="pointer-events: none; user-select: auto;"/>
                </div><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor" style="user-select: auto;">API INFORMATION</span>
                </div><br/>
                <input style="text-align: center;" name="" type="text" value="<?php echo strtoupper(str_replace('-', ' ',$get_api_details['api_type']).' - '.$get_api_details['api_website']); ?>" placeholder="" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" readonly/><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                	<span id="api-status-span" class="a-cursor" style="user-select: auto;">API FUNCTION TYPE</span>
                </div><br/>
                <select style="text-align: center;" id="" name="type" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                    <option value="" default hidden selected>Choose API Function Type</option>
                    <?php
                        foreach($api_type_array as $api_code => $api_text){
                            echo '<option value="'.strtolower(trim($api_code)).'" >'.strtoupper($api_text).' FILE</option>';
                        }
                    ?>
                </select><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor" style="user-select: auto;">API FILE</span>
                </div><br/>
                <input style="text-align: center;" name="api-file" type="file" accept=".php" value="" placeholder="" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                
                <button name="update-api-file" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPLOAD API FILE
                </button><br>
            </form>
            
        </div><br/>
        	
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        	<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">UPLOADED FILES</span><br>
        	
        	<?php
        		$refined_website_url = trim(str_replace(["https","http",":/","/","www."," "],"",$get_api_details["api_website"]));
        		$api_name = strtolower(str_replace([" ","."], "-", trim($get_api_details["api_type"]."-".$refined_website_url)).".php");
        		foreach($api_type_array as $index => $type){
        			if($index == 1){
        				$api_file_folder_path = "../func/api-gateway/".$api_name;
        			}else{
        				if($index == 2){
        					$api_file_folder_path = "../func/api-gateway/requery/".$api_name;
        				}else{
        					if($index == 3){
        						$api_file_folder_path = "../func/api-gateway/verify/".$api_name;
        					}
        				}
        			}
        			
        			if(file_exists($api_file_folder_path)){
        				$api_name_det = '['.$api_type_array[$index].'] '.$get_api_details["api_type"].' - '.$refined_website_url;
        				echo '<span style="user-select: auto;" class="color-10 text-bold-500 m-font-size-16 s-font-size-18">'.strtoupper($api_name_det).' <img title="Delete" src="'.$web_http_host.'/asset/fa-delete.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-6 m-margin-lt-1 s-margin-lt-1" onclick="customJsRedirect(`/bc-spadmin/ApiUpload.php?apiID='.$get_api_details["id"].'&&api-file-link='.$api_file_folder_path.'`, `Are you sure you want to delete '.strtoupper($api_name_det).'?`);" /></span>';
        				echo '<br/>';
        			}
        			
        		}
        	?>
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