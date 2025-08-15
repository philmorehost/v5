<?php session_start([
	'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
include("../func/bc-config.php");
include("../func/modern-header.php");

<?php
    if(isset($_POST["buy_electric"])){
        $purchase_method = "web";
        $action_function = 1;
        $disco = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["disco"])));
        $meter_type = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["meter-type"])));
        $meter_number = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["meter-number"])));
        $amount = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["amount"])));

        $_POST['epp'] = $disco;
        $_POST['type'] = $meter_type;
        $_POST['meter-number'] = $meter_number;

		include_once("func/electric.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        unset($_SESSION["meter_amount"]);
        unset($_SESSION["meter_number"]);
        unset($_SESSION["meter_provider"]);
        unset($_SESSION["meter_type"]);
        unset($_SESSION["meter_name"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["verify-meter"])){
        $purchase_method = "web";
        $action_function = 3;
        $disco = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["disco"])));
        $meter_type = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["meter-type"])));
        $meter_number = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["meter-number"])));
        $amount = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["amount"])));

        $_POST['epp'] = $disco;
        $_POST['type'] = $meter_type;
        $_POST['meter-number'] = $meter_number;

		include_once("func/electric.php");
        $json_response_decode = json_decode($json_response_encode,true);
        if($json_response_decode["status"] == "success"){
            $_SESSION["meter_amount"] = $amount;
            $_SESSION["meter_number"] = $meter_number;
            $_SESSION["meter_provider"] = $epp;
            $_SESSION["meter_type"] = $type;
            $_SESSION["meter_name"] = $json_response_decode["desc"];
        }

        if($json_response_decode["status"] == "failed"){
            $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        }
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["reset-electric"])){
        unset($_SESSION["meter_amount"]);
        unset($_SESSION["meter_number"]);
        unset($_SESSION["meter_provider"]);
        unset($_SESSION["meter_type"]);
        unset($_SESSION["meter_name"]);
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
                        <a class="nav-link" href="/web/Airtime.php">
                            Airtime
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/web/Data.php">
                            Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/web/Cable.php">
                            Cable TV
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">
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
                <h1 class="h2">Electricity Bill Payment</h1>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="disco" class="form-label">Disco</label>
                                    <select class="form-select" name="disco" id="disco" required>
                                        <option value="">Select Disco</option>
                                        <option value="eko">Eko</option>
                                        <option value="ikeja">Ikeja</option>
                                        <option value="kano">Kano</option>
                                        <option value="ph">Port Harcourt</option>
                                        <option value="jos">Jos</option>
                                        <option value="ibadan">Ibadan</option>
                                        <option value="kaduna">Kaduna</option>
                                        <option value="abuja">Abuja</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="meter-type" class="form-label">Meter Type</label>
                                    <select class="form-select" name="meter-type" id="meter-type" required>
                                        <option value="">Select Meter Type</option>
                                        <option value="prepaid">Prepaid</option>
                                        <option value="postpaid">Postpaid</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="meter-number" class="form-label">Meter Number</label>
                                    <input type="text" name="meter-number" class="form-control" id="meter-number" placeholder="Meter Number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" name="amount" class="form-control" id="amount" placeholder="Amount" required>
                                </div>
                                <button type="submit" name="buy_electric" class="btn btn-primary">Pay Bill</button>
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
