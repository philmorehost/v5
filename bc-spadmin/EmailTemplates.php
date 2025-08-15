<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_POST["update-template"])){
        $subject = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["subject"])));
        $body = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["body"])));
        $email_type = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["type"]))));
        
        if(!empty($subject) && !empty($body) && !empty($email_type)){
            $template_details = mysqli_query($connection_server, "SELECT * FROM sas_super_admin_email_templates WHERE email_type='$email_type'");
            if(mysqli_num_rows($template_details) == 1){
                mysqli_query($connection_server, "UPDATE sas_super_admin_email_templates SET subject='$subject', body='$body' WHERE email_type='$email_type'");
                //Email Template Updated Successfully
                $json_response_array = array("desc" => "Email Template Updated Successfully");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(mysqli_num_rows($template_details) > 1){
                    //Duplicated Details
                    $json_response_array = array("desc" => "Duplicated Details");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(mysqli_num_rows($template_details) == 0){
                        mysqli_query($connection_server, "INSERT INTO sas_super_admin_email_templates (email_type, subject, body) VALUES ('$email_type', '$subject', '$body')");
                        //Email Template Created Successfully
                        $json_response_array = array("desc" => "Email Template Created Successfully");
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
            }
        }else{
            if(empty($subject)){
                //Subject Field Empty
                $json_response_array = array("desc" => "Subject Field Empty");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(empty($body)){
                    //Body Field Empty
                    $json_response_array = array("desc" => "Body Field Empty");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(empty($email_type)){
                        //Email Type Field Empty
                        $json_response_array = array("desc" => "Email Type Field Empty");
                        $json_response_encode = json_encode($json_response_array,true);
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
    <title>Email Template</title>
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
    
    	<div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR REGISTRATION TEMPLATE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Email address:</span> <span id="" class="color-10" style="user-select: auto;">{email}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Phone number:</span> <span id="" class="color-10" style="user-select: auto;">{phone}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Home Address:</span> <span id="" class="color-10" style="user-select: auto;">{address}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Website:</span> <span id="" class="color-10" style="user-select: auto;">{website}</span></span><br/>
    			</div><br/>
                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-reg" placeholder="Email Type" hidden readonly required/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-reg','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-reg','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>
    	
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR LOGIN TEMPLATE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Email:</span> <span id="" class="color-10" style="user-select: auto;">{email}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">IP address:</span> <span id="" class="color-10" style="user-select: auto;">{ip_address}</span></span><br/>
                </div><br/>
                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-log" placeholder="Email Type" hidden readonly required/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-log','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-log','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>
    	
		<div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR PASSWORD UPDATE TEMPLATE</span><br>
    	            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
    	                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
    	                </div><br/>
    	                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-pass-update" placeholder="Email Type" hidden readonly required/>
    	                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-pass-update','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
    	                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-pass-update','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>
		
		<div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
			<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR ACCOUNT UPDATE TEMPLATE</span><br>
			<form method="post" enctype="multipart/form-data" action="">
				<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Email address:</span> <span id="" class="color-10" style="user-select: auto;">{email}</span></span>, 
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Phone number:</span> <span id="" class="color-10" style="user-select: auto;">{phone}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Home address:</span> <span id="" class="color-10" style="user-select: auto;">{address}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Website:</span> <span id="" class="color-10" style="user-select: auto;">{website}</span></span><br/>
					
				</div><br/>
				<input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-account-update" placeholder="Email Type" hidden readonly required/>
				<input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-account-update','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
				<textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-account-update','body'); ?></textarea><br>
				<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
					UPDATE TEMPLATE
				</button><br>
			</form>	
		</div><br/>
		
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR PASSWORD RECOVERY TEMPLATE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Recovery Code:</span> <span id="" class="color-10" style="user-select: auto;">{recovery_code}</span></span><br/>
                </div><br/>
                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-account-recovery" placeholder="Email Type" hidden readonly required/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-account-recovery','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-account-recovery','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>
        
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR ACCOUNT STATUS TEMPLATE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Account Status:</span> <span id="" class="color-10" style="user-select: auto;">{account_status}</span></span><br/>
                </div><br/>
                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-account-status" placeholder="Email Type" hidden readonly required/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-account-status','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-account-status','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>
				
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR TRANSACTION (ADMIN) TEMPLATE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Admin Fullname:</span> <span id="" class="color-10" style="user-select: auto;">{admin_firstname}</span>, <span id="" class="color-10" style="user-select: auto;">{admin_lastname}</span></span><br/>
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Email:</span> <span id="" class="color-10" style="user-select: auto;">{email}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Balance Before:</span> <span id="" class="color-10" style="user-select: auto;">{balance_before}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Balance After:</span> <span id="" class="color-10" style="user-select: auto;">{balance_after}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Amount Charged:</span> <span id="" class="color-10" style="user-select: auto;">{amount}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Description:</span> <span id="" class="color-10" style="user-select: auto;">{description}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Transaction Type:</span> <span id="" class="color-10" style="user-select: auto;">{type}</span></span><br/>
                </div><br/>
                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-transactions" placeholder="Email Type" hidden readonly required/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-transactions','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-transactions','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>

        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
    		<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR CREDIT/DEBIT TEMPLATE</span><br>
            <form method="post" enctype="multipart/form-data" action="">
    			<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Balance Before:</span> <span id="" class="color-10" style="user-select: auto;">{balance_before}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Balance After:</span> <span id="" class="color-10" style="user-select: auto;">{balance_after}</span></span><br/>
                    <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Amount Charged:</span> <span id="" class="color-10" style="user-select: auto;">{amount}</span></span>, 
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Description:</span> <span id="" class="color-10" style="user-select: auto;">{description}</span></span><br/>
    		        <span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Transaction Type:</span> <span id="" class="color-10" style="user-select: auto;">{type}</span></span><br/>
                </div><br/>
                <input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-funding" placeholder="Email Type" hidden readonly required/>
                <input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-funding','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-funding','body'); ?></textarea><br>
    			<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
    				UPDATE TEMPLATE
    			</button><br>
    		</form>	
    	</div><br/>

		
		<div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
			<span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR REFUND TEMPLATE</span><br>
			<form method="post" enctype="multipart/form-data" action="">
				<div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Firstname:</span> <span id="" class="color-10" style="user-select: auto;">{firstname}</span></span>, 
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Lastname:</span> <span id="" class="color-10" style="user-select: auto;">{lastname}</span></span><br/>
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Amount:</span> <span id="" class="color-10" style="user-select: auto;">{amount}</span></span>, 
					<span id="" class="color-4 m-font-size-14 s-font-size-16"><span style="user-select: auto;">Description:</span> <span id="" class="color-10" style="user-select: auto;">{description}</span></span><br/>
				</div><br/>
				<input style="text-align: left;" id="" name="type" onkeyup="" type="text" value="vendor-refund" placeholder="Email Type" hidden readonly required/>
				<input style="text-align: left;" id="" name="subject" onkeyup="" type="text" value="<?php echo getSuperAdminEmailTemplate('vendor-refund','subject'); ?>" placeholder="Email Subject" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
				<textarea style="text-align: left; resize: none;" id="" name="body" onkeyup="" placeholder="Email Body" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-87 s-width-45 m-height-15rem s-height-15rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required><?php echo getSuperAdminEmailTemplate('vendor-refund','body'); ?></textarea><br>
				<button name="update-template" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-90 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
					UPDATE TEMPLATE
				</button><br>
			</form>	
		</div><br/>
		
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <div style="text-align: center;" class="color-10 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-87 s-width-45">
                    <span id="admin-status-span" class="a-cursor" style="user-select: auto;">NB: Contact Admin For Further Assistance!!!</span>
                </div><br/>
            </form>
        </div>
        
    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>