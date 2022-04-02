<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init = "getDetail()">

        <h2 class="mt-3">ใบขอเบิกค่าใช้จ่าย</h2>
        <div class="row mx-0 mt-2">
            
                    <table class="table table-hover my-1" ng-show="PVCs.length == 0">
                        <tr ng-show="!isLoad">
                            <th>ไม่มีใบเบิกค่าใช้จ่าย ที่ยังไม่ได้ออก PV</th>
                        </tr>
                        <tr ng-show="isLoad">
                            <th>
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="PVCs.length != 0">
                        <tr>
                            <th>เลข PVC</th>
                            <th>วันที่</th>
                            <th>ใบเบิกค่าใช้จ่าย</th>
                            <th>ใบกำกับภาษี / บิลเงินสด / ใบเสนอราคา</th>
                            <th>ผู้ขอเบิก</th>
                           
                        </tr>
                        
                        <tr ng-repeat="pvc in PVCs track by $index" ng-click="getPVCDetail(pvc.PVC_No)">
                            <td style="text-align: center;">{{pvc.PVC_No}}</td>
                            <td style="text-align: center;">{{pvc.Withdraw_Date}}</td>
                            
                            <td style="text-align: center;">
                            <a href="/file/re_req/{{pvc.PVC_No}}" target="_blank">FILE</a>
                            </td>
                            <td style="text-align: center;">
                                <a href="/acc/payment_voucher/get_quotation/{{pvc.PVC_No}}" target="_blank">{{pvc.quotation_name}}</a><br>  
                            </td>
                            <td style="text-align: center;">{{pvc.Withdraw_Name}}</td>
                            
                            
                        </tr>
                    </table>
               
        </div>
        <h2 class="mt-3">เพิ่มรายละเอียด</h2>
    <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
        <div class="card-body">
                <label><h3>หลักฐานในการขอเบิกเงิน</h3></label><br>
                <form id="checkBox">
                    <div>
                        <input type="checkbox"   value="tax">
                        <label for="option1"> ใบกำกับภาษี</label><br>
                        <input type="checkbox"  value ="billAndID">
                        <label for="option2"> บิลเงินสด + นามบัตรหริอสำเนาบัตรประชาชนเจ้าของร้าน</label><br>
                        <input type="checkbox"   value ="quotation">
                        <label for="option3"> ใบเสนอราคา</label><br>
                    </div>
                    <div>
                        <label><h3>จ่ายออกจากโครงการ</h3></label><br>
                        <input type="checkbox"  value="project1">
                        <label for="option1"> โครงการ 1 </label><br>
                        <input type="checkbox"  value="project2">
                        <label for="option2"> โครงการ 2</label><br>
                        <input type="checkbox" value="project3">
                        <label for="option3"> โครงการ 3</label><br>
                        <input type="checkbox"  value="SPJ1">
                        <label for="option3"> SPJ 1</label><br>
                        <input type="checkbox"  value="SPJ2">
                        <label for="option3"> SPJ 2</label><br>
                    </div>
                </form>
                <div>
                    <label for="re_req_number_form">เลขที่ใบขอเบิกค่าใช้จ่าย (EX-)</label><br>
                    <input type="text" ng-model="re_req_number" id="re_req_number_form" class="form-control">
                </div>
                <div ng-show="PVCDetail.length!= 0">
                    <label><h3>รายละเอียด</h3></label><p><h3>{{currentNo}}</h3></p>
                    <table class="table table-hover my-1" style="border-collapse: collapse; width: 100%;">
                        <tr>
                            
                            <th>วันที่เกิดค่าใช้จ่าย</th>
                            <th>วัตถุประสงค์และรายละเอียดค่าใช้จ่าย</th>
                            <th>จำนวนเงิน(บาท)</th>
                        </tr>
                        <tr ng-repeat = "item in tableDetails">
                            <td style="text-align:center;"><b> {{item.date}}</b> </td>
                            <td style="text-align:center;"><b> {{item.details}}</b> </td>
                            <td style="text-align:center;"><b> {{item.money}}</b></td>
                        </tr>
                       
                    </table>
                </div>
                <div ng-show="PVCDetail.length == 0">
                    <p><h3>กรุณาเลือกไฟล์ PVC</h3></p>

                </div>
                <div>
                    <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="submit(currentNo)">ยืนยัน</button>
                </div>
                
        </div>
    </div>
        
     
        
        <script>
            addModal('formValidate1', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้กรอกวันที่');
            addModal('formValidate2', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'Supplier นี้สามารถขอภาษีซื้อได้');
            addModal('formValidate3', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'เลือก Supplier ก่อนครับผม');
            addModal('formValidate4', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เลือกประเภทการสั่งจ่าย');
            addModal('formValidate5', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มรายละเอียดสำหรับออกใบสำคัญสั่งจ่าย');
            addModal('formValidate6', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มว่าสั่งจ่ายใคร');
            addModal('formValidate7', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มเลขที่ใบสำคัญ');
            addModal('formValidate8', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้ใส่จำนวนเงินสั่งจ่าย');
            addModal('formValidate9', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เลือกว่าสั่งจ่ายในนามอะไร');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.PVCs = []
        $scope.PVCDetail =[]
        $scope.tableDetails=[]
        $scope.details={}
        $scope.currentNo = ''
        $scope.re_req_number='';
        $scope.getDetail = function(){
            $http.get('/fin/pvc/get_PVCs').then(function(response){$scope.PVCs = response.data; $scope.isLoad = false;console.log($scope.PVCs)});
        }
        $scope.getPVCDetail = function(PVC_No){
            $http.get(`/fin/pvc/get_PVC_Detail/${PVC_No}`).then(function(response){$scope.PVCDetail = response.data; $scope.isLoad = false;
                console.log($scope.PVCDetail);$scope.tableDetails = JSON.parse($scope.PVCDetail[0].Table_Of_Details);
                $scope.currentNo = $scope.PVCDetail[0]["PVC_No"]
               
                console.log( $scope.currentNo )
            });
            

        }
        $('input[type="checkbox"]').on('change', function() {
        $(this).siblings('input[type="checkbox"]').prop('checked', false);

        $scope.submit = function(PVC_No){
            const url = "https://uaterp.cbachula.com/home"
            console.log(PVC_No)
            const options = document.querySelectorAll("input[type='checkbox']:checked")
          
            const details = {"proof":options[0].value,"project":options[1].value};
            
            $scope.details = details
            console.log($scope.details["proof"])
           $.post(`pvc/post_additional_data/${PVC_No}`,{
               proof: $scope.details["proof"], 
               project:$scope.details["project"],
               re_req_number:$scope.re_req_number 

           },function(data){
               console.log(data)
           })
           window.location.replace(url);
           
        }
});
  	});

</script>