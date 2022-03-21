app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('cancel_pv_modal', '');
    $('#cancel_pv_modal_text').append($compile('<span>ยืนยันการยกเลิกใบเบิกเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_pv()">ยืนยัน</button></span>')($scope));
    add_modal('cancel_pv_complete_modal', 'ยกเลิกใบเบิกเงินสำเร็จ');
    add_modal('cancel_pv_error_modal', 'เกิดปัญหาระหว่างการยกเลิก กดใหม่อีกครั้ง');

    add_modal('confirm_paid_pv_modal_1', 'ยืนยันการจ่ายเงิน?');
    $('#confirm_paid_pv_modal_1_text').append($compile('<button type="button" class="btn btn-default btn-block mt-2" ng-click="confirm_paid_pv()">ยืนยัน</button>')($scope));
    add_modal('confirm_paid_pv_modal_2', 'ยืนยันการจ่ายเงิน?<br> \
                                        <form class="form-row mt-2"> \
                                            <div class="col-8"> \
                                                <label class="mb-1" for="pv_proof_date">วันที่ใบสำคัญ&nbsp;</label> \
                                                <input type="date" class="form-control" id="pv_proof_date"> \
                                            </div> \
                                            <div class="col-4"> \
                                                <label class="mb-1" for="pv_proof_vat">ภาษีซื้อ&nbsp;</label> \
                                                <input type="number" class="form-control" id="pv_proof_vat" value="0"> \
                                            </div> \
                                        </form>');
    $('#confirm_paid_pv_modal_2_text').append($compile('<button type="button" class="btn btn-default btn-block mt-2" ng-click="confirm_paid_pv_validate2()">ยืนยัน</button>')($scope));
    add_modal('confirm_paid_pv_complete_modal', 'ยืนยันการจ่ายเงินสำเร็จ');
    add_modal('confirm_paid_pv_error_modal', 'เกิดปัญหาระหว่างการยืนยันการจ่ายเงิน กดใหม่อีกครั้ง');
    
    add_modal('confirm_paid_pv_validate_modal1', 'ยังไม่ได้ใส่วันที่ใบสำคัญ');
    add_modal('confirm_paid_pv_validate_modal2', 'ภาษีซื้อต้องมีค่ามากกว่า 0');
    add_modal('confirm_paid_pv_validate_modal3', 'ภาษีซื้อมีค่ามากกว่าหรือน้อยกว่าที่ควรจะเป็น');
    add_modal('confirm_paid_pv_validate_modal4', 'ยังไม่ได้เลือกโครงการ');

    add_modal('add_pv_validate_modal1', 'ยังไม่ได้เลือกโครงการ');

    $scope.is_load = true;

    $http.get('/acc/payment_voucher_list/get_pvs').then((response) => {
        $scope.pvs = response.data;
        $scope.init();
        $scope.is_load = false;
    });

    $scope.init = () => {
        angular.forEach($scope.pvs, value => {

            value.menu = '';

            if (value.pv_status == 'รอยืนยันการจ่าย') {
                value.menu += '<a class="dropdown-item" ng-click="confirm_paid_pv_validate(\'' + value.pv_no + '\')"><i class="fa fa-check-double" aria-hidden="true"></i> ยืนยันการจ่ายเงิน</a>';
            }
            
            value.menu += '<a class="dropdown-item" ng-click="open_pv(\'' + value.pv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบสำคัญจ่าย</a>\
                            <a class="dropdown-item" ng-click="edit_pv(\'' + value.pv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบสำคัญจ่าย</a>\
                            <a class="dropdown-item" ng-click="cancel_pv_validate(\'' + value.pv_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกใบสำคัญจ่าย</a>';

        });
    }

    $scope.order_by = 'pv_datetime';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }

    $scope.open_pv_slip = slip_no => window.open('/file/slip/' + slip_no);

    $scope.is_confirm_paid_pv_clicked = false;
    $scope.temp_confirm_paid_pv = null;
    $scope.pv_proof_date = '0';
    $scope.pv_proof_vat = 0;
    
    $('#confirm_paid_pv_modal').on('hide.bs.modal', e => {
        $scope.pv_proof_date = '0';
        $scope.pv_proof_vat = 0;
    });

    $scope.confirm_paid_pv_validate = pv_no => {
        var temp_pv;
        angular.forEach($scope.pvs, value => { if(value.pv_no == pv_no) temp_pv = value; })
        if (temp_pv.pv_status == 'รอยืนยันการจ่าย') {
            $scope.temp_confirm_paid_pv = temp_pv;
            if (temp_pv.pv_type == 'A' || temp_pv.pv_type == 'C') {
                $('#confirm_paid_pv_modal_1').modal('toggle');
            } else if (temp_pv.pv_type == 'B') {
                $('#confirm_paid_pv_modal_2').modal('toggle');
            }
        } else {
            $('#confirm_paid_pv_error_modal').modal('toggle');
        }
    }
    
    $scope.confirm_paid_pv_validate2 = () => {
        $scope.pv_proof_date = $('#pv_proof_date').val();
        $scope.pv_proof_vat = $('#pv_proof_vat').val();
        if($scope.pv_proof_date == '') {
            $('#confirm_paid_pv_validate_modal1').modal('toggle');
        } else if($scope.pv_proof_vat < 0) {
            $('#confirm_paid_pv_validate_modal2').modal('toggle');
        } else if(Math.abs($scope.pv_proof_vat - (Number($scope.temp_confirm_paid_pv.pv_total_amount) / 107 * 7)) > 10 && $scope.pv_proof_vat != 0) {
            $('#confirm_paid_pv_validate_modal3').modal('toggle');
        } else {
            $scope.confirm_paid_pv();
        }
    }

    $scope.confirm_paid_pv = () => {

        if(!$scope.is_confirm_paid_pv_clicked) {

            var pv_proof_date = $scope.pv_proof_date;
            var pv_proof_vat = $scope.pv_proof_vat;

            $scope.is_confirm_paid_pv_clicked = true;

            $('#confirm_paid_pv_modal_1').modal('hide');
            $('#confirm_paid_pv_modal_2').modal('hide');
            
            $.post("/acc/payment_voucher_list/confirm_paid_pv", {
                post : true,
                pv_no: $scope.temp_confirm_paid_pv.pv_no,
                pv_type: $scope.temp_confirm_paid_pv.pv_type,
                total_amount: $scope.temp_confirm_paid_pv.pv_total_amount,
                pv_proof_vat: pv_proof_vat,
                pv_proof_date: pv_proof_date
            }, function(data) {
                $scope.temp_confirm_paid_pv = null;
                if(data == 'error') {
                    $scope.is_confirm_paid_pv_clicked = false;
                    $('#confirm_paid_pv_error_modal').modal('toggle');
                } else {
                    $('#confirm_paid_pv_complete_modal').modal('toggle');
                    $http.get('/acc/payment_voucher_list/get_pvs').then((response) => {$scope.pvs = response.data; $scope.init();});
                }
            });

        }

    }

    $scope.open_pv = pv_no => window.open('/file/pv/' + pv_no);
    $scope.edit_pv = pv_no => window.open('/acc/payment_voucher_list/edit_payment_voucher/' + pv_no);

    $scope.temp_cancel_pv_no = '';
    $scope.is_cancel_pv_clicked = false;

    $scope.cancel_pv_validate = (pv_no) => {
        $scope.temp_cancel_pv_no = pv_no;
        $('#cancel_pv_modal').modal('toggle');
    }

    $scope.cancel_pv = () => {

        if (!$scope.is_cancel_pv_clicked) {

            $scope.is_cancel_pv_clicked = true;
            $('#cancel_pv_modal').modal('hide');

            $.post("/acc/payment_voucher_list/cancel_pv", {
                post : true,
                pv_no: $scope.temp_cancel_pv_no
            }, function(data) {
                $scope.temp_cancel_pv_no = '';
                if(data == 'error') {
                    $scope.is_cancel_pv_clicked = false;
                    $('#cancel_pv_error_modal').modal('toggle');
                } else {
                    $('#cancel_pv_complete_modal').modal('toggle');
                    $http.get('/acc/payment_voucher_list/get_pvs').then((response) => {$scope.pvs = response.data; $scope.init();});
                }
            });

        }

    }

});