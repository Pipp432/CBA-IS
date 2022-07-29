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
                            <!-- <option value="Stmspe1">งบแสดงฐานะการเงินโครงการพิเศษ 1</option>
                            <option value="Stmspe2">งบแสดงฐานะการเงินโครงการพิเศษ 2</option> -->
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
            
                <h3 class="my-1">
                    <span ng-show="selectedStmType == 'Stm1'">งบแสดงฐานะการเงิน โครงการ 1</span>
                    <span ng-show="selectedStmType == 'Stm2'">งบแสดงฐานะการเงิน โครงการ 2</span>
                    <span ng-show="selectedStmType == 'Stm3'">งบแสดงฐานะการเงิน โครงการ 3</span>
                    <!-- <span ng-show="selectedStmType == 'Stmspe1'">งบแสดงฐานะการเงินโครงการพิเศษ 1</span>
                    <span ng-show="selectedStmType == 'Stmspe2'">งบแสดงฐานะการเงินโครงการพิเศษ 2</span> -->
                    <span ng-show="selectedStmType == 'Stmprofit1'">งบกำไรขาดทุน โครงการ 1</span>
                    <span ng-show="selectedStmType == 'Stmprofit2'">งบกำไรขาดทุน โครงการ 2</span>
                    <span ng-show="selectedStmType == 'Stmprofit3'">งบกำไรขาดทุน โครงการ 3</span>
                </h3>
                <br>

                <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
                <!-- งบแสดงฐานะทางการเงิน -->
                <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                <div ng-show = "selectedStmType == 'Stm1' || selectedStmType == 'Stm2' || selectedStmType == 'Stm3'"  class="mt-2 p-0">
                <h4>สินทรัพย์หมุนเวียน</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: assetfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">รวมสินทรัพย์หมุนเวียน &emsp; {{asset1 | number:2}}</th>
                        <!-- <th id="asset" style="text-align: right;">{{asset1 | number:2}}</th> -->
                    </tr>
                    </table>
                    <br>
                <h4>สินทรัพย์ไม่หมุนเวียน</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: totalAssetfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">รวมสินทรัพย์ไม่หมุนเวียน &emsp; {{asset2 | number:2}}</th>
                        <!-- <th id="totalasset" style="text-align: right;">{{asset2 | number:2}}</th> -->
                    </tr>
                    <tr>
                        <th style="text-align: right;" colspan="6">รวมสินทรัพย์ &emsp; {{totalAsset | number:2}}</th>
                        <!-- <th id="totalasset" style="text-align: right;">{{totalAsset | number:2}}</th> -->
                    </tr>
                    </table>
                    <br>

                <h4>หนี้สิน</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: payablefilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th colspan="6" style="text-align: right;">{{payable | number:2}}</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: payabletaxfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th colspan="6" style="text-align: right;">{{payabletax | number:2}}</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: debtfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">รวมหนี้สิน  &emsp; {{debt | number:2}}</th> 
                    </tr>
                    </table>
                    <br>

                <h4>ส่วนของเจ้าของ</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: ownerfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">รวมส่วนของผู้เป็นหุ้นส่วน  &emsp; {{owner | number:2}}</th>
                    </tr>
                    <tr>
                        <th style="text-align: right;" colspan="6">รวมหนี้สินและส่วนของเจ้าของ &emsp; {{debtandowner | number:2}}</th>
                    </tr>
                    </table>
                </div> 

                <div ng-show = "selectedStmType == 'Stmprofit1' || selectedStmType == 'Stmprofit2' || selectedStmType == 'Stmprofit3'"  class="mt-2 p-0">
                <h4>รายได้</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: incomefilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">ขายสุทธิ  &emsp; {{income | number:2}}</th>
                    </tr>
                    </table>

                <br>
                <h4>ต้นทุนขาย</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: costsalefilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">ซื้อสุทธิ &emsp; {{costsale | number:2}}</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: coststockfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                    <th style="text-align: right;" colspan="6">ต้นทุนสินค้าที่มีไว้ขาย  &emsp; {{coststock | number:2}}</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: coststock2filter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                    <th style="text-align: right;" colspan="6">{{coststock2 | number:2}}</th>
                    </tr>
                    <tr>
                        <th style="text-align: right;" colspan="6">กำไรขั้นต้น  &emsp; {{grossprofit | number:2}}</th>
                    </tr>
                    </table>
                    
                <br>
                <h4>ค่าใช้จ่ายในการขายและบริหาร</h4>
                    <table class="table table-hover my-1">
                    <tr>
                        <th style="text-align: left;">เลขที่บัญชี</th>
                        <th style="text-align: left;">ชื่อบัญชี</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: expense1filter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                        <th style="text-align: right;" colspan="6">ค่าใช้จ่ายในการขาย &emsp; {{expense1 | number:2}}</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: expense2filter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                    <th style="text-align: right;" colspan="6">ค่าใช้จ่ายในการบริหาร  &emsp; {{expense2 | number:2}}</th>
                    </tr>
                    <tr>
                    <th style="text-align: right;" colspan="6">กำไรจากการดำเนินงาน  &emsp; {{operprofit | number:2}}</th>
                    </tr>
                    <p> รายได้อื่น  </p>
                    <tbody ng-repeat="data in bigdata | filter: vatfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                    <th style="text-align: right;" colspan="6">{{vat | number:2}}</th>
                    </tr>
                    <tr>
                    <th style="text-align: right;" colspan="6">กำไรก่อนหักภาษี  &emsp; {{profitnovat | number:2}}</th>
                    </tr>
                    <tbody ng-repeat="data in bigdata | filter: incometaxfilter ">
                        <td style="text-align: left;">{{data.account_no}}</td>
                        <td style="text-align: left;">{{data.account_name==null? 'none' : data.account_name}}</td>
                        <td style="text-align: right;">{{data.total_amount | number:2}}</td>
                    </tbody>
                    <tr>
                    <th style="text-align: right;" colspan="6">กำไรสุทธิ	  &emsp; {{netprofit | number:2}}</th>
                    </tr>


                    

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

        $scope.bigdata = [];
        $scope.showAfterSubmit = false;

        $scope.asset1 = '';
        $scope.asset2 = '';
        $scope.totalAsset = '';
        $scope.amount = '';
        $scope.payable = '';
        $scope.payabletax = '';
        $scope.debt = '';
        $scope.owner = '';
        $scope.debtandowner = '';

        $scope.income = '';
        $scope.costsale = '';
        $scope.coststock = '';
        $scope.grossprofit = '';
        $scope.coststock2 = '';
        $scope.expense1 = '';
        $scope.expense2 = '';      
        $scope.vat = '';
        $scope.profitnovat = '';
        $scope.netprofit ='';
        $scope.b6 = '';
        $scope.b7 = '';
        $scope.b10 = '';
        $scope.b11 = '';
        $scope.b13 = '';
        $scope.b15 = '';
        $scope.incometax = '';

        //ตระกูล filter
        $scope.assetfilter = function (item) { 
            // console.log(item.prefix);
            return item.prefix === '11-1' || item.prefix === '12-1' || item.prefix === '13-1' || item.prefix === '13-3' || item.prefix === '14-0'||item.prefix === '15-0'; 
        };
        $scope.totalAssetfilter = function (item) { 
            return item.prefix === '16-1' ; 
        };
        $scope.payablefilter = function (item) { 
            return item.prefix === '21-1' ; 
        };
        $scope.payabletaxfilter = function (item) { 
            return item.prefix === '21-2' ; 
        };
        $scope.debtfilter = function (item) { 
            return item.prefix === '22-1' || item.prefix === '23-1' || item.prefix === '24-1' || item.prefix === '25-1' ; 
        };
        $scope.ownerfilter = function (item) { 
            return item.prefix === '31-0' || item.prefix === '32-0' ;
        };
        $scope.incomefilter = function (item) { 
            return item.prefix === '41-1' ;
        };
        $scope.costsalefilter = function (item) { 
            return item.prefix === '51-1' ;
        };
        $scope.coststockfilter = function (item) { 
            return item.prefix === '51-2' ;
        };
        $scope.coststock2filter = function (item) { 
            return item.prefix === '14-0' ;
        };
        $scope.expense1filter = function (item) { 
            return item.prefix === '52-0' || item.prefix === '52-1' || item.prefix === '52-2' || item.prefix === '52-3';
        };
        $scope.expense2filter = function (item) { 
            return item.prefix === '53-1' || item.prefix === '53-2' || item.prefix === '53-3' || item.prefix === '53-4' || item.prefix === '53-5';
        };
        $scope.vatfilter = function (item) { 
            return item.prefix === '42-1' || item.prefix === '43-1' || item.prefix === '44-1';
        };
        $scope.incometaxfilter = function (item) { 
            return item.prefix === '63-1' ;
        };
        
        
        $scope.getStartDate = function(){
            //console.log(processDate($scope.startDate));
            return processDate($scope.startDate);
            
        }
        $scope.getDueDate = function(){
            return processDate($scope.dueDate);
        }

        function convertDate(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            //console.log([date.getFullYear(), mnth, day].join("-"));
            return [date.getFullYear(), mnth, day].join("-");
        }
        // Process datetime object helper function
        function processDate (date){
            let currentDate = new Date();
                return convertDate(date);
        }

        $scope.confirmDetail = function() {
            $scope.showAfterSubmit = true;
            $.post("/acc/statement/post_data", { 
                selected_stm_type : $scope.selectedStmType,
                start_date :$scope.getStartDate(),
                due_date :$scope.getDueDate()
            }, function(data) {
                $scope.bigdata = JSON.parse(data);
                // addModal('successModal', 'testtest', `get ${$scope.selectedStmType} `);
                // $('#successModal').modal('toggle');
                // $('#successModal').on('hide.bs.modal', function (e) {
                //     //window.location.reload();
                // });

                //console.log($scope.bigdata);
                $scope.calculateStm();
                $scope.$digest();
                //$scope.calculateStm();
            });

           
        }

        $scope.calculateStm = function() {
            $scope.asset1 = 0;
            $scope.asset2 = 0;
            $scope.totalAsset = 0;
            $scope.amount = 0;
            $scope.payable = 0;
            $scope.payabletax = 0;
            $scope.debt = 0;
            $scope.owner = 0;
            $scope.debtandowner = 0;

            $scope.income = 0;
            $scope.costsale = 0;
            $scope.coststock = 0;
            $scope.grossprofit = 0;
            $scope.coststock2 = 0;
            $scope.expense1 = 0;
            $scope.expense2 = 0;      
            $scope.vat = 0;
            $scope.profitnovat = 0;
            $scope.netprofit =0;
            $scope.b6 = 0;
            $scope.b7 = 0;
            $scope.b10 = 0;
            $scope.b11 = 0;
            $scope.b13 = 0;
            $scope.b15 = 0;
            $scope.incometax = 0;

            //console.log($scope.bigdata);
            
            if($scope.bigdata.length != 0) {
                $scope.amount = 0;
                // angular.forEach($scope.bigdata, function(value, key){
                    if($scope.selectedStmType == 'Stm1' || $scope.selectedStmType == 'Stm2' || $scope.selectedStmType == 'Stm3'){
                        angular.forEach($scope.bigdata, function(value, key){
                            // console.log(value);
                            if(value.prefix == '11-1' || value.prefix == '12-1' || value.prefix == '13-1' || value.prefix == '13-3' || value.prefix == '14-0' || value.prefix == '15-0' ){
                                
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.asset1 += (parseFloat($scope.amount));

                            } else if(value.prefix == '16-1'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.asset2 += (parseFloat($scope.amount));

                            } else if(value.prefix == '21-1'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.payable += (parseFloat($scope.amount));

                            } else if(value.prefix == '21-2'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.payabletax += (parseFloat($scope.amount));

                            } else if(value.prefix == '22-1' || value.prefix == '23-1' || value.prefix == '24-1' || value.prefix == '25-1' ){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.debt += (parseFloat($scope.amount));

                            } else if(value.prefix == '31-0' || value.prefix == '32-0'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.owner += (parseFloat($scope.amount));
                            } 
                        
                        });
                        $scope.debt += $scope.payable + $scope.payabletax;
                        $scope.totalAsset = $scope.asset1 + $scope.asset2;
                        $scope.debtandowner = $scope.owner + $scope.debt;
                    }

                    else if($scope.selectedStmType == 'Stmspe1' || $scope.selectedStmType == 'Stmspe2'){
                        angular.forEach($scope.bigdata, function(value, key){
                            if(value.prefix == '11-2' || value.prefix == '12-2' || value.prefix == '13-2' ){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.asset1 += (parseFloat($scope.amount));

                            } else if(value.prefix == '16-2'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.asset2 += (parseFloat($scope.amount));

                            } else if(value.prefix == '23-2' || value.prefix == '24-2' || value.prefix == '25-2' ){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.debt += (parseFloat($scope.amount));

                            }  else if(value.prefix == '31-1' || value.prefix == '32-1' ){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.owner += (parseFloat($scope.amount));
                            }
                            //$scope.debt += $scope.payable + $scope.payabletax;
                        });
                        $scope.totalAsset = $scope.asset1 + $scope.asset2;
                        $scope.debtandowner = $scope.owner + $scope.debt;
                    }

                    else if($scope.selectedStmType == 'Stmprofit1' || $scope.selectedStmType == 'Stmprofit2' || $scope.selectedStmType == 'Stmprofit3'){
                        angular.forEach($scope.bigdata, function(value, key){
                            if(value.account_no == '41-1100' || value.account_no == '41-1200' || value.account_no == '41-1300'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.b6 += (parseFloat($scope.amount));

                            } else if(value.account_no == '41-1110' || value.prefix == '41-1210' || value.prefix == '41-1310'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.b7 += (parseFloat($scope.amount));

                            } else if(value.account_no == '51-1100' || value.account_no == '51-1200' || value.account_no == '51-1300'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.b10 += (parseFloat($scope.amount));

                            } else if(value.account_no == '51-1110' || value.account_no == '51-1210' || value.account_no == '51-1310'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.b11 += (parseFloat($scope.amount));

                            }else if(value.prefix == '51-2'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.b13 += (parseFloat($scope.amount));
                            } else if(value.prefix == '14-0'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.b15 += (parseFloat($scope.amount));
                            } else if(value.prefix == '52-0' || value.prefix == '52-1' || value.prefix == '52-2' || value.prefix == '52-3'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.expense1 += (parseFloat($scope.amount));
                            } else if(value.prefix == '53-1' || value.prefix == '53-2' || value.prefix == '53-3' || value.prefix == '53-4' || value.prefix == '53-5'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.expense2 += (parseFloat($scope.amount));
                            } else if(value.prefix == '42-1' || value.prefix == '43-1' || value.prefix == '44-1' ){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.vat += (parseFloat($scope.amount));
                            } else if(value.prefix == '63-1'){
                                if(value.total_amount == 'null'){$scope.amount = 0;}
                                else{$scope.amount = value.total_amount;}
                                $scope.incometax += (parseFloat($scope.amount));
                            }
                            //else if(value.prefix == '31-1' || value.prefix == '32-1' ){
                            //     if(value.total_amount == 'null'){$scope.amount = 0;}
                            //     else{$scope.amount = value.total_amount;}
                            //     $scope.owner += (parseFloat($scope.amount));
                            // }
                        });
                        $scope.income = $scope.b6 -$scope.b7;
                        $scope.costsale = $scope.b10 -$scope.b11;
                        $scope.coststock = $scope.b13 +$scope.costsale;
                        $scope.coststock2 = $scope.coststock - $scope.b15;
                        $scope.grossprofit = $scope.income - $scope.coststock2;
                        $scope.operprofit =  $scope.grossprofit - $scope.expense1 - $scope.expense2;
                        $scope.profitnovat = $scope.operprofit - $scope.vat;
                        $scope.netprofit = $scope.profitnovat - $scope.incometax;

                    }
                
            }
            
        } 
    });
    



</script>