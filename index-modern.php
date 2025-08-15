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
<?php
    include("func/modern-header.php");
?>

<div class="container">
    <h1>Welcome to the new and improved VTU vending script!</h1>
    <p>This is a modern design using Bootstrap 5.</p>
</div>

<?php
    include("func/modern-footer.php");
?>
