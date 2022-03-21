 <!doctype html>
<html>
<head>
 	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>
<body>

	<div class="container" ng-controller="moduleAppController" >
	
		<div class="row mt-4">
			<div class="mx-auto">
				<img src="/public/img/doggy.jpg" class="mx-auto" style="width: 100%; align-content: center;">
			</div>
		</div>

	<!--forecast vs actual Sales Table  -->
		<br>
		<section class="mb-2">
			<div class="card rounded shadow p-3">
			<div class="row mt-2">
			  <div class="col">

					<h4> Weekly and Accumulated sales </h4>
					<br>
			  <select class="form-control" ng-model="selectedWeek_sales" id="dropdownWeek" ng-change = "selected_sales()">
						<option value="">Choose Week</option>
						<option value="1">Week 1  </option>
						<option value="2">Week 2  </option>
						<option value="3">Week 3  </option>
						<option value="4">Week 4  </option>
						<option value="5">Week 5  </option>
						<option value="6">Week 6  </option>
						<option value="7">Week 7  </option>
						<option value="8">Week 8  </option>
						<option value="9">Week 9  </option>
						<option value="10">Week 10</option>
					</select>
				</br>


	<!--		<div class="col" ng-show="showSales">-->
					<table class="table responsive" id = "actualTable" ng-show="showSales" style="overflow-x: scroll; overflow-y: hidden;">
						<thead style="background-color: aliceblue">
						<tr>
							<th style="text-align: center;">Product Line</th>
							<th style="text-align: center;">Forecast</th>
							<th style="text-align: center;">Actual Sales</th>
							<th style="text-align: center;">Actual diff</th>
							<th style="text-align: center;">Accum Forecast</th>
							<th style="text-align: center;">Accum Actual</th>
							<th style="text-align: center;">Accum Diff</th>
						</tr>
						</thead>
						<tbody ng-repeat = "sales in accum_data | filter:{week:selectedWeek_sales} ">
						<tr>
							<td style="text-align: center;  padding-right: 2%;">{{sales.product_line}}</td>
							<td style="text-align: right; padding-right: 2%;">{{sales.sales_forecast | number:2}} </td>
							<td style="text-align: right; padding-right: 2%;">{{sales.actual_sales | number:2}} </td>


							<td style="text-align: right; padding-right: 2%; color:#e50000;" ng-show="sales.actual_diff < 0">{{sales.actual_diff | number:2}}</td>
							<td style="text-align: right; padding-right: 2%; color: #2DB734;" ng-show="sales.actual_diff >= 0" >{{sales.actual_diff | number:2}}</td>
							<td style="text-align: right; padding-right: 5%;">{{sales.accum_forecast | number:2}} </td>
							<td style="text-align: right; padding-right: 2%;">{{sales.accum_sales | number:2}}</td>
							<td style="text-align: right; padding-right: 2%; color:#e50000;" ng-show="sales.accum_sales - sales.accum_forecast< 0">{{sales.accum_sales - sales.accum_forecast | number:2}}</td>
							<td style="text-align: right; padding-right: 2%; color: #2DB734;" ng-show="sales.accum_sales - sales.accum_forecast>= 0" >{{sales.accum_sales - sales.accum_forecast | number:2}}</td>

						</tr>
						</tbody>
					</table>	
			</div>
			</div>
			</div>
		</section>	
	<!--	Actual GP GPM  Table-->
		<br>
		<section class="mb-2">
			<div class="card rounded shadow p-3 ">
			<div class="row mt-2">
			  <div class="col">
					<h4> Weekly and Accumulated GP </h4>
					<br>

					<select class="form-control" ng-model="selectedWeek_margin" id="dropdownWeek" ng-change = "selected_margin()">
						<option value="">Choose Week</option>
						<option value="1">Week 1  </option>
						<option value="2">Week 2  </option>
						<option value="3">Week 3  </option>
						<option value="4">Week 4  </option>
						<option value="5">Week 5  </option>
						<option value="6">Week 6  </option>
						<option value="7">Week 7  </option>
						<option value="8">Week 8  </option>
						<option value="9">Week 9  </option>
						<option value="10">Week 10</option>
					</select>
					</br>

					<table class="table" ng-show="showMargin" style="overflow-x: scroll; overflow-y: hidden;">
						<thead style="background-color: aliceblue">
						<tr>
							<th style="text-align: center;">Product Line</th>
							<th style="text-align: center;">Actual GP</th>
							<th style="text-align: center;">Accum Actual GP</th>
							<th style="text-align: center;">Actual GPM</th>
							<th style="text-align: center;">Accum Actual GPM</th>
	<!--					<th style="text-align: center;">Percent ACtual GP</th>-->
						</tr>
						</thead>
						<tbody ng-repeat = "sales in accum_data | filter:{week:selectedWeek_margin} ">
						<tr>
							<td style="text-align: center;">{{sales.product_line}}</td>
							<td style="text-align: right; padding-right: 5%">{{sales.actual_gp | number:2}} </td>
							<td style="text-align: right; padding-right: 8%">{{sales.accum_actual_gp | number:2}} </td>
							<td style="text-align: center;">{{sales.actual_gpm | number:2}} %</td>
							<td style="text-align: center;">{{sales.accum_actual_gp *100 / sales.accum_sales  | number:2}} %</td>
	<!--					<td style="text-align: right;">{{sales.percent_actual_gp}}</td>-->

						</tr>
						</tbody>
					</table>
					</div>
			</div>
			</div>
		</section>
	<!--Cumulative forecast vs actual Sales LineChart  By Week-->
				<br>
