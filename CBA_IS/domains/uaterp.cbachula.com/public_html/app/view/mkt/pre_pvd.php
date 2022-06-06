<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">คำร้องขอใบลดหนี้ PV-D</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">

            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="soxNo">SOX</label>
                        <input ng-model='sox_no' type="text" class="form-control mb-2" placeholder="SOX-xxxxx" id="soxNo">
                    </div>
                    <div class="col-md-4">
                        <label for="totalAmount">จำนวนเงิน</label>
                        <input type="text" class="form-control mb-2"  id="totalAmount">
                    </div>
                </div>

                <hr>

                <h5 class="card-title mt-0 mb-3">ข้อมูลลูกค้า</h5>
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="bank_no">เลขที่บัญชี</label>
                        <input type="text" class="form-control mb-2"  id="bank_no">
                    </div>

                    <div class="col-md-4">
                        <label for="recipient">ชื่อบัญชี</label>
                        <input type="text" class="form-control mb-2"  id="recipient">
                    </div>

                    <div class="col-md-8">
                        <label for="bank">ธนาคารและสาขา</label>
                        <input type="text" class="form-control mb-2"  id="bank">
                    </div>

                    <!-- <div class="col-md-6">
                        <label for="vatID">เลขที่ผู้เสียภาษีอากร</label>
                        <input type="text" class="form-control mb-2" id="vatID">
                    </div> -->
                </div>
                
                <hr>

                <div class="form-row">
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
            addModal('formValidate1', 'คำร้องขอใบลดหนี้ (PV-D)', 'กรอกรายละเอียดไม่ครบถ้วน');
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
			if( $('#totalAmount').val() == '' || $('#soxNo').val() == '' || 
                 $('#note').val() == '' || $('#bank').val() == '' || $('#bank_no').val() == '' || $('#recipient').val() == '') 
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

            $.post("/mkt/pre_pvd/post_requestwsd", {
                sox_no : $scope.sox_no,
                totalAmount : $('#totalAmount').val(),
                note : $('#note').val(),
                bank : $('#bank').val(),
                bank_no : $('#bank_no').val(),
                recipient : $('#recipient').val()
            }, function(data) {
                addModal('successModal', 'Request PV-D', 'ส่งคำร้องขอใบลดหนี้ (PV-D) สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) { location.reload(); });
            });
            
        }

  	});

</script>

