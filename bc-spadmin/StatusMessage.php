<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_POST["update-status"])){
        $message = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["message"])));
        if(!empty($message)){
            $select_vendor_status_message = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_status_messages");
            if(mysqli_num_rows($select_vendor_status_message) == 1){
            	mysqli_query($connection_server, "UPDATE sas_super_admin_status_messages SET message='$message'");
            	//Hurray! Status Message Updated Successfully
            	$json_response_array = array("desc" => "Hurray! Status Message Updated Successfully");
            	$json_response_encode = json_encode($json_response_array,true);
            }else{
            	if(mysqli_num_rows($select_vendor_status_message) > 1){
            		mysqli_query($connection_server, "DELETE FROM sas_super_admin_status_messages");
            		mysqli_query($connection_server, "INSERT INTO sas_super_admin_status_messages (message) VALUES ('$message')");
            		//Hurray! Status Message Recreated Successfully
            		$json_response_array = array("desc" => "Hurray! Status Message Recreated Successfully");
            		$json_response_encode = json_encode($json_response_array,true);
            	}else{
            		mysqli_query($connection_server, "INSERT INTO sas_super_admin_status_messages (message) VALUES ('$message')");
            		//Hurray! Status Message Created Successfully
            		$json_response_array = array("desc" => "Hurray! Status Message Created Successfully");
            		$json_response_encode = json_encode($json_response_array,true);
            	}
            }
		}else{
			if(empty($message)){
                //Message Field Empty
				$json_response_array = array("desc" => "Message Field Empty");
				$json_response_encode = json_encode($json_response_array,true);
            }
		}
        
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
    
    $select_vendor_super_admin_status_message = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_status_messages");
    if(mysqli_num_rows($select_vendor_super_admin_status_message) == 1){
    	$get_vendor_super_admin_status_message = mysqli_fetch_array($select_vendor_super_admin_status_message);
    	$get_vendor_super_admin_status_message_text = 	$get_vendor_super_admin_status_message["message"];
    }else{
    	$get_vendor_super_admin_status_message_text = "";
    }
?>
<!DOCTYPE html>
<head>
    <title>Status Message</title>
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
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-1 s-padding-bm-1 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">STATUS MESSAGE</span><br>
            <form method="post" action="">
            	<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-4" style="user-select: auto;">{firstname}</span></span>, 
    		    </div><br/>
                <textarea style="text-align: left; resize: none;" id="" name="message" onkeyup="" placeholder="Message" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo $get_vendor_super_admin_status_message_text; ?></textarea><br>
                <button name="update-status" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    UPDATE STATUS
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

	<?php include("../func/bc-spadmin-footer.php"); ?>
	
</body>
</html>