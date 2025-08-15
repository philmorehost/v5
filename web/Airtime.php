<?php session_start([
	'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
include("../func/bc-config.php");
include("../func/modern-header.php");

<?php
    if(isset($_POST["buy_airtime"])){
        $purchase_method = "web";
        $network = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["network"])));
        $phone = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["phone"])));
        $amount = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["amount"])));

        // Map the network to the format expected by the backend
        $isp_map = [
            'mtn' => 1,
            'glo' => 2,
            'airtel' => 3,
            '9mobile' => 4,
        ];
        $isp = $isp_map[$network];

        $_POST['isp'] = $isp;
        $_POST['phone-number'] = $phone;

		include_once("func/airtime.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }
?>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/web/Dashboard.php">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Fund Wallet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">
                            Airtime
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Cable TV
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Electricity
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout.php">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Buy Airtime</h1>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="network" class="form-label">Network</label>
                                    <select class="form-select" name="network" id="network" required>
                                        <option value="">Select Network</option>
                                        <option value="mtn">MTN</option>
                                        <option value="glo">GLO</option>
                                        <option value="airtel">Airtel</option>
                                        <option value="9mobile">9mobile</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone Number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" name="amount" class="form-control" id="amount" placeholder="Amount" required>
                                </div>
                                <button type="submit" name="buy_airtime" class="btn btn-primary">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php
include("../func/modern-footer.php");
?>
