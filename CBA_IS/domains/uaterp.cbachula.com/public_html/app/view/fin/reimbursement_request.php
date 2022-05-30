<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init = "getDetail()">
        <h2 class="mt-3">ใบขอเบิกค่าใช้จ่าย</h2>
        <div class="row mx-0 mt-2">
            
                    <table class="table table-hover my-1" ng-show="re_reqs.length == 0">
                        <tr ng-show="!isLoad">
                            <th>Nothing to see!</th>
                        </tr>
                        <tr ng-show="isLoad">
                            <th>
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="re_reqs.length != 0">
                        <tr>
                            <th>เลขที่ใบขอเบิกค่าใช้จ่าย</th>
                            <th>วันที่</th>
                            <th>ใบเบิกค่าใช้จ่าย</th>
                            <th>ใบกำกับภาษี / บิลเงินสด / ใบเสนอราคา</th>
                            <th>ผู้ขอเบิก</th>
                           
                        </tr>
                        
                        <tr ng-repeat="re_req in re_reqs track by $index" ng-click="getReReqDetail(re_req.re_req_no)">
                            <td>{{re_req.re_req_no}}</td>
                            <td>{{re_req.withdraw_date}}</td>
                            
                            <td >
                            <a href="/file/re_req/{{re_req.re_req_no}}" target="_blank">{{re_req.re_req_no}}</a>
                            </td>
                            <td>
                                <a href="/acc/payment_voucher/get_quotation/{{re_req.re_req_no}}" target="_blank">{{re_req.quotation_name}}</a><br>  
                            </td>
                            <td>{{re_req.withdraw_name}}</td>
                            
                            
                        </tr>
                    </table>
               
        </div>

    <div ng-show="selected === true">    
            <h2 class="mt-3">เพิ่มรายละเอียด {{currentNo}}</h2>
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
                
                    <div ng-show="re_reqDetail.length!= 0"><br>
                        <label><h3>รายละเอียด</h3></label>
                        <table class="table table-hover my-1" style="border-collapse: collapse; width: 100%;">
                            <tr>
                                
                                <th>วันที่เกิดค่าใช้จ่าย</th>
                                <th>วัตถุประสงค์และรายละเอียดค่าใช้จ่าย</th>
                                <th>จำนวนเงิน(บาท)</th>
                            </tr>
                            <tr ng-repeat = "item in tableDetails">
                                <td><b> {{item.date}}</b> </td>
                                <td><b> {{item.details}}</b> </td>
                                <td><b> {{item.money}}</b></td>
                            </tr>
                        
                        </table>
                    </div>
                    <div ng-show="re_reqDetail.length == 0">
                        <p><h3>กรุณาเลือกไฟล์ Reimbursement Request</h3></p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="submit(currentNo)">ยืนยัน</button>
                    </div>
                    
            </div>
        </div>
    </div>
     
        
        <script>
            addModal('formValidate1', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'Please Select a document');
        </script>
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    </div>
</body>
</html>
<style>
    td { border-bottom: 1px solid lightgray; text-align: center;}
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>
<script>
    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.re_reqs = []
        $scope.selected = false;
        $scope.re_reqDetail =[]
        $scope.tableDetails=[]
        $scope.details={}
        $scope.currentNo = ''
        $scope.re_req_number='';
        $scope.getDetail = function(){
            $http.get('/fin/reimbursement_request/get_re_req').then(function(response){$scope.re_reqs = response.data; $scope.isLoad = false;});
        }
        $scope.getReReqDetail = function(re_req_no){
            $scope.selected = true;
            $http.get(`/fin/reimbursement_request/get_re_req_Detail/${re_req_no}`).then(function(response){$scope.re_reqDetail = response.data; $scope.isLoad = false;
                console.log($scope.re_reqDetail);$scope.tableDetails = JSON.parse($scope.re_reqDetail[0].details);
                $scope.currentNo = $scope.re_reqDetail[0]["re_req_no"]
               
                console.log( $scope.currentNo )
            });
            
        }
        $('input[type="checkbox"]').on('change', function() {
        $(this).siblings('input[type="checkbox"]').prop('checked', false);
        $scope.submit = function(re_req_no){
           
            
            
            if(re_req_no=="") {
                $('#formValidate1').modal('toggle');
            }
            const options = document.querySelectorAll("input[type='checkbox']:checked")
          
            const details = {"proof":options[0].value,"project":options[1].value};
            console.log(details);
            var date = new Date();
            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = date.getFullYear();
            date = yyyy + '-' + mm + '-' + dd ;
            console.log(date);
            
           $.post(`/fin/reimbursement_request/post_additional_data`,{
               proof: details["proof"], 
               project:details["project"],
               re_req_number: $scope.currentNo,
               authorize_date: date
           },function(data){
               console.log(data)
           }).fail(function(jqXHR){console.log(jqXHR)}).done(function(){
                addModal('successModal', 'เบิกเงินรองจ่าย','สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hidden.bs.modal', function (e) {
                    $scope.toMainMenu();
                })
                
            
           })
          
        }
       
        $scope.toMainMenu = function(){
            const url = "https://uaterp.cbachula.com/home"
            window.location.assign(url);
        }
});
  	});
</script>