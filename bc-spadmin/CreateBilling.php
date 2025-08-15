<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_POST["create-billing"])){
        $type = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["type"])));
        $starting_date = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["starting-date"])));
        $ending_date = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["ending-date"])));
        $desc = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["desc"])));
        $amount = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9.]+/","",trim(strip_tags(strtolower($_POST["amount"])))));
        if(!empty($type) && !empty($starting_date) && strtotime($starting_date) && !empty($ending_date) && strtotime($ending_date) && !empty($amount) && is_numeric($amount) && (strtotime($starting_date) <= strtotime($ending_date))){
            $check_billing_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_billings WHERE starting_date='$starting_date' && ending_date='$ending_date'");

            if(mysqli_num_rows($check_billing_details) == 0){
                mysqli_query($connection_server, "INSERT INTO sas_vendor_billings (bill_type, description, amount, starting_date, ending_date) VALUES ('$type', '$desc', '$amount', '$starting_date','$ending_date')");
		        //Billing Created Successfully
                $json_response_array = array("desc" => "Billing Created Successfully");
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(mysqli_num_rows($check_billing_details) == 1){
                    //Billing Information Already Exists
                    $json_response_array = array("desc" => "Billing Information Already Exists");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(mysqli_num_rows($check_billing_details) > 1){
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
                if(empty($starting_date)){
                    //Starting Date Field Empty
                    $json_response_array = array("desc" => "Starting Date Field Empty");
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(!strtotime($starting_date)){
                        //Invalid Starting Date String
                        $json_response_array = array("desc" => "Invalid Starting Date String");
                        $json_response_encode = json_encode($json_response_array,true);
                    }else{
                        if(empty($ending_date)){
                            //Ending Date Field Empty
                            $json_response_array = array("desc" => "Ending Date Field Empty");
                            $json_response_encode = json_encode($json_response_array,true);
                        }else{
                            if(!strtotime($ending_date)){
                                //Invalid Ending Date String
                                $json_response_array = array("desc" => "Invalid Ending Date String");
                                $json_response_encode = json_encode($json_response_array,true);
                            }else{
                                if(empty($amount)){
                                    //Amount Field Empty
                                    $json_response_array = array("desc" => "Amount Field Empty");
                                    $json_response_encode = json_encode($json_response_array,true);
                                }else{
                                    if(!is_numeric($amount)){
                                        //Non-numeric Amount
                                        $json_response_array = array("desc" => "Non-numeric Amount");
                                        $json_response_encode = json_encode($json_response_array,true);
                                    }else{
                                        if(strtotime($starting_date) > strtotime($ending_date)){
                                            //Ending Date Must Be Greater Than Or Equals Starting Date
                                            $json_response_array = array("desc" => "Ending Date Must Be Greater Than Or Equals Starting Date");
                                            $json_response_encode = json_encode($json_response_array,true);
                                        }
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
    <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">CREATE BILLING</span><br>
        <form method="post" enctype="multipart/form-data" action="">
            <div style="text-align: center;" class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-20 s-width-15">
                <img src="<?php echo $web_http_host; ?>/asset/billing-icon.png" class="a-cursor m-width-100 s-width-100" style="pointer-events: none; user-select: auto;"/>
            </div><br/>
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="api-status-span" class="a-cursor" style="user-select: auto;">BILLING INFORMATION</span>
            </div><br/>
            <input style="text-align: center;" name="type" type="text" value="" placeholder="Billing Type e.g Maintenance Fee" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="api-status-span" class="a-cursor" style="user-select: auto;">BILLING AMOUNT</span>
            </div><br/>
            <input style="text-align: center;" name="amount" type="number" value="" placeholder="Amount" step="0.0001" min="1" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="api-status-span" class="a-cursor" style="user-select: auto;">STARTING DATE</span>
            </div><br/>
            <input style="text-align: center;" name="starting-date" type="date" value="" placeholder="" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="api-status-span" class="a-cursor" style="user-select: auto;">ENDING DATE</span>
            </div><br/>
            <input style="text-align: center;" name="ending-date" type="date" value="" placeholder="" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
            
            <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="api-status-span" class="a-cursor" style="user-select: auto;">DESCRIPTION</span>
            </div><br/>
            <textarea style="text-align: center; resize: none;" id="" name="desc" onkeyup="" placeholder="Description (Optional)" class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-height-5rem s-height-5rem m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" ></textarea><br/>
            
            <button name="create-billing" type="submit" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                CREATE BILLING
            </button><br>
        </form>
    </div><br/>
    
    <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        <div style="text-align: center;" class="color-10 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="admin-status-span" class="a-cursor" style="user-select: auto;">NB: Contact Admin For Further Assistance!!!</span>
            </div><br/>
        </form>
    </div>
    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>