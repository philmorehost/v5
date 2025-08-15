<?php session_start([
	'cookie_lifetime' => 286400,
	'gc_maxlifetime' => 286400,
]);
include("../func/bc-config.php");
include("../func/modern-header.php");

<?php
    if(isset($_POST["buy_cable"])){
        $purchase_method = "web";
        $action_function = 1;
        $cable_provider = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["cable-provider"])));
        $smart_card_number = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["smart-card-number"])));
        $cable_plan = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["cable-plan"])));

        $_POST['isp'] = $cable_provider;
        $_POST['iuc-number'] = $smart_card_number;
        $_POST['quantity'] = $cable_plan;

		include_once("func/cable.php");
        $json_response_decode = json_decode($json_response_encode,true);
        $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        unset($_SESSION["iuc_number"]);
        unset($_SESSION["cable_provider"]);
        unset($_SESSION["cable_package"]);
        unset($_SESSION["cable_name"]);
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["verify-cable"])){
        $purchase_method = "web";
        $action_function = 3;
        $cable_provider = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["cable-provider"])));
        $smart_card_number = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["smart-card-number"])));
        $cable_plan = mysqli_real_escape_string($connection_server, trim(strip_tags($_POST["cable-plan"])));

        $_POST['isp'] = $cable_provider;
        $_POST['iuc-number'] = $smart_card_number;
        $_POST['quantity'] = $cable_plan;

		include_once("func/cable.php");
        $json_response_decode = json_decode($json_response_encode,true);
        if($json_response_decode["status"] == "success"){
            $_SESSION["iuc_number"] = $iuc_no;
            $_SESSION["cable_provider"] = $isp;
            $_SESSION["cable_package"] = $quantity;
            $_SESSION["cable_name"] = $json_response_decode["desc"];
        }

        if($json_response_decode["status"] == "failed"){
            $_SESSION["product_purchase_response"] = $json_response_decode["desc"];
        }
        header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    if(isset($_POST["reset-cable"])){
        unset($_SESSION["iuc_number"]);
        unset($_SESSION["cable_provider"]);
        unset($_SESSION["cable_package"]);
        unset($_SESSION["cable_name"]);
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
                        <a class="nav-link active" aria-current="page" href="#">
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
                <h1 class="h2">Cable TV Subscription</h1>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="cable-provider" class="form-label">Cable Provider</label>
                                    <select class="form-select" name="cable-provider" id="cable-provider" required>
                                        <option value="">Select Provider</option>
                                        <option value="dstv">DSTV</option>
                                        <option value="gotv">GOTV</option>
                                        <option value="startimes">Startimes</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="smart-card-number" class="form-label">Smart Card Number</label>
                                    <input type="text" name="smart-card-number" class="form-control" id="smart-card-number" placeholder="Smart Card Number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cable-plan" class="form-label">Cable Plan</label>
                                    <select class="form-select" name="cable-plan" id="cable-plan" required>
                                        <option value="">Select Plan</option>
                                    </select>
                                </div>
                                <button type="submit" name="buy_cable" class="btn btn-primary">Subscribe</button>
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
