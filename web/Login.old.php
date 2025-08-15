<?php session_start([
    'cookie_lifetime' => 86400,
	'gc_maxlifetime' => 86400,
]);

    include("../func/bc-config.php");
    if(isset($get_logged_user_details["username"]) && !empty($get_logged_user_details["username"]) && ($get_logged_user_details["status"] == 1)){
	$redirecturl = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["redirecturl"])));
		if(!empty(trim($redirecturl)) && file_exists("..".$redirecturl)){
			header("Location: ".$redirecturl);
		}else{
			header("Location: /web/Dashboard.php");
		}
    }

    if(isset($_POST["login"])){
	$user = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["user"]))));
	$pass = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["pass"])));
	if(!empty($user) && !empty($pass)){
		$get_vendor_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE website_url='".$_SERVER["HTTP_HOST"]."'"));
		$get_user_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_vendor_details["id"]."' && username='$user'");
		if(mysqli_num_rows($get_user_details) == 1){
			$md5_pass = md5($pass);
			$check_user_password_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_vendor_details["id"]."' && username='$user' && password='$md5_pass'");
			if(mysqli_num_rows($check_user_password_details) == 1){
				while($user_detail = mysqli_fetch_assoc($check_user_password_details)){
					if($user_detail["status"] == 1){
						$_SESSION["user_session"] = strtolower($user_detail["username"]);

							// Email Beginning
							$log_template_encoded_text_array = array("{firstname}" => $user_detail["firstname"], "{lastname}" => $user_detail["lastname"], "{username}" => $user_detail["username"], "{ip_address}" => $_SERVER["REMOTE_ADDR"]);
							$raw_log_template_subject = getUserEmailTemplate('user-log','subject');
							$raw_log_template_body = getUserEmailTemplate('user-log','body');
							foreach($log_template_encoded_text_array as $array_key => $array_val){
								$raw_log_template_subject = str_replace($array_key, $array_val, $raw_log_template_subject);
								$raw_log_template_body = str_replace($array_key, $array_val, $raw_log_template_body);
							}
							sendVendorEmail($user_detail["email"], $raw_log_template_subject, $raw_log_template_body);
							// Email End

						//Welcome Back Message
						$json_response_array = array("desc" => "Welcome Back, ".ucwords($user_detail["firstname"]));
						$json_response_encode = json_encode($json_response_array,true);
					}else{
							if($user_detail["status"] == 2){
								//Account Locked, Contact Admin
								$json_response_array = array("desc" => "Account Locked, Contact Admin");
								$json_response_encode = json_encode($json_response_array,true);
							}else{
								if($user_detail["status"] == 3){
									//Account Deleted, Contact Admin
									$json_response_array = array("desc" => "Account Deleted, Contact Admin");
									$json_response_encode = json_encode($json_response_array,true);
								}else{
									//Invalid Account Status
									$json_response_array = array("desc" => "Invalid Account Status");
									$json_response_encode = json_encode($json_response_array,true);
								}
							}
					}
				}
			}else{
				if(mysqli_num_rows($check_user_password_details) < 1){
					//Incorrect Password
					$json_response_array = array("desc" => "Incorrect Password");
					$json_response_encode = json_encode($json_response_array,true);
				}
			}
		}else{
			if(mysqli_num_rows($get_user_details) > 1){
				//Duplicated Details, Contact Admin
				$json_response_array = array("desc" => "Duplicated Details, Contact Admin");
				$json_response_encode = json_encode($json_response_array,true);
			}else{
				//User Not Exists
				$json_response_array = array("desc" => "User Not Exists");
				$json_response_encode = json_encode($json_response_array,true);
			}
		}
	}else{
		if(empty($user)){
			//Username Field Empty
			$json_response_array = array("desc" => "Username Field Empty");
			$json_response_encode = json_encode($json_response_array,true);
		}else{
			if(empty($pass)){
				//Password Field Empty
				$json_response_array = array("desc" => "Password Field Empty");
				$json_response_encode = json_encode($json_response_array,true);
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
	<title>Login | VTU BUSINESS WEBSITE</title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
    <style>
	body{
		background-color: var(--color-5);
	}
    </style>
</head>
<body>
    <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-abs s-position-abs br-radius-5px m-width-94 s-width-50 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-25 s-padding-tp-3 m-padding-bm-25 s-padding-bm-3 m-margin-tp-20 s-margin-tp-5 m-margin-lt-2 s-margin-lt-24">
        <img src="<?php echo $web_http_host; ?>/uploaded-image/<?php echo str_replace(['.',':'],'-',$_SERVER['HTTP_HOST']).'_'; ?>logo.png" style="user-select: auto; object-fit: contain; object-position: center;" class="a-cursor m-position-rel s-position-rel m-inline-block-dp s-inline-block-dp m-width-30 s-width-30 m-height-30 s-height-30 m-margin-tp-0 s-margin-tp-0 m-margin-bm-3 s-margin-bm-2"/><br/>
        <span style="user-select: auto;" class="text-bg-1 color-4 m-inline-block-dp s-inline-block-dp text-bold-500 m-font-size-20 s-font-size-25 m-margin-bm-2 s-margin-bm-2">ACCOUNT LOGIN</span><br>
        <form method="post" action="">
            <input style="text-align: center; text-transform: lowercase;" name="user" type="text" value="" placeholder="Username" pattern="[a-zA-Z]{6,}" title="Username must be atleast 6 lowercase letters long (No Space)" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" autocomplete="on" required/><br/>
            <input style="text-align: center;" name="pass" type="password" value="" placeholder="********" pattern="{8,}" title="Password must be atleast 8 character long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" autocomplete="off" required/><br/>
            <button id="" name="login" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-2 s-margin-bm-2" >
                LOGIN
            </button><br/>
            <span style="user-select: auto;" class="color-4 m-block-dp s-block-dp m-font-size-16 s-font-size-18 m-margin-tp-2 s-margin-tp-2 m-margin-bm-2 s-margin-bm-2">Dont have an account?
		<a href="<?php echo $web_http_host; ?>/web/Register.php">
			<span style="user-select: auto;" class="a-cursor color-8 text-bold-600 m-font-size-16 s-font-size-18">
				Signup
			</span>
		</a>
            </span><br>
            <span style="user-select: auto;" class="color-4 m-block-dp s-block-dp m-font-size-16 s-font-size-18">
		<a href="<?php echo $web_http_host; ?>/web/PasswordRecovery.php">
			<span style="user-select: auto;" class="a-cursor color-2 text-bold-600 m-font-size-16 s-font-size-18">
				Password Recovery
			</span>
		</a>
            </span><br>
        </form>
    </div>

<?php if(isset($_SESSION["product_purchase_response"])){ ?>
<div style="text-align: center;" id="customAlertDiv" class="bg-2 box-shadow m-z-index-2 s-z-index-2 m-block-dp s-block-dp m-position-fix s-position-fix m-top-20 s-top-40 br-radius-5px m-width-60 s-width-26 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-1 m-padding-bm-5 s-padding-bm-1 m-margin-lt-19 s-margin-lt-36 m-margin-bm-2 s-margin-bm-2">
	<span style="user-select: notne;" class="color-10 text-bold-500 m-font-size-20 s-font-size-25">
		<?php echo $_SESSION["product_purchase_response"]; ?>
	</span><br/>
	<button style="text-align: center; user-select: auto;" onclick="customDismissPop();" onkeypress="keyCustomDismissPop(event);" class="button-box onhover-bg-color-10 a-cursor color-2 bg-10 m-font-size-12 s-font-size-13 br-style-tp-0 m-inline-dp s-inline-block-dp m-position-rel s-position-rel m-width-30 s-width-30 m-height-auto s-height-auto m-margin-tp-1 s-margin-tp-1 m-margin-bm-2 s-margin-bm-2 m-margin-lt-0 s-margin-lt-0 m-margin-rt-0 s-margin-rt-0 m-padding-tp-5 s-padding-tp-5 m-padding-bm-5 s-padding-bm-5 m-padding-lt-5 s-padding-lt-5 m-padding-rt-5 s-padding-rt-5">
		DISMISS
	</button>
</div>
<script>
	function customDismissPop(){
		var customAlertDiv = document.getElementById("customAlertDiv");
		setTimeout(function(){
			customAlertDiv.style.display = "none";
		}, 300);
	}

	document.addEventListener("keydown", function(event){
		if(event.keyCode === 13){
			//prevent enter key default function
			event.preventDefault();
			var customAlertDiv = document.getElementById("customAlertDiv");
			setTimeout(function(){
				customAlertDiv.style.display = "none";
			}, 300);
		}
	});

	clearProductResponse();
	function clearProductResponse(){
		var productHttp = new XMLHttpRequest();
        productHttp.open("GET", "../unset-product.php");
        productHttp.setRequestHeader("Content-Type", "application/json");
        // productHttp.onload = function(){
        //     alert(productHttp.status);
        // }
        productHttp.send();
	}
</script>
<?php } ?>
<script src="/jsfile/bc-custom-all.js"></script>
</body>
</html>