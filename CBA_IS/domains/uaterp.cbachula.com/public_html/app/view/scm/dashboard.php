<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="row row-cols-2 row-cols-md-4 mt-2 p-0">
            
            <div class="col p-0">
                <div class="card text-white bg-secondary m-2" ng-click="setStatusFilter(1)">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยังไม่ยืนยัน</h5>
                        <h1 class="card-text mt-1">{{countNotConfirmed}}</h1>
                    </div>
                </div>
            </div>
            
            <div class="col p-0">
                <div class="card text-white bg-info m-2" ng-click="setStatusFilter(2)">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยังไม่ได้รับสินค้า</h5>
                        <h1 class="card-text mt-1">{{countNotReceived}}</h1>
                    </div>
                </div>
            </div>
            
            <div class="col p-0">
                <div class="card text-white bg-primary m-2" ng-click="setStatusFilter(3)">
                    <div class="card-body">
                        <h5 class="card-title my-0">ได้รับสินค้าแล้ว</h5>
                        <h1 class="card-text mt-1">{{countReceived}}</h1>
                    </div>
                </div>
            </div>
            
            <div class="col p-0">
                <div class="card text-white bg-danger m-2" ng-click="setStatusFilter(4)">
                    <div class="card-body">
                        <h5 class="card-title my-0">ยกเลิก</h5>
                        <h1 class="card-text mt-1">{{countCancelled}}</h1>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="mt-2 p-0">
            
            <table class="table table-hover my-1" ng-show="soxs.length != 0">
                <tr>
                    <th>เลข PO</th>
					<th>เลข RR</th>
                    <th>วันที่</th>
                    <th>
                        <select class="form-control" ng-model="productTypeFilter">
                            <option value="">เลือกประเภทสินค้า</option>
                            <option value="Stock">Stock</option>
                            <option value="Order">Order</option>
                            <option value="Install">Install</option>
                        </select>
                    </th>
                    <th>ผู้ออกเอกสาร</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-repeat="dashboard in dashboards | filter:{product_type:productTypeFilter, status:statusFilter}">
                    <td ng-click="viewPO(dashboard)">{{dashboard.po_no}}</td>
					<td ng-click="viewRR(dashboard)">{{dashboard.rr_no}}</td>
                    <td>{{dashboard.po_date}}</td>
                    <td>{{dashboard.product_type}}</td>
                    <td>{{dashboard.approved_employee}} {{dashboard.employee_nickname_thai}}</td>
                    <td>
                        <span class="text-secondary" ng-show="dashboard.status==1"><i class="fa fa-circle" aria-hidden="true"></i> ยังไม่ยืนยัน</span>
                        <span class="text-info" ng-show="dashboard.status==2"><i class="fa fa-circle" aria-hidden="true"></i> ยังไม่ได้รับสินค้า</span>
                        <span class="text-primary" ng-show="dashboard.status==3"><i class="fa fa-circle" aria-hidden="true"></i> ได้รับสินค้าแล้ว</span>
                        <span class="text-danger" ng-show="dashboard.status==4"><i class="fa fa-circle" aria-hidden="true"></i> ยกเลิก</span>
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
        $scope.countNotConfirmed = 0;
        $scope.countNotReceived = 0;
        $scope.countReceived = 0;
        $scope.countCancelled = 0;
        
        angular.forEach($scope.dashboards, function(value){
            if(value.status == 1) $scope.countNotConfirmed++;
            else if(value.status == 2) $scope.countNotReceived++;
            else if(value.status == 3) $scope.countReceived++;
            else if(value.status == 4) $scope.countCancelled++;
        });
        
        $scope.productTypeFilter = '';
        $scope.statusFilter = '';
        $scope.isFiltered = false;
        
        $scope.setStatusFilter = function(num) {
            if($scope.isFiltered && $scope.statusFilter == num) {
                $scope.statusFilter = '';
                $scope.isFiltered = false;
            } else {
                $scope.statusFilter = num;
                $scope.isFiltered = true;
            }
        }
        
        $scope.viewPO = function(po) {
            window.open('/file/po/' + po.po_no);
        }
        
		$scope.viewRR = function(po) {
			window.open('/file/rr/' + po.rr_no);
		}
    });

</script>