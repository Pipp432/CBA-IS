<!doctype html>
<html>
<head>
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
	<div class="container" ng-controller="moduleAppController" >
		<div id="actual" style="height:800px" ></div>
	</div>
</body>
</html>
<script>

	app.controller('moduleAppController', function($scope, $http, $compile) {
    $scope.week = <?php echo $this->forecast_vs_actual ?>;
	
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(actualSalesChart);
	

    function actualSalesChart() {
        let data_array = [
            ["Product_Line", "Actual Sale", {role: "style"}]
        ];
		
		for(let sales of $scope.week){
			data_array.push([sales['product_line'], parseInt(sales['actual_sale']),'#ffc2c8']);
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
            title: "Actual Sales By Line",
            legend: {
                position: "none"
            }
        };
        var chart = new google.visualization.BarChart(document.getElementById("actual"));
        chart.draw(view, options);
    }
	
});

</script>