<!DOCTYPE html>
<html>

    <body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบขอเบิกค่าใช้จ่าย / Reimbursement Request</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class = "col-md-6">
                        <label for="dateTextbox">วันที่เบิก</label>
                        <input type="date" class="form-control" id="dateTextbox" ng-change="getWithdrawDate()" ng-model="withdrawDate">
                    </div>
                    <div class = "col-md-6">
                        <label for="dateTextbox">วันครบกำหนดชำระเงิน</label>
                        <input type="date" class="form-control" id="dateTextbox" ng-model="dueDate" ng-change = "dueDateValid()">
                        
                    </div>
                    
                </div>
                
                <div class = "row mx-0">
                    <div class = "col-md-6">
                            <label for="withdrawNameTextbox">ผู้เบิกเงิน</label>
                            <input type="text" class="form-control" id="withdrawNameTextbox" ng-change="addWithdrawName()" ng-model="withdrawName" >
                            
                    </div>
                    <div class = "col-md-6">
                        <label for="iDTextbox">รหัสพนักงาน</label>
                        <input type="text" class="form-control" id="iDTextbox" ng-blur ="getEmployeeId()" ng-model="employeeId">
                    </div>
                   
                </div>
                <div class = "row mx-0"> 
                    <div class = "col-md-4">
                        <label for="lineIdTextbox">LINE ID พนักงาน</label>
                        <input type="text" class="form-control" id="lineIdTextbox"  ng-model="employeeLine">
                        <br>
                    </div>
                   
                    <div class = "col-md-4">
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
                                <input type="text" class="form-control" ng-model="otherBankName"/>
                            </div>
                        </div>
                        <br>
                        <div class = "col-md-4">
                        <label for="branchTextbox">สาขา</label>
                        <input type="text" class="form-control" id="branchTextBox" ng-model="bankBranch">
                    </div>
                </div>
                <div class="row mx-0">
                    <div class = "col-md-6">
                        <label>ชื่อบัญชีธนาคารที่รับโอน</label>
                        <input type="text" class="form-control" id="bookBankNameTextbox"  ng-model="bankBookNumber" >

                        <br>
                    </div>
                    <div class = "col-md-4">
                        <label>เลขที่บัญชีธนาคารที่รับโอน</label>
                        <input type="text" class="form-control" id="bookBankNameTextbox"  ng-model="bankBookName" >
                        
                        <br>
                    </div>
                  </div>    
                        
             
                <div class="card-body">
                    <table id ="inputTable">
                        <tr>
                            <th>วันที่เกิดค่าใช้จ่าย</th>
                            <th>วัตถุประสงค์/รายละเอียดค่าใช้จ่าย</th>
                            <th>จำนวนเงิน(บาท)</th>
                        </tr>
                        <tr id ="row1">
                            <td >
                                <input type="date" class="form-control" id="dateBox1"  ng-model="date1">
                            </td>
                            <td ><input type="text" class="form-control" id="detailsTextbox1"  ng-model="detailsTextbox1" ></td>
                            <td><input type="text" class="form-control" id="moneyTextbox1"  ng-model="moneyTextbox1" ></td>
                        </tr>
                        
                    </table>
                    <br>
                    <button type="button" class="btn btn-default btn-block" id="addRows" onclick="addRows()">เพิ่มช่อง</button>
                </div>
                <form id="form" >
                    <br>
                    <label>ใบเสนอราคา (KEEP BELOW 10 MB)</label>
                    <input type="file" class="form-control-file" id="quotation" name="quotation_pic">
                    
                    <br>
                </form>
                    
                    <button type="button" class="btn btn-default btn-block btn-transition" id="buttonConfirmDetail" ng-click="submit()">ยืนยัน</button>
                    
                </div>
           
            
        </div>
</body>
</html>
<style>
    input{
      text-transform:uppercase;
    }
    td{
        border: 3px solid #dddddd;
        text-align: center;
    }
    th{
        border: 3px solid #dddddd;
        text-align: center;
    }
    .btn-transition{
        transition: 0.5s;

}
    .btn-transition:hover{
        background-color: #44b853;

    }
</style>

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
    
    let num = 1;
    function addRows(){
        const element = document.getElementById(`row${num}`);
        num++;
        element.insertAdjacentHTML('afterend',`<tr id ="row${num}">
                            <td style ="border: 3px solid #dddddd;">
                                <input type="date" class="form-control" id="dateBox${num}" ng-change="addSeller()" ng-model="date${num}">
                            </td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="detailsTextbox${num}" ng-change="addSeller()" ng-model="detailsTextbox${num}" style="text-transform:uppercase"></td>
                            <td style ="border: 3px solid #dddddd;"><input type="text" class="form-control" id="moneyTextbox${num}" ng-change="addSeller()" ng-model="moneyTextbox${num}" style="text-transform:uppercase"></td>
                        </tr>`);

        
       
    }
    function getNumOfRows(){
        return num;
    }
</script>
<script>
         addModal('formValidate0', 'ใบเบิกค่าใช้จ่าย', 'กรุณาใส่วันที่เบิก');
         addModal('formValidate1', 'ใบเบิกค่าใช้จ่าย', 'กรุณาใส่ชื่อผู้เบิกเงิน');
         addModal('formValidate2', 'ใบเบิกค่าใช้จ่าย', 'กรุณาใส่รหัสพนักงาน');
         addModal('formValidate3', 'ใบเบิกค่าใช้จ่าย', 'กรุณาใส่ LINE ID พนักงาน')
         
         addModal('formValidate5', 'ใบเบิกค่าใช้จ่าย', 'กรุณาเลือกธนาคาร');
         addModal('formValidate6', 'ใบเบิกค่าใช้จ่าย', 'กรุณาใส่ชื่อบัญชีธนาคารที่รับโอน');
         
         addModal('formValidate8', 'ใบเบิกค่าใช้จ่าย', 'กรุณาใส่วันครบกำหนดชำระเงิน');
         addModal('formValidate9', 'ใบเบิกค่าใช้จ่าย', 'Invalid due date');
         
</script>
<script>
    app.controller('moduleAppController', function($scope, $http, $compile) {
    
        $scope.withdrawDate='';
        $scope.dueDate='';
        $scope.withdrawName='';
        $scope.employeeId ='';
        $scope.employeeLine=''
        $scope.bankName ='';
        $scope.taxNumber='';
        $scope.bankBookNumber='';
        $scope.bankBookName='';
        $scope.authorizerName=''; 
        $scope.createdDate= new Date();
        $scope.valid = false;
        $scope.bankBranch =''

        // Date utility functions
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
        // Data processing function
        function processTable(){
            let number = getNumOfRows();
            let dateArray =[];
            let detailsArray =[];
            let moneyArray =[];
            for (let index = 1; index <= number; index++) {
                const date = document.getElementById(`dateBox${index}`).value;
                dateArray.push(date);
            }
           
            for (let index = 1; index <= number; index++) {
                const details= document.getElementById(`detailsTextbox${index}`).value;
                detailsArray.push(details);
            }
          
            for (let index = 1; index <= number; index++) {
                const money = document.getElementById(`moneyTextbox${index}`).value;
                moneyArray.push(money);
            }
           
            let resultObjectArray = []
            for(let index = 0; index < number; index++){
                const entry = {
                    date:dateArray[index],
                    details:detailsArray[index],
                    money:moneyArray[index]
                }
                resultObjectArray.push(entry);
                
            }

            return JSON.stringify(resultObjectArray);           
        }


        $scope.getWithdrawDate = function(){
            return processDate($scope.withdrawDate);
        }
        $scope.getCreatedDate = function(){
            return processDate($scope.createdDate);
        }
        $scope.getDueDate = function(){
            return processDate($scope.dueDate);
        }
        $scope.getWithdrawName = function(){
            return $scope.withdrawName
        }
        $scope.getEmployeeId  = function(){
            return $scope.employeeId;
        }
        $scope.getEmployeeLine = function(){
            return  $scope.employeeLine;
        }
        $scope.getBankName =function(){
            return `${$scope.bankName} ${$scope.bankBranch}`;
        };
        $scope.getTaxNumber=function(){
            return $scope.taxNumber;
        };
        $scope.getBankBookName=function(){
            return $scope.bankBookName;
        }; 
        $scope.getBankBookNumber=function(){
            return $scope.bankBookNumber;
        }; 
        $scope.getTableData=function(){
            return processTable();
        }; 

        $scope.dueDateValid = function(){
            if($scope.dueDate.getTime()<$scope.createdDate.getTime())$('#formValidate9').modal('toggle');
        }
        
    
        $scope.validateInput = function(){
            if($scope.withdrawDate ==='') $('#formValidate0').modal('toggle');
            else if($scope.withdrawName==='') $('#formValidate1').modal('toggle');
            else if($scope.employeeId ==='') $('#formValidate2').modal('toggle');
            else if($scope.employeeLine==='') $('#formValidate3').modal('toggle');
            else if($scope.bankBookName==='')$('#formValidate6').modal('toggle');
      
            else if($scope.dueDate ==='')$('#formValidate8').modal('toggle');
            else if($scope.dueDate.getTime()<$scope.createdDate.getTime())$('#formValidate9').modal('toggle');
            else $scope.valid = true;
        }

        $scope.submit = function(){
                $scope.validateInput();
                if($scope.valid === true){       
                var confirmModal = addConfirmModal('confirmModal', 'Confirm',"ยืนยันข้อมูล","postQuote()"); 
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
       
        $scope.postQuote = function(){
            $('#confirmModal').modal('hide');
                var formData = new FormData(form);
   
            $.ajax({
                url: "reimbursement_request/post_quotation",
                type: "POST",
                
                method: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                }).done(function (data) {
                    $scope.rq_no = data;
                    console.log(data)
                    $scope.postReReq();
                
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                console.log(textStatus);
                console.log(errorThrown);
                addModal('uploadFailModal', 'upload image','file size to big keep file size below 10 MB');
                $('#uploadFailModal').modal('toggle');
            });    
                
            }
            
        
    
        $scope.postReReq = function(){
            $('#confirmModal').modal('hide');
                $.post("reimbursement_request/post_reimbursement_Request",{
                post:true,
                re_req_no : $scope.rq_no,
                withdrawDate :$scope.getWithdrawDate(),
                withdrawName:$scope.getWithdrawName(),
                employeeId : $scope.getEmployeeId(),
                employeeLine: $scope.getEmployeeLine(),
                bankName : $scope.getBankName(),
                taxNumber: $scope.getTaxNumber(),
                bankBookName: $scope.getBankBookName(),
                bankBookNumber: $scope.getBankBookNumber(),
                dueDate:$scope.getDueDate(),
                createdDate: $scope.getCreatedDate(),
                table : $scope.getTableData(),
                company_code:"3"                
            }).done(function(data){ 
                console.log(data)
                addModal('successModal', 'เบิกเงินรองจ่าย',`บันทึก ${data} สำเร็จ`);
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {  $scope.toMainMenu(); });
            }).fail(function(a,b,c){
                console.log(a,b,c)
            })
           

        }
            
        
       
        
        $scope.toMainMenu = function(){
            const url = "https://uaterp.cbachula.com/home";
            window.location.assign(url);
        }
        
    });
    </script>
