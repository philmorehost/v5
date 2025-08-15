<?php session_start();
include("../func/bc-config.php");

if (isset($_POST["buy-airtime"])) {
    $batch_number = substr(str_shuffle("123456789012345678901234567890"), 0, 6);
    $purchase_method = "web";
    //Ilterate Bulk Phone
    $bulk_phone_no = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["bulk-phone-number"])));
    $bulk_phone_no = array_filter(explode(",", trim($bulk_phone_no)));
    $bulk_phone_no = array_unique($bulk_phone_no);

    foreach ($bulk_phone_no as $each_phone_number) {
        $_POST["phone-number"] = $each_phone_number;
        if (count(array_filter(explode(",", trim($_POST["isp"])))) > 1) {
            $_POST["isp"] = identifyISP($each_phone_number);
        }

        include("func/airtime.php");
        alterTransaction($reference, "batch_number", $batch_number);

        $json_response_decode = json_decode($json_response_encode, true);
        //echo '<script>alert("'.$json_response_decode["status"].': '.$json_response_decode["desc"].'");</script>';
    }

    $select_batch_transaction = mysqli_query($connection_server, "SELECT batch_number FROM sas_bulk_product_purchase WHERE batch_number = '$batch_number'");
    if (mysqli_num_rows($select_batch_transaction) == 0) {
        //RECORD BATCH PURCHASE DETAILS
        $batch_product_name = "airtime";
        $batch_sql = "INSERT INTO sas_bulk_product_purchase (vendor_id, username, product_name, batch_number) VALUES ('".$get_logged_user_details["vendor_id"]."', '".$get_logged_user_details["username"]."', '$batch_product_name', '$batch_number')";
        // Prepare the statement
        mysqli_query($connection_server, $batch_sql);
    }

    $_SESSION["product_purchase_response"] = "AIRTIME PROCESSED, CHECK BULK BATCH PAGE FOR BATCH: " . $batch_number;
    header("Location: " . $_SERVER["REQUEST_URI"]);
}

?>
<!DOCTYPE html>

<head>
    <title>Bulk Airtime | <?php echo $get_all_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
</head>

<body>
    <?php include("../func/bc-header.php"); ?>
    <div style="text-align: center;"
        class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-1 s-padding-bm-1 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
        <span style="user-select: auto;"
            class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">BULK
            AIRTIME</span><br>
        <form method="post" action="">
            <div style="text-align: center; user-select: auto;"
                class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                <img alt="Airtel" id="airtel-lg" product-status="enabled" src="/asset/airtel.png"
                    onclick="tickBulkAirtimeCarrier('airtel');"
                    class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 " />
                <img alt="MTN" id="mtn-lg" product-status="enabled" src="/asset/mtn.png"
                    onclick="tickBulkAirtimeCarrier('mtn');"
                    class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1" />
                <img alt="Glo" id="glo-lg" product-status="enabled" src="/asset/glo.png"
                    onclick="tickBulkAirtimeCarrier('glo');"
                    class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1" />
                <img alt="9mobile" id="9mobile-lg" product-status="enabled" src="/asset/9mobile.png"
                    onclick="tickBulkAirtimeCarrier('9mobile');"
                    class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1" />
            </div><br />
            <input id="isprovider" name="isp" type="text" placeholder="Isp" hidden readonly required />
            <textarea style="text-align: center; min-height: 100px;" id="phone-number" name=""
                onkeyup="tickBulkAirtimeCarrier();" type="text" value=""
                placeholder="Phone seperated by line or commas e.g 08124232128, 09068240860" pattern="[0-9]{11}"
                title="Charater must be an 11 digit"
                class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1"
                required></textarea><br />
            <textarea style="" id="filtered-phone-number" name="bulk-phone-number" type="text" value="" hidden readonly
                required></textarea>

            <div style="text-align: center;"
                class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                <span id="phone-numbers-span" class="a-cursor" style="user-select: auto;">Phone Number Count: 0</span>
            </div><br />
            <input style="text-align: center;" id="product-amount" name="amount" onkeyup="tickBulkAirtimeCarrier();"
                type="text" value="" placeholder="Amount e.g 100" pattern="[0-9]{3,}"
                title="Charater must be atleast 3 digit"
                class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1"
                required /><br />
            <div style="text-align: left;"
                class="color-2 bg-3 m-inline-block-dp s-inline-block-dp m-width-60 s-width-45 m-margin-bm-1 s-margin-bm-1">
                <input id="phone-bypass" onclick="tickBulkAirtimeCarrier('airtel');" type="checkbox"
                    class="outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none m-width-auto s-width-auto m-margin-bm-1 s-margin-bm-1" />
                <div
                    class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-80 s-width-80 m-margin-lt-1 s-margin-lt-1">
                    <label for="phone-bypass" class="a-cursor" style="user-select: auto;">
                        Bypass Phone Verification
                    </label>
                </div>
            </div><br>
            <button id="proceedBtn" name="buy-airtime" type="button" style="pointer-events: none; user-select: auto;"
                class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                BUY AIRTIME
            </button><br>
            <div style="text-align: center;"
                class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
            </div>
        </form>
    </div>

    <?php include("../func/short-trans.php"); ?>
    <?php include("../func/bc-footer.php"); ?>

</body>

</html>