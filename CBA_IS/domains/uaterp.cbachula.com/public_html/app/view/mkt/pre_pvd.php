<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">คำร้องขอใบลดหนี้ PV-D</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">

            <div class="card-body">

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="employeeID">รหัสพนักงาน</label>
                        <input type="text" class="form-control mb-2" id="employeeID">
                    </div>

                    <div class="col-md-4">
                        <label for="employeeLine">Line id</label>
                        <input type="text" class="form-control mb-2" id="employeeLine">
                    </div>
                </div>

                <hr>

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="soxNo">SOX</label>
                        <input ng-model='sox_no' type="text" class="form-control mb-2" id="soxNo">
                    </div>

                    <div class="col-md-4">
                        <label for="totalAmount">จำนวนเงิน</label>
                        <input type="text" class="form-control mb-2"  id="totalAmount">
                    </div>
                </div>
					
                
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="vatID">เลขที่ผู้เสียภาษีอากร</label>
                        <input type="text" class="form-control mb-2" id="vatID">
                    </div>
                
                    <hr>
                
                    <div class="col-md-12">
                        <label for="note">หมายเหตุ</label>
                        <textarea cols="30" rows="2" class="form-control mb-2"  id="note"></textarea>
                    </div>

                </div>

                <hr>


                <div class="row mx-0">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default btn-block" ng-click="formValidate()">ส่งคำร้องขอใบลดหนี้</button>
                    </div>
                </div>

            </div>

        </div>

        <!-- <div class="card p-1 my-3" style="border:none; border-radius:10px; background-color:rgba(255, 255, 255, 0.6);">
            <div class="card-body"> 
               
                <button type="button" class="btn btn-default btn-block" ng-click="formValidate()">ส่งคำร้องขอใบลดหนี้</button>
            </div>
        </div> -->


        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'คำร้องขอใบลดหนี้ (PV-D)', 'ยังกรอกรายละเอียดไม่ครบถ้วน');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    
    /* body {
        background: url('/public/img/cbs-background.png') no-repeat center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
    } */
	
	/* ::placeholder {
	  color: blue;
	} */

</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {

        $scope.formValidate = function() {
			if($('#employeeID').val() == '' || $('#employeeLine').val() == '' || $('#totalAmount').val() == '' || $('#soxNo').val() == '' || 
                $('#vatID').val() == '' || $('#note').val() == '') 
                {$('#formValidate1').modal('toggle'); 
			}else {
                var confirmModal = addConfirmModal('confirmModal', 'คำร้องขอใบลดหนี้ PV-D', 'ยืนยันการส่งคำร้องขอใบลดหนี้ PV-D ', 'postRequestWSD()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postRequestWSD = function() {
            
            $('#confirmModal').modal('hide');
            
            //var provinceStr = (($('#customerProvince').val() == 'กรุงเทพมหานคร') ? '' : 'จังหวัด') + $('#customerProvince').val();
            console.log($scope.sox_no);

            $.post("/mkt/pre_pvd/post_requestwsd", {
                employeeID : $('#employeeID').val(),
                employeeLine : $('#employeeLine').val(),
                sox_no : $scope.sox_no,
                totalAmount : $('#totalAmount').val(),
                vatID : $('#vatID').val(),
                note : $('#note').val()
                //,
                //customerTel : $('#customerTel').val()
            }, function(data) {
                addModal('successModal', 'Request PV-D', 'ส่งคำร้องขอใบลดหนี้ (PV-D) สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) { location.reload(); });
            });
            
        }

  	});

</script>

