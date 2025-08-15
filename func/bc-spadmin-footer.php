<?php if(isset($_SESSION["product_purchase_response"])){ ?>
<div style="text-align: center; max-height: 40%;" id="customAlertDiv" class="bg-2 box-shadow m-z-index-2 s-z-index-2 m-scroll-auto s-scroll-auto m-block-dp s-block-dp m-position-fix s-position-fix m-top-20 s-top-30 br-radius-5px m-width-60 s-width-26 m-height-auto s-height-auto m-padding-lt-1 s-padding-lt-1 m-padding-rt-1 s-padding-rt-1 m-padding-tp-5 s-padding-tp-1 m-padding-bm-5 s-padding-bm-1 m-margin-lt-19 s-margin-lt-26 m-margin-bm-2 s-margin-bm-2">
	<span style="user-select: auto; word-break: break-word;" class="color-10 text-bold-500 m-font-size-15 s-font-size-18">
		<?php echo $_SESSION["product_purchase_response"]; ?>
	</span><br/>
	<button style="text-align: center; user-select: auto;" onclick="customDismissPop();" onkeypress="keyCustomDismissPop(event);" class="button-box br-radius-50 onhover-bg-color-10 a-cursor color-2 bg-10 m-font-size-10 s-font-size-10 br-style-tp-0 m-inline-dp s-inline-block-dp m-bottom-0 s-bottom-5 m-position-sti s-position-sti m-width-30 s-width-30 m-height-auto s-height-auto m-margin-tp-1 s-margin-tp-1 m-margin-bm-2 s-margin-bm-2 m-margin-lt-0 s-margin-lt-0 m-margin-rt-0 s-margin-rt-0 m-padding-tp-5 s-padding-tp-5 m-padding-bm-5 s-padding-bm-5 m-padding-lt-5 s-padding-lt-5 m-padding-rt-5 s-padding-rt-5">
		DISMISS
	</button>
</div>
<script>
	function customDismissPop(){
		var customAlertDiv = document.getElementById("customAlertDiv");
		setTimeout(function(){
			customAlertDiv.style.display = "none";
		}, 300);
	}
	
	document.addEventListener("keydown", function(event){
		if(event.keyCode === 13){
			//prevent enter key default function
			event.preventDefault();
			var customAlertDiv = document.getElementById("customAlertDiv");
			setTimeout(function(){
				customAlertDiv.style.display = "none";
			}, 300);
		}
	});
	
	clearProductResponse();
	function clearProductResponse(){
		var productHttp = new XMLHttpRequest();
        productHttp.open("GET", "../unset-product.php");
        productHttp.setRequestHeader("Content-Type", "application/json");
        // productHttp.onload = function(){
        //     alert(productHttp.status);
        // }
        productHttp.send();
	}
</script>
<?php } ?>
<div class="m-width-100 s-width-0 m-block-dp s-none-dp m-height-80px s-height-0px"></div>
</div>
<div id="footerMenuDiv" style="height: 80px; border-radius: 40px 40px 0 0; user-select: auto;" class="color-1 bg-3 m-z-index-1 s-z-index-none m-scroll-x m-position-fix s-position-rel m-block-dp s-none-dp m-bottom-0 s-bottom-0 m-width-100 s-width-0">
	<a href="<?php echo $web_http_host; ?>/bc-spadmin/Vendors.php" class="a-cursor">
		<button style="left: auto; user-select: auto;" page-name="Vendors.php" class="footer-btn-js a-cursor bg-3 br-none outline-none m-inline-dp s-inline-block-dp m-position-abs s-position-rel m-bottom-0 m-width-20 m-height-100">
			<img src="<?php echo $web_http_host; ?>/asset/user-icon.svg" style="pointer-events: none; object-fit: contain; object-position: bottom; filter: brightness(10%);" class="footer-btn-img-js a-cursor bg-3 m-width-30 m-height-70"/><br/>
			<span style="filter: brightness(10%);" class="a-cursor color-8 text-bold-600 m-font-size-10">VENDORS</span>
		</button>
	</a>

	<a href="<?php echo $web_http_host; ?>/bc-spadmin/MarketPlace.php" class="a-cursor">
		<button style="user-select: auto;" page-name="MarketPlace.php" class="footer-btn-js a-cursor bg-3 br-none outline-none m-inline-dp s-inline-block-dp m-position-abs s-position-rel m-bottom-0 m-width-20 m-height-100">
			<img src="<?php echo $web_http_host; ?>/asset/cart-icon.png" style="pointer-events: none; object-fit: contain; object-position: bottom; filter: brightness(10%);" class="footer-btn-img-js a-cursor bg-3 m-width-30 m-height-70"/><br/>
			<span style="filter: brightness(10%);" class="a-cursor color-8 text-bold-600 m-font-size-10">MARKETPLACE</span>
		</button>
	</a>

	<a href="<?php echo $web_http_host; ?>/bc-spadmin/Dashboard.php" class="a-cursor">
		<button style="user-select: auto;" page-name="Dashboard.php" class="footer-btn-js a-cursor bg-3 br-none outline-none m-inline-dp s-inline-block-dp m-position-abs s-position-rel m-bottom-0 m-width-20 m-height-100">
			<img src="<?php echo $web_http_host; ?>/asset/home-icon.svg" style="pointer-events: none; object-fit: contain; object-position: bottom; filter: brightness(10%);" class="footer-btn-img-js a-cursor bg-3 m-width-30 m-height-70"/><br/>
			<span style="filter: brightness(10%);" class="a-cursor color-8 text-bold-600 m-font-size-10">HOME</span>
		</button>
	</a>

	<a href="<?php echo $web_http_host; ?>/bc-spadmin/CreateAPI.php" class="a-cursor">
		<button style="user-select: auto;" page-name="CreateAPI.php" class="footer-btn-js a-cursor bg-3 br-none outline-none m-inline-dp s-inline-block-dp m-position-abs s-position-rel m-bottom-0 m-width-20 m-height-100">
			<img src="<?php echo $web_http_host; ?>/asset/developer-icon.png" style="pointer-events: none; object-fit: contain; object-position: bottom; filter: brightness(10%);" class="footer-btn-img-js a-cursor bg-3 m-width-30 m-height-70"/><br/>
			<span style="filter: brightness(10%);" class="a-cursor color-8 text-bold-600 m-font-size-10">CREATE API</span>
		</button>
	</a>
	
	<a href="<?php echo $web_http_host; ?>/bc-spadmin/Transactions.php" class="a-cursor">
		<button style="user-select: auto;" page-name="Transactions.php" class="footer-btn-js a-cursor bg-3 br-none outline-none m-inline-dp s-inline-block-dp m-position-abs s-position-rel m-bottom-0 m-width-20 m-height-100">
			<img src="<?php echo $web_http_host; ?>/asset/trans-icon.png" style="pointer-events: none; object-fit: contain; object-position: bottom; filter: brightness(10%);" class="footer-btn-img-js a-cursor bg-3 m-width-30 m-height-70"/><br/>
			<span style="filter: brightness(10%);" class="a-cursor color-8 text-bold-600 m-font-size-10">TRANSACTIONS</span>
		</button>
	</a>

	<!-- Background Color Block -->
	<div style="z-index: -1; height: 45px; border-radius: 0px 0px 0 0; user-select: auto;" class="color-1 bg-10 m-scroll-x m-position-fix s-position-rel m-block-dp s-none-dp m-bottom-0 s-bottom-0 m-width-100 s-width-0"></div>
	<div style="z-index: -2; height: 50px; border-radius: 0px 0px 0 0; user-select: auto;" class="bg-1 m-scroll-x m-position-fix s-position-rel m-block-dp s-none-dp m-bottom-0 s-bottom-0 m-width-100 s-width-0"></div>
	<div style="z-index: -3; height: 50px; border-radius: 0px 0px 0 0; user-select: auto;" class="bg-4 m-scroll-x m-position-fix s-position-rel m-block-dp s-none-dp m-bottom-0 s-bottom-0 m-width-100 s-width-0"></div>
