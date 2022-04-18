<!DOCTYPE html>

<html>



<body>



    <div class="container mt-3" ng-controller="moduleAppController">



        <h2 class="mt-3">เบิกเงินรองจ่าย</h2>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- Employee detail, contact,  receipt/IV and bank slip -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->



        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">

            <div class="card-body">


                <div class="row mx-0">

                    <div class="col-md-4">

                        <label for="employeeTextbox">รหัสผู้ออกใบเบิกเงินรองจ่าย</label>

                        <input type="text" class="form-control" id="employeeTextbox" ng-change="addEmployee()"
                            ng-model="employee_id" style="text-transform:uppercase">

                    </div>

                    <div class="col-md-8">

                        <label for="employeeDetailTextbox">ชื่อผู้ออกใบเบิกเงินรองจ่าย</label>

                        <input type="text" class="form-control" id="employeeDetailTextbox" ng-model="employee_name"
                            disabled>

                    </div>

                </div>

                <div class="row mx-0">

                    <div class="col-md-4">

                        <label for="lineId">Line Id</label>

                        <input type="text" class="form-control" id="LineId" ng-model="line_id">

                    </div>

                </div>

                <hr>

                <div class="row mx-0">

                    <div class="col-md-4">

                        <label for="productName">รายการ</label>

                        <input type="text" class="form-control" id="productName" ng-model="product_name">

                    </div>

                    <div class="col-md-4">

                        <label for="price">ราคา</label>

                        <input type="text" class="form-control" id="price" ng-model="price">

                    </div>

                </div>

                <div class="row mx-0">

                    <div class="col-md-4">

                        <label for="bank">ธนาคาร</label>

                        <input type="text" class="form-control" id="bank" ng-model="bank_name">

                    </div>

                    <div class="col-md-4">

                        <label for="bankNo">เลขบัญชี</label>

                        <input type="text" class="form-control" id="bankNo" ng-model="bank_no">

                    </div>

                </div>
                <form id='form'>
                    <div class="row mx-0">

                        <div class="col-md-4">

                            <label for="ivrc">Invoice/Receipt</label>

                            <input type="file" class="form-control-file" id="ivrc" name="ivrc">


                        </div>

                        <div class="col-md-4">

                            <label for="bankSlip">Bank Slip</label>

                            <input type="file" class="form-control-file" id="bankSlip" name="bankSlip">

                        </div>

                    </div>
                </form>
                <hr>



                <div class="row mx-0">

                    <div class="col-md-2">

                        <label for="buttonConfirmDetail" style="color:white;">.</label>

                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail"
                            ng-click="confirmDetail()">ยืนยัน</button>

                    </div>

                </div>

            </div>

        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- FORM VALIDATION -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->



        <script>
            addModal('formValidate0', 'เบิกเงินรองจ่าย', 'ไม่เจอรหัสพนักงานนี้');

            addModal('formValidate1', 'เบิกเงินรองจ่าย', 'เพิ่มรหัสพนักงานก่อนนะครับ');

            addModal('formValidate2', 'เบิกเงินรองจ่าย', 'เพิ่ม line ID ก่อนนะครับ');

            addModal('formValidate5', 'เบิกเงินรองจ่าย', 'กรอกข้อมูลไม่ครบ');
        </script>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->



    </div>



</body>



</html>




<script>

    app.controller('moduleAppController', function ($scope, $http, $compile) {

        $scope.employee_id = '';
        $scope.employee_name = '';
        $scope.line_id = '';
        $scope.product_name = '';
        $scope.price = '';
        $scope.bank_name = '';
        $scope.bank_no = '';

        $scope.addEmployee = function () { //todo add current employee name and id automaticly

            if ($scope.employee_id.length === 5) {

                $http.get("/public/json/employee.json?t=<?= time() ?>").then(function (response) {

                    var found = false;

                    angular.forEach(response.data, function (value, key) {

                        if (value.employee_id == $scope.employee_id.toUpperCase()) {

                            $scope.employee_name = value.employee_name_thai;

                            found = true;

                        }

                    });

                    if (!found) {

                        $scope.employee_name = 'Employee not found!';

                    }

                });

            } else {

                $scope.employee_name = '';

            }

        }


        $scope.confirmDetail = function () {

            if ($scope.employee_name === '') {

                $('#formValidate1').modal('toggle');

            } else if ($scope.employee_name === 'Employee not found!') {

                $('#formValidate0').modal('toggle');

            } else if ($scope.line_id === '') {

                $('#formValidate2').modal('toggle');

            } else if ($scope.product_name === '') {

                $('#formValidate3').modal('toggle');

            } else if ($scope.price === '' || $scope.bank_name === '' || $scope.bank_no === '') {

                $('#formValidate5').modal('toggle');

            } else {

                $scope.showAfterSubmit = true;

                $scope.formValidate();
            }
        }

        $scope.formValidate = function () {

            var confirmModal = addConfirmModal('confirmModal', 'เบิกเงินรองจ่าย',
                'ยืนยันการออกใบเบิกเงินรองจ่าย', 'postRequest()');

            $('body').append($compile(confirmModal)($scope));

            $('#confirmModal').modal('toggle');
        }

        $scope.postRequest = function () {

            $('#confirmModal').modal('hide');

            var formData = new FormData();

            formData.append('invoice/receipt', $('#ivrc')[0].files[0]);
            formData.append('slip', $('#bankSlip')[0].files[0]);
            formData.append('employee_id', $scope.employee_id.toUpperCase());
            formData.append('lineId', $scope.line_id);
            formData.append('employee_name', $scope.employee_name);
            formData.append('product_name', $scope.product_name);
            formData.append('cost', $scope.price);
            formData.append('bank_name', $scope.bank_name);
            formData.append('bank_no', $scope.bank_no);

            $.ajax({
                url: 'petty_cash_request/post_pva',
                type: "POST",
                dataType: 'text',
                method: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
            }).done(function (data) {
                console.log(data);
                if(data == 'success') {
                    addModal('uploadSuccessModal', 'upload', 'success');
                    $('#uploadSuccessModal').modal('toggle');
                    $('#uploadSuccessModal').on('hide.bs.modal', function (e) {
                        location.reload();
                    });
                } else {
                    addModal('uploadFailModal', 'upload failed', data);
                    $('#uploadFailModal').modal('toggle');
                    $('#uploadFailModal').on('hide.bs.modal', function (e) {
                        location.reload();
                    });
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log('ajax.fail');
                addModal('postFailModal', 'upload imgae', 'fail');
                $('#postFailModal').modal('toggle');
                $('#postFailModal').on('hide.bs.modal', function (e) {
                    location.reload();
                });
            });         
        }
    });
</script>