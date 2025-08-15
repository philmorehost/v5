<?php session_start();
    include("../func/bc-config.php");
	
    if(isset($_POST["submit-sender-id"])){
        $sender_id = mysqli_real_escape_string($connection_server, preg_replace("/[^a-zA-Z]+/","",trim(strip_tags(strtolower($_POST["sender-id"])))));
        $sample_message = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["sample-message"])));
        
        if(!empty($sender_id) && (strlen($sender_id) >= 3) && (strlen($sender_id) <= 11) && !empty($sample_message) && (strlen($sample_message) >= 1) && (strlen($sample_message) <= 160)) {
            $check_sender_id = mysqli_query($connection_server, "SELECT * FROM sas_bulk_sms_sender_id WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && sender_id='$sender_id'");
            $check_sender_id_by_user = mysqli_query($connection_server, "SELECT * FROM sas_bulk_sms_sender_id WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && username='".$get_logged_user_details["username"]."' && sender_id='$sender_id'");
            if(mysqli_num_rows($check_sender_id) == 0){
                mysqli_query($connection_server, "INSERT INTO sas_bulk_sms_sender_id (vendor_id, username, sender_id, sample_message, status) VALUES ('".$get_logged_user_details["vendor_id"]."', '".$get_logged_user_details["username"]."', '$sender_id', '$sample_message', '2')");
                //Sender ID Submitted Successfully For Review
                $json_response_array = array("desc" => "Sender ID Submitted Successfully For Review");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(mysqli_num_rows($check_sender_id) == 1){
                    if(mysqli_num_rows($check_sender_id_by_user) == 1){
                        //Sender ID Already Submitted by You
                        $json_response_array = array("desc" => "Sender ID Already Submitted by You");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        //Sender ID Already Exists
                        $json_response_array = array("desc" => "Sender ID Already Exists");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }else{
                    if(mysqli_num_rows($check_sender_id) > 1){
                        //Duplicated Details, Contact Admin
                        $json_response_array = array("desc" => "Duplicated Details, Contact Admin");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
            }
        }else{
            if(empty($sender_id)){
                //Sender ID Field Is Empty
                $json_response_array = array("desc" => "Sender ID Field Is Empty");
                $json_response_encode = json_encode($json_response_array,true); 
            }else{
                if(strlen($sender_id) < 3){
                    //Sender ID Must Not Be Less Than 3 Letter
                    $json_response_array = array("desc" => "Sender ID Must Not Be Less Than 3 Letter");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(strlen($sender_id) > 11){
                        //Sender ID Must Not Be Greater Than 11 Letter
                        $json_response_array = array("desc" => "Sender ID Must Not Be Greater Than 11 Letter");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(empty($sample_message)){
                            //Sample Message Field Is Empty
                            $json_response_array = array("desc" => "Sample Message Field Is Empty");
                            $json_response_encode = json_encode($json_response_array,true); 
                        }else{
                            if(strlen($sample_message) < 1){
                                //Sample Message Must Not Be Less Than 1 Character
                                $json_response_array = array("desc" => "Sample Message Must Not Be Less Than 1 Character");
                                $json_response_encode = json_encode($json_response_array,true);
                            }else{
                                if(strlen($sample_message) > 160){
                                    //Sender ID Must Not Be Greater Than 160 Character
                                    $json_response_array = array("desc" => "Sender ID Must Not Be Greater Than 160 Character");
                                    $json_response_encode = json_encode($json_response_array,true);
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
    <title>Sender ID | <?php echo $get_all_site_details["site_title"]; ?></title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">SUBMIT SENDER ID</span><br>
            <form method="post" action="">
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="user-status-span" class="a-cursor" style="user-select: auto;">SENDER ID</span>
                </div><br/>
                <input style="text-align: center;" name="sender-id" type="text" value="" placeholder="Sender ID" pattern="[a-zA-Z]{3,11}" title="Sender ID must be atleast 3 and not greater than 11 letters long (No Space or Special Character)" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" autocomplete="off" required/><br/>
                <input style="text-align: center;" name="sample-message" type="text" value="" placeholder="Sample Message" pattern="[a-zA-Z0-9]{1, 160}" title="Sample Message must be atleast 1 and not greater than 160 letters long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" autocomplete="off" required/><br/>
                <button id="" name="submit-sender-id" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-46 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-2 s-margin-bm-2" >
                    SUBMIT ID
                </button><br/>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>
        
		<?php
            
            if(!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
            	$page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
            	$offset_statement = " OFFSET ".((10 * $page_num) - 10);
            }else{
            	$offset_statement = "";
            }
            
            if(isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))){
                $search_statement = " && (sender_id LIKE '%".trim(strip_tags($_GET["searchq"]))."%')";
                $search_parameter = "searchq=".trim(strip_tags($_GET["searchq"]))."&&";
            }else{
                $search_statement = "";
                $search_parameter = "";
            }
            $get_user_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_bulk_sms_sender_id WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && username='".$_SESSION["user_session"]."' $search_statement ORDER BY date DESC LIMIT 10 $offset_statement");
            
        ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">SENDER IDs</span><br>
                <form method="get" action="SubmitSenderID.php" class="m-margin-tp-1 s-margin-tp-1">
                    <input style="user-select: auto;" name="searchq" type="text" value="<?php echo trim(strip_tags($_GET["searchq"])); ?>" placeholder="Sender ID e.t.c" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                    <button style="user-select: auto;" type="submit" class="button-box a-cursor color-2 bg-10 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                        <img src="<?php echo $web_http_host; ?>/asset/white-search.png" class="m-width-50 s-width-50 m-height-100 s-height-100" />
                    </button>
                </form>
            </div>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-width-100 s-width-100 m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                    	<th>S/N</th><th>Sender ID</th><th>Sample Message</th><th>Status</th><th>Date</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_user_transaction_details) >= 1){
                    	while($user_transaction = mysqli_fetch_assoc($get_user_transaction_details)){
                    		$transaction_type = ucwords($user_transaction["type_alternative"]);
                    		$countTransaction += 1;
                    		echo 
                    		'<tr>
                    			<td>'.$countTransaction.'</td><td style="user-select: auto;">'.$user_transaction["sender_id"].'</td><td>'.$user_transaction["sample_message"].'</td><td>'.tranStatus($user_transaction["status"]).'</td><td>'.formDate($user_transaction["date"]).'</td>
                    		</tr>';
                    	}
                    }
                    ?>
                </table>
            </div>
            <div class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
                <?php if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)){ ?>
                <a href="SubmitSenderID.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-lt s-float-lt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Prev</button>
                </a>
                <?php } ?>
                <?php
                	if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
                		$trans_next = (trim(strip_tags($_GET["page"])) +1);
                	}else{
                		$trans_next = 2;
                	}
                ?>
                <a href="SubmitSenderID.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
                </a>
            </div>
        </div>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
</html>