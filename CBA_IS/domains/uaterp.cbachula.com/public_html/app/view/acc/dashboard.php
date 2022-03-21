<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">
			
            <div class="col">
                <div class="card text-white bg-secondary m-2" ng-click="getDashboardIVCR()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบกำกับภาษี/ใบเสร็จรับเงิน (IV/CR)</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-secondary m-2" ng-click="getDashboardIV()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบกำกับภาษี (IV)</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-secondary m-2" ng-click="getDashboardCR()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบเสร็จรับเงิน (CR)</h5>
                    </div>
                </div>
            </div>
		</div>
		<div class="row row-cols-2 row-cols-md-3 mt-2 p-0">
           <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPV()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบสำคัญสั่งจ่าย (PV)</h5>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-white bg-primary m-2" ng-click="getDashboardPO()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบสั่งซื้อ (PO)</h5>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข {{doc}}</th>
                    <th>{{temp}}</th>
					<th ng-show="doc == 'PO'">เลข SO</th>
                    <th>วันที่</th>
                    <th>ผู้อนุมัติ</th>
                    <th ng-show="doc == 'PV'">สถานะ</th>
                </tr>
                <tr ng-show="isLoad && doc != ''">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                    </th>
                </tr>
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFile(dashboard)">
                    <td>{{dashboard.file_no}} 
                        <span ng-show="doc == 'PO'">({{dashboard.rr}}{{dashboard.ci}})</span>
                        <span ng-show="doc == 'IV' && dashboard.invoice_type == 'CN'">(ลดหนี้)</span>
                    </td>
                    <td>{{dashboard.temp}}</td>
					<td ng-show="doc == 'PO'">{{dashboard.so}}</td>
                    <td>{{dashboard.file_date}} {{dashboard.file_time}}</td>
                    <td>{{dashboard.file_emp_id}} {{dashboard.file_emp_name}}</td>
                    <td ng-show="doc == 'PV'">
                        <span ng-show="dashboard.slip_name == null && dashboard.receipt_name == null">รอโอนเงิน</span>
                        <span ng-show="dashboard.slip_name != null && dashboard.receipt_name == null">โอนเงินแล้ว</span>
                        <span ng-show="dashboard.slip_name != null && dashboard.receipt_name != null">ได้ใบเสร็จแล้ว</span>
                        <span ng-show="dashboard.slip_name != null"> <a href="/acc/dashboard/pv_slip/{{dashboard.file_no}}" target="_blank">สลิป</a></span>
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
        
        // $scope.isLoad = true;
        $scope.dashboards = [];
        $scope.doc = '';
        $scope.dashboardsIv = <?php echo $this->dashboardIv; ?>;
		$scope.dashboardsCr = <?php echo $this->dashboardCr; ?>;
        $scope.dashboardsPv = <?php echo $this->dashboardPv; ?>;
        $scope.dashboardsPo = <?php echo $this->dashboardPo; ?>;
        
		$scope.getDashboardIVCR = function() {
            $scope.dashboards = $scope.dashboardsIv;
            $scope.doc = 'IV_CR';
            $scope.temp = 'เลข SO';
        }
        $scope.getDashboardIV = function() {
            $scope.dashboards = $scope.dashboardsIv;
            $scope.doc = 'IV';
            $scope.temp = 'เลข SO';
        }
		$scope.getDashboardCR = function() {
            $scope.dashboards = $scope.dashboardsCr;
            $scope.doc = 'CR';
            $scope.temp = 'เลข IV';
        }
        
        $scope.getDashboardPV = function() {
            $scope.dashboards = $scope.dashboardsPv;
            $scope.doc = 'PV';
            $scope.temp = 'ประเภทการสั่งจ่าย';
        }
        
        $scope.getDashboardPO = function() {
            $scope.dashboards = $scope.dashboardsPo;
            $scope.doc = 'PO';
            $scope.temp = 'Supplier';
        }
        
        $scope.viewFile = function(file) {
            if(file.invoice_type == 'CN') {
                window.open('/file/cn/' + file.file_no);
            } else {
                window.open('/file/' + $scope.doc.toLowerCase() + '/' + file.file_no);
            }
        }
        
    });

</script>