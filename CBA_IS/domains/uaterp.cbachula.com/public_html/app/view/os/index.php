<!DOCTYPE html>
<html>

<body>

    <div class="row row-cols-2 row-cols-md-5 mt-2" style="padding: 0;" id="osRow">
		<?php 
$tz = 'Asia/Bangkok';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz)); 
$dt=$dt->setTimestamp($timestamp); 
$dt=$dt -> format('Hi');
		
    echo "<script>addModuleLink('osRow', '/os/sales_order', 'shopping-bag', 'Sales Order (SO)');</script>";
    echo "<script>addModuleLink('osRow', '/home/add_customer', 'user-plus', 'Add Customer');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/purchase_order', 'shopping-cart', 'Purchase Order (PO)');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/confirm_install', 'truck', 'Confirm Install (CI)');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/request_counter_sales', 'map-o', 'Request Counter Sales (RCS)');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/commart', 'desktop', 'CBA x COMMART');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/dashboard', 'folder-open-o', 'Dashboard');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/t_price', 'truck', 'คำนวณค่าส่ง');</script>";
	echo "<script>addModuleLink('osRow', 'https://docs.google.com/spreadsheets/d/1-lnIdt3ccJ7f6eBWI56mnuVq-yX1xU95VDu-eIJkSJI/edit?usp=sharing', 'truck', 'Supplier Delivery Time Slot');</script>";
		
	echo "<script>addModuleLink('osRow', '/mkt/reimbursement_request', 'desktop', 'ใบขอเบิกค่าใช้จ่าย');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/sales_and_margin', 'bar-chart', 'Actual vs Forecast Sales/Margin');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/sp_tracking', 'table', 'SP Tracking');</script>";
	echo "<script>addModuleLink('osRow', '/is/thelastday', 'heart', 'The Last Day');</script>";
	echo "<script>addModuleLink('osRow', '/mkt/Cancel_Sox', 'shopping-bag', 'Cancel Sox');</script>";
	
		
		
?>
        
        
        
        
        
        
    </div>
    
</body>

</html>

<style>
</style>

<script>
</script>