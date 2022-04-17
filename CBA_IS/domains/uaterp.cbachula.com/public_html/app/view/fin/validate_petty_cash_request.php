<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">confirm เบิกเงินรองจ่าย</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!--  -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1">
                        <tr>
                            <th>วันเวลา</th>
                            <th>รหัสพนักงาน</th>
                            <th>ชื่อพนักงาน</th>
                            <th>line id</th>
                            <th>ชื่อสินค้า</th>
                            <th>ค่าใช้จ่าย</th>
                            <th>bank information</th>
                            <th>Slip โอนให้พนักงาน</th>
                            <th>confirm/reject</th>
                        </tr>
                        <tr ng-show="minor_requests.length == 0">
                            <th colspan="8">ไม่มีเลข ใบเบิกเงินรองจ่าย ที่ยังไม่ได้โอน</th>
                        </tr>
                        <tr ng-repeat="minor_request in minor_requests | unique:'internal_pva_no' | orderBy:['date','time']"
                            ng-show="minor_requests.length > 0">
                            <td>{{minor_request.pv_date}} {{minor_request.pv_time}}</td>
                            <td>{{minor_request.employee_id}}</td>
                            <td>{{minor_request.employee_name}}</td>
                            <td>{{minor_request.line_id}}</td>
                            <td>{{minor_request.product_names}}</td>
                            <td>{{minor_request.total_paid | number:2}}</td>
                            <td>{{minor_request.bank_name}} {{minor_request.bank_no}} </td>
                            <td>


                                <!-- <label for="{{minor_request.internal_pva_no}}" class="custom-file-upload" id = "lab{{minor_request.internal_pva_no}}">uploaded</label> -->
                                <input id='{{minor_request.internal_pva_no}}' type="file" class="form-control-file" name={{minor_request.internal_pva_no}}>



                            </td>
                            <td style="text-align: right;">
                                <a target = '_blank' href="/fin/validate_petty_cash_request/get_re/{{minor_request.internal_pva_no}}">Check reciept/invoice</a>
                                <a target = '_blank' href="/fin/validate_petty_cash_request/get_iv/{{minor_request.internal_pva_no}}">Check slip</a>
                                <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail"
                                    ng-click="confirm(minor_request)">Confirm</button>
                                <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail"
                                    ng-click="reject(minor_request)">Reject</button>
                            </td>

                        </tr>
                    </table>
                </div>
                <!-- <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="test()">test</button> -->
            </div>
        </div>

    </div>

</body>

</html>

<style>
    td {
        border-bottom: 1px solid lightgray;
    }

    th {
        border-bottom: 1px solid lightgray;
        text-align: center;
    }
</style>

<script>


    app.controller('moduleAppController', function ($scope, $http, $compile) {

        $scope.minor_requests = <?php echo json_encode($this -> minor_requests); ?> ;
        var first = true;



        $scope.test = function() {
            console.log($scope.minor_requests);
        }


        $scope.reject = function ($minor_request) {
            $.post("validate_petty_cash_request/reject_request", {
                post : true,
                internal_pva_no : $minor_request.internal_pva_no,
            }, function(data) {
                console.log(data);
                location.reload();
            });
        }

        $scope.confirm = function ($minor_request) {
            //console.log($minor_request);
            var upload = true;
            var formData = new FormData();
            var mod = '';
            formData.append('slip_file', $('#' + $minor_request['internal_pva_no'])[0].files[0]);

            for (var pair of formData.entries()) {
                //console.log(pair[0]+ ', ' + pair[1]); 
                if (pair[1] === 'undefined') {
                    upload = false;
                    mod = mod + 'ยังไม่ได้อัปรูป<br>';
                }
            }
            formData.append('internal_pva_no', $minor_request.internal_pva_no);

            if (upload) {
                $.ajax({
                    url: 'validate_petty_cash_request/confirm_request',
                    type: "POST",
                    dataType: 'text',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    if (data == 'success') {
                        addModal('succModal', 'upload imgae', 'success');
                        $('#succModal').modal('toggle');
                        $('#succModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        });
                    } else {
                        console.log(data);
                        addModal('uploadFailModal', 'upload imgae', 'fail' + data);
                        $('#uploadFailModal').modal('toggle');
                        $('#uploadFailModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        });
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('ajax.fail');
                    addModal('uploadFailModal', 'upload imgae', 'fail');
                    $('#uploadFailModal').modal('toggle');
                    $('#uploadFailModal').on('hide.bs.modal', function (e) {
                        location.reload();
                    });
                });
            } else {
                addModal('formValidate10', 'confirm เบิกเงินรองจ่าย', mod);
                $('#formValidate10').modal('toggle');
            }
        }



    });
</script>