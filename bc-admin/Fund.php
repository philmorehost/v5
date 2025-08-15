<?php session_start();
    include("../func/bc-admin-config.php");
        
    $payment_gateway_array = array("monnify", "flutterwave", "paystack");
?>
<!DOCTYPE html>
<head>
    <title>Fund Wallet | <?php echo $get_all_site_details["site_title"]; ?></title>
    <meta charset="UTF-8" />
    <meta name="description" content="<?php echo substr($get_all_site_details["site_desc"], 0, 160); ?>" />
    <meta http-equiv="Content-Type" content="text/html; " />
    <meta name="theme-color" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="<?php echo $css_style_template_location; ?>">
    <link rel="stylesheet" href="/cssfile/bc-style.css">
    <meta name="author" content="BeeCodes Titan">
    <meta name="dc.creator" content="BeeCodes Titan">
    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="/jsfile/bc-custom-all.js"></script>
    <script>
    
    	function monnifyPaymentGateway(){
    		payWithMonnify();
    	}

        function flutterwavePaymentGateway(){
    		makePaymentFlutterwave();
    	}

        function paystackPaymentGateway(){
    		makePaymentPaystack();
    	}
    	
        //MONNIFY CHECKOUT GATEWAY
        function payWithMonnify() {
            setTimeout(() => {
                MonnifySDK.initialize({
                    amount: document.getElementById("amount-to-pay").value,
                    currency: "NGN",
                    reference: document.getElementById("num-ref").value,
                    customerFullName: document.getElementById("user-name").value,
                    customerEmail: document.getElementById("user-email").value,
                    apiKey: document.getElementById("gateway-public").value,
                    contractCode: document.getElementById("gateway-encrypt").value,
                    paymentDescription: "Wallet Funding",
                    metadata: {
                        "name": "",
                        "age": ""
                    },
                    incomeSplitConfig: [],
                    onLoadStart: () => {
                        console.log("loading has started");
                    },
                    onLoadComplete: () => {
                        console.log("SDK is UP");
                    },
                    onComplete: function(response) {
                        //Implement what happens when the transaction is completed.
                        window.location.href = "/bc-admin/Dashboard.php";
                    },
                    onClose: function(data) {
                        //Implement what should happen when the modal is closed here
                        //window.location.href = "/bc-admin/Dashboard.php";
                    }
                });
            }, 100);
        }

        //FLUTTERWAVE CHECKOUT GATEWAY
        function makePaymentFlutterwave(){
            setTimeout(() => {
                FlutterwaveCheckout({
                    public_key: document.getElementById("gateway-public").value,
                    tx_ref: document.getElementById("num-ref").value,
                    amount: document.getElementById("amount-to-pay").value,
                    currency: "NGN",
                    payment_options: "card, banktransfer, ussd",
                    redirect_url: "",
                    meta: {
                        consumer_id: "",
                        consumer_mac: "",
                    },
                    customer: {
                        email: document.getElementById("user-email").value,
                        phone_number: document.getElementById("user-phone").value,
                        name: document.getElementById("user-name").value,
                    },
                    customizations: {
                        title: "",
                        description: "",
                        logo: "",
                    },
                    callback: function(payment) {
                        window.location.href = "/bc-admin/Dashboard.php";
                    }
                });
            }, 100);
        }

        //PAYSTACK CHECKOUT GATEWAY
        function makePaymentPaystack(){
            setTimeout(() => {
                let handler = PaystackPop.setup({
                key: document.getElementById("gateway-public").value, // Replace with your public key
                email: document.getElementById("user-email").value,
                amount: document.getElementById("amount-to-pay").value * 100,
                currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
                ref: document.getElementById("num-ref").value, // Replace with a reference you generated
                
                // label: "Optional string that replaces customer email"
                onClose: function() {
                    //window.location.href = "/bc-admin/Dashboard.php";
                },
                callback: function(response){
                    window.location.href = "/bc-admin/Dashboard.php";
                }
                });
                handler.openIframe();
            }, 100);
        }
    </script>
