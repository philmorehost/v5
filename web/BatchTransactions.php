<?php session_start();
include("../func/bc-config.php");
?>
<!DOCTYPE html>

<head>
    <title>Batch Transactions | <?php echo $get_all_site_details["site_title"]; ?></title>
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
    <?php
    $select_user_requeried_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && reference='" . trim(strip_tags($_GET["requery"])) . "'");
    if (mysqli_num_rows($select_user_requeried_transaction_details) == 1) {
        $purchase_method = "web";
        include_once($_SERVER["DOCUMENT_ROOT"] . "/web/func/requery-transaction.php");
        $json_response_decode = json_decode($json_response_encode, true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
    }

    if (!isset($_GET["searchq"]) && isset($_GET["page"]) && !empty(trim(strip_tags($_GET["page"]))) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)) {
        $page_num = mysqli_real_escape_string($connection_server, trim(strip_tags($_GET["page"])));
        $offset_statement = " OFFSET " . ((20 * $page_num) - 20);
    } else {
        $offset_statement = "";
    }

    if (isset($_GET["searchq"]) && !empty(trim(strip_tags($_GET["searchq"])))) {
        $search_statement = " && (product_name LIKE '%" . trim(strip_tags($_GET["searchq"])) . "%' OR batch_number LIKE '%" . trim(strip_tags($_GET["searchq"])) . "%')";
        $search_parameter = "searchq=" . trim(strip_tags($_GET["searchq"])) . "&&";
    } else {
        $search_statement = "";
        $search_parameter = "";
    }
    $get_user_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_bulk_product_purchase WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' $search_statement ORDER BY date DESC LIMIT 20 $offset_statement");

    ?>
    <div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
        <div
            class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-tp-5 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-600 m-font-size-20 s-font-size-25">BATCH
                TRANSACTIONS</span><br>
            <form method="get" action="BatchTransactions.php" class="m-margin-tp-1 s-margin-tp-1">
                <input style="user-select: auto;" name="searchq" type="text"
                    value="<?php echo trim(strip_tags($_GET["searchq"])); ?>"
                    placeholder="Batch Number e.t.c"
                    class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" />
                <button style="user-select: auto;" type="submit"
                    class="button-box a-cursor color-2 bg-10 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1">
                    <img src="<?php echo $web_http_host; ?>/asset/white-search.png"
                        class="m-width-50 s-width-50 m-height-100 s-height-100" />
                </button>
            </form>
        </div>
        <div style="border: 1px solid var(--color-4); user-select: auto;"
            class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
            <table class="m-width-100 s-width-100 table-tag m-font-size-12 s-font-size-14"
                title="Horizontal Scroll: Shift + Mouse Scroll Button">
                <tr>
                    <th>S/N</th>
                    <th>Product Name</th>
                    <th>Batch number</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <?php
                if (mysqli_num_rows($get_user_transaction_details) >= 1) {
                    while ($user_transaction = mysqli_fetch_assoc($get_user_transaction_details)) {

                        $get_successful_batch_transaction = mysqli_num_rows(mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && status='1' && batch_number='" . $user_transaction["batch_number"] . "'"));
                        $get_pending_batch_transaction = mysqli_num_rows(mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && status='2' && batch_number='" . $user_transaction["batch_number"] . "'"));
                        $get_failed_batch_transaction = mysqli_num_rows(mysqli_query($connection_server, "SELECT * FROM sas_transactions WHERE vendor_id='" . $get_logged_user_details["vendor_id"] . "' && username='" . $get_logged_user_details["username"] . "' && status='3' && batch_number='" . $user_transaction["batch_number"] . "'"));

                        $batch_transations_status = "Success: " . $get_successful_batch_transaction . "; Pending: " . $get_pending_batch_transaction . "; Failed: " . $get_failed_batch_transaction;

                        $countTransaction += 1;
                        echo
                            '<tr>
                                    <td>' . $countTransaction . '</td><td>' . $user_transaction["product_name"] . '</td><td style="user-select: auto;"><a style="color: inherit; text-decoration: underline;" target="_blank" href="/web/Transactions.php?searchq=' . $user_transaction["batch_number"] . '">' . $user_transaction["batch_number"] . '</a></td><td>'.$batch_transations_status.'</td><td>' . formDate($user_transaction["date"]) . '</td>
                                </tr>';
                    }
                }
                ?>
            </table>
        </div>
        <div
            class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2 m-margin-tp-2 s-margin-tp-2">
            <?php if (isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) > 1)) { ?>
                <a
                    href="BatchTransactions.php?<?php echo $search_parameter; ?>page=<?php echo (trim(strip_tags($_GET["page"])) - 1); ?>">
                    <button style="user-select: auto;"
                        class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-lt s-float-lt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Prev</button>
                </a>
            <?php } ?>
            <?php
            if (isset($_GET["page"]) && is_numeric(trim(strip_tags($_GET["page"]))) && (trim(strip_tags($_GET["page"])) >= 1)) {
                $trans_next = (trim(strip_tags($_GET["page"])) + 1);
            } else {
                $trans_next = 2;
            }
            ?>
            <a href="BatchTransactions.php?<?php echo $search_parameter; ?>page=<?php echo $trans_next; ?>">
                <button style="user-select: auto;"
                    class="button-box color-2 bg-4 onhover-bg-color-7 m-inline-block-dp s-inline-block-dp m-float-rt s-float-rt m-width-20 s-width-20 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1">Next</button>
            </a>
        </div>
    </div>
    <?php include("../func/bc-footer.php"); ?>

</body>

</html>