<!--				<div class="row mt-4">-->
						<section>
<!--					<section class="col-md-6 col-xs-12 col-sm-12">-->
							<div class="card rounded shadow p-2 pb-3">
								<h4 class="card-title mt-2 ml-3 ">Cumulative Forecast Sales Vs Actual Sales</h4>
								<h5 class="card-subtitle  ml-3 text-muted">By Week</h5>
								<div id="cumulativeLineChart"  
									 style="height:500px; width: 100%; display: flex; padding-bottom: 5px; overflow-x: scroll; overflow-y: hidden;"></div>
							</div>	
				
					</section>
	<br>

	<!--		%GP Bar chart 		-->
				<section>
<!--				<section class="col-md-6 col-xs-12 col-sm-12">-->
							<div class="card rounded shadow p-2 pb-3">
								<h4 class="card-title mt-2 ml-3">Cumulative Forecast GP Vs Actual GP</h4>
								<h5 class="card-subtitle  ml-3 text-muted">By Product Line</h5>
								<div id="gpChart" 
									 style="height:500px; width: 100%; display: flex; overflow-x: scroll; overflow-y: hidden;"> </div>
							</div>
				</section>
				
<!--				</div>-->
				
<!--	fa sales n margin line -->
	
			 <hr class="rounded">

				<section class="mb-2">
				<div class="card shadow p-5 my-3 ">
					<h4>Cumulative Actual vs Forecast Sales & Margin </h4>
					<h5 class="card-subtitle text-muted">By Product Line</h5>
<!--					<p>ยังไม่เสร็จนะพี่เพชร สมมติว่ายังไม่เห็นไปก่อน</p>-->
						<br>
					<select class="form-control" ng-model="selected_ByLine" id="dropdownWeek" ng-change = "render_faSalesTotal()">
						<option value="">Choose Line</option>
						<option value="1">Line 1  </option>
						<option value="2">Line 2  </option>
						<option value="3">Line 3  </option>
						<option value="4">Line 4  </option>
						<option value="5">Line 5  </option>
						<option value="6">Line 6  </option>
						<option value="7">Line 7  </option>
						<option value="8">Line 8  </option>
						<option value="9">Line 9  </option>
						<option value="0">Line 10</option>
					</select>
						</br>
					

					<div class="row">
						<div class="col-md-6 col-12">
							<div id="fa_sales_total" ></div>
						</div>
						<div class="col-md-6 col-12">
							<div id="fa_margin_total" ></div>
						</div>
					</div>
				</div>
				</section>
					
	

	</div>
</body>
</html>


<!--///////////////////////////////////////////////////// script อยู่นี้ /////////////////////////////////////////////////////////-->


<script>
	app.controller('moduleAppController', function($scope, $http, $compile) {
    $scope.accum_data = <?php echo $this->forecast_vs_actual ?>;
	
	$scope.fa_sales_total = <?php echo $this->fa_sales_total ?>;
	$scope.fa_margin_total = <?php echo $this->fa_margin_total ?>;
	
	$scope.selectedWeek_margin = '';
	$scope.selectedWeek_sales = '';
	$scope.selected_ByLine = '';
		
	$scope.showAfterSubmit = false;	
		
	google.charts.load('current', {
        'packages': ['corechart','bar']
    });
		
		
		google.charts.setOnLoadCallback(cumulativeSalesLineChart);
		google.charts.setOnLoadCallback(gpChart);
//		google.charts.setOnLoadCallback(drawFaSalesTotal);
//		google.charts.setOnLoadCallback(drawFaMarginTotal);
		
//		*************** Forecast vs Sales Line chart*********************
		$scope.data_array1 = [];
		
		function cumulativeSalesLineChart(){
			for(let each of $scope.accum_data){
					$scope.data_array1.push([parseInt(each['week']), parseInt(each['accum_sales_company']), parseInt(each['accum_forecast_company'])
											]);
				}
			var data = new google.visualization.DataTable();
				data.addColumn('number','Week');
				data.addColumn('number','Actual Sales');
				data.addColumn('number','Forecast');
//				data.addColumn({type: 'number', role: 'tooltip'}); 
				data.addRows($scope.data_array1);

				var options = { title: 'Cumulative Forecast Vs Actual Sales',
//							   legend: { position: 'bottom' },
							   colors:['#39b0db','#ff616b'],
							    hAxis: {
									  title: 'Week'
									}			
							  };

			
			var table = new google.visualization.LineChart(document.getElementById('cumulativeLineChart'));
			
			table.draw(data,options)
	}
		
		
//		******************GP GPM Bar Chart*****************
		function gpChart(){
			let data_array2 = [
				['Line','% Actual GP']
			];
			
			
			for(let each of $scope.accum_data){
				data_array2.push([each['product_line'], parseInt(each['percent_actual_gp'])]);
				data_array2 = data_array2.slice(0,11);
			}
			
			var data2 = google.visualization.arrayToDataTable(data_array2);
			 	
		
			
			var options = { title: 'Cumulative Forecast Vs Actual GP',
							bar: {groupWidth: "25%"},
							legend: { position: "none" },
//						    axes: {
//									  y: {
//									  0: { label: 'Product Line'} 
//									 }
//								   },
						    hAxis: {
									  title: 'Percentage'
									},
						   vAxis: { title: 'Product Line'},

						    colors:['#7e83e0']

		
						  };

			
			var tablegp = new google.visualization.BarChart(document.getElementById("gpChart"));
			
			tablegp.draw(data2,options);	
		}
		
		
		// By Line
		
		$scope.render_faSalesTotal = function(){
			
			let filtered_data = ($scope.fa_sales_total).filter(function(obj) {return obj.product_line == $scope.selected_ByLine});
            
            var data3 = google.visualization.arrayToDataTable([
			['Xaxis','Total Sales',{ role: "style" }],
			['Actual Sales',parseInt(filtered_data[0]['actual_sales']),'#50CB93'],
			['Forecast Sales',parseInt(filtered_data[0]['forecast_sales']),'#54436B']
			
		     ]);
		
			var options3 = {
							title: "Sales",
							titleTextStyle: {
								fontSize: 18,
								color:'#828282',
								bold: true,
								italic: false
							},
							bar: {groupWidth: "60%"},
							legend: { position: "none" },
							colors:['#50CB93', '#54436B']
						  };

            var chart = new google.visualization.ColumnChart(document.getElementById('fa_sales_total'));
            chart.draw(data3,options3);
            
            let filtered_margin = ($scope.fa_margin_total).filter(function(obj) {return obj.product_line == $scope.selected_ByLine});
            
            var data4 = google.visualization.arrayToDataTable([
			['Xaxis','Total Sales',{ role: "style" }],
			['Actual Margin',parseInt(filtered_margin[0]['actual_margin']),'#ED8E7C'],
			['Forecast Margin',parseInt(filtered_margin[0]['forecast_margin']),'#A03C78']
			
		     ]);
		
			var options4 = {
							title: "Margin",
							titleTextStyle: {
								fontSize: 18,
								color:'#828282',
								bold: true,
								italic: false
							},
							bar: {groupWidth: "60%"},
							legend: { position: "none" },
							colors:['#50CB93', '#54436B']
						  };

            var chart = new google.visualization.ColumnChart(document.getElementById('fa_margin_total'));
            chart.draw(data4,options4);

			
		}

//		function drawFaSalesTotal(){
//		var data = google.visualization.arrayToDataTable([
//			['Xaxis','Total Sales',{ role: "style" }],
//			['Actual Sales',parseInt($scope.fa_sales_total[0]['actual_sales']),'#50CB93'],
//			['Forecast Sales',parseInt($scope.fa_sales_total[0]['forecast_sales']),'#54436B']
//			
//		]);
//
//		 var view = new google.visualization.DataView(data);
//		 view.setColumns([0, 1,
//						   { calc: "stringify",
//							 sourceColumn: 1,
//							 type: "string",
//							 role: "annotation" },
//						   2]);
//		
//		 var options = {
//			title: "Sales",
//			titleTextStyle: {
//				fontSize: 18,
//				color:'#828282',
//				bold: true,
//				italic: false
//			},
//			bar: {groupWidth: "60%"},
//			legend: { position: "none" }
//		  };
//		 var chart = new google.visualization.ColumnChart(document.getElementById("fa_sales_total"));
//		 chart.draw(view, options);
//	}
	
//		function drawFaMarginTotal(){
//			var data = google.visualization.arrayToDataTable([
//				['Xaxis','Total Margin',{ role: "style" }],
//				['Actual Margin',parseInt($scope.fa_margin_total[0]['actual_margin']),'#ED8E7C'],
//				['Forecast Margin',parseInt($scope.fa_margin_total[0]['forecast_margin']),'#A03C78']
//
//			]);
//
//			 var view = new google.visualization.DataView(data);
//			 view.setColumns([0, 1,
//							   { calc: "stringify",
//								 sourceColumn: 1,
//								 type: "string",
//								 role: "annotation" },
//							   2]);
//
//			 var options = {
//				title: "Margin",
//				titleTextStyle: {
//					fontSize: 18,
//					color:'#828282',
//					bold: true,
//					italic: false
//				},
//				bar: {groupWidth: "60%"},
//				legend: { position: "none" }
//			  };
//			 var chart = new google.visualization.ColumnChart(document.getElementById("fa_margin_total"));
//			 chart.draw(view, options);
//		}
		
		
		addModal('formValidate', 'ฮั่นแน่', 'เลือก Week ก่อนสิเจ้าคะ!');
		addModal('formValidate1', 'ฮั่นแน่', 'เลือก Line ก่อนสิเจ้าคะ!');
		
		$scope.selected_sales=function(){
			if ($scope.selectedWeek_sales ===''){
				 	$('#formValidate').modal('toggle');
					$scope.showSales = false;
			}
			else {
				$scope.showSales = true;
			}
		}
		$scope.selected_margin=function(){
			if ($scope.selectedWeek_margin ===''){
				 	$('#formValidate').modal('toggle');
					$scope.showMargin = false;
			}
			else {
				$scope.showMargin = true;
			}
		}
		$scope.selected_margin=function(){
			if ($scope.selectedWeek_margin ===''){
				 	$('#formValidate').modal('toggle');
					$scope.showMargin = false;
			}
			else {
				$scope.showMargin = true;
			}
		}
		$scope.selectedLine =function(){
			if ($scope.selected_ByLine ===''){
				 	$('#formValidate1').modal('toggle');
					$scope.showLineDetail = false;
			}
			else {
				$scope.showLineDetail = true;
			}
		}
		
});	
</script>

<!--///////////////////////////////////////////////////////////จบแล้ว////////////////////////////////////////////////////////////-->
<!--	
แอบเก็บไว้ดูคนเดียวจ้า อย่ามองพ้มอายอย่ามองพ้มอาย
		$scope.selected_sales = function(){
			if($scope.selectedWeek_sales !=''){
				$scope.showSales = true;
			}	
		}
		
		$scope.selected_margin = function(){
			if($scope.selectedWeek_margin !=''){
				$scope.showMargin = true;
			}	
		}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		
		$scope.render_data = function(){
			$scope.data_array1 = [];
			$scope.filtered_data = ($scope.accum_data).filter(function(obj) {return obj.week == $scope.selectedWeek});
			
			for(let each of $scope.filtered_data){
				$scope.data_array1.push([parseInt(each['week']), parseInt(each['actual_sales']), parseInt(each['sales_forecast'])]);
			}
			
			
			
			var data = new google.visualization.DataTable();
			data.addColumn('number','Week');
			data.addColumn('number','Actual Sales');
			data.addColumn('number','Forecast');
			data.addRows($scope.data_array1);
			
			var options = { chart: {
							title: ''},
							width: 900,
							height: 500
						  };

			
			var table = new google.visualization.LineChart(document.getElementById('cumulativeLineChart'));
			
			table.draw(data,options)
		}
		
-->


