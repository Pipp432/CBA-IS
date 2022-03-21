<!DOCTYPE html>

<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
    <div class="container" ng-controller="moduleAppController" >
		<div class="row">
			<div id="barchart_values" class="col" style="height:800px" ></div>
		    <div id="margin" class="col" style="height:800px" ></div>
		</div>
    </div>
</body>

</html>

<script>
app.controller('moduleAppController', function($scope, $http, $compile) {
    $scope.products_so = <?php echo $this->top10_so_data ?>;
	$scope.products_margin = <?php echo $this->top10_margin_data ?>;
	
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChartSO);
	google.charts.setOnLoadCallback(drawChartMargin);

    function drawChartSO() {
        let data_array = [
            ["Product_name", "SO_numbers", {role: "style"}]
        ];
		
		for(let product of $scope.products_so){
			data_array.push([product['product_name'], parseInt(product['count']), '#ffc2c8']);
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
            bar: {
                groupWidth: "60%"
            },
            legend: {
                position: "none"
            }
        };
        var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
        chart.draw(view, options);
    }
	
	
    function drawChartMargin() {
        let data_array = [
            ["Product_name", "Margin", {role: "style"}]
        ];
		
		for(let product of $scope.products_margin){
			data_array.push([product['product_name'], parseInt(product['sum_margin']), '#87CEFA']);
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
            bar: {
                groupWidth: "60%"
            },
            legend: {
                position: "none"
            }
        };
        var chart = new google.visualization.BarChart(document.getElementById("margin"));
        chart.draw(view, options);
    }
});
</script>