</head>
<body>
	<?php include("../func/bc-admin-header.php"); ?>	
        <div style="text-align: center;" class="bg-10 m-block-dp s-block-dp m-position-rel s-position-rel br-radius-5px m-width-94 s-width-94 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-10 s-padding-tp-10 m-padding-bm-10 s-padding-bm-10 m-margin-tp-5 s-margin-tp-5 m-margin-lt-2 s-margin-lt-2 m-margin-bm-2 s-margin-bm-2">
            <span style="user-select: auto;" class="text-bg-1 color-4 text-bold-500 m-font-size-20 s-font-size-25 m-inline-block-dp s-inline-block-dp m-margin-bm-1 s-margin-bm-1">FUND WALLET</span><br>
            <form method="post" action="">
                <div style="text-align: center; user-select: auto;" class="bg-3 m-inline-block-dp s-inline-block-dp m-width-70 s-width-50 m-margin-tp-1 s-margin-tp-1 m-margin-bm-0 s-margin-bm-0">
                    <?php
                        foreach($payment_gateway_array as $gateway_name){
                            $get_gateway_details = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_super_admin_payment_gateways WHERE gateway_name='$gateway_name'"));
                            if(in_array($get_gateway_details["status"], array(1, 2))){
                                if($get_gateway_details["status"] == 1){
                                    $gateway_status = '<img alt="'.ucwords(trim($get_gateway_details["gateway_name"])).'" id="'.strtolower(trim($get_gateway_details["gateway_name"])).'-lg" product-status="enabled" gateway-public="'.trim($get_gateway_details["public_key"]).'" gateway-encrypt="'.trim($get_gateway_details["encrypt_key"]).'" gateway-int="'.trim($get_gateway_details["percentage"]).'" product-name-array="'.implode(",",$payment_gateway_array).'" src="/asset/'.strtolower(trim($get_gateway_details["gateway_name"])).'.jpg" onclick="vtickPaymentGateway(this, `'.strtolower(trim($get_gateway_details["gateway_name"])).'`, `gatewayname`, `fundProceedBtn`, `jpg`);" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1 "/>';
                                }else{
                                    $gateway_status = '<img alt="'.ucwords(trim($get_gateway_details["gateway_name"])).'" id="'.strtolower(trim($get_gateway_details["gateway_name"])).'-lg" product-status="disabled" src="/asset/'.strtolower(trim($get_gateway_details["gateway_name"])).'.jpg" class="a-cursor bg-2 br-radius-100px m-inline-block-dp s-inline-block-dp onhover-img-filter m-width-20 s-width-22 m-margin-tp-1 s-margin-tp-1 m-margin-bm-1 s-margin-bm-1 m-margin-lt-1 s-margin-lt-1 "/>';
                                }
                            }else{
                                $gateway_status = '';
                            }

                            echo $gateway_status;
                        }
                    ?>
                </div><br/>
                <input id="gatewayname" name="" type="text" placeholder="Gateway Name" hidden readonly required/>
                <input id="amount-to-pay" name="" type="text" placeholder="" hidden readonly required/>
                <input id="user-name" name="" type="text" value="<?php echo $get_logged_admin_details['firstname']." ".$get_logged_admin_details['lastname']." ".$get_logged_admin_details['othername']; ?>" placeholder="" hidden readonly required/>
                <input id="user-email" name="" type="email" value="<?php echo $get_logged_admin_details['email']; ?>" placeholder="" hidden readonly required/>
                <input id="user-phone" name="" type="number" value="<?php echo $get_logged_admin_details['phone_number']; ?>" placeholder="" hidden readonly required/>
                <input id="num-ref" name="" type="number" value="" placeholder="" hidden readonly required/>
                <input id="gateway-public" name="" type="text" placeholder="" hidden readonly required/>
                <input id="gateway-encrypt" name="" type="text" placeholder="" hidden readonly required/>
                <input style="text-align: center;" id="fund-amount" name="" type="number" value="" onkeyup="vcheckPaymentGatewayDetails('fundProceedBtn','2');" placeholder="Amount e.g 100" step="1" min="100" title="Charater must be atleast 3 digit" class="input-box outline-none color-4 bg-2 m-inline-block-dp s-inline-block-dp outline-none br-radius-5px br-width-4 br-color-4 m-width-60 s-width-45 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" required/><br/>
                <button id="fundProceedBtn" name="" type="button" onclick="" style="pointer-events: none; user-select: auto;" class="button-box a-cursor outline-none color-2 bg-7 m-inline-block-dp s-inline-block-dp outline-none onhover-bg-color-5 br-radius-5px br-width-4 br-color-4 m-width-63 s-width-47 m-padding-tp-2 s-padding-tp-1 m-padding-bm-2 s-padding-bm-1 m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-margin-bm-1 s-margin-bm-1" >
                    PROCEED
                </button><br>
                <div style="text-align: center;" class="color-4 bg-3 m-inline-block-dp s-inline-block-dp m-font-size-14 s-font-size-16 m-width-60 s-width-45">
                    <span id="product-status-span" class="a-cursor" style="user-select: auto;"></span>
                </div>
            </form>
        </div>

	<?php include("../func/bc-admin-footer.php"); ?>
	
</body>
</html>