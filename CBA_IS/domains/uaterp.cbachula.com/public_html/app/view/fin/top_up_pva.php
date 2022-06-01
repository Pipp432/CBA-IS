<!DOCTYPE html>
<html>

<body>


    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">แนบหลักฐานเติมเงินรองจ่าย</h2>

        <div>
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <div class="row mx-0 mt-2">
                            <table class="table table-hover my-1">
                                <tr>
                                    <th>PV-A no</th>
                                    <th>จำนวนเงินเติม</th>
                                    <th>upload slip</th>
                                    <th>confirm</th>
                                </tr>
                                <tr ng-show="pvas.length == 0">
                                    <th colspan="8">ไม่มีใบ PV-A ที่ยังไม่ได้โอน</th>
                                </tr>
                                <tr ng-repeat="pva in pvas | unique:'pv_no' | orderBy:'pv_no'"
                                    ng-show="pvas.length > 0">
                                    <td>{{pva.pv_no}}</td>
                                    <td>{{pva.total_paid | number:2}}</td>
                                    <td>
                                        <input id='{{pva.pv_no}}' type="file" class="form-control-file"
                                            name={{pva.pv_no}}>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail"
                                            ng-click="upload_slip_pva(pva)">ยืนยันอัปสลิป</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
        </div>








    </div>

</body>

</html>

<script>


    app.controller('moduleAppController', function($scope, $http, $compile) {

        $scope.pvas = [];
        angular.forEach(<?php echo ($this -> pvas);?>, function (value, key) { 
            $scope.pvas.push(value); 
        });


        $scope.upload_slip_pva = function ($pva) {
            var upload = true;
            var formData = new FormData();
            formData.append('slip_file', $('#' + $pva['pv_no'])[0].files[0]);
            for (var pair of formData.entries()) {
                if (pair[1] === 'undefined') {
                    upload = false;
                }
            }
            formData.append('pv_no', $pva['pv_no']);

            if (upload) {
                $.ajax({
                    url: 'WS/PVA_slip',
                    type: "POST",
                    dataType: 'text',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    processData: false, 
                    contentType: false,
                }).done(function (data) {
                    if (data == 'success') {
                        addModal('succModal', 'upload slip', 'success');
                        $('#succModal').modal('toggle');
                        $('#succModal').on('hide.bs.modal', function (e) {
                            //location.reload();
                            var tmp_pvas = [];
                            angular.forEach($scope.pvas, function (value, key) { 
                                if(value != $pva) tmp_pvas.push(value); 
                            });
                            $scope.pvas = tmp_pvas;
                            $scope.$apply(); //update ng-repeat (usually update after $scope variable change but idk why it don't update now)
                        });
                    } else {
                        console.log(data);
                        addModal('uploadFailModal', 'upload slip', 'fail' + data);
                        $('#uploadFailModal').modal('toggle');
                        $('#uploadFailModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        }); 
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('ajax.fail');
                    addModal('uploadFailModal', 'upload slip', 'fail');
                    $('#uploadFailModal').modal('toggle');
                    $('#uploadFailModal').on('hide.bs.modal', function (e) {
                        location.reload();
                    });
                });
            } else {
                addModal('formValidate10', 'confirm เบิกเงินรองจ่าย', 'ยังไม่ได้อัปรูป');
                $('#formValidate10').modal('toggle');
            }
        }
    });

    

</script>