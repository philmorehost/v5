<?php session_start();
    include("../func/bc-spadmin-config.php");
    
    $get_host_name = array_filter(explode(":",trim($_SERVER["HTTP_HOST"])));
    $get_host_name = $get_host_name[0];
    if(isset($_POST["delete-cart"])){
        $get_cart_items = mysqli_real_escape_string($connection_server, $_COOKIE[str_replace([":","."],"_",$get_host_name)."_".$get_logged_spadmin_details["id"]."_cart_items"]);
        $marketplace_redirect = false;
        if(isset($get_cart_items)){
            $exp_cart_items = array_filter(explode(" ",trim($get_cart_items)));
            if(count($exp_cart_items) >= 1){
                foreach($exp_cart_items as $items){
                    $all_refined_cart_items .= "id='$items' ";
                }
                $exp_all_refined_cart_items = array_filter(explode(" ",trim($all_refined_cart_items)));
                $implode_cart_items = implode(" OR ", $exp_all_refined_cart_items);
                //Clear Cart Items Cookies
                setcookie(str_replace([":","."],"_",$get_host_name)."_".$get_logged_spadmin_details["id"]."_cart_items", "", (time() - 100));
                mysqli_query($connection_server, "DELETE FROM sas_api_marketplace_listings WHERE $implode_cart_items");
                //Cart Item Deleted Successfully
                $json_response_array = array("desc" => "Cart Item Deleted Successfully");
                $json_response_encode = json_encode($json_response_array,true);
                $marketplace_redirect = true;
            }else{
                //No Item In Cart
                $json_response_array = array("desc" => "No Item In Cart");
                $json_response_encode = json_encode($json_response_array,true);
            }
        }else{
            //Cart Is Empty
            $json_response_array = array("desc" => "Cart Is Empty");
            $json_response_encode = json_encode($json_response_array,true);
        }
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        if($marketplace_redirect == false){
            header("Location: ".$_SERVER["REQUEST_URI"]);
        }else{
            header("Location: /bc-spadmin/MarketPlace.php");
        }
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

        <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
            <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">CARTS ITEMS</span>
            </div>

            <?php
                $get_cart_items = $_COOKIE[str_replace([":","."],"_",$get_host_name)."_".$get_logged_spadmin_details["id"]."_cart_items"];
				
				if(isset($get_cart_items) && !empty($get_cart_items)){
                    $exp_cart_items = array_filter(explode(" ",trim($get_cart_items)));
                    
                    $count_old_cart_items = 0;
                    $count_new_cart_items = 0;
                    $count_old_cart_items_amount = 0;
                    $count_new_cart_items_amount = 0;
                    foreach($exp_cart_items as $item_id){
                        if(is_numeric($item_id) && ($item_id > 0)){
                            $get_active_cart_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_api_marketplace_listings WHERE id='".$item_id."'"));
                            
                            if(isset($get_active_cart_details["api_type"])){
                                $api_website = str_replace(["//www.","/","http:","https:"],"",$get_active_cart_details["api_website"]);
                                $api_type = strtoupper(str_replace(["_","-"]," ",$get_active_cart_details["api_type"]));
                                $product_description = checkTextEmpty($get_active_cart_details["description"]);
                                $product_api_price = toDecimal($get_active_cart_details["price"], 2);
                                $api_status_array = array(1 => "Public", 2 => "Private");
                                $count_new_cart_items += 1;
                                $count_new_cart_items_amount += $get_active_cart_details["price"];
                                $api_status = "".$api_status_array[$get_active_cart_details["status"]]."";
                                echo 
                                    '<div class="bg-3 br-radius-5px br-color-4 br-width-3 br-style-all-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
                                        <span style="user-select: auto;" id="" class="color-4 text-bold-600 m-position-rel s-position-rel m-font-size-20 s-font-size-22 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-2 s-margin-lt-2">'.strtoupper($api_type).' API</span>
                                        <span style="user-select: auto;" id="" class="color-4 text-bold-600 m-position-rel s-position-rel m-font-size-18 s-font-size-22 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-clr-float-both s-clr-float-both m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-rt-2 s-margin-rt-2">Price: N'.$product_api_price.'</span><br/>
                                        <span style="user-select: auto;" id="" class="color-4 text-bold-600 m-position-rel s-position-rel m-font-size-13 s-font-size-16 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-2 s-margin-lt-2"><span style="user-select: auto; text-decoration: underline;">Description:</span> <span class="color-7 text-bold-500 m-font-size-12 s-font-size-14 m-inline-dp s-inline-dp">'.$product_description.'</span></span><br/>
                                        <span style="user-select: auto;" id="" class="color-4 text-bold-600 m-position-rel s-position-rel m-font-size-13 s-font-size-16 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-2 s-margin-lt-2"><span style="user-select: auto; text-decoration: underline;">Status:</span> <span class="color-7 text-bold-500 m-font-size-12 s-font-size-14 m-inline-dp s-inline-dp">'.$api_status.'</span></span><br/>
                                        <span style="user-select: auto;" id="" class="color-7 text-bold-500 m-position-rel s-position-rel m-font-size-13 s-font-size-16 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-2 s-margin-bm-2 m-margin-lt-2 s-margin-lt-2">API Website: <a href="https://'.$api_website.'" style="user-select: auto; text-decoration: underline;" class="color-4 text-bold-600">https://'.$api_website.'</a></span>
                                        <span style="user-select: auto; text-decoration: underline; color: red;"  onclick="removeAPIFromCart(`'.$item_id.'`, `'.$get_logged_spadmin_details["id"].'`);" id="" class="a-cursor text-bold-400 m-position-rel s-position-rel m-font-size-14 s-font-size-16 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-clr-float-both s-clr-float-both m-margin-tp-1 s-margin-tp-1 m-margin-bm-2 s-margin-bm-2 m-margin-rt-2 s-margin-rt-2">Remove</span><br/>
                                    </div>';
                            }else{
                                //Unknown Item_id
                            }
                        }
                    }
                }else{
                    //No Item In Cart
                    echo 
                        '<img alt="Logo" src="'.$web_http_host.'/asset/ooops.gif" style="user-select: auto; object-fit: contain; object-position: center;" class="m-position-rel s-position-rel m-inline-block-dp s-inline-block-dp m-width-60 s-width-50 m-height-auto s-height-auto m-margin-lt-20 s-margin-lt-20"/><br/>
                        <center>
                            <span style="user-select: auto;" id="" class="color-10 text-bold-600 m-position-rel s-position-rel m-font-size-20 s-font-size-22 m-inline-block-dp s-inline-block-dp m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-2 s-margin-lt-2">Oooops!!! Cart Empty</span>
                        </center>';
                }
           ?>
           <?php if($count_new_cart_items > 0){
                if(($count_new_cart_items) == 1){
                    $item_singular_plural = "item";
                }else{
                    if(($count_new_cart_items) > 1){
                        $item_singular_plural = "items";
                    }else{
                        $item_singular_plural = "item";
                    }
                }
           ?>
                <div style="user-select: auto; text-align: right;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-100 s-width-100">
                    <span style="user-select: auto;" class="color-4 m-inline-block-dp s-inline-block-dp text-bold-500 m-font-size-16 s-font-size-18 m-margin-tp-1 s-margin-tp-1 m-margin-rt-2 s-margin-rt-2 m-margin-bm-2 s-margin-bm-1">Sub Total: N<?php echo toDecimal(($count_old_cart_items_amount + $count_new_cart_items_amount), 2); ?></span><br>
                    <span style="user-select: auto;" class="color-4 m-inline-block-dp s-inline-block-dp text-bold-500 m-font-size-16 s-font-size-18 m-margin-tp-1 s-margin-tp-1 m-margin-rt-2 s-margin-rt-2 m-margin-bm-2 s-margin-bm-1">Total Amount: N<?php echo toDecimal($count_new_cart_items_amount, 2); ?></span><br>
                    <form method="post" action="">
                        <button onclick="askPermissionSubBtn(this,'Are you sure you want to delete Cart Items?');" name="delete-cart" type="button" style="user-select: auto;" class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-30 s-width-20 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-tp-2 s-margin-tp-2 m-margin-rt-2 s-margin-rt-2 m-margin-bm-2 s-margin-bm-2" >
                            DELETE ALL
                        </button><br/>
                    </form>
                </div>
           <?php } ?>
        </div>
        
        
    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>