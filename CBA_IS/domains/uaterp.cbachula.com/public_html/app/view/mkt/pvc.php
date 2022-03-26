<!DOCTYPE html>
<html>

    <body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบชำระ supplier/PV-C</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <label for="dateTextbox">วันที่เบิก</label>
                <input type="date" class="form-control" id="dateTextbox" ng-change="getWithdrawDate()" ng-model="withdrawDate">
                <br>
                <label for="withdrawNameTextbox">ผู้เบิกเงิน</label>
                <input type="text" class="form-control" id="withdrawNameTextbox" ng-change="addWithdrawName()" ng-model="withdrawName" >
                <br>
                <label for="iDTextbox">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="iDTextbox" ng-blur ="getEmployeeId()" ng-model="employeeId">
                <br>
                <label for="lineIdTextbox">LINE ID พนักงาน</label>
                <input type="text" class="form-control" id="lineIdTextbox"  ng-model="employeeLine">
                <br>
                <label for="taxIDTextbox">เลขที่ผู้เสียภาษีอากร</label>
                <input type="text" class="form-control" id="taxIDTextbox" ng-model="taxNumber">
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
                        <input type="text" class="form-control" ng-model="otherBankName"/>
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
                <form id="form">
                <br>
                   <label>ใบเสนอราคา(pdf)</label>
                   <input type="file" class="form-control-file" id="quotation" name="Quotation_pic">
                    
                    <br>
                </form>
                    
                    <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="submit()">ยืนยัน</button>
                     
           
           
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
         addModal('formValidate0', 'ใบชำระ supplier/PV-C', 'กรุณาใส่วันที่เบิก');
         addModal('formValidate1', 'ใบชำระ supplier/PV-C', 'กรุณาใส่ชื่อผู้เบิกเงิน');
         addModal('formValidate2', 'ใบชำระ supplier/PV-C', 'กรุณาใส่รหัสพนักงาน');
         addModal('formValidate3', 'ใบชำระ supplier/PV-C', 'กรุณาใส่ LINE ID พนักงาน')
         addModal('formValidate4', 'ใบชำระ supplier/PV-C', 'กรุณาใส่เลขที่ผู้เสียภาษีอากร');
         addModal('formValidate5', 'ใบชำระ supplier/PV-C', 'กรุณาเลือกธนาคาร');
         addModal('formValidate6', 'ใบชำระ supplier/PV-C', 'กรุณาใส่ชื่อบัญชีธนาคารที่รับโอน');
         addModal('formValidate7', 'ใบชำระ supplier/PV-C', 'กรุณาใส่ชื่อผู้ร้บรอง');
         addModal('formValidate8', 'ใบชำระ supplier/PV-C', 'วันที่ผิด');
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
       

        // Date utility functions
        function convertDate(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        }
        function processDate (date){
            let currentDate = new Date();
            if(currentDate.getTime()<date.getTime()) {
                console.log("Error wrong time");
                $('#formValidate8').modal('toggle');
                return;
            }
            else {
                return convertDate(date);
            }
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
            return $scope.bankName;
        };
        $scope.getTaxNumber=function(){
            return $scope.taxNumber;
        };
        $scope.getBankBookName=function(){
            return $scope.bankBookName;
        };
        $scope.getAuthorizerName=function(){
            return $scope.authorizerName
        }; 
        $scope.getTableData=function(){
            return processTable();
        }; 
        
    
       

        
       

    
        $scope.submit = function(){
            if($scope.withdrawDate ==='') $('#formValidate0').modal('toggle');
            else if($scope.withdrawName==='') $('#formValidate1').modal('toggle');
            else if($scope.employeeId ==='') $('#formValidate2').modal('toggle');
            else if($scope.employeeLine==='') $('#formValidate3').modal('toggle');
            else if($scope.bankName ==='')$('#formValidate4').modal('toggle');
            else if($scope.taxNumber ===''&& typeof $scope.taxNumber != Number)$('#formValidate5').modal('toggle');
            else if( $scope.bankBookName==='')$('#formValidate6').modal('toggle');
            else if($scope.authorizerName ==='')$('#formValidate7').modal('toggle');
            else{ 
                var confirmModal = addConfirmModal('confirmModal', 'Confirm',"ยืนยันข้อมูล",'postQuote()'); 
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
                // window.open("https://uaterp.cbachula.com/acc/confirm_payment_voucher");
                
                
            }
            
          
        }
        // $scope.postQuotationFile = function(){
        //    var data = new FormData();
        //     data.append('quotationFile', $('#quotationFile')[0].files[0]);
        //     $.ajax({
		// 		url: '/mkt/upload_quotation_file/post_quotation_file',
		// 		data: data,
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		method: 'POST',
		// 		type: 'POST',
		// 		success: function () {
		// 			// addModal('successModal', 'Upload Quotation', 'อัพโหลดไฟล์ ใบเสนอราคา เรียบร้อยแล้ว');
		// 			// $('#successModal').modal('toggle');
					
		// 		}
		// 	});
        // }
        $scope.postQuote = function(){
            $('#confirmModal').modal('hide');
            
            var formData = new FormData(form);
            console.log(form)
            formData.forEach(data=>console.log(data))

            $.ajax({
                url: 'pvc/post_quotation',
                type: "POST",
                dataType: 'json',
                method: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                }).done(function (data) {
                console.log(data);
                
                console.log(data['success']);
                if(data['success']) {
                    $scope.rq_no = data['rq_no'];
                    $scope.postPVC();
                } else {
                    addModal('uploadFailModal', 'upload imgae', ' 1 fail');
                    $('#uploadFailModal').modal('toggle');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log('ajax.fail');
                console.log(textStatus);
                console.log(errorThrown);
                addModal('uploadFailModal', 'upload imgae', '2 fail');
                $('#uploadFailModal').modal('toggle');
            });    

        }
    

        $scope.postPVC = function(){
            $('#confirmModal').modal('hide');
            $.post("pvc/post_PVC",{
                PVC_No : $scope.rq_no,
                withdrawDate :$scope.getWithdrawDate(),
                withdrawName:$scope.getWithdrawName(),
                employeeId : $scope.getEmployeeId(),
                employeeLine: $scope.getEmployeeLine(),
                bankName : $scope.getBankName(),
                taxNumber: $scope.getTaxNumber(),
                bankBookName: $scope.getBankBookName(),
                authorizerName: $scope.getAuthorizerName() ,
                table : $scope.getTableData(),
                
            },function(data,status){
                addModal('successModal', 'เบิกเงินรองจ่าย','สำเร็จ','toMainMenu()');
                $('#successModal').modal('toggle');
                
            })

        }
        $scope.toMainMenu = function(){
            window.location.assign('https://uaterp.cbachula.com/home');
        }
        // $scope.postQuotationItems = function() {
            
           
		// 		var data = new FormData();
        //         data.append('quotationFile', $('#quotationFile')[0].files[0]);
        //         data.append('PVC_No', $scope.quotationFile);
                
		// 	$.ajax({
		// 		url: '/mkt/pvc/post_quotation_file',
		// 		data: data,
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		method: 'POST',
		// 		type: 'POST',
		// 		success: function () {
		// 			// addModal('successModal', 'Upload IRD', 'อัพโหลดไฟล์ IRD เรียบร้อยแล้ว');
		// 			// $('#successModal').modal('toggle');
		// 			// $('#successModal').on('hide.bs.modal', function (e) {
		// 			// 	window.location.assign('/scm/upload_ird');
		// 			// });
		// 		}
		// 	});
                
            
            
        // }
        
    })
    </script>