</div>
<script>
	var footerBtnJs = document.getElementsByClassName("footer-btn-js");
	var footerBtnImgJs = document.getElementsByClassName("footer-btn-img-js");
	var currentPage = window.location.href;
	
	for(x=0; x < footerBtnJs.length; x++){
		if(x !== 0){
			footerBtnJs[x].style = "left: "+(x * 20)+"%;";
		}
		var pageName = currentPage.split("/");
		pageName = pageName[(pageName.length-1)];
		if(footerBtnJs[x].getAttribute("page-name") === pageName){
			footerBtnImgJs[x].classList.remove("m-width-30");
			footerBtnImgJs[x].classList.add("m-width-100");
			footerBtnJs[x].classList.remove("bg-3");
			footerBtnJs[x].classList.add("bg-10");
			footerBtnJs[x].classList.add("br-radius-30px");
			footerBtnJs[x].classList.add("m-padding-tp-3");
		}else{
			footerBtnImgJs[x].classList.remove("m-width-100");
			footerBtnImgJs[x].classList.add("m-width-30");
		}
	}
</script>
<script src="/jsfile/bc-custom-all.js"></script>
<script src="/jsfile/bc-custom-vendor.js"></script>
<script src="/jsfile/bc-custom-super.js"></script>

<?php mysqli_close($connection_server); ?>