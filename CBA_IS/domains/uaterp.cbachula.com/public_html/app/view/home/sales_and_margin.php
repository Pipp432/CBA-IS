<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>SP Tracking</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous"
	  />
	  <!-- JavaScript Bundle with Popper -->
	<script
		src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
		crossorigin="anonymous"
	  ></script>
	<link
		rel="stylesheet"
		href="extensions/fixed-columns/bootstrap-table-fixed-columns.css"
	  />
	<script src="extensions/fixed-columns/bootstrap-table-fixed-columns.js"></script>
	<link
		rel="stylesheet"
		href="extensions/sticky-header/bootstrap-table-sticky-header.css"
	  />
	<link
		rel="stylesheet"
		href="extensions/fixed-columns/bootstrap-table-fixed-columns.css"
	  />
	<script src="extensions/sticky-header/bootstrap-table-sticky-header.js"></script>
	<script src="extensions/fixed-columns/bootstrap-table-fixed-columns.js"></script>
	<link rel="stylesheet" href="/public/sp_tracking.css">
	
	<link href=https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css rel=stylesheet>
<!--	this one diasbles the sticky header TT-->
	<link href=https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/css/dataTables.bootstrap4.min.css rel=stylesheet>

	

</head>

<body>
	<div class="container mt-5" style="padding-left: 0px; padding-right: 0px;" ng-controller='moduleAppController'>
		<load ng-show="isLoad" class="text-center">
			<section colspan="6">
                <h6 class="my-0"><span class="spinner-border" role="status" aristyle="width:25px; height:25px;"></span> Loading ...</h6>
            </section>
		</load>
		
		<div ng-cloak>
	<!--		table3-->

			<section >
				<h2 class="text-center">Individual SP Tracking</h2>
				<h5 class="pl-4">Click the table header to sort</h5>
				<div class="container table-container card shadow px-5 py-3 mt-2 mb-5">
					<div class="mx-0 mt-2 firstTable table-responsive"  >
						<table class="table table-hover my-1"  id="sortsiaisus">
							<thead>
							<tr>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">CE ID</th>
								<th style="text-align: left; vertical-align: middle;background: #FFFFFF">SP</th>
								<th style="text-align: right; vertical-align: middle;background: #FFFFFF">Total Sales</th>
								<th style="text-align: right; vertical-align: middle;background: #FFFFFF">LP</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">SO Number</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Basic Selling Skill</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Handling OBJ</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Service Mindset</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Week 3 - Fun Quest 1</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Week 3 - Fun Quest 2</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Week 3 - Fun Quest 3</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Week 5-6 - Fun Quest #1</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Week 5-6 - Fun Quest #2</th>
								<th style="text-align: center; vertical-align: middle;background: #FFFFFF">Week 5-6 - Fun Quest #3</th>

							</tr>
							</thead>

							<tbody>

							<tr ng-repeat="sp in sp_data">

								<td style="text-align: center;">{{sp.ce_id}}</td>

								<th style="text-align: left; background: #FFFFFF;">{{sp.employee_id}} : {{sp.employee_nickname_thai}}</th>
								<td style="text-align: right;">{{sp.total_sales | number}}</td>
								<td style="text-align: right;">{{sp.total_point| number}}</td>
								<td style="text-align: center;">{{sp.count_so| number}}</td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Basic Selling Skill') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Basic Selling Skill') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Handling OBJ') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Handling OBJ') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Service Mindset') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Service Mindset') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Week 3 - Fun Quest 1') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Week 3 - Fun Quest 1') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Week 3 - Fun Quest 2') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Week 3 - Fun Quest 2') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Week 3 - Fun Quest 3') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Week 3 - Fun Quest 3') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Week 5-6 - Fun Quest #1') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Week 5-6 - Fun Quest #1') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Week 5-6 - Fun Quest #2') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Week 5-6 - Fun Quest #2') == -1"> </td>

								<td style="text-align: center;" ng-if="sp.remark.indexOf('Week 5-6 - Fun Quest #3') > -1"><i class="fa fa-smile-o" style="color: #2314A3;"></i></td>
								<td style="text-align: center;" ng-if = "sp.remark.indexOf('Week 5-6 - Fun Quest #3') == -1"> </td>

							</tr>
							</tbody>

						</table>
					</div>
			</div>
			</section>
	<!--		table4-->
			<section>
				<h2 class="text-center">SP คนไหนมาขายสายเราบ้างนะ</h2>
				<h5 class="pl-4">Click the table header to sort</h5>
				<div class="card shadow mt-2 mb-5 py-2">
					<div id="top10_table" ></div>
				</div>
			</section>
		</div>
	</div>
	<br>
	<br>
	
</body>
</html>

<script>
	$(document).ready(function(){
		var table = $('#sortsiaisus').DataTable({
		   fixedHeader: true
		});
	});
</script>
<script>
	app.controller('moduleAppController', function($scope, $http, $compile) {
		$scope.sp_data =<?php echo $this->sp_data ?>;
		$scope.top10_data = <?php echo $this->top10_sp_data ?>;

//		$scope.selectedWeek ='';
		let line = '1';
		
		google.charts.load('current', {packages: ['table']});
		google.charts.setOnLoadCallback(drawTop10);
		
		function drawTop10() {
			$scope.data_array = [];
//			$scope.filtered_data = ($scope.top10_data).filter(function(obj) {return obj.week== $scope.selectedWeek});
			for(let each of $scope.top10_data){
				$scope.data_array.push([each['employee_id'], each['employee_nickname_thai'], parseInt(each['total_sales']), parseInt(each['sum_margin']), parseInt(each['count_so']), each['latest_date'],each['latest_date_all']]);
			}
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'SP ID');
			data.addColumn('string', 'SP Name');
			data.addColumn('number', 'Total Sales');
			data.addColumn('number', 'Total Margin');
			data.addColumn('number', 'Number of SO');
			data.addColumn('string', `Last Active in Line ${line}`);
			data.addColumn('string', 'Last Active in CBA');
			data.addRows($scope.data_array);
			
			var table = new google.visualization.Table(document.getElementById('top10_table'));
			
			var cssClassNames = {
				'headerRow': 'header',
				'selectedTableRow': 'selected-background large-font',
				'tableCell': 'font-adjust'
			};

			var options ={
				showRowNumber: true,
				chartArea:{left:0,top:0,width:"100%",height:"100%"},
				height: 500,
				'allowHtml': true, 
				'cssClassNames': cssClassNames
			}
        	table.draw(data, options);
			
		}
	});
	
</script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/dataTables.bootstrap4.min.js></script>


