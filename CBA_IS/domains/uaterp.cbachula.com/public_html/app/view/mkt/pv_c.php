<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบชำระ supplier/PV-C</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <label for="dateTextbox">วันที่เบิก</label>
                <input type="date" class="form-control" id="dateTextbox" ng-change="addDate()" ng-model="withdrawDate">
                <br>
                <label for="withdrawNameTextbox">ผู้เบิกเงิน</label>
                <input type="text" class="form-control" id="withdrawNameTextbox" ng-change="addWithdrawName()" ng-model="withdrawName" style="text-transform:uppercase">
                <br>
                <label for="iDTextbox">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="iDTextbox" ng-change="addSeller()" ng-model="employeeId" style="text-transform:uppercase">
                <br>
                <label for="lineIdTextbox">LINE ID พนักงาน</label>
                <input type="text" class="form-control" id="lineIdTextbox" ng-change="addSeller()" ng-model="employeeLine" style="text-transform:uppercase">
                <br>
                <label for="taxIDTextbox">เลขที่ผู้เสียภาษีอากร</label>
                <input type="text" class="form-control" id="taxIDTextbox" ng-change="addSeller()" ng-model="taxNumber" style="text-transform:uppercase">
                <br>
                <label for="BankDropdown">ธนาคาร</label>
                <select class="form-control ng-pristine ng-valid ng-empty ng-touched" ng-model="bankName" id="bankDropdown" onchange="checkBank(this.value)"; ng-change = "addBankName()">
                            <option value="" selected="selected">เลือกธนาคาร</option>

                            <option value="กสิกรไทย">กสิกรไทย</option>

                            <option value="ไทยพาณิชย์">ไทยพาณิชย์</option>

                            <option value="กรุงไทย">กรุงไทย</option>

                            <option value="TMB">TMB</option>

                            <option value="others">อื่นๆ</option>
                        </select>
                        <br>
                    <div id="otherBank" style='display:none;'>
                        <label>Enter bank name</label>
                        <br>
                        <input type="text" class="form-control" ng-model="otherBankName" style="text-transform:uppercase"/>
                    </div>
                <br>
                <label>ชื่อบัญชีธนาคารที่รับโอน</label>
                <input type="text" class="form-control" id="bookBankNameTextbox" ng-change="addSeller()" ng-model="bankBookName" >
                
                <br> 
                <label>ผู้ร้บรอง</label>
                <input type="text" class="form-control" id="authorizerNameTextbox" ng-change="addSeller()" ng-model="authorizerName" >
                
                <br> 

                <div class="card-body">
                    <table id ="inputTable">
                        <tr>
                            <th style ="border: 3px solid #dddddd;">วันที่เกิดค่าใช้จ่าย</th>
                            <th style ="border: 3px solid #dddddd;">วัตถุประสงค์/รายละเอียดค่าใช้จ่าย</th>
                            <th style ="border: 3px solid #dddddd;">จำนวนเงิน(บาท)</th>
                        </tr>
                        <tr id ="row1">
                            <td style ="border: 3px solid #dddddd;">
                                <input type="date" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id">
                            </td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="detailsTextbox1" ng-change="addSeller()" ng-model="detailsTextbox1" style="text-transform:uppercase"></td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="moneyTextbox1" ng-change="addSeller()" ng-model="moneyTextbox1" style="text-transform:uppercase"></td>
                        </tr>
                        
                    </table>
                    <br>
                    <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" onclick="addRows()">เพิ่มช่อง</button>
                </div>
                <br>
                    <label>ใบเสนอราคา(pdf)</label>
                    <input class="form-control-file" type="file" id="ivFile">
                    <br>
                    <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" onclick="goToAcc()">ยืนยัน</button>
            </div>
           
            
        </div>
</body>
<script>
    
    function checkBank(bank){
        const element = document.getElementById("otherBank");
        if(bank=='others'){
            element.style.display='block';
        }
        else {
            element.style.display='none';    
        }
    }
    function goToAcc(){
        window.location.href = "https://uaterp.cbachula.com/acc/confirm_payment_voucher";
    }
    let num = 1;
    function addRows(){
        const element = document.getElementById(`row${num}`);
        num++;
        element.insertAdjacentHTML('afterend',`<tr id ="row${num}">
                            <td style ="border: 3px solid #dddddd;">
                                <input type="date" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id">
                            </td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="detailsTextbox${num}" ng-change="addSeller()" ng-model="detailsTextbox${num}" style="text-transform:uppercase"></td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="moneyTextbox${num}" ng-change="addSeller()" ng-model="moneyTextbox${num}" style="text-transform:uppercase"></td>
                        </tr>`);

        
       
    }
</script>
<script>
    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.withdrawDate='';
        $scope.withdrawName='';
        $scope.employeeId ='';
        $scope.employeeLine=''
        $scope.bankName ='';
        $scope.taxNumber='';
        $scope.bankBookName='';
        $scope.authorizerName='';
        $scope.addDate = function(){
            console.log($scope.withdrawDate);

        }
        $scope.addWithdrawName = function(){
            console.log($scope.withdrawName)

        }
        $scope.addBankName = function(){
            console.log($scope.bankName)
           
        }
        

    })
</script>