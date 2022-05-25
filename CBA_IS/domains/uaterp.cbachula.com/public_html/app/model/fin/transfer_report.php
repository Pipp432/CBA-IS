<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">รายงานการโอน / Transfer Report (TR)</h2> 

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CR DETAIL -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <table class="table table-hover my-1">
                        <tr>
                            <th>โครงการ</th>
                            <th>เลข CR ที่ยังไม่ออก TR</th>
                            <th>ยอดรวม</th>
                        </tr>
                        <tr ng-show="crnotrs.length == 0">
                            <th colspan="3">ไม่มีรายการที่ต้องโอน</th>
                        </tr>
                        <tr ng-show="crnotrs.length != 0">
                            <td>โครงการ 1</td>
                            <td><span ng-repeat="crnotr in crnotrs | filter:{project:'1'}">{{crnotr.cr_no}}, </span></td>
                            <td>{{crnotrs[0].sum1 | number:2}}</td>
                        </tr>
                        <tr ng-show="crnotrs.length != 0">
                            <td>โครงการ 2</td>
                            <td><span ng-repeat="crnotr in crnotrs | filter:{project:'2'}">{{crnotr.cr_no}}, </span></td>
                            <td>{{crnotrs[0].sum2 | number:2}}</td>
                        </tr>
                        <tr ng-show="crnotrs.length != 0">
                            <td>โครงการ 3</td>
                            <td><span ng-repeat="crnotr in crnotrs | filter:{project:'3'}">{{crnotr.cr_no}}, </span></td>
                            <td>{{crnotrs[0].sum3 | number:2}}</td>
                        </tr>
                    </table>
                </div>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ทำรายงานการโอน</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'รายงานการโอน / Transfer Report (TR)', 'ไม่มีรายการที่ต้องโอน');
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

        $scope.crnotrs = <?php echo $this->crnotrs; ?>;
        
        $scope.formValidate = function() {
            if($scope.crnotrs.length == 0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'รายงานการโอน / Transfer Report (TR)', 'ยืนยันการทำรายงานการโอน', 'postTrItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postTrItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/fin/transfer_report/post_tr", {
                post : true,
                trItems : JSON.stringify(angular.toJson($scope.crnotrs))
            }, function(data) {
                console.log(data);
                addModal('successModal', 'รายงานการโอน / Transfer Report (TR)', 'บันทึกรายงานการโอน ' + data + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });           
            }); 
        }
    
    });

</script>