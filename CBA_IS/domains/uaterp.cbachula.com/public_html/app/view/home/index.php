<!DOCTYPE html>
<html>
    <style>
        /*body {
            background:#7FF38D
        }*/
    </style>
	<script src="https://use.fontawesome.com/releases/v4.2/js/all.js"></script>
<body>

    <div class="container-fluid px-0" ng-controller="homeAppController">
        
        <div class="container-fluid" style="background-image:url('/public/img/cbs-background-blue.png'); background-size:cover; background-position:center; height:auto; width=100%; text-align:center;">
            <br>
            <h4 class="my-2" style="color:white;">สวัสดี, {{employeeDetail.employee_nickname_thai}}</h4>
<!--			<h5 class="my-2" style="color:white;"><i class="fa fa-star" aria-hidden="true"></i> {{employeeDetail.point}} Points</h5>-->
            <h5 class="my-2" style="color:white;"><i class="fa fa-star" aria-hidden="true"></i> สู้ๆ นะค้าบบบ จาก IS</h5>
            <!--<h4 class="mt-3" style="color:white;">สู้ๆ นะค้าบบบ จาก IS ^^</h4>
            <h4 class="mt-3" style="color:white;" ng-show="employeeDetail.product_line == '5'">วันนี้ 4 เครื่อง จากแก้มมมมมมมมม</h4>
            <h4 class="mt-3" style="color:white;" ng-show="employeeDetail.product_line == '6'">32000 to go!!! จาก อาล้อมอ้อมอ้อมม</h4>-->
            <br>
        </div>
<!--
        <div class="container-fluid p-0" style="width: 100%;height: auto;background-image:url('/public/img/https://www.thairath.co.th/media/mSQWlZdCq5b6ZLkrgJpz8Q9G2Ah7nXjf.jpg'); background-size:cover; background-position:center;">
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="background-color:#4DDF50;">
				<ol class="carousel-indicators">
					<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
					<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
					<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
				</ol>
				<div class="carousel-inner">

					<div class="carousel-item active">
						<div class="row carousel-item-row justify-content-md-center banner" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('/public/img/green1.jpg');height:500px;">
							<div class="col-7 text-center"><a href="" target="_blank"><img src="/public/img/green1.jpg" style="height:500px; width:auto;"></a></div>
						</div>
					</div>
					<div class="carousel-item">
						<div class="row carousel-item-row justify-content-md-center banner" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('/public/img/green2.jpg');height:500px;">
							<div class="col-7 text-center"><a href="" target="_blank"><img src="/public/img/green2.jpg" style="height:500px; width:auto;;"></a></div>
						</div>
					</div>
                    <div class="carousel-item">
						<div class="row carousel-item-row justify-content-md-center banner" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('/public/img/green3.jpg');height:500px; ">
							<div class="col-7 text-center"><a href="" target="_blank"><img src="/public/img/green3.jpg" style="height:500px; width:auto;;"></a></div>
						</div>
					</div>
				</div>
				<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
		</div>
-->
		<div class="container">
			<div class = 'row'>
				<div class="col-sm-12 p-0 "><div class="m-2 card">				
					<div class="card-body">
						<h5>CBA สู้ ๆ CBA สู้ตาย CBA ไว้ลายสู้ตายสู้ ๆ ทรงพระเจริญจ้า <i class="fa fa-heart"></i><i class="fa fa-angellist"></i></h5>
						
						
						<div class="progress">
							
							<div class="progress-bar" role="progressbar" style="width: <?php echo number_format($this->sales[0]['sales']/40000000*100,2,".",","); ?>%" aria-valuenow="<?php echo number_format($this->sales[0]['sales']/40000000*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						
						<h5>Current sales: <?php echo number_format($this->sales[0]['sales'],2,".",",") ?> <i class="fa fa-arrow-right"></i> <?php echo number_format(40000000.00 - $this->sales[0]['sales'],2,".",",") ?> to go!</h5>
					</div>
				</div></div>
			</div>
		</div>
		
        <br>
        <div class="container">
			
            <?php 
                if ($this->getPosition=='gm' || $this->getPosition=='smd' || $this->getPosition=='se' || $this->getPosition=='is'|| $this->getPosition=='fin' || $this->getPosition=='spj') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">General Manager & Sales and Marketing Director</h4>';
                    echo '<div class="row row-cols-2 row-cols-md-5 mt-2" style="padding: 0;" id="gmRow">';
                    echo "<script>addModuleLink('gmRow', '/mkt/sales_report', 'cloud-download', 'Sales Report');</script>";
                    echo "<script>addModuleLink('gmRow', '/mkt/point_report', 'cloud-download', 'Point Report');</script>";
					echo "<script>addModuleLink('gmRow', '/mkt/actualsales_margin', 'money', 'Sales and Margin');</script>";
					echo "<script>addModuleLink('gmRow', '/mkt/sp_response', 'child', 'SP Engagements');</script>";
					echo "<script>addModuleLink('gmRow', '/mkt/sp_tracking', 'table', 'SP Tracking');</script>";
					//echo "<script>addModuleLink('gmRow', '/is/thelastday', 'heart', 'The Last Day');</script>";
                    
                    echo '</div>';
                } if ($this->getPosition=='hr' || $this->getPosition=='ibc'){ 
					echo '<div class="row row-cols-2 row-cols-md-5 mt-2" style="padding: 0;" id="gmRow">';
                    echo "<script>addModuleLink('gmRow', '/mkt/point_report', 'cloud-download', 'Point Report');</script>";
					echo "<script>addModuleLink('gmRow', '/mkt/sp_response', 'child', 'SP Engagements');</script>";
					//echo "<script>addModuleLink('gmRow', '/is/thelastday', 'heart', 'The Last Day');</script>";
                    
                    echo '</div>';
				}if($this->getPosition=='is') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">Information System</h4>';
                    require 'app/view/is/index.php'; 
                } if($this->getPosition=='cm' || $this->getPosition=='ce' || $this->getPosition=='is') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">Marketing</h4>';
					
                    require 'app/view/mkt/index.php'; 
                    if($this->getPosition=='cm' || $this->getPosition=='is') {
                        echo "<script>addModuleLink('mktRow', '/mkt/sales_report', 'cloud-download', 'Sales Report');</script>";
                        echo "<script>addModuleLink('mktRow', '/mkt/point_report', 'cloud-download', 'Point Report');</script>";
                    }
                    // if($this->getPosition=='ce' || $this->getPosition=='is') {
                    //     echo "<script>addModuleLink('mktRow', '/mkt/xiaomi_report', 'cloud-download', 'Xiaomi Report');</script>";
                    // }
                } if ($this->getPosition=='acc' || $this->getPosition=='is') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">Accounting</h4>';
                    require 'app/view/acc/index.php';
                } if ($this->getPosition=='fin' || $this->getPosition=='is') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">Finance</h4>';
                    require 'app/view/fin/index.php';
                } if ($this->getPosition=='scm' || $this->getPosition=='is') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">Supply Chain Management</h4>';
                    require 'app/view/scm/index.php';
                } if ($this->getPosition=='hr' || $this->getPosition=='is') {
                    if($this->getPosition=='is') echo '<h4 class="my-2">Human Resources</h4>';
                    require 'app/view/hr/index.php';
                } 
				if($this->getPosition=='is') echo '<h4 class="my-2">Everyone</h4>';
				echo '<div class="row row-cols-2 row-cols-md-5 mt-2" style="padding: 0;" id="ERow">';
				echo "<script>addModuleLink('ERow', '/mkt/petty_cash_request', 'heart', 'ขอเบิกเงินรองจ่าย');</script>";
				echo "<script>addModuleLink('ERow', '/mkt/pre_pvd', 'desktop', 'คำร้องขอใบลดหนี้ (PV-D)');</script>";
				echo '</div>';

            ?>
			
			
        </div>
			
    </div>
    		<?php 
			$tz = 'Asia/Bangkok';
			$timestamp = time();
			$dt = new DateTime("now", new DateTimeZone($tz)); 
			$dt=$dt->setTimestamp($timestamp); 
			$dt=$dt -> format('Hi');
			if($this->getPosition=='cm' || $this->getPosition=='ce'){
			if ($dt > "0900" && $dt < "1900") {}
			else{
				echo '<div style="display: flex;justify-content: center;"><img style="width: 1600px;" src="/public/img/petch.png" alt="icon"/></div>';
			}}
			?>
</body>

</html>

<style>
    .itemCol { background-color: azure; border-radius: 10px; }
    .itemCol:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
    .moduleLink { color: #6aa8d9; text-decoration: none; }
    .moduleLink:hover { color: #0959a2; text-decoration: none; }
</style>

<script>
	//location.replace("https://erp.cbachula.com/is/hee");
    app.controller('homeAppController', function($scope, $http, $compile) {
        $http.get("/home/employeeDetail").then(function(response) {
            $scope.employeeDetail = response.data;
            if($scope.employeeDetail.product_line == 'X' || $scope.employeeDetail.position == 'IS') {
                addModuleLink('mktRow', '/mkt/xiaomi_report', 'file-excel-o', 'Xiaomi Report');
            }
        });
    });

</script>