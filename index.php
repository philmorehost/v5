<?php
  include("func/bc-connect.php");
    
  //Select vendor_2 Table
	$select_vendor_2_table = mysqli_fetch_array(mysqli_query($connection_server, "SELECT * FROM sas_vendors WHERE website_url='".$_SERVER["HTTP_HOST"]."' LIMIT 1"));
	if(($select_vendor_2_table == true) && ($select_vendor_2_table["website_url"] == $_SERVER["HTTP_HOST"]) && ($select_vendor_2_table["status"] == 1)){
        $vendor_2_account_details = $select_vendor_2_table;
    }else{
        $vendor_2_account_details = "";
    }
    
  include("index-modern.php");
?>