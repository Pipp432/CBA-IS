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
                <div class="card text-white bg-primary m-2" ng-click="getDashboardPO()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบสั่งซื้อ (PO)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPV()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบสำคัญสั่งจ่าย (PV)</h5>
                    </div>
                </div>
            </div>

		</div>
		<div class="row row-cols-2 row-cols-md-3 mt-2 p-0">

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVA()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-A (เงินรองจ่าย)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVB()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-B (Supplier)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVD()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-D (ใบลดหนี้))</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVC()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบเบิกค่าใช้จ่าย</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVC_confirm()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-C (ค่าใช้จ่าย)</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT everything not pv  -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div ng-show = "temp != 'PV-D' && temp != 'PV-A' && temp != 'PV-C' && temp != 'PPV-C'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข {{doc}}</th>
                    <th>{{temp}}</th>
                    <th ng-show="doc == 'PV'">ชื่อ Supplier</th>
					<th ng-show="doc == 'PO'">เลข SO</th>
                    <th ng-show="doc == 'IV_CR'">เลข SOX</th>
                    <th ng-show="doc == 'IV_CR'">ไฟล์ CR</th>
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
                    <td ng-show="doc == 'PV'">{{dashboard.pv_name}}</td>
					<td ng-show="doc == 'PO'">{{dashboard.so}}</td>
                    <td ng-show="doc == 'IV_CR'">{{dashboard.sox_no}}</td>
                    <td ng-show="doc == 'IV_CR'">N/A [WIP]</td>
                    <td>{{dashboard.file_date}} {{dashboard.file_time}}</td>
                    <td>{{dashboard.file_emp_id}} {{dashboard.file_emp_name}}</td>
                    <td ng-show="doc == 'PV'">
                        <span ng-show="dashboard.slip_name == null && dashboard.receipt_name == null">รอโอนเงิน</span>
                        <span ng-show="dashboard.slip_name != null && dashboard.receipt_name == null">โอนเงินแล้ว</span>
                        <span ng-show="dashboard.slip_name != null && dashboard.receipt_name != null">ได้ใบเสร็จแล้ว</span>
                        <span ng-show="dashboard.slip_name != null"> <a href="/acc/dashboard/pv_slip/{{dashboard.file_no}}" target="_blank" ng-click="stopEvent($event)">สลิป invoice</a></span>
                        <span ng-show="dashboard.cr_name == null">ไม่มีใบ CR </span>
                        <a ng-show="dashboard.cr_name != null" href="/acc/dashboard/get_PVB_CR/{{dashboard.file_no}}" target="_blank" ng-click="stopEvent($event)">ดูใบ CR</a> </span>

                        <!-- <span ng-show="pvType == 'Supplier'"> <a href="/acc/dashboard/get_IVPC_Files_dashboard/bill/{{dashboard.file_no}}" target="_blank">ดูใบวางบิล </a>
                        <a  href="/acc/dashboard/get_IVPC_Files_dashboard/tax/{{dashboard.file_no}}" target="_blank">ดูใบกำกับภาษี </a>
                        <a  href="/acc/dashboard/get_IVPC_Files_dashboard/debt/{{dashboard.file_no}}" target="_blank">ดูใบลดหนี้ </a> </span> -->
                        
                    </td>
                </tr>
            </table>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT PVD -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PV-D'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-D</th>
                    <th>เลข IV</th>
                    <th>เลข SOX</th>
                    <th>วันที่</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> no PV-D to show</h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFile(dashboard)">
                    <td>{{dashboard.pvd_no}}</td>
                    <td>{{dashboard.invoice_no}}</td>
                    <td>{{dashboard.sox_no}}</td>
                    <td>{{dashboard.pvd_date}} {{dashboard.pvd_time}}</td>
                    <td>
                        <span ng-show="dashboard.PVD_status < 3">fin ยังไม่ upload slip</span>
                        <!-- todo get pvd slip -->
                        <a ng-show="dashboard.PVD_status >= 3" href="/acc/dashboard/get_PVD_slip/{{dashboard.pvd_no}}" target="_blank" ng-click="stopEvent($event)">slip</a>

                    </td>
                    <!-- todo convert status to readable -->
                    <td>{{dashboard.PVD_status}}</td>
                </tr>
            </table>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT PVA -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PV-A'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-A</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-A to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFilePVA(dashboard)">
                    <td>{{dashboard.pv_no}}</td>
                    <td>{{dashboard.pv_date}} {{dashboard.pv_time}}</td>
                    <td>{{dashboard.product_names}}</td>
                    <td>{{dashboard.total_paid}}</td>
                    <td>
                        <span ng-show="dashboard.pv_status < 4">fin ยังไม่ upload slip</span>
                        <a ng-show = "dashboard.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvaslip/{{dashboard.pv_no}}" target="_blank" ng-click="stopEvent($event)">slip</a> 

                    </td>
                    <!-- todo convert status to readable -->
                    <td>{{dashboard.pv_status}}</td>
                </tr>
            </table>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div ng-show = "temp == 'PPV-C'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-C</th>
                    <th>วันที่</th>
                    <th>จำนวนเงิน</th>
                    <th>ผู้ออกใบเบิกค่าใช้จ่าย</th>
                    <th>ผู้กดยืนยัน</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> ไม่มีใบเบิกค่าใช้จ่าย แสดง </h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFile(dashboard)">
                    <td>{{dashboard.ex_no}}</td>
                    <td>{{dashboard.withdraw_date}}</td>
                    <td>{{dashboard.total_paid}}</td>
                    <td>{{dashboard.employee_id}} {{dashboard.employee_nickname_thai}}</td>
                </tr>
            </table>
            
        </div>


        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div ng-show = "temp == 'PV-C'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-C</th>
                    <th>วันที่</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>เอกสาร IV</th>
                    <th>เอกสาร PVC</th>
                    <th>ผู้ออกใบ PVC</th>
                    <th>ผู้กดยืนยัน</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> no PV-C to show </h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards">
                    <td>{{dashboard.pv_no}}</td>
                    <td>{{dashboard.pv_date}}</td>
                    <td>{{dashboard.total_paid}}</td>
                    <td>
                        <a href="/acc/dashboard_acc/pv_slip/{{dashboard.pv_no}}">slip</a>

                    </td>
                    <td>
                        <a href="https://uaterp.cbachula.com/file/re_req/{{dashboard.re_req_no}}">{{dashboard.ex_no}}</a>
                    </td>
                    <td>
                        <a href="https://uaterp.cbachula.com/file/pvc/{{dashboard.pv_no}}">{{dashboard.pv_no}}</a>
                    </td>
                    <td>{{dashboard.approved_employee}} {{dashboard.employee_nickname_thai}}</td>
                </tr>
            </table>
            
        </div>

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
        console.log($scope.dashboardsIv)
        $scope.dashboardsPv = <?php echo $this->dashboardPv; ?>;
        $scope.dashboardsPva = <?php echo $this->dashboardPva; ?>;
        $scope.dashboardsPvb = <?php echo $this->dashboardPvb; ?>;
        $scope.dashboardsPvc = <?php echo $this->dashboardPvc; ?>;
        $scope.dashboardsPvc_confirm = <?php echo $this->dashboardPvc_confirm; ?>;
        $scope.dashboardsPvd = <?php echo $this->dashboardPvd; ?>;
        $scope.dashboardsPo = <?php echo $this->dashboardPo; ?>;
        $scope.pvType = '';
        
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
            $scope.pvType = '';
            $scope.temp = 'ประเภทการสั่งจ่าย';
        }

        $scope.getDashboardPVA = function() {
            $scope.dashboards = $scope.dashboardsPva; 
            $scope.doc = 'PV';
            $scope.pvType = 'pva';
            $scope.temp = 'PV-A';
        }

        $scope.getDashboardPVB = function() {

            $scope.dashboards = $scope.dashboardsPvb; 
            $scope.doc = 'PV';
            $scope.pvType = 'Supplier';
            $scope.temp = 'PV-B';
        }

        $scope.getDashboardPVD = function() {

            $scope.dashboards = $scope.dashboardsPvd; 
            $scope.doc = 'PV';
            $scope.pvType = 'pvd';
            $scope.temp = 'PV-D';
        }

        $scope.getDashboardPVC = function(){
            $scope.dashboards = $scope.dashboardsPvc; 
            $scope.doc = 'PV';
            $scope.pvType = 'pvc';
            $scope.temp = 'PPV-C';
        }

        $scope.getDashboardPVC_confirm = function(){
            $scope.dashboards = $scope.dashboardsPvc_confirm; 
            $scope.doc = 'PV';
            $scope.pvType = 'pvc';
            $scope.temp = 'PV-C';
        }

        
        $scope.getDashboardPO = function() {
            $scope.dashboards = $scope.dashboardsPo;
            $scope.doc = 'PO';
            $scope.temp = 'Supplier';
        }

        $scope.stopEvent = function(e){
            e.stopPropagation();
        }
        
        $scope.viewFile = function(file) {
            if(file.invoice_type == 'CN') {
                window.open('/file/cn/' + file.file_no);
            } else {
                window.open('/file/' + $scope.doc.toLowerCase() + '/' + file.file_no);
            }
        }

        $scope.viewFilePVA = function($dashboard) {
            window.open('/file/pva/' + $dashboard.pv_no);
        }
        
    });

</script>