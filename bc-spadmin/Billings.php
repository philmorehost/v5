<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    if(isset($_GET["deleteBillingID"])){
        $billing_id_number = mysqli_real_escape_string($connection_server, preg_replace("/[^0-9]+/", "", trim(strip_tags($_GET["deleteBillingID"]))));
        if(is_numeric($billing_id_number)){
            $select_billing_with_id = mysqli_query($connection_server, "SELECT * FROM sas_vendor_billings WHERE id='$billing_id_number'");
            if(mysqli_num_rows($select_billing_with_id) == 1){
                mysqli_query($connection_server, "DELETE FROM sas_vendor_billings WHERE id='$billing_id_number'");
                $json_response_array = array("desc" => ucwords("Billing Deleted Successfully"));
                $json_response_encode = json_encode($json_response_array,true);
            }else{
                if(mysqli_num_rows($select_billing_with_id) > 1){
                    $json_response_array = array("desc" => ucwords("Duplicated Details, Contact Admin"));
                    $json_response_encode = json_encode($json_response_array,true);
                }else{
                    if(mysqli_num_rows($select_billing_with_id) < 1){
                        $json_response_array = array("desc" => ucwords("Billing Details Not Exists Or May Have Been Deleted"));
                        $json_response_encode = json_encode($json_response_array,true);
                    }
                }
            }
        }else{
            //Non-numeric string
            $json_response_array = array("desc" => "Non-numeric string");
            $json_response_encode = json_encode($json_response_array,true);
        }
        
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: /bc-spadmin/Billings.php");
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
    <?php
    	//Redirect To Vendor Page
        $getVendorUrl = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["vendorUrl"])));
    	$getVendorLogAuth = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["vendorLogAuth"])));
        $getRedirectUrl = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["redirect"])));
    	
    	if(isset($_GET["vendorUrl"]) && !empty($getVendorUrl) && isset($_GET["vendorLogAuth"]) && !empty($getVendorLogAuth)){
            if(isset($_GET["redirect"]) && !empty($getRedirectUrl)){
                echo '<script>	window.onload = function(){	window.open("http://'.$getVendorUrl.'/bc-admin/Dashboard.php?logVendorAdmin='.$getVendorLogAuth.'&&redirectAdminTo='.$getRedirectUrl.'","_blank"); window.open("/bc-spadmin/Vendors.php","_self");	}	</script>';
            }else{
                echo '<script>	window.onload = function(){	window.open("http://'.$getVendorUrl.'/bc-admin/Dashboard.php?logVendorAdmin='.$getVendorLogAuth.'","_blank"); window.open("/bc-spadmin/Vendors.php","_self");	}	</script>';
            }
    	}
    ?>
</head>
<body>
    <?php include("../func/bc-spadmin-header.php"); ?>
        <?php
            
            if(!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
                $page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
                $offset_statement = " OFFSET ".((10 * $page_num) - 10);
            }else{
                $offset_statement = "";
            }
            
            if(isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))){
                $search_statement = " && (bill_type LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR amount LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR description LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR starting_date LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR ending_date LIKE '%".trim(strip_tags($_GET["searchq"]))."%')";
                $search_parameter = "searchq=".trim(strip_tags($_GET["searchq"]))."&&";
            }else{
                $search_statement = "";
                $search_parameter = "";
            }
            $get_active_billing_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_billings WHERE starting_date != '' $search_statement ORDER BY date DESC LIMIT 10 $offset_statement");
            
        ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">VENDOR BILLINGS</span><br>
                <form method="get" action="Billings.php" class="m-margin-tp-1 s-margin-tp-1">
                    <input style="user-select: auto;" name="searchq" type="text" value="<?php echo trim(strip_tags($_GET["searchq"])); ?>" placeholder="Billing Type, Description, Year, Month, Day e.t.c" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                    <button style="user-select: auto;" type="submit" class="button-box a-cursor color-2 bg-4 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                        <img src="<?php echo $web_http_host; ?>/asset/white-search.png" class="m-width-50 s-width-50 m-height-100 s-height-100" />
                    </button>
                </form>
            </div>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-1 s-margin-bm-1">BILLING LIST (<?php echo mysqli_num_rows($get_active_billing_details); ?>)</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>S/N</th><th>Type</th><th>Description</th><th>Amount</th><th>Starting Date</th><th>Ending Date</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_active_billing_details) >= 1){
                        while($billing_details = mysqli_fetch_assoc($get_active_billing_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            $delete_billing_detail = '<span onclick="customJsRedirect(`/bc-spadmin/Billings.php?deleteBillingID='.$billing_details["id"].'`, `Are you sure you want to delete billing with serial number ['.$countTransaction.']`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Delete Billing" src="'.$web_http_host.'/asset/fa-delete.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            
                            $billing_type_with_link = $billing_details["bill_type"].' <span onclick="customJsRedirect(`/bc-spadmin/BillingEdit.php?billingID='.$billing_details["id"].'`, `Are you sure you want to edit billing with serial number ['.$countTransaction.']`);" style="text-decoration: underline; color: green;" class="a-cursor"><img title="Edit" src="'.$web_http_host.'/asset/fa-edit.png" style="width: 12px; padding: 6px 6px 6px 6px;" class="a-cursor bg-1 m-margin-lt-1 s-margin-lt-1" /></span>';
                            
                            echo 
                            '<tr>
                                <td>'.$countTransaction.'</td><td>'.$billing_type_with_link.'</td><td>'.checkTextEmpty($billing_details["description"]).'</td><td>'.toDecimal($billing_details["amount"], 2).'</td><td>'.formDateWithoutTime($billing_details["starting_date"]).'</td><td>'.formDateWithoutTime($billing_details["ending_date"]).'</td><td class="m-width-15 s-width-15">'.$delete_billing_detail.'</td>
                            </tr>';
                        }
                    }
                    ?>
                </table>
            </div><br/>

            <div class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
                <?php if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)){ ?>
                <a href="Bilings.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
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
                <a href="Bilings.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
                </a>
            </div>
        </div>

    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>