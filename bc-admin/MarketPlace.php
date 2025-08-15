<?php session_start();
    include("../func/bc-admin-config.php");
    
    if(isset($_SESSION["spadmin_vendor_auth"]) && ($_SESSION["spadmin_vendor_auth"] == true)){
		$status_statement = "(status='1' OR status='2')";
	}else{
		$status_statement = "status='1'";
	}
?>
<!DOCTYPE html>
<head>
    <title>API MarketPlace | <?php echo $get_all_super_admin_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_super_admin_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
</head>
<body>
    <?php include("../func/bc-admin-header.php"); ?>
        <?php
            
            if(!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)){
                $page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
                $offset_statement = " OFFSET ".((10 * $page_num) - 10);
            }else{
                $offset_statement = "";
            }
            
            if(isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))){
                $search_statement = " && (api_website LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR api_type LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR description LIKE '%".trim(strip_tags($_GET["searchq"]))."%' OR price LIKE '%".trim(strip_tags($_GET["searchq"]))."%')";
                $search_parameter = "searchq=".trim(strip_tags($_GET["searchq"]))."&&";
            }else{
                $search_statement = "";
                $search_parameter = "";
            }
            $get_active_user_details = mysqli_query($connection_server, "SELECT * FROM sas_api_marketplace_listings WHERE $status_statement $search_statement ORDER BY date DESC LIMIT 20 $offset_statement");
            
        ?>
        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">MARKETPLACE</span>
                <a href="Cart.php"  style="text-decoration: none;" class="a-cursor">
                    <div class="a-cursor bg-3 m-width-8 s-width-4 m-height-auto s-height-auto m-float-rt s-float-rt m-clr-float-both s-clr-float-both m-margin-bm-1 s-margin-bm-1 m-margin-rt-5 s-margin-rt-2">
                        <img src="<?php echo $web_http_host; ?>/asset/cart.png" style="user-select: auto; pointer-events: none;" class="a-cursor m-position-rel s-position-rel m-inline-block-dp s-inline-block-dp m-width-100 s-width-100 m-height-auto s-height-auto" />
                        <span style="user-select: auto; padding: 2px 4px 2px 4px;" id="count-cart-items" class="a-cursor color-4 bg-10 text-bold-600 br-radius-50 m-position-abs s-position-abs m-font-size-11 s-font-size-13 m-inline-block-dp s-inline-block-dp">0</span>
                    </div>
                </a><br>
                <form method="get" action="MarketPlace.php" class="m-margin-tp-1 s-margin-tp-1">
                    <input style="user-select: auto;" name="searchq" type="text" value="<?php echo trim(strip_tags($_GET["searchq"])); ?>" placeholder="Product Name, API Website e.t.c" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                    <button style="user-select: auto;" type="submit" class="button-box a-cursor color-2 bg-4 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                        <img src="<?php echo $web_http_host; ?>/asset/white-search.png" class="m-width-50 s-width-50 m-height-100 s-height-100" />
                    </button>
                </form>
            </div>

            <span style="user-select: auto;" class="color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-lt-2 s-margin-lt-2 m-margin-bm-1 s-margin-bm-1">AVAILABLE API LIST (ONETIME PAYMENT)</span><br>
            <div style="border: 1px solid var(--color-4); user-select: auto;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
                <table class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
                    <tr>
                        <th>S/N</th><th>API Website</th><th>Product Type</th><th>Price (Naira)</th><th>Description</th><th>Status</th><th>Action</th>
                    </tr>
                    <?php
                    if(mysqli_num_rows($get_active_user_details) >= 1){
                        while($user_details = mysqli_fetch_assoc($get_active_user_details)){
                            $transaction_type = ucwords($user_details["type_alternative"]);
                            $countTransaction += 1;
                            
                            $api_website = str_replace(["//www.","/","http:","https:"],"",$user_details["api_website"]);
                            $api_type = strtoupper(str_replace(["_","-"]," ",$user_details["api_type"]));
                            $product_description = str_replace("\n","<br/>",checkTextEmpty($user_details["description"]));
                            $product_api_price = toDecimal($user_details["price"], 2);
                            $api_status_array = array(1 => "Public", 2 => "Private");

                            $select_api_lists = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='".$get_logged_admin_details["id"]."' && api_base_url='".$api_website."' && api_type='".$user_details["api_type"]."'");
                            if(mysqli_num_rows($select_api_lists) > 0){
                                if(mysqli_num_rows($select_api_lists) == 1){
                                    $api_status = "Installed";
                                    $purchase_action_button = '';
                                }else{
                                    if(mysqli_num_rows($select_api_lists) > 1){
                                        $api_status = "Installed (Duplicated API)";
                                        $purchase_action_button = '( )';
                                    }
                                }
                            }else{
                                $api_status = '<span style="color: red;">new</span>';
                                $purchase_action_button = '( <span onclick="addAPIToCart(this, `'.$user_details["id"].'`, `'.$get_logged_admin_details["id"].'`);" id="cart-'.$user_details["id"].'-'.$get_logged_admin_details["id"].'" style="text-decoration: underline; color: green;" class="a-cursor cart-spans">Add Cart</span> )';
                            }
                            $all_user_account_action = $api_status." ".$purchase_action_button;
                            if($api_website != $_SERVER["HTTP_HOST"]){
                                echo 
                                '<tr>
                                    <td>'.$countTransaction.'</td><td>'."https://".$api_website.'</td><td>'.$api_type.'</td><td>'.$product_api_price.'</td><td style="user-select: auto;">'.$product_description.'</td><td>'.$api_status_array[$user_details["status"]].'</td><td>'.$all_user_account_action.'</td>
                                </tr>';
                            }
                        }
                    }
                    ?>
                </table>
            </div><br/>

            <div class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
                <?php if(isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)){ ?>
                <a href="MarketPlace.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
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
                <a href="MarketPlace.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                    <button style="user-select: auto;" class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
                </a>
            </div>
        </div>

        
    <?php include("../func/bc-admin-footer.php"); ?>
    
</body>
</html>