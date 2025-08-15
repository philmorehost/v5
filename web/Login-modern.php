<?php session_start([
    'cookie_lifetime' => 86400,
	'gc_maxlifetime' => 86400,
]);

    include("../func/bc-config.php");
    include("../func/modern-header.php");

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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="user" class="form-label">Username</label>
                            <input type="text" name="user" class="form-control" id="user" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Password</label>
                            <input type="password" name="pass" class="form-control" id="pass" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="/web/Register.php">Don't have an account? Sign up</a><br>
                    <a href="/web/PasswordRecovery.php">Forgot your password?</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION["product_purchase_response"])){ ?>
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="responseModalLabel">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php echo $_SESSION["product_purchase_response"]; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
    var responseModal = new bootstrap.Modal(document.getElementById('responseModal'), {});
    responseModal.show();
</script>
<?php
    // Unset the session variable to prevent it from showing again on page refresh
    unset($_SESSION["product_purchase_response"]);
}
?>

<?php
    include("../func/modern-footer.php");
?>
