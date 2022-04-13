<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">คำร้องขอใบลดหนี้ PV-D</h2>

        <div class="card p-1 my-3" style="border:none; border-radius:10px; background-color:rgba(255, 255, 255, 0.6);">

            <div class="card-body">

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="employeeID">รหัสพนักงาน</label>
                        <input type="text" class="form-control mb-2" placeholder="รหัสพนักงาน" id="employeeID">
                    </div>

                    <div class="col-md-8">
                        <label for="employeeLine">Line id</label>
                        <input type="text" class="form-control mb-2" placeholder="Line id" id="employeeLine">
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-3">
                        <label for="soxNo">SOX</label>
                        <input ng-model='sox_no' type="text" class="form-control mb-2" placeholder="SOX" id="soxNo">
                    </div>

                    <div class="col-md-3">
                        <label for="totalAmount">จำนวนเงิน</label>
                        <input type="text" class="form-control mb-2" placeholder="จำนวนเงิน" id="totalAmount">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="vatID">เลขที่ผู้เสียภาษีอากร</label>
                        <input type="text" class="form-control mb-2" placeholder="เลขที่ผู้เสียภาษีอากร" id="vatID">
                    </div>
					
                </div>
                
                <div class="form-row">
                    <div class="col-md-12">
                        <label for="note">หมายเหตุ</label>
                        <textarea cols="30" rows="4" class="form-control mb-2" placeholder="หมายเหตุ" id="note"></textarea>
                    </div>

                </div>

            </div>

        </div>

        <div class="card p-1 my-3" style="border:none; border-radius:10px; background-color:rgba(255, 255, 255, 0.6);">

            <div class="card-body"> 

                <h5 class="card-title mt-0 mb-3">กรุณาตรวจสอบข้อมูลว่าถูกต้องหรือไม่ก่อนกดยืนยันส่งคำร้องขอใบลดหนี้</h5> 
                <button type="button" class="btn btn-default btn-block" ng-click="formValidate()">ส่งคำร้องขอใบลดหนี้</button>

            </div>

        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'คำร้องขอใบลดหนี้ (PV-D)', 'ยังกรอกรายละเอียดไม่ครบถ้วน');
			// addModal('formValidate2', 'คำร้องขอใบลดหนี้ (PV-D)', 'กรุณากรอกชื่อเป็นภาษาไทย');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    
    body {
        background: url('/public/img/cbs-background.png') no-repeat center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
    }
	
	::placeholder {
	  color: blue;
	}

</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.formValidate = function() {
			if($('#employeeID').val() == '' || $('#employeeLine').val() == '' || $('#totalAmount').val() == '' || $('#soxNo').val() == '' || 
                $('#vatID').val() == '' || $('#note').val() == '') 
                {$('#formValidate1').modal('toggle'); 
			}else {
                var confirmModal = addConfirmModal('confirmModal', 'คำร้องขอใบลดหนี้ PV-D', 'ยืนยันการส่งคำร้องขอใบลดหนี้ PV-D ', 'postRequestPVD()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postRequestPVD = function() {
            
            $('#confirmModal').modal('hide');
            
            //var provinceStr = (($('#customerProvince').val() == 'กรุงเทพมหานคร') ? '' : 'จังหวัด') + $('#customerProvince').val();
            console.log($scope.sox_no);

            $.post("/mkt/pre_pvd/post_requestpvd", {
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
                $('#successModal').on('hide.bs.modal', function (e) { location.assign('/mkt/pre_pvd') }); 
            });
            
        }

  	});

</script>