<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init = "getDetail()">

        <h2 class="mt-3">อัพโหลด slip สำหรับ PVC</h2>
        <div class="row mx-0 mt-2">
            
                    <table class="table table-hover my-1" ng-show="pvcs.length == 0">
                        <tr ng-show="!isLoad">
                            <th>Nothing to see!</th>
                        </tr>
                        <tr ng-show="isLoad">
                            <th>
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pvcs.length != 0">
                        <tr>
                            <th>เลขที่ใบ PVC</th>
                            <th>วันที่</th>
                            <th>จำนวนเงินรวมทั้งสิ้น</th>
                            <th>อัพสลิปจ่ายร้านค้า</th>
                            <th>เอกสาร PVC</th>
                            <th>Confirm</th>
                        </tr>
                        
                        <tr ng-repeat="pvc in pvcs track by $index">
                            <td>{{pvc.pv_no}}</td>
                            <td>{{pvc.pv_date}}</td>
                            <td>{{pvc.total_paid}}</td>
                            <td>
                                <form id = "form {{pvc.pv_no}}">
                                <input type="file" name= "file" id ="file">
                                </form>
                            </td>

                            <td style="text-align: center;">
                            <a href="/file/pvc/{{pvc.pv_no}}" target="_blank">PVC file</a>
                            </td>

                            <td style="text-align: center;">
                                <button type="button" class="btn btn-default btn-block" ng-click="confirm(pvc.pv_no)">confirm</button>
                            </td>
                            
                            
                            
                        </tr>
                    </table>
               
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
    td { border-bottom: 1px solid lightgray; text-align: center;  }
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.pvcs =[];
      $scope.getDetail = function(){
          $http.get("/fin/upload_slip_pvc/get_PVCs").then(function(response){
            $scope.pvcs = response.data; $scope.isLoad = false;
          })
      }
      $scope.confirm = function(pv_no){
          const form = document.getElementById(`form ${pv_no}`)
          var formData = new FormData(form)
          for (var pair of formData.entries()) {
    console.log(pair); 
}
          $.ajax({
            url : `/fin/upload_slip_pvc/add_slip/${pv_no}`,
            type: 'POST',
            dataType: 'json',
            method: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
        }).fail(function(response,a,b){
            console.log(response)
            console.log(a);
            console.log(b);

        }).done(function(){
            $scope.goToMainMenu();
              
        });
       
           
      }
      $scope.goToMainMenu = function(){
          location.reload();
      }
  	});

</script>