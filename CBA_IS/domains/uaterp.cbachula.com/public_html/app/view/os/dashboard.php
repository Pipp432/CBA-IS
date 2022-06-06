<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard SOX</h2>
        <select id ="filter" name = "filter" ng-model="filter" ng-change = "getData()">
            <option value="Line 1">1</option>
            <option value="Line 2">2</option>
            <option value="Line 3">3</option>
            <option value="Line 4">4</option>
            <option value="Line 5">5</option>
            <option value="Line 6">6</option>
            <option value="Line 7">7</option>
            <option value="Line 8">8</option>
            <option value="Line 9">9</option>
            <option value="Line 10">10</option>
            <option value="All Lines">All</option>
            
        </select>
        <br>
        <br>
        <div ng-show="filter && soxs">
            <h3>Viewing: {{filter}}</h3>
            <table class="table table-hover my-1">
                <tr>
                    <th> SOX No. </th>
                    <th> SO No. </th>
                    <th> Product Line </th>
                </tr>
                <tr ng-repeat = "sox in soxs track by $index">
                    <td> {{sox.sox_no}}</td>
                    <td> {{sox.so_no}}</td>
                    <td> {{sox.product_line}}</td>
                </tr>
            </table>
        </div>

         <div ng-show="filter && !soxs">
             
            <h3>Viewing: {{filter}}</h3>
            <h3 style="text-align: center ;">No SOXs in {{filter}}</h3>
        </div>

</body>

</html>

<style>
    a:hover { text-decoration: none; }
    .blue { color: #6aa8d9; }
    .textLight { color: #aaa; }
    .textLight2 { color: #777; }
    .textDarkLight { color: #444; }
    th{
        text-align: center;
    }
    td{
        text-align: center;
    }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.lineDictionary = 
        {
            "Line 1" : "1",
            "Line 2" : "2",
            "Line 3" : "3",
            "Line 4" : "4",
            "Line 5" : "5",
            "Line 6" : "6",
            "Line 7" : "7",
            "Line 8" : "8",
            "Line 9" : "9",
            "Line 10" : "10",
            "All" : "."
        }
        $scope.filter = ''
        $scope.getData = function(){
            let line = $scope.filter;
            if(line!=="All Lines"){
                $http.get(`/mkt/os_dashboard/get_soxs/${($scope.lineDictionary)[line]}`).then((response)=>{$scope.soxs = response["data"];})
            }else{
                $http.get(`/mkt/os_dashboard/get_all_soxs`).then((response)=>{$scope.soxs = response["data"]; console.log($scope.soxs)})
            }
           
        }
        $scope.getData();
    });

</script>
