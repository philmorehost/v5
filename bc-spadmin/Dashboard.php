<?php session_start();
    include("../func/bc-spadmin-config.php");
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
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel m-scroll-x s-scroll-x br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <div style="" class="color-2 bg-10  br-radius-5px m-inline-block-dp s-inline-block-dp m-width-100 s-width-100 m-height-100 s-height-100">
                <div style="text-align: left;" class="color-4 m-font-scolor-2 bg-3 text-bold-300 m-font-size-16 s-font-size-20ize-14 s-font-size-18 m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-95 s-width-95 m-height-auto s-height-auto m-position-rel s-position-rel m-margin-tp-2 s-margin-tp-2 m-margin-lt-2 s-margin-lt-2">Welcome, <?php echo strtoupper($get_logged_spadmin_details["firstname"]." ".$get_logged_spadmin_details["lastname"]).checkIfEmpty(ucwords($get_logged_spadmin_details["othername"]),", ", ""); ?></div>
                <div style="text-align: left;" class="bg-2 br-radius-5px m-block-dp s-block-dp m-scroll-none s-scroll-none m-width-94 s-width-94 m-height-auto s-height-auto m-margin-lt-2 s-margin-lt-2 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-1 s-padding-tp-1 m-padding-bm-1 s-padding-bm-1 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <span class="color-4 m-font-size-14 s-font-size-18">WEBSITE: <span class="color-10 text-bold-600"><?php echo ucwords($web_http_host); ?></span>, ACCOUNT STATUS: <span class="color-10 text-bold-600"><?php echo accountStatus($get_logged_spadmin_details["status"]); ?></span></span>
                </div>
                <div style="text-align: center;" class="bg-2 box-shadow onhover-bg-color-5 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-0 s-margin-lt-0 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/user-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20 s-width-20" /><br>
                    <span class="color-4 text-bold-500 m-font-size-14 s-font-size-18">Vendor</span><br/>
                    <a title="Create Vendor" href="<?php echo $web_http_host; ?>/bc-spadmin/VendorReg.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20 s-width-20" />
                  		
                    </a>
                    <a title="View Vendors" href="<?php echo $web_http_host; ?>/bc-spadmin/Vendors.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
                
                <div style="text-align: center;" class="bg-2 box-shadow onhover-bg-color-5 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-1 s-margin-lt-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/developer-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20" /><br>
                    <span class="color-4 text-bold-500 m-font-size-14 s-font-size-18">API</span><br/>
                    <a title="Create API" href="<?php echo $web_http_host; ?>/bc-spadmin/CreateAPI.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20 s-width-20" />
                                      		
                    </a>
                    <a title="View APIs" href="<?php echo $web_http_host; ?>/bc-spadmin/MarketPlace.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
                
                <div style="text-align: center;" class="bg-2 box-shadow onhover-bg-color-5 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-0 s-margin-lt-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/billing-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" /><br>
                    <span class="color-4 text-bold-500 m-font-size-14 s-font-size-18">Billings</span><br/>
                    <a title="Create Billing" href="<?php echo $web_http_host; ?>/bc-spadmin/CreateBilling.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20 s-width-20" />
                  		
                    </a>
                    <a title="View Billings" href="<?php echo $web_http_host; ?>/bc-spadmin/Billings.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
                
                <div style="text-align: center;" class="bg-2 box-shadow onhover-bg-color-5 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-1 s-margin-lt-0 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/add-fund.svg" class="bg-10 box-shadow br-radius-5px m-width-20" /><br>
                    <span class="color-4 text-bold-500 m-font-size-14 s-font-size-18">Payment Gateways</span><br/>
                    <a title="Payment Gateway" href="<?php echo $web_http_host; ?>/bc-spadmin/PaymentGateway.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
                
                <div style="text-align: center;" class="bg-2 box-shadow onhover-bg-color-5 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-0 s-margin-lt-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/mail-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" /><br>
                    <span class="color-4 text-bold-500 m-font-size-14 s-font-size-18">Mail</span><br/>
                    <a title="Send Mail" href="<?php echo $web_http_host; ?>/bc-spadmin/SendMail.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/create-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20 s-width-20" />
                  		
                    </a>
                    <a title="Email Template" href="<?php echo $web_http_host; ?>/bc-spadmin/EmailTemplates.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
                
                <div style="text-align: center;" class="bg-2 box-shadow onhover-bg-color-5 br-radius-5px m-inline-block-dp s-inline-block-dp m-scroll-none s-scroll-none m-width-45 s-width-29 m-height-auto s-height-auto m-margin-lt-1 s-margin-lt-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-2 s-padding-tp-2 m-padding-bm-2 s-padding-bm-2 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1">
                    <img src="<?php echo $web_http_host; ?>/asset/trans-icon.png" class="bg-10 box-shadow br-radius-50 m-width-20" /><br>
                    <span class="color-4 text-bold-500 m-font-size-14 s-font-size-18">Pay Orders | Transactions</span><br/>
                    <a title="View Payment Order" href="<?php echo $web_http_host; ?>/bc-spadmin/PaymentOrders.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" />
                                      		
                    </a>
                    <a title="View Transactions" href="<?php echo $web_http_host; ?>/bc-spadmin/Transactions.php" class="m-margin-lt-1 s-margin-lt-1 m-margin-rt-1 s-margin-rt-1">
                    	<img src="<?php echo $web_http_host; ?>/asset/view-icon.png" class="bg-10 box-shadow br-radius-5px m-width-20 s-width-20" />
                                      		
                    </a>
                </div>
                
            </div>
        </div>
		
		<?php include("../func/spadmin-short-trans.php"); ?>
    <?php include("../func/bc-spadmin-footer.php"); ?>
    
</body>
</html>