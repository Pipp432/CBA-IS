<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SALES -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="row row-cols-2 row-cols-md-2 mt-2 p-0">
            
            <div class="col p-0">
                <div class="card text-white bg-secondary m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยอดขาย</h5>
                        <h1 class="card-text mt-1">฿ {{dashboards[0].total_sales | number:2}}</h1>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">
            
            <div class="col p-0">
                <div class="card text-white bg-info m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยอดขายโครงการ 1</h5>
                        <h1 class="card-text mt-1">฿ {{dashboards[0].total_sales1 | number:2}}</h1>
                    </div>
                </div>
            </div>
            
            <div class="col p-0">
                <div class="card text-white bg-info m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยอดขายโครงการ 2</h5>
                        <h1 class="card-text mt-1">฿ {{dashboards[0].total_sales2 | number:2}}</h1>
                    </div>
                </div>
            </div>
            <div class="col p-0">
                <div class="card text-white bg-info m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยอดขายโครงการ 3</h5>
                        <h1 class="card-text mt-1">฿ {{dashboards[0].total_sales3 | number:2}}</h1>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- CR -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข SOX</th>
                    <th>เลข CR</th>
                    <th>วันที่</th>
                    <th>ผู้อนุมัติ</th>
                    <th>สลิป</th>
                </tr>
                <tr ng-repeat="dashboard in dashboards">
                    <td ng-click="viewFile(dashboard)">{{dashboard.sox_no}}</td>
                    <td ng-click="viewFile(dashboard)">{{dashboard.invoice_no}}</td>
                    <td ng-click="viewFile(dashboard)">{{dashboard.invoice_date}} {{dashboard.invoice_time}}</td>
                    <td ng-click="viewFile(dashboard)">{{dashboard.approved_employee}} {{dashboard.employee_nickname_thai}}</td>
                    <td>
                        {{dashboard.total_sales_price}}
                        <a href="/fin/cash_receipt/sox_slip/{{dashboard.sox_no}}" target="_blank">สลิปครับ</a>
                    </td>
                </tr>
            </table>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.dashboards = <?php echo $this->dashboards; ?>;
        
        $scope.viewFile = function(cr) {
            // var iv = cr.cr_no.substring(0,1) + 'IV-' + cr.cr_no.substring(4,9);
            window.open('/file/iv/' + cr.invoice_no);
        }
        
    });

</script>