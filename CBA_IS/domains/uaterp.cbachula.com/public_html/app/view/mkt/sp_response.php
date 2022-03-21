<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SP Response</title>
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
	
	<script src=https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js></script>
	<script src=https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js></script>
	<script src=https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/dataTables.bootstrap4.min.js></script>

</head>

<body>
	<!--	Cumulative forecast vs actuak Sales Table  -->
	
			<div class="container" ng-controller="moduleAppController" >
			<section class="my-5">
				<h2>SP Engagements By Week</h2>
				<div class="card shadow px-5 py-3 mt-2 mb-5">
					<div class="row" style="padding-left: 15%;" >
						<div class="col-12">
							<div id="barchart_values"></div>
						</div>
					</div>
				</div>
			</section>
<!--
				<div class="col-12">
				<h1 class="display-6">SP Engagements By Week</h1>
				<div id="barchart_values" ></div>
-->
				<section class="my-5">
				<h2>SP Engagements By Day</h2>
				<div class="card shadow px-5 py-3 mt-2 mb-5 ">
					<div class="row">
						<div class="col-12">
							<select class="form-control" ng-model="selectedWeek" id="dropdownWeek" ng-change="testDraw()">
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
						</div>						
					</div>
					<div class="row" style="padding-left: 18%; padding-top: 1%;">
						<div class="col-12">
							<div id="barchart_values2"></div>
						</div>
						
					</div>
				</div>
			</section>
				
				<section >
				<h2 class="text-center">Individual SP Tracking</h2>
				<h5 class="pl-4">Click the table header to sort</h5>
				<div class="col-12">
				<div class="container table-container card shadow px-5 py-3 mt-2 mb-5">
					<select class="form-control" ng-model="selectedLine" id="dropdownWeek" 
								ng-change="selected()">
							<option value="">Choose Line</option>
							<option value="CE1">Line 1  </option>
							<option value="CE2">Line 2  </option>
							<option value="CE3">Line 3  </option>
							<option value="CE4">Line 4  </option>
							<option value="CE5">Line 5  </option>
							<option value="CE6">Line 6  </option>
							<option value="CE7">Line 7  </option>
							<option value="CE8">Line 8  </option>
							<option value="CE9">Line 9  </option>
							<option value="CE0">Line 10</option>
					</select>
					<div class="mx-0 mt-2 firstTable table-responsive" ng-show="showDetail" >
						<table class="table table-hover my-1"  id="sortsiaisus" >
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

							<tr ng-repeat="sp in sp_data | filter:{ce_id : selectedLine}" >

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
				
		</div>
<!--
				<h1 class="display-6">SP Engagements By Day</h1>
				<select class="form-control" ng-model="selectedWeek" id="dropdownWeek" ng-change="testDraw()">
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
				<label for="buttonConfirmSubmit" style="color:white;">.</label>
					<button type="button" class="btn btn-default btn-block" id="buttonConfirmSubmit" ng-click="confirmSubmit()">Submit</button>
				<div id="barchart_values2" ></div>
-->
			
				

				
<!--
				<h4>Sales Contributing SP</h4>
			
				<select class="form-control" ng-model="selectedWeek" id="dropdownWeek" value="{{week}}">
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

				<table class="table">
					<thead>
					<tr>
						<th>Accum Point Range</th>
						<th>Monday</th>
						<th>Tuesday</th>
						<th>Wednesday</th>
						<th>Thursday</th>
						<th>Friday</th>
						<th>Saturday</th>
					</tr>
					</thead>
					<tbody ng-repeat = "sp in sp_contributing_data">
					<tr>
						<td>{{sp.sp_range}}</td>
						<td>{{sp.week}}</td>
						<td>{{sp_sales}}</td>
						
					</tr>
					</tbody>
					
				</table>
-->
				
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
    $scope.sp_contributings = <?php echo $this->sp_contributing_data ?>;
	$scope.sp_engagements = <?php echo $this->sp_engagement_data ?>;
	$scope.sp_data =<?php echo $this->sp_data ?>;
	$scope.selectedWeek = '';
	$scope.showDetail = false;
//	$scope.showAfterSubmit = false;
	$scope.a = {"1":{},
		"2":{},
		"3":{},
		"4":{},
		"5":{},
		"6":{},
		"7":{},
		"8":{},
		"9":{},
		"10":{}};
	
	
	$scope.da={};
	
	

	for( let sp_engagement of $scope.sp_engagements) {
		$scope.w = sp_engagement.week;
		if (Object.keys($scope.a[$scope.w]).includes(sp_engagement.typename)){
			$scope.a[$scope.w][sp_engagement.typename] += parseInt(sp_engagement.count_sp);
		} else {
			$scope.a[$scope.w][sp_engagement.typename] = parseInt(sp_engagement.count_sp);
		}
		/*$scope.w = sp_engagement.week;
		$scope.a[$scope.w][sp_engagement.typename] += sp_engagement.count_sp;*/
	}
	
      google.charts.load('current', {'packages':['corechart']});
	
      google.charts.setOnLoadCallback(drawbarChart);
	 
//	$scope.confirmSubmit = function() {
//                $scope.showAfterSubmit = true;
//                $scope.engagement = $scope.sp_engagements.filter(function filterWeek(engagement){return engagement.week == $scope.selectedweek;});
//		google.charts.setOnLoadCallback(testDraw);
//
//            }
      
	
	  function drawbarChart() {
		let data_array = [
            ["Week", "Check Name","Other","Promotion","Sales","Training", { role: 'annotation' }]
        ];
		
		for(let sp in $scope.a){
			data_array.push(
				[sp ,parseInt($scope.a[sp]['Check Name']), 
				 parseInt($scope.a[sp]['Other']),
				 parseInt($scope.a[sp]['Promotion']),
				 parseInt($scope.a[sp]['Sales']),
				 parseInt($scope.a[sp]['Training']),'']
			);
		} 
		  $scope.da=data_array;
		var data = new google.visualization.arrayToDataTable(data_array);
		

      var options = {
        width: 800,
        height: 600,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
		 hAxis: {title: 'Points'},
		vAxis: { title: 'Week'}
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(data, options);
	}
	
	$scope.testDraw = function(){
		$scope.b={};
		
		 let data_array = [
				["Date","Check Name","Other","Promotion","Sales","Training", { role: 'annotation' }]
			];

			for( let sp_engagement of $scope.sp_engagements) {
				if(sp_engagement.week == $scope.selectedWeek){
					let d = sp_engagement.date;
					if (Object.keys($scope.b).includes(d)){
						$scope.b[d][sp_engagement.typename] = sp_engagement.count_sp;
					} else {
						 $scope.b[d]={};
						 $scope.b[d][sp_engagement.typename] = sp_engagement.count_sp;
					}
				}
					
				}
			
			console.log($scope.b);
			console.log($scope.sp_engagements);

			for(let day in $scope.b) {
				data_array.push(
					[day.toString(),
					 parseInt($scope.b[day]['Check Name']), 
					 parseInt($scope.b[day]['Other']),
					 parseInt($scope.b[day]['Promotion']),
					 parseInt($scope.b[day]['Sales']),
					 parseInt($scope.b[day]['Training']),'']
				);
			}
		
			var data = new google.visualization.arrayToDataTable(data_array);


		  var options = {
			width: 700,
			height: 500,
			legend: { position: 'top', maxLines: 3 },
			bar: { groupWidth: '75%' },
			isStacked: true,
			hAxis: {title: 'SP'},
			vAxis: { title: 'Day'}
		  };
		  var chart = new google.visualization.BarChart(document.getElementById("barchart_values2"));
		  chart.draw(data, options);
	}

//		$scope.render_data= function() {
//			let data_array = [
//				["Date","Check Name","Other","Promotion","Sales","Training", { role: 'annotation' }]
//			];
//
//			for( let sp_engagement of $scope.sp_engagements) {
//				if (parseInt(sp_engagement['week']) == parseInt($scope.selectedweek)){
//					let d = sp_engagement.date;
//					if (Object.keys($scope.b).includes(d)){
//						$scope.b[d][sp_engagement.typename] = sp_engagement.count_sp;
//					} else {
//						 $scope.b[d]={};
//						 $scope.b[d][sp_engagement.typename] = sp_engagement.count_sp;
//					}
//				}
//			}
//
//			for(let day in $scope.b) {
//				data_array.push(
//					[day.toDateString(),
//					 parseInt($scope.b[day]['Check Name']), 
//					 parseInt($scope.b[day]['Other']),
//					 parseInt($scope.b[day]['Promotion']),
//					 parseInt($scope.b[day]['Sales']),
//					 parseInt($scope.b[day]['Training']),'']
//				)};
//
//
//
//
//			var data = new google.visualization.arrayToDataTable(data_array);
//
//
//		  var options = {
//			width: 600,
//			height: 400,
//			legend: { position: 'top', maxLines: 3 },
//			bar: { groupWidth: '75%' },
//			isStacked: true
//		  };
//		  var chart = new google.visualization.BarChart(document.getElementById("barchart_values2"));
//		  chart.draw(data, options);
//	}
//function by_day() {
//	let data_array =[
//		['Date','Check Name','Other','Promotion','Sales','Training',{role:'annotation'}]
//		];
//	for(let sp of $scope.sp_engagements) {
//		data_array.push
//	}
//}
		addModal('formValidate', 'ฮั่นแน่', 'เลือก Week ก่อนสิคะ!');	
		$scope.selected =function(){
			if ($scope.selectedLine ===''){
				 	$('#formValidate').modal('toggle');
					$scope.showDetail = false;
			}
			else {
				$scope.showDetail = true;
			}
		}
});

</script>