<?php session_start();
    include("../func/bc-config.php");
        
    if(isset($_POST["set-answer"])){
        $quest = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["quest"])));
        $answer = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["answer"]))));
        if(!empty($quest) && is_numeric($quest) && !empty($answer)){
            if((strlen($answer) >= 3) && (strlen($answer) <= 20)){
                if(empty(trim($get_logged_user_details["security_quest"])) || empty(trim($get_logged_user_details["security_answer"])) || (strlen($get_logged_user_details["security_answer"]) < 3) || (strlen($get_logged_user_details["security_answer"]) > 20)){
                    alterUser($get_logged_user_details["username"], "security_quest", $quest);
                    alterUser($get_logged_user_details["username"], "security_answer", $answer);
                    //Security Details Sets Successfully, Proceed To Answer Security Questions
                    $json_response_array = array("desc" => "Security Details Sets Successfully, Proceed To Answer Security Questions");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    //Security Details Cannot Be Changed, Click On Recovery Button To Alter Details
                    $json_response_array = array("desc" => "Security Details Cannot Be Changed, Click On Recovery Button To Alter Details");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                //Security Answer Must Be Between 3-20 Charaters Without Special Charaters
                $json_response_array = array("desc" => "Security Answer Must Be Between 3-20 Charaters Without Special Charaters");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Security Answer Cannot Be Empty
            $json_response_array = array("desc" => "Security Answer Cannot Be Empty");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["submit-answer"])){
        $answer = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["answer"]))));
        if(!empty($answer)){
            if((strlen($answer) >= 3) && (strlen($answer) <= 20)){
                if($answer == $get_logged_user_details["security_answer"]){
                    setcookie("security_answer", $answer, time() + (6 * 60 * 60));
                    //Hurray!!! Security Verification Successful
                    $json_response_array = array("desc" => "Hurray!!! Security Verification Successful");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    //Invalid Security Answer, Try Again
                    $json_response_array = array("desc" => "Invalid Security Answer, Try Again");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                //Security Answer Must Be Between 3-20 Charaters Without Special Charaters
                $json_response_array = array("desc" => "Security Answer Must Be Between 3-20 Charaters Without Special Charaters");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Security Answer Cannot Be Empty
            $json_response_array = array("desc" => "Security Answer Cannot Be Empty");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["reset-answer"])){
        $quest = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["quest"])));
        $answer = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_POST["answer"]))));
        $pass = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["pass"])));
        if(!empty($quest) && is_numeric($quest) && !empty($answer)){
            if((strlen($answer) >= 3) && (strlen($answer) <= 20)){
                $md5_pass = md5($pass);
    			$check_user_password_details = mysqli_query($connection_server, "SELECT * FROM sas_users WHERE vendor_id='".$get_logged_user_details["vendor_id"]."' && username='".$get_logged_user_details["username"]."' && password='$md5_pass'");
    			if(mysqli_num_rows($check_user_password_details) == 1){
    			    alterUser($get_logged_user_details["username"], "security_quest", $quest);
                    alterUser($get_logged_user_details["username"], "security_answer", $answer);
                    unset($_SESSION["reset-security-detail"]);
                    //Security Details Resets Successfully
                    $json_response_array = array("desc" => "Security Details Resets Successfully");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    //Incorrect Password
                    $json_response_array = array("desc" => "Incorrect Password");
                    $json_response_encode = json_encode($json_response_array,true);
                }
            }else{
                //Security Answer Must Be Between 3-20 Charaters Without Special Charaters
                $json_response_array = array("desc" => "Security Answer Must Be Between 3-20 Charaters Without Special Charaters");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Security Answer Cannot Be Empty
            $json_response_array = array("desc" => "Security Answer Cannot Be Empty");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["reset-detail"])){
        $_SESSION["reset-security-detail"] = "reset";
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["cancel-reset"])){
        unset($_SESSION["reset-security-detail"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
?>
<!DOCTYPE html>
<head>
    <title>Security Question | <?php echo $get_all_site_details["site_title"]; ?></title>
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
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-10 s-padding-tp-10 m-padding-bm-10 s-padding-bm-10 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <?php if(!isset($_COOKIE["security_answer"]) || ($_COOKIE["security_answer"] !== $get_logged_user_details["security_answer"])){ ?>
            <?php if(!isset($_SESSION["reset-security-detail"])){ ?>
                <?php if(empty(trim($get_logged_user_details["security_quest"])) || empty(trim($get_logged_user_details["security_answer"])) || (strlen($get_logged_user_details["security_answer"]) < 3) || (strlen($get_logged_user_details["security_answer"]) > 20)){ ?>
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">SET SECURITY QUESTION</span><br>
                <form method="post" action="">
                    <select style="text-align: center;" id="" name="quest" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                        <option value="" default hidden selected>Choose Security Question</option>
                        <?php
                            //Security Quests
                            $get_security_quest_details = mysqli_query($connection_server, "SELECT * FROM sas_security_quests");
                            if(mysqli_num_rows($get_security_quest_details) >= 1){
                                while($security_details = mysqli_fetch_assoc($get_security_quest_details)){
                                    echo '<option value="'.$security_details["id"].'">'.$security_details["quest"].'</option>';
                                }
                            }
                        ?>
                    </select><br/>
                    <input style="text-align: center;" id="" name="answer" onkeyup="" type="text" value="" placeholder="Security Answer e.g Dog" pattern="[0-9a-zA-Z ]{3,20}" title="Security Answer Must Be Between 3-20 Charaters Without Special Charaters" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                    <button id="" name="set-answer" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                        SET
                    </button><br>
                </form>
                <?php } ?>

                <?php if(!empty(trim($get_logged_user_details["security_quest"])) && !empty(trim($get_logged_user_details["security_answer"])) && (strlen($get_logged_user_details["security_answer"]) >= 3) && (strlen($get_logged_user_details["security_answer"]) <= 20)){
                    $get_security_quest = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_security_quests WHERE id='".$get_logged_user_details["security_quest"]."'"))
                ?>
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1"><?php echo $get_security_quest["quest"]; ?></span><br>
                <form method="post" action="">
                    <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                        <span id="user-status-span" class="a-cursor" style="user-select: auto;">SECURITY ANSWER</span>
                    </div><br/>
                    <input style="text-align: center;" id="" name="answer" onkeyup="" type="text" value="" placeholder="Security Answer e.g Dog" pattern="[0-9a-zA-Z ]{3,20}" title="Security Answer Must Be Between 3-20 Charaters Without Special Charaters" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                    <button id="" name="submit-answer" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                        SUBMIT ANSWER
                    </button><br>
                </form>
                <form method="post" action="">
                    <button id="" name="reset-detail" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                        RESET DETAIL
                    </button><br>
                </form>
                <?php } ?>
            <?php }else{ ?>
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">RESET SECURITY QUESTION</span><br>
                <form method="post" action="">
                    <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                        <span id="user-status-span" class="a-cursor" style="user-select: auto;">SECURITY QUESTION</span>
                    </div><br/>
                    <select style="text-align: center;" id="" name="quest" onchange="" class="select-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
                        <option value="" default hidden selected>Choose Security Question</option>
                        <?php
                            //Security Quests
                            $get_security_quest_details = mysqli_query($connection_server, "SELECT * FROM sas_security_quests");
                            if(mysqli_num_rows($get_security_quest_details) >= 1){
                                while($security_details = mysqli_fetch_assoc($get_security_quest_details)){
                                    echo '<option value="'.$security_details["id"].'">'.$security_details["quest"].'</option>';
                                }
                            }
                        ?>
                    </select><br/>
                    <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                        <span id="user-status-span" class="a-cursor" style="user-select: auto;">SECURITY ANSWER</span>
                    </div><br/>
                    <input style="text-align: center;" id="" name="answer" onkeyup="" type="text" value="" placeholder="Security Answer e.g Dog" pattern="[0-9a-zA-Z ]{3,20}" title="Security Answer Must Be Between 3-20 Charaters Without Special Charaters" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                    <input style="text-align: center;" name="pass" type="password" value="" placeholder="Account Password" pattern="{8,}" title="Password must be atleast 8 character long" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" autocomplete="off" required/><br/>
                    <button id="" name="reset-answer" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                        RESET
                    </button><br>
                </form>
                <form method="post" action="">
                    <button id="" name="cancel-reset" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                        CANCEL RESET
                    </button><br>
                </form>
            <?php } ?>
            <?php }else{ ?>
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25">SECURITY QUESTION CAN ONLY BE RESET WHEN IT EXPIRES</span><br>
            <?php } ?>
            
        </div>
	<?php include("../func/bc-footer.php"); ?>
	
</body>
</html>