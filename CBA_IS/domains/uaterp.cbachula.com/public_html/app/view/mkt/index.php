<!DOCTYPE html>
<html>

<body>

    <div class="row row-cols-2 row-cols-md-5 mt-2" style="padding: 0;" id="mktRow">
		<?php 
$tz = 'Asia/Bangkok';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz)); 
$dt=$dt->setTimestamp($timestamp); 
$dt=$dt -> format('Hi');
		
    echo "<script>addModuleLink('mktRow', '/mkt/sales_order', 'shopping-bag', 'Sales Order (SO)');</script>";
    echo "<script>addModuleLink('mktRow', '/home/add_customer', 'user-plus', 'Add Customer');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/purchase_order', 'shopping-cart', 'Purchase Order (PO)');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/confirm_install', 'truck', 'Confirm Install (CI)');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/request_counter_sales', 'map-o', 'Request Counter Sales (RCS)');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/commart', 'desktop', 'CBA x COMMART');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/dashboard', 'folder-open-o', 'Dashboard');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/t_price', 'truck', 'คำนวณค่าส่ง');</script>";
	echo "<script>addModuleLink('mktRow', 'https://docs.google.com/spreadsheets/d/1-lnIdt3ccJ7f6eBWI56mnuVq-yX1xU95VDu-eIJkSJI/edit?usp=sharing', 'truck', 'Supplier Delivery Time Slot');</script>";
		
	echo "<script>addModuleLink('mktRow', '/mkt/reimbursement_request', 'desktop', 'ใบขอเบิกค่าใช้จ่าย');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/sales_and_margin', 'bar-chart', 'Actual vs Forecast Sales/Margin');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/sp_tracking', 'table', 'SP Tracking');</script>";
	echo "<script>addModuleLink('mktRow', '/is/thelastday', 'heart', 'The Last Day');</script>";
	echo "<script>addModuleLink('mktRow', '/mkt/Cancel_Sox', 'shopping-bag', 'Cancel Sox');</script>";
	
		
		
?>
        
        
        
        
        
        
    </div>
    
</body>

</html>

<style>
</style>

<script>
</script>