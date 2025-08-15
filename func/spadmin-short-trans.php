<?php
    $get_user_transaction_details = mysqli_query($connection_server, "SELECT * FROM sas_vendor_transactions ORDER BY date DESC LIMIT 5");
?>
<div class="bg-3 m-width-100 s-width-100 m-height-auto s-height-auto m-margin-bm-2 s-margin-bm-2">
    <div class="bg-3 m-width-96 s-width-96 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2">
        <form method="get" action="Transactions.php">
            <input style="user-select: auto;" name="searchq" type="text" value="<?php echo trim(strip_tags($_GET["searchq"])); ?>" placeholder="Email, Reference No e.t.c" class="input-box color-4 bg-2 outline-none br-radius-5px br-width-4 br-color-4 m-width-40 s-width-30 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/>
            <button style="user-select: auto;" type="submit" class="button-box a-cursor color-2 bg-10 outline-none onhover-bg-color-7 br-radius-5px br-width-4 br-color-4 m-width-10 s-width-5 m-height-2 s-height-2 m-margin-bm-1 s-margin-bm-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1" >
                <img src="<?php echo $web_http_host; ?>/asset/white-search.png" class="m-width-50 s-width-50 m-height-100 s-height-100" />
            </button>
        </form>
    </div>
    <div style="border: 1px solid var(--color-4); user-select: auto; cursor: grab;" class="bg-3 m-width-95 s-width-95 m-height-auto s-height-auto m-scroll-x s-scroll-x m-margin-lt-2 s-margin-lt-2">
        <table style="" class="table-tag m-font-size-12 s-font-size-14" title="Horizontal Scroll: Shift + Mouse Scroll Button">
            <tr>
                <th>S/N</th><th>Reference</th><th>Type</th><th style="">Description</th><th>Amount</th><th>Amount Paid</th><th>Status</th><th>Date</th>
            </tr>
            <?php
                if(mysqli_num_rows($get_user_transaction_details) >= 1){
                    while($user_transaction = mysqli_fetch_assoc($get_user_transaction_details)){
                        $transaction_type = ucwords($user_transaction["type_alternative"]);
                        $countTransaction += 1;
                        echo 
                        '<tr>
                            <td>'.$countTransaction.'</td><td style="user-select: auto;">'.$user_transaction["reference"].'</td><td>'.$transaction_type.'</td><td>'.$user_transaction["description"].'</td><td>'.toDecimal($user_transaction["amount"], 2).'</td><td>'.toDecimal($user_transaction["discounted_amount"], 2).'</td><td>'.tranStatus($user_transaction["status"]).'</td><td>'.formDate($user_transaction["date"]).'</td>
                        </tr>';
                    }
                }
            ?>
        </table>
    </div>
</div>