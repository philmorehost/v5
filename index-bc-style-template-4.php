<?php
    include("func/bc-connect.php");
    //Select Vendor Table
	$select_vendor_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE website_url='".$_SERVER["HTTP_HOST"]."' LIMIT 1"));
	if(($select_vendor_table == true) && ($select_vendor_table["website_url"] == $_SERVER["HTTP_HOST"]) && ($select_vendor_table["status"] == 1)){
        $vendor_account_details = $select_vendor_table;
    }else{
        $vendor_account_details = "";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Buy Airtime Data Bundle Recharge Card Data Card Exam Pin Bulk SMS- Pay Electric Bills Cable TV Bills ">
    <meta name="author" content="BeeTech Creativity Nigeria Limited">
    <meta name="dc.creator" content="BeeTech Creativity Nigeria Limited">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <title>Buy Airtime Data Bundle Recharge Card Data Card Exam Pin Bulk SMS- Pay Electric Bills Cable TV Bills </title>

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="landing-assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="landing-assets/css/font-awesome.css">

    <link rel="stylesheet" href="landing-assets/css/templatemo-lava-3.css">

    <link rel="stylesheet" href="landing-assets/css/owl-carousel.css">

</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.php" class="logo">
                            <img id="web-logo" src="/uploaded-image/<?php echo str_replace(['.',':'],'-',$_SERVER['HTTP_HOST']).'_'; ?>logo.png" style="float:left; clear: both; width: auto; height: 60px; margin: 10px 0 0 20px;"/>
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#welcome" class="menu-item">Home</a></li>
                            <li class="scroll-to-section"><a href="#about" class="menu-item">About</a></li>
                            </li>
							<li><a href="/web/Dashboard.php">Login</a>
                            </li>
							<li><a href="/web/Register.php">Register</a>
                            </li>
                          
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->


    <!-- ***** Welcome Area Start ***** -->
    <div class="welcome-area" id="welcome">

        <!-- ***** Header Text Start ***** -->
        <div class="header-text">
            <div class="container">
                <div class="row">
                    <div class="left-text col-lg-6 col-md-12 col-sm-12 col-xs-12"
                        data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                        
                        <p>VTU Telecom base platform in Nigeria that services your daily needs ranging from AirtimeVTU, Data, Bills Payment, TV subscription, Electricity Bills, Instant Nation-wide bank Transfer, Educational Epins services etc.</p> 
                        <a href="/web/Dashboard.php" class="main-button-slider">Login</a>
						<a href="/web/Register.php" class="main-button-slider">Register</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->
    </div>
    <!-- ***** Welcome Area End ***** -->

    <!-- ***** Features Big Item Start ***** -->
    <section class="section" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="airtime">
                            <h4>Airtime VTU</h4>
                            <p>Get discounts from VTU airtime recharge on all GSM networks. It is faster and flexible. You also enjoy huge percentage disoucnt. </p>
                           
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="data bundle">
                            <h4>Data Bundle</h4>
                            <p>Buy or Sell MTN, GLO, AIRTEL, and 9MOBILE data plans at affordable prices. Get 1GB of MTN SME for as low as ₦175-260.</p>
                           
                        </div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="Recharge Card printing">
                            <h4>Recharge Card Printing</h4>
                            <p>The recharge card printing portal, converts epins to recharge cards you can sell to make money from selling recharge cards in Nigeria.</p>
                            
                        </div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="Data Card Printing">
                            <h4>Data Card Printing</h4>
                            <p>The Data card printing portal, converts VTU Data to Data recharge cards you can sell to make money from selling recharge cards in Nigeria.</p>
                            
                        </div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="Exam Pin">
                            <h4>Exam Pin</h4>
                            <p>This is the best place you can buy your Exam ePin such as WAEC Result Checker, NECO, NABTECH, JAMB DE, JAMB UTME </p>
                           
                        </div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="Electricity">
                            <h4>Electricity</h4>
                            <p>Pay Prepaid and Postpaid meter electricity bills with Simple and Secured system at your comfort anywhere, anytime. </p>
                           
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="Cable TV">
                            <h4>Cable TV</h4>
                            <p>Pay for your TV subscription – GOtv, DStv or StarTimes, as Paying for your TV subscription is always a breeze and stressless.</p>
                           
                        </div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="Bulk SMS">
                            <h4>Bulk SMS</h4>
                            <p>Send Bulk SMS to all GSM numbers in Nigeria, Instant Delivery Guarantee</p>
                            
                        </div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                    <div class="features-item">
                        <div class="features-icon">
                            <img src="landing-assets/images/features-img.png" alt="API">
                            <h4>API Integration</h4>
                            <p>Plug Our Powerful API system into your application as a developer and experience smooth and swift delivery of products and service.</p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Big Item End ***** -->

    <div class="left-image-decor"></div>

    <!-- ***** Features Big Item Start ***** -->
    <section class="section" id="promotion">
        <div class="container">
            <div class="row">
                <div class="left-image col-lg-5 col-md-12 col-sm-12 mobile-bottom-fix-big"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <img src="landing-assets/images/left-img.png" class="rounded img-fluid d-block mx-auto" alt="App">
                </div>
                <div class="right-text offset-lg-1 col-lg-6 col-md-12 col-sm-12 mobile-bottom-fix">
                    <ul>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                            <img src="landing-assets/images/about-img.png" alt="create vtu account">
                            <div class="text">
                                <h4>Create Account</h4>
                                <p>Begin by signing up for an account. It's a quick and easy process that gives you access to all our services.</p>
                            </div>
                        </li>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.5s">
                            <img src="landing-assets/images/about-img.png" alt="fund vtu wallet">
                            <div class="text">
                                <h4>Fund Your Wallet</h4>
                                <p>After creating your account, fund your wallet with your desired amount. This serves as your gateway to a variety of services we offer.</p>
                            </div>
                        </li>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.6s">
                            <img src="landing-assets/images/about-img.png" alt="enjoy vtu services">
                            <div class="text">
                                <h4>Enjoy Our Services</h4>
                                <p>With a funded wallet, you're ready to enjoy the convenience and reliability of our services</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Big Item End ***** -->

    <div class="right-image-decor"></div>



    <!-- ***** Footer Start ***** -->
    <footer id="contact-us">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <!-- ***** Contact Form Start ***** -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="left-image col-lg-5 col-md-12 col-sm-12 mobile-bottom-fix-big"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <img id="web-logo" src="/uploaded-image/<?php echo str_replace(['.',':'],'-',$_SERVER['HTTP_HOST']).'_'; ?>logo.png" style="float:left; clear: both; width: 250px; height: 250px; margin: 10px 0 0 20px;"/>
                </div>
                    </div>
                    <!-- ***** Contact Form End ***** -->
                    <div class="right-content col-lg-6 col-md-12 col-sm-12">
                        <h2>More About Us</h2>
                        <p>Your Comprehensive Bill Payment Infrastructure - Buy Mobile Data Bundles, VTU Airtime, Pay Electricity Bills, and TV Subscriptions at Cost-Effective Rates, Buy Exam Pins such as WAEC Result Checker, Neco, NABTEB, JAMB, and Bulk SMS Services</p><br/>
                      <!--<ul class="social">
                            <li><a href="https://fb.com/templatemo"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-rss"></i></a></li>
                            <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                        </ul>-->
                        <h3>Office Address</h3>
                        <p><?php echo $vendor_account_details["home_address"]; ?></p><br>
                        <h3>Email Desk</h3>
                        <?php
                        	$footer_email = $vendor_account_details["email"];
                        ?>
                        <p><a style="color: inherit; font-weight: bold;" href="mailto:<?php echo $footer_email; ?>">Contact Us</a> - for complaint, feedback and unresolved issues</p><br>
                        <h3>Contact Us</h3>
                        <?php
                        	$footer_phone_number = "+234".substr($vendor_account_details["phone_number"], 1, 11);
                        ?>
                        <p>Call: <a style="color: inherit; font-weight: bold;" href="tel:<?php echo $footer_phone_number; ?>"><?php echo $footer_phone_number; ?></a></p><br>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="sub-footer">
                        <p>Copyright &copy; <script>document.write(new Date().getFullYear())</script> - All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="landing-assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="landing-assets/js/popper.js"></script>
    <script src="landing-assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="landing-assets/js/owl-carousel.js"></script>
    <script src="landing-assets/js/scrollreveal.min.js"></script>
    <script src="landing-assets/js/waypoints.min.js"></script>
    <script src="landing-assets/js/jquery.counterup.min.js"></script>
    <script src="landing-assets/js/imgfix.min.js"></script>

    <!-- Global Init -->
    <script src="landing-assets/js/custom.js"></script>
	
    <!-- Floating Whatsapp Widget -->
    <script type="text/javascript">
        const webBrandName = '<?php echo strtoupper($_SERVER["HTTP_HOST"]); ?>';
        const webPhoneNo = '<?php echo "+234".substr($vendor_account_details["phone_number"], 1, 11); ?>';
        let config = {"contacts":[{"name": webBrandName,"title":"","phone":webPhoneNo,"image":""}],"type":"ww-extended","call":"","brand": "","subtitle":"click the below button to chat with us","welcome":"","size":"ww-normal","position":"ww-right","text":"Hi, My name is ......","teamSize":"1","email":"beebayads@gmail.com","toggle":false};
        let s = document.createElement('script'); s.type = 'text/javascript';
        s.async = true;
        s.src = 'https://cdn.jsdelivr.net/gh/Bayanovart23/scriptForWidget@main/index.js';
        let x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        s.onload = function () { 
            tmWidgetInit(config) 
        };
    </script>

</body>
</html>