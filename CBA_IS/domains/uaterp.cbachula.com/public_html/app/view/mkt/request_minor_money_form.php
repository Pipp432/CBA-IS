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

                        <label for="productName">ชื่อสินค้า</label>

                        <input type="text" class="form-control" id="productName" ng-model="product_name">

                    </div>

                    <div class="col-md-4">

                        <label for="price">ราคา</label>

                        <input type="text" class="form-control" id="price" ng-model="price">

                    </div>

                </div>
                <form id='form'>
                    <div class="row mx-0">

                        <div class="col-md-4">

                            <label for="invoice/receipt">Invoice/Receipt</label>

                            <input type="file" class="form-control-file" id="invoice/receipt" name="Invoice_Receipt_pic">


                        </div>

                        <div class="col-md-4">

                            <label for="bankSlip">Bank Slip</label>

                            <input type="file" class="form-control-file" id="bankSlip" name="bank_slip">

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

            addModal('formValidate5', 'ใบสั่งขาย / Sales Order (SO)', 'ยังไม่ได้เพิ่มสินค้าเข้าใบสั่งขาย');
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

        $scope.addEmployee = function () {

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

            } else if ($scope.price === '') {

                $('#formValidate4').modal('toggle');

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

            var formData = new FormData(form);


            $.ajax({
                url: 'request_minor_money_form/post_img_Request',
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
                    $scope.postRequest2();
                } else {
                    addModal('uploadFailModal', 'upload image', 'fail');
                    $('#uploadFailModal').modal('toggle');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log('ajax.fail');
                addModal('uploadFailModal', 'upload image', 'fail');
                $('#uploadFailModal').modal('toggle');
            });         
        }
        $scope.postRequest2 = function () {

            $.post("request_minor_money_form/post_Request", {

                post: true,

                rq_no: $scope.rq_no,

                employee_id: $scope.employee_id.toUpperCase(),

                lineId: $scope.line_id,

                product_name: $scope.product_name,

                cost: $scope.price,

            }, function (data) {

                addModal('successModal', 'เบิกเงินรองจ่าย', data);

                $('#successModal').modal('toggle');
            });
        }
    });
</script>