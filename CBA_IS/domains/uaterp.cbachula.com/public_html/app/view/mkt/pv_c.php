<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบชำระ supplier/PV-C</h2>

      
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <label for="UserNameTextbox">วันที่เบิก</label>
                <input type="date" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id">
                <br>
                <label for="UserNameTextbox">ผู้เบิกเงิน</label>
                <input type="text" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase">
                <br>
                <label for="IDTextbox">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="IDTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase">
                <br>
                <label for="IDTextbox">LINE ID พนักงาน</label>
                <input type="text" class="form-control" id="IDTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase">
                <br>
                <label for="MoneyTextbox">จำนวนเงิน</label>
                <input type="number" class="form-control" id="MoneyTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase">    
                <br>
                <label for="TaxIDTextbox">เลขที่ผู้เสียภาษีอากร</label>
                <input type="text" class="form-control" id="TaxIDTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase">
                <br>
                <label for="BankDropdown">เลขที่ผู้เสียภาษีอากร</label>
                <select class="form-control ng-pristine ng-valid ng-empty ng-touched" ng-model="selectedProductType" id="BankDropdown" onchange='checkBank(this.value);'>
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
                        <input type="text" class="form-control" name="color"/>
                    </div>
                <br>
                <label>ชื่อบัญชีธนาคารที่รับโอน</label>
                <input type="text" class="form-control" id="BankNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id" >
                
                <br> 
                <label>ผู้ร้บรอง</label>
                <input type="text" class="form-control" id="BankNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id" >
                
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
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase"></td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase"></td>
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
    function addRows(){
        
        const element = document.getElementById("row1");
        element.insertAdjacentHTML('afterend',`<tr id ="row1">
                            <td style ="border: 3px solid #dddddd;">
                                <input type="date" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id">
                            </td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="UserNameTextbox" ng-change="addSeller()" ng-model="seller_employee_id" style="text-transform:uppercase"></td>
                            <td style ="border: 3px solid #dddddd;">Germany</td>
                        </tr>`);

    }
</script>