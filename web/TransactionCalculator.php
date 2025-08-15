<?php session_start();
include("../func/bc-config.php");
?>
<!DOCTYPE html>

<head>
    <title>Transactions Calculator | <?php echo $get_all_site_details["site_title"]; ?></title>
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
    <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
        <div style="text-align: center;"
            class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;"
                class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25">TRANSACTIONS CALCULATOR</span><br>
            <form method="get" action="TransactionCalculator.php" class="m-margin-tp-1 s-margin-tp-1">

                <div style="text-align: center;"
                    class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="api-status-span" class="a-cursor text-bold-700" style="user-select: auto;">CUSTOMIZED
                        DATE</span>
                </div><br />
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 7 days', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    7 DAYS
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 2 weeks', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    2 WEEKS
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 1 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    1 MONTH
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 2 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    2 MONTH
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 3 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    3 MONTH
                </button><br />
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 4 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    4 MONTH
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 5 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    5 MONTH
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 6 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    6 MONTH
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 6 months', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    8 MONTH
                </button>
                <button type="button"
                    onclick="updateTransactionCalculatorCustomDate(`<?php echo date('Y-m-d', strtotime('- 1 years', time())); ?>`, `<?php echo date('Y-m-d'); ?>`);"
                    style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-4 m-inline-block-dp s-inline-block-dp m-font-size-10 s-font-size-15 outline-none onhover-bg-color-10 br-radius-5px br-width-4 br-color-4 m-width-auto s-width-auto m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    1 YEAR
                </button><br />
                <?php

                $search_starting_date = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_GET["starts"]))));
                $search_ending_date = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_GET["ends"]))));

                if ((strtotime($search_starting_date) !== false) && (strtotime($search_ending_date) !== false) && (strtotime($search_starting_date) < strtotime($search_ending_date))) {
                    $default_starting_date = $search_starting_date;
                    $default_ending_date = $search_ending_date;
                } else {
                    $default_starting_date = date("Y-m-d");
                    $default_ending_date = date("Y-m-d");
                }

                ?>
                <div style="text-align: center;"
                    class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="" class="a-cursor" style="user-select: auto;">STARTING DATE</span>
                </div><br />
                <input style="text-align: center;" id="starting-date" name="starts" type="date"
                    value="<?php echo $default_starting_date; ?>" placeholder=""
                    class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1"
                    required /><br />

                <div style="text-align: center;"
                    class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="" class="a-cursor" style="user-select: auto;">ENDING DATE</span>
                </div><br />
                <input style="text-align: center;" id="ending-date" name="ends" type="date"
                    value="<?php echo $default_ending_date; ?>" placeholder=""
                    class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1"
                    required /><br />
                <button type="submit" style="user-select: auto;"
                    class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1">
                    CALCULATE
                </button><br>
            </form>
        </div>
        <div style="text-align: center;"
            class="bg-3 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-3 m-padding-bm-5 s-padding-bm-3 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <?php
            $airtime_array = array("product_name" => "Airtime", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $sme_data_array = array("product_name" => "SME Data", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $cg_data_array = array("product_name" => "Corporate Data", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $dd_data_array = array("product_name" => "Direct Data", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $shared_data_array = array("product_name" => "Shared Data", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $electric_array = array("product_name" => "Electricity Bill", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $exam_array = array("product_name" => "Exam PIN", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $cable_array = array("product_name" => "Cable TV", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $sms_array = array("product_name" => "Bulk SMS", "amount" => 0, "amount_paid" => 0, "qty" => 0);
            $card_array = array("product_name" => "Card", "amount" => 0, "amount_paid" => 0, "qty" => 0);

            $product_type = mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_GET["type"]))));
            $starting_date = strtotime(mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_GET["starts"])))) . " 00:00:00");
            $ending_date = strtotime(mysqli_real_escape_string($connection_server, trim(strip_tags(strtolower($_GET["ends"])))) . " 23:59:59");

            if (($starting_date !== false) && ($ending_date !== false) && ($starting_date < $ending_date)) {
                $transaction_calculator_reference_array = array();
                $select_transaction_based_on_date_provided = mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && status='1'");
                if (mysqli_num_rows($select_transaction_based_on_date_provided) > 0) {
                    while ($transaction_details = mysqli_fetch_assoc($select_transaction_based_on_date_provided)) {
                        if ((strtotime($transaction_details["date"]) >= $starting_date) && (strtotime($transaction_details["date"]) <= $ending_date)) {
                            if (!empty($transaction_details["api_id"]) && is_numeric($transaction_details["api_id"]) && !empty($transaction_details["product_id"]) && is_numeric($transaction_details["product_id"])) {
                                $select_api_list_with_id = mysqli_query($connection_server, "SELECT * FROM sas_apis WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && id='" . $transaction_details["api_id"] . "'");
                                $select_product_list_with_id = mysqli_query($connection_server, "SELECT * FROM sas_products WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && id='" . $transaction_details["product_id"] . "'");
                                if ((mysqli_num_rows($select_api_list_with_id) == 1) && (mysqli_num_rows($select_product_list_with_id) == 1)) {
                                    //AIRTIME SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "airtime") !== false) {
                                        $airtime_array["amount"] += $transaction_details["amount"];
                                        $airtime_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $airtime_array["qty"] += 1;
                                    }

                                    //SME SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "sme data") !== false) {
                                        $sme_data_array["amount"] += $transaction_details["amount"];
                                        $sme_data_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $sme_data_array["qty"] += 1;
                                    }

                                    //CG SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "cg data") !== false) {
                                        $cg_data_array["amount"] += $transaction_details["amount"];
                                        $cg_data_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $cg_data_array["qty"] += 1;
                                    }

                                    //SHARED SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "shared data") !== false) {
                                        $shared_data_array["amount"] += $transaction_details["amount"];
                                        $shared_data_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $shared_data_array["qty"] += 1;
                                    }

                                    //DIRECT DATA SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "dd data") !== false) {
                                        $shared_data_array["amount"] += $transaction_details["amount"];
                                        $shared_data_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $shared_data_array["qty"] += 1;
                                    }

                                    //ELECTRIC SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "electric") !== false) {
                                        $electric_array["amount"] += $transaction_details["amount"];
                                        $electric_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $electric_array["qty"] += 1;
                                    }

                                    //EXAM SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "exam") !== false) {
                                        $exam_array["amount"] += $transaction_details["amount"];
                                        $exam_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $exam_array["qty"] += 1;
                                    }

                                    //CABLE SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "cable") !== false) {
                                        $cable_array["amount"] += $transaction_details["amount"];
                                        $cable_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $cable_array["qty"] += 1;
                                    }

                                    //SMS SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "sms") !== false) {
                                        $sms_array["amount"] += $transaction_details["amount"];
                                        $sms_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $sms_array["qty"] += 1;
                                    }

                                    //CARD SALES
                                    if (strpos(strtolower($transaction_details["type_alternative"]), "card") !== false) {
                                        $card_array["amount"] += $transaction_details["amount"];
                                        $card_array["amount_paid"] += $transaction_details["discounted_amount"];
                                        $card_array["qty"] += 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $product_detail_json = array($airtime_array, $sme_data_array, $cg_data_array, $dd_data_array, $shared_data_array, $electric_array, $exam_array, $cable_array, $sms_array, $card_array);

            foreach ($product_detail_json as $trans_array) {
                echo
                    '<div style="text-align: center; font-style: italic; margin-top: 20px;"
                    	class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-100 s-width-100">
                    	<span id="" class="a-cursor" style="user-select: auto;">' . ucwords($trans_array["product_name"]) . ' Transactions (Qty: ' . $trans_array["qty"] . ')</span>
                    </div><br />
                    <div style="text-align: center;" class="bg-2 br-color-5 br-style-all-1 br-width-1 m-inline-block-dp s-inline-block-dp br-radius-5px m-width-90 s-width-90 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-2 s-margin-tp-1">
                        <span style="user-select: auto;" class="color-4 text-bold-800 m-font-size-16 s-font-size-18">₦' . toDecimal($trans_array["amount"], 2) . ' Amount</span><br>
                        <span style="user-select: auto;" class="color-4 text-bold-500 m-font-size-14 s-font-size-16">₦' . toDecimal($trans_array["amount_paid"], 2) . ' Amount Paid</span><br>
                        <span style="user-select: auto; font-style: italic;" class="color-4 text-bold-500 m-font-size-16 s-font-size-18">' . str_replace(["-", "_"], " ", ucwords($trans_array["product_name"])) . '</span><br>
                    </div>';
            }
            ?>


        </div>
    </div>
    <script>
        function updateTransactionCalculatorCustomDate(start_date, end_date) {
            var starting_date = document.getElementById("starting-date");
            var ending_date = document.getElementById("ending-date");
            starting_date.value = start_date;
            ending_date.value = end_date;
        }
    </script>
    <?php include("../func/bc-footer.php"); ?>

</body>

</html>