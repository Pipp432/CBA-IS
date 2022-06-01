<!DOCTYPE html>

<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<scrip src="https://www.google.com/jsapi"></scrip>
	<link rel="stylesheet" href="/public/sales_and_margin.css">
</head>

<body>
    <div class="container" ng-controller="moduleAppController" >
		<load ng-show="isLoad" class="text-center">
			<section colspan="6">
                <h6 class="my-0"><span class="spinner-border" role="status" aristyle="width:25px; height:25px;"></span> Loading ...</h6>
            </section>
		</load>
		
		<div ng-cloak>
		
			<section class="my-5">
				<h2>Cumulative Actual vs Forecast Sales & Margin</h2>
				<div class="card shadow p-5 my-3 ">
					<div class="row">
						<div class="col-md-6 col-12">
							<div id="fa_sales_total"></div>
						</div>
						<div class="col-md-6 col-12">
							<div id="fa_margin_total"></div>
						</div>
					</div>
				</div>
			</section>

			<section class="mb-5">
				<h2>Actual vs Forecast <b>Sales</b> by Week</h2>
				<div class="card shadow p-5 my-3 mb-5" id="fa_sales_weeks" style="height:500px"></div>
				<h2>Cumulative Actual vs Forecast <b>Sales</b> by Category</h2>
				<div class="card shadow p-5 my-3 mb-5" id="fa_sales_cat_all" style="max-height:800px" ></div>
				<h2>Actual vs Forecast <b>Sales</b> ของแต่ละ cat เลือกดูตาม week ได้เลยโลดด</h2>

				<div class="card shadow py-4 px-5 my-3">
					<select  class="btn btn-light btn-md dropdown-toggle col-3"  ng-model="selectedWeekSales"  id="dropdownWeek" ng-change="render_sales()">
						<option value="">Choose Week</option>
		<!--
						<option value="1">Week 1</option>
						<option value="2">Week 2</option>
						<option value="3">Week 3</option>
						<option value="4">Week 4</option>
						<option value="5">Week 5</option>
						<option value="6">Week 6</option>
						<option value="7">Week 7</option>
		-->
						<option value="8">Week 8</option>
						<option value="9">Week 9</option>
						<option value="10">Week 10</option>
					</select>

					<div class="mt-3 mb-3" id="fa_sales_cat"></div>
				</div>
			</section>

			<section class="mb-5">
				<h2>Actual vs Forecast <b>Margin</b> by Week</h2>
				<div class="card shadow p-5 my-3 mb-5" id="fa_margin_weeks" style="height:500px"></div>

				<h2>Cumulative Actual vs Forecast <b>Margin</b> by Category </h2>
				<div class="card shadow p-5 my-3 mb-5" id="fa_margin_cat_all" >
				</div>

				<h2>Actual vs Forecast <b>Margin</b> ของแต่ละ cat เลือกดูตาม week ได้เลยโลดด</h2>

				<div class="card shadow py-4 px-5 my-3">
					<select  class="btn btn-light btn-md dropdown-toggle col-3"  ng-model="selectedWeekMargin"  id="dropdownWeek" ng-change="render_margin()">

						<option value="">Choose Week</option>
		
						<option value="1">Week 1</option>
						<option value="2">Week 2</option>
						<option value="3">Week 3</option>
						<option value="4">Week 4</option>
						<option value="5">Week 5</option>
						<option value="6">Week 6</option>
						<option value="7">Week 7</option>
		
						<option value="8">Week 8</option>
						<option value="9">Week 9</option>
						<option value="10">Week 10</option>
					</select>

				<div class="mt-3 mb-3" id="fa_margin_cat" style="max-height: 800px"></div>
				</div>
			</section>

			<section class="mb-5">
				<h2>Top 10 อะร๊ะ ไม่บอกหรอก</h2>
				<div class="card shadow px-4">
					<div class="row">
						<div class="col-md-6 col-12">
							<div id="top_so" style="height:800px"></div>
						</div>
						<div class="col-md-6 col-12">
							<div id="top_margin" style="height:800px"></div>
						</div>
					</div>
				</div>
			</section>

			<section class="mb-5">
				<h2>Cat ไหนขายดี/ไม่ดี บ้างนะ</h2>
				<div class="card shadow pt-4 pb-1 py-4">
					<div id="stack" style="height: 450px"></div>
				</div>
			</section>
		</div>
    </div>
</body>
<br>
<br>
</html>


<script>
app.controller('moduleAppController', function($scope, $http, $compile) {
	
    $scope.top10_so = <?php echo $this->top10_so_data ?>;
	$scope.top10_margin = <?php echo $this->top10_margin_data ?>;
	
	$scope.fa_sales_total = <?php echo $this->fa_sales_total ?>;
	$scope.fa_margin_total = <?php echo $this->fa_margin_total ?>;
	
	$scope.fa_sales_weeks = <?php echo $this->fa_sales_weeks ?>;
	$scope.fa_margin_weeks = <?php echo $this->fa_margin_weeks ?>;
	
	$scope.fa_sales_cat_all = <?php echo $this->fa_sales_cat_all ?>;
	$scope.fa_margin_cat_all = <?php echo $this->fa_margin_cat_all ?>;
	
	$scope.fa_sales_cat = <?php echo $this->fa_sales_cat ?>;
	$scope.fa_margin_cat = <?php echo $this->fa_margin_cat ?>;
	
	$scope.stack_data = <?php echo $this->stack_data ?>;
	$scope.cat_for_stack = <?php echo $this->cat_for_stack ?>;
	
	$scope.selectedWeekSales ='';
	$scope.selectedWeekMargin ='';
	
    google.charts.load("visualization",'current', {'packages': ['corechart','bar']});
	
    google.charts.setOnLoadCallback(drawTop10So);
	google.charts.setOnLoadCallback(drawTop10Margin);
	
	google.charts.setOnLoadCallback(drawFaSalesTotal);
	google.charts.setOnLoadCallback(drawFaMarginTotal);
	
	google.charts.setOnLoadCallback(drawFaSalesWeeks);
	google.charts.setOnLoadCallback(drawFaMarginWeeks);
	
	google.charts.setOnLoadCallback(drawFaSalesCateAll);
	google.charts.setOnLoadCallback(drawFaMarginCateAll);
	
	google.charts.setOnLoadCallback(drawStack);

    function drawTop10So() {
        let data_array = [
            ["Product_name", "SO_numbers", {role: "style"}]
        ];
		
		for(let product of $scope.top10_so){
			data_array.push([product['product_name'], parseInt(product['count']), '#766161']);
		}
     
        var data = google.visualization.arrayToDataTable(data_array);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2
        ]);

        var options = {
            title: "Top 10 Products with Highest Sales Order",
			titleTextStyle: {
				fontSize: 18,
				color:'#444444',
				bold: true,
				italic: false
			},
            bar: {
                groupWidth: "70%"
            },
            legend: {
                position: "none"
            },
			hAxis: {
			  title: 'Number of SO'
			}
        };
        var chart = new google.visualization.BarChart(document.getElementById("top_so"));
        chart.draw(view, options);
    }

    function drawTop10Margin() {
        let data_array = [
            ["Product_name", "Margin", {role: "style"}]
        ];
		
		for(let product of $scope.top10_margin){
			data_array.push([product['product_name'], parseInt(product['sum_margin']), '#87A7B3']);
		}
     
        var data = google.visualization.arrayToDataTable(data_array);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2
        ]);

        var options = {
            title: "Top 10 Products with Highest Margin",
			titleTextStyle: {
				fontSize: 18,
				color:'#444444',
				bold: true,
				italic: false
			},
            bar: {
                groupWidth: "70%"
            },
            legend: {
                position: "none"
            },
			hAxis: {
			  title: 'Margin (THB)'
			}
        };
        var chart = new google.visualization.BarChart(document.getElementById("top_margin"));
        chart.draw(view, options);
    }
	
	function drawFaSalesWeeks(){
	
		let data_array = [
			['Week','Actual Sales','Forecast Sales']
		];
		
		for(let week of $scope.fa_sales_weeks ){
			data_array.push([week['week'], parseInt(week['actual_sales']), parseInt(week['forecast_sales'])]);
		}
		
		var data = google.visualization.arrayToDataTable(data_array);

		var options = {
			chart:{
				title: 'Actual vs Forecast Sales All Weeks',
				trendlines: { 
							0:	{
								type: 'linear',
								color: 'green',
								lineWidth: 3,
								labelInLegend: 'Actual Sales Trendline',
        						visibleInLegend: true
								},
						  	1:	{
								type: 'linear',
							  	labelInLegend: 'Forecast Sales Trendline',
        						visibleInLegend: true
						  		}
						}
        		  },
			axes: {
              y: {
              	0: { label: 'Sales (THB)'} 
            	}
          	},
			vAxis: { format: 'decimal'},
			colors:['#50CB93', '#54436B']
	
		};
		var chart = new google.charts.Bar(document.getElementById('fa_sales_weeks'));

//        chart.draw(view, options);
        chart.draw(data, google.charts.Bar.convertOptions(options));
	}
	
	function drawFaMarginWeeks(){
		let data_array = [
			['Week','Actual Margin','Forecast Margin']
		];
		
		for(let week of $scope.fa_margin_weeks ){
			data_array.push([week['week'], parseInt(week['actual_margin']), parseInt(week['forecast_margin'])]);
		}
		
		var data = google.visualization.arrayToDataTable(data_array);
	
		var options = {
			chart:{
				title: 'Actual vs Forecast Margin All Weeks',
				trendlines: { 
							0:	{
								type: 'linear',
								color: 'green',
								lineWidth: 3,
								labelInLegend: 'Actual Margin Trendline',
        						visibleInLegend: true
								},
						  	1:	{
								type: 'linear',
							  	labelInLegend: 'Forecast Margin Trendline',
        						visibleInLegend: true
						  		}
						}
        		  },
			axes: {
              y: {
              0: { label: 'Margin (THB)'} 
            	}
          	},
			vAxis: { format: 'decimal'},
			colors:['#ED8E7C','#A03C78']
	
		};
		var chart = new google.charts.Bar(document.getElementById('fa_margin_weeks'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
		
	}
	
	$scope.render_sales = function(){
		
		let filtered_data = ($scope.fa_sales_cat).filter(function(obj) {return obj.week== $scope.selectedWeekSales});
		let data_array =[
			['Category', 'Actual Sales', 'Forecast Sales']
		];
		for(let each of filtered_data){
			data_array.push([each['category_name'], parseInt(each['actual_sales']),parseInt(each['forecast_sales'])]);
		}
		
		console.log(`cat: ${data_array.length}`);
		let length = data_array.length;
		let height = 200;
		switch(true){
			case(length<5):
				height = 200;
				break;
			case(length<=10):
				height = 400;
				break;
			default:
				height = 600;
		}
		console.log(`height: ${height}`);
		
		var data = google.visualization.arrayToDataTable(data_array);
		
		var options = {
          chart: {
            title: `Actual vs Forecast Sales by Category: Week ${$scope.selectedWeekSales}`,
          },
          bars: 'horizontal',
		  bar: {groupWidth: "80%"},
		  axes: {
            x: {
              0: { label: 'Sales (THB)'} 
            	}
          	},
		  colors:['#50CB93', '#54436B'],
		  hAxis: { format: 'decimal'},
		  height: `${height}`
        };

        var chart = new google.charts.Bar(document.getElementById('fa_sales_cat'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
	}
	
	$scope.render_margin = function(){
		
		let filtered_data = ($scope.fa_margin_cat).filter(function(obj) {return obj.week== $scope.selectedWeekMargin});
		let data_array =[
			['Category', 'Actual Sales', 'Forecast Sales']
		];
		for(let each of filtered_data){
			data_array.push([each['category_name'], parseInt(each['actual_margin']),parseInt(each['forecast_margin'])]);
		}
		
		console.log(`cat: ${data_array.length}`);
		let length = data_array.length;
		let height = 200;
		switch(true){
			case(length<5):
				height = 200;
				break;
			case(length<=10):
				height = 400;
				break;
			default:
				height = 600;
		}
		console.log(`height: ${height}`);
		
		var data = google.visualization.arrayToDataTable(data_array);
		
		var options = {
          chart: {
            title: `Actual vs Forecast Margin by Category: Week ${$scope.selectedWeekMargin}`,
          },
          bars: 'horizontal',
		  bar: {groupWidth: "80%"},
		  axes: {
            x: {
              0: { label: 'Sales (THB)'} 
            	}
          	},
		  colors:['#ED8E7C','#A03C78'],
		  hAxis: { format: 'decimal'},
		  height: `${height}`
        };

        var chart = new google.charts.Bar(document.getElementById('fa_margin_cat'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
	}
	
	function drawFaSalesTotal(){
		var data = google.visualization.arrayToDataTable([
			['Xaxis','Total Sales',{ role: "style" }],
			['Actual Sales',parseInt($scope.fa_sales_total[0]['actual_sales']),'#50CB93'],
			['Forecast Sales',parseInt($scope.fa_sales_total[0]['forecast_sales']),'#54436B']
			
		]);

		 var view = new google.visualization.DataView(data);
		 view.setColumns([0, 1,
						   { calc: "stringify",
							 sourceColumn: 1,
							 type: "string",
							 role: "annotation" },
						   2]);
		
		 var options = {
			title: "Sales",
			titleTextStyle: {
				fontSize: 18,
				color:'#828282',
				bold: true,
				italic: false
			},
			bar: {groupWidth: "60%"},
			legend: { position: "none" }
		  };
		 var chart = new google.visualization.ColumnChart(document.getElementById("fa_sales_total"));
		 chart.draw(view, options);
	}
	
	function drawFaMarginTotal(){
		var data = google.visualization.arrayToDataTable([
			['Xaxis','Total Margin',{ role: "style" }],
			['Actual Margin',parseInt($scope.fa_margin_total[0]['actual_margin']),'#ED8E7C'],
			['Forecast Margin',parseInt($scope.fa_margin_total[0]['forecast_margin']),'#A03C78']
			
		]);

		 var view = new google.visualization.DataView(data);
		 view.setColumns([0, 1,
						   { calc: "stringify",
							 sourceColumn: 1,
							 type: "string",
							 role: "annotation" },
						   2]);
		
		 var options = {
			title: "Margin",
			titleTextStyle: {
				fontSize: 18,
				color:'#828282',
				bold: true,
				italic: false
			},
			bar: {groupWidth: "60%"},
			legend: { position: "none" }
		  };
		 var chart = new google.visualization.ColumnChart(document.getElementById("fa_margin_total"));
		 chart.draw(view, options);
	}
	
	function drawStack(){
		
		$scope.weekAsKey = {
			"1":{},
			"2":{},
			"3":{},
			"4":{},
			"5":{},
			"6":{},
			"7":{},
			"8":{},
			"9":{},
			"10":{}
		};
		
		for(let each of $scope.stack_data){
			let week = each.week;
			$scope.weekAsKey[week][each.category_name] = each.actual_sales;
		}
		
		let catArray=[];
		for(let each of $scope.cat_for_stack){
			catArray.push(each.category_name);
		}
		
		var data = new google.visualization.DataTable();
		data.addColumn("string","Week");
		
		for(let i=0; i<catArray.length; i++){
			data.addColumn("number",catArray[i]);
		}
		
		let dataArray = [];
		for(let week=1; week<=10; week++){
			dataArray.push([week.toString()])
			for(let i=0; i<catArray.length; i++){
				dataArray[week-1].push(parseInt($scope.weekAsKey[week][catArray[i]]));
			}
		}
		
		data.addRows(dataArray);
		
		var options_fullStacked = {
          isStacked: 'percent',
          legend: {position: 'top', maxLines: 2},
          hAxis: {
            minValue: 0,
            ticks: [0, .25, .50, .75, 1]
          },
		  bar: {groupWidth: "70%"},
		  vAxis: {
			  title:"Week"
		  },
		  series: {
			0:{color:'#003638'},
			1:{color:'#0b6b6e'},
			2:{color:'#53B8BB'},
			3:{color:'#F3F2C9'},
			4:{color:'#753422'},
			5:{color:'#B05B3B'},
			6:{color:'#D79771'},
			7:{color:'#2C2E43'},
			8:{color:'#595260'},
			9:{color:'#4F0E0E'},
			10:{color:'#BB8760'},
			11:{color:'#FFDADA'},
			12:{color:'#FFF1F1'},
			13:{color:'#FFA900'},
			14:{color:'#FF7600'},
			15:{color:'#CD113B'},
			16:{color:'#52006A'},
			17:{color:'#402218'},
			18:{color:'#865439'},
			19:{color:'#C68B59'},
			20:{color:'#D7B19D'},
			21:{color:'#5F939A'},
			22:{color:'#3A6351'},
			23:{color:'#A0937D'},
			24:{color:'#FFC074'},
			25:{color:'#C84B31'}
		  }
        };
		
		var chart = new google.visualization.BarChart(document.getElementById("stack"));
      	chart.draw(data, options_fullStacked);
		
	}
	
	function drawFaSalesCateAll(){
	
		let data_array =[
			['Category', 'Actual Sales', 'Forecast Sales']
		];
		for(let each of $scope.fa_sales_cat_all){
			data_array.push([each['category_name'], parseInt(each['actual_sales']),parseInt(each['forecast_sales'])]);
		}
		
		console.log(`cat: ${data_array.length}`);
		let length = data_array.length;
		let height = 200;
		switch(true){
			case(length<5):
				height = 200;
				break;
			case(length<=10):
				height = 400;
				break;
			default:
				height = 600;
		}
		console.log(`height: ${height}`);
		
		var data = google.visualization.arrayToDataTable(data_array);
		
		var options = {
          chart: {
            title: `Actual vs Forecast Sales by Category`,
          },
          bars: 'horizontal',
		  bar: {groupWidth: "80%"},
		  axes: {
            x: {
              0: { label: 'Sales (THB)'} 
            	}
          	},
		  colors:['#50CB93','#54436B'],
		  hAxis: { format: 'decimal'},
		  height: `${height}`
        };

        var chart = new google.charts.Bar(document.getElementById('fa_sales_cat_all'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
	}
	
	function drawFaMarginCateAll(){
	
		let data_array =[
			['Category', 'Actual Margin', 'Forecast Margin']
		];
		for(let each of $scope.fa_margin_cat_all){
			data_array.push([each['category_name'], parseInt(each['actual_margin']),parseInt(each['forecast_margin'])]);
		}
		
		console.log(`cat: ${data_array.length}`);
		let length = data_array.length;
		let height = 200;
		switch(true){
			case(length<5):
				height = 200;
				break;
			case(length<=10):
				height = 400;
				break;
			default:
				height = 600;
		}
		console.log(`height: ${height}`);
		
		var data = google.visualization.arrayToDataTable(data_array);

		var options = {
          chart: {
            title: `Actual vs Forecast Margin by Category`,
          },
          bars: 'horizontal',
		  bar: {groupWidth: "80%"},
		  axes: {
            x: {
              0: { label: 'Sales (THB)'} 
            	}
          	},
		  colors:['#ED8E7C','#A03C78'],
		  hAxis: { format: 'decimal'},
		  height: `${height}`
        };

        var chart = new google.charts.Bar(document.getElementById('fa_margin_cat_all'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
	}
	
	$(window).smartresize(function(){
		drawTop10So();
		drawTop10Margin();
		drawFaSalesTotal();
		drawFaMarginTotal();
		drawFaSalesWeeks();
		drawFaMarginWeeks();
		drawFaSalesCateAll();
		drawFaMarginCateAll();
		drawStack();
		$scope.render_sales();
		$scope.render_margin();
		
	});
	
});
	
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="/public/json/sales_and_margin.js"></script>
