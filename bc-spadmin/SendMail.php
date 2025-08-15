<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_POST["send-mail"])){
        $subject = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["subject"])));
        $body = mysqli_real_escape_string($connection_server, str_replace(["\r\n"], "<br/>", trim(strip_tags($_POST["body"]))));
        $mailto = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["mailto"]))));
        $mailto_array = array("all","a","b","d","bd");
        if(!empty($subject) && !empty($body) && !empty($mailto) && in_array($mailto, $mailto_array)){
            $select_users = mysqli_query($connection_server, "SELECT * FROM sas_vendors");
                if(mysqli_num_rows($select_users) >= 1){
                    // Email Beginning
                    $send_mail_to_specified_users = sendSuperAdminEmailSpecific($mailto, $subject, $body);
                    if($send_mail_to_specified_users == "success"){
                        //Mail Sent Successfully
                        $json_response_array = array("desc" => "Mail Sent Successfully");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if($send_mail_to_specified_users == "failed"){
                            //Error: No Account For Mail-To Type
                            $json_response_array = array("desc" => "Error: No Account For Mail-To Type");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            if($send_mail_to_specified_users == "error"){
                                //Error: Invalid Mail-To Function
                                $json_response_array = array("desc" => "Error: Invalid Mail-To Function");
                                $json_response_encode = json_encode($json_response_array,true);
                            }
                        }
                    }
                    // Email End
                }else{
                    //Error: No Account
                    $json_response_array = array("desc" => "Error: No Account");
                    $json_response_encode = json_encode($json_response_array,true);
                }
		}else{
			if(empty($subject)){
                //Email Subject Field Empty
				$json_response_array = array("desc" => "Email Subject Field Empty");
				$json_response_encode = json_encode($json_response_array,true);
            }else{
                if(empty($body)){
                    //Email Body Field Empty
                    $json_response_array = array("desc" => "Email Body Field Empty");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(empty($mailto)){
                        //Mail-To Field Empty
                        $json_response_array = array("desc" => "Mail-To Field Empty");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(!in_array($mailto, $mailto_array)){
                            //Invalid Mail-To Function
                            $json_response_array = array("desc" => "Invalid Mail-To Function");
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
?>
<!DOCTYPE html>
<head>
    <title>Mailing System</title>
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
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">MAILING SYSTEM</span><br>
            <form method="post" action="">
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Email address:</span> <span id="" class="color-10" style="user-select: auto;">{email}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Phone number:</span> <span id="" class="color-10" style="user-select: auto;">{phone}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Email:</span> <span id="" class="color-10" style="user-select: auto;">{email}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Home address:</span> <span id="" class="color-10" style="user-select: auto;">{address}</span></span><br/>
    			</div><br/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getVendorEmailTemplate('user-reg','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <select style="text-align: center;" id="" name="mailto" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                	<option value="" selected hidden default>Choose Mail To</option>
                    <option value="all">All Accounts</option>
                	<option value="a">Active Accounts</option>
                	<option value="b">Blocked Accounts</option>
                    <option value="d">Deleted Accounts</option>
                    <option value="bd">Blocked and Deleted Accounts</option>
                </select><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getVendorEmailTemplate('user-reg','body'); ?></textarea><br>
                <button name="send-mail" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    SEND MAIL
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

	<?php include("../func/bc-spadmin-footer.php"); ?>
	
</body>
</html>