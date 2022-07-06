<!DOCTYPE html>
<html>
<body>
    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Statement</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-4">
                        <label for="dropdownStmType">ประเภทรายงาน</label>
                        <select class="form-control" ng-model="selectedStmType" id="dropdownProductType">
                            <option value="">-</option>
                            <option value="Stm1">งบแสดงฐานะการเงิน โครงการ 1</option>
                            <option value="Stm2">งบแสดงฐานะการเงิน โครงการ 2</option>
                            <option value="Stm3">งบแสดงฐานะการเงิน โครงการ 3</option>
                            <option value="Stmspe1">งบแสดงฐานะการเงินโครงการพิเศษ 1</option>
                            <option value="Stmspe2">งบแสดงฐานะการเงินโครงการพิเศษ 2</option>
                            <option value="Stmprofit1">งบกำไรขาดทุน โครงการ 1</option>
                            <option value="Stmprofit2">งบกำไรขาดทุน โครงการ 2</option>
                            <option value="Stmprofit3">งบกำไรขาดทุน โครงการ 3</option>
                        </select>
                    </div>

                    <!-- <div class="col-md-4">
                        <label for="dropdownDate">จนถึงวันที่</label>
                        <select class="form-control" ng-model="selectedDate" id="dropdownProductType">
                            <option value="">วันที่</option>
                            <option value="Apr">30/04/2022</option>
                            <option value="May">31/05/2022</option>
                            <option value="Jun">30/06/2022</option>
                            <option value="Jul">31/07/2022</option>
                            <option value="Aug">30/08/2022</option>
                        </select>
                    </div> -->
                    <div class = "col-md-3">
                        <label for="dateTextbox">จากวันที่</label>
                        <input type="date" class="form-control" id="dateTextbox" ng-model="startDate" ng-change="getStartDate()" >
                    </div>
                    <div class = "col-md-3">
                        <label for="dateTextbox">ถึงวันที่</label>
                        <input type="date" class="form-control" id="dateTextbox" ng-model="dueDate" ng-change = "getDueDate()">
                    </div>

                    <div class="col-md-2">
                        <label for="buttonConfirmDetail" style="color:white;">.</label>
                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="confirmDetail()">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="showAfterSubmit">

            <div class="card-body">

                <div class="row mx-0">

                    <h4 class="my-1">
                        <span ng-show="selectedStmType == 'Stm1'">งบแสดงฐานะการเงิน โครงการ 1</span>
                        <span ng-show="selectedStmType == 'Stm2'">งบแสดงฐานะการเงิน โครงการ 2</span>
                        <span ng-show="selectedStmType == 'Stm3'">งบแสดงฐานะการเงิน โครงการ 3</span>
                        <span ng-show="selectedStmType == 'Stmspe1'">งบแสดงฐานะการเงินโครงการพิเศษ 1</span>
                        <span ng-show="selectedStmType == 'Stmspe2'">งบแสดงฐานะการเงินโครงการพิเศษ 2</span>
                        <span ng-show="selectedStmType == 'Stmprofit1'">งบกำไรขาดทุน โครงการ 1</span>
                        <span ng-show="selectedStmType == 'Stmprofit2'">งบกำไรขาดทุน โครงการ 2</span>
                        <span ng-show="selectedStmType == 'Stmprofit3'">งบกำไรขาดทุน โครงการ 3</span>
                    </h4>

                    <!-- <table class="table table-hover my-1" ng-show="selectedStmType.length != 0">
                        <tr>
                            <th>yay</th>
                        </tr>
                    </table> -->

                    <table class="table table-hover my-1" ng-show="selectedStmType.length != 0">
                        <tr>
                            <th style="text-align: left;">เลขที่บัญชี</th>
                            <th style="text-align: left;">ชื่อบัญชี</th>
                            <!-- <th>จำนวนเงิน</th> -->
                        </tr>

                        <tr ng-repeat="data in datas track by $index">
                          
                            <td style="text-align: left;">{{data.account_no}}</td>
                            <td style="text-align: left;">{{data.account_name}}</td>

                            <td>
                                <!-- <span ng-show="data.debitcredit == 'D'" style="text-align: left;">{{data.total_amount | number:2}}</span>
                                <span ng-show="data.debitcredit == 'C'" style="text-align: right;">{{data.total_amount | number:2}}</span> -->
                                <!-- <span ng-show="data.debitcredit == 'C'" style="text-align: right;">{{data.total_amount =='-'? 'ค่าขนส่ง' : data.total_amount | number:2}}</span>-->
                                <!-- {{data.total_amount | number:2}} -->
                            </td>
                        </tr>
                    </table>
                </div> 
            </div>
        </div>


    </div>



</body>
</html>

<style>

    td { border-bottom: 1px solid lightgray; }

    th { border-bottom: 1px solid lightgray; text-align: center; }

</style>


<script>


    app.controller('moduleAppController', function($scope, $http, $compile) {

        $scope.selectedStmType='';
        $scope.selectedDate='';
        $scope.startDate='';
        $scope.dueDate='';

        $scope.datas = [];
        $scope.showAfterSubmit = false;

        $scope.getStartDate = function(){
            return processDate($scope.startDate);
        }
        $scope.getDueDate = function(){
            return processDate($scope.dueDate);
        }

        $scope.confirmDetail = function() {
            $scope.showAfterSubmit = true;
            $.post("/acc/statement/post_data", { 
                selected_stm_type : $scope.selectedStmType,
                start_date :$scope.getStartDate(),
                due_date :$scope.getDueDate(),

            }, function(data) {
                $scope.datas = JSON.parse(data);
                addModal('successModal', 'testtest', 'get data at '  + ' -- '+$scope.selectedStmType );
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    //window.location.reload();
                });
                console.log($scope.datas);
            });
        }

        function convertDate(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        }
        // Process datetime object helper function
        function processDate (date){
            let currentDate = new Date();
                return convertDate(date);
        }
    });



</script>