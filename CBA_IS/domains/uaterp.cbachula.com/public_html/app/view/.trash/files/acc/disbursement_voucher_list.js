app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('cancel_dv_modal', '');
    $('#cancel_dv_modal_text').append($compile('<span>ยืนยันการยกเลิกใบเบิกเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_dv()">ยืนยัน</button></span>')($scope));
    add_modal('cancel_dv_complete_modal', 'ยกเลิกใบเบิกเงินสำเร็จ');
    add_modal('cancel_dv_error_modal', 'เกิดปัญหาระหว่างการยกเลิก กดใหม่อีกครั้ง');

    add_modal('confirm_paid_dv_modal', 'ยืนยันการจ่ายเงิน?<br> \
                                        <form class="form-row mt-2"> \
                                            <div class="col-8"> \
                                                <label class="mb-1" for="dv_proof_date">วันที่ใบสำคัญ&nbsp;</label> \
                                                <input type="date" class="form-control" id="dv_proof_date"> \
                                            </div> \
                                            <div class="col-4"> \
                                                <label class="mb-1" for="dv_proof_vat">ภาษีซื้อ&nbsp;</label> \
                                                <input type="number" class="form-control" id="dv_proof_vat" value="0"> \
                                            </div> \
                                        </form>');
    $('#confirm_paid_dv_modal_text').append($compile('<button type="button" class="btn btn-default btn-block mt-2" ng-click="confirm_paid_dv_validate2()">ยืนยัน</button>')($scope));
    add_modal('confirm_paid_dv_complete_modal', 'ยืนยันการจ่ายเงินสำเร็จ');
    add_modal('confirm_paid_dv_error_modal', 'เกิดปัญหาระหว่างการยืนยันการจ่ายเงิน กดใหม่อีกครั้ง');
    
    add_modal('confirm_paid_dv_validate_modal1', 'ยังไม่ได้ใส่วันที่ใบสำคัญ');
    add_modal('confirm_paid_dv_validate_modal2', 'ภาษีซื้อต้องมีค่ามากกว่า 0');
    add_modal('confirm_paid_dv_validate_modal3', 'ภาษีซื้อมีค่ามากกว่าหรือน้อยกว่าที่ควรจะเป็น');
    add_modal('confirm_paid_dv_validate_modal4', 'ยังไม่ได้เลือกโครงการ');

    add_modal('add_pv_type_1_modal', 'ยืนยันการออกใบสำคัญจ่าย?<br>');
    $('#add_pv_type_1_modal_text').append($compile('<button type="button" class="btn btn-default btn-block mt-2" ng-click="add_pv_type_1()">ยืนยัน</button>')($scope));
    add_modal('add_pv_type_1_complete_modal', 'ยืนยันการออกใบสำคัญจ่ายสำเร็จ');
    add_modal('add_pv_type_1_error_modal', 'เกิดปัญหาระหว่างการยืนยันการออกใบสำคัญจ่าย กดใหม่อีกครั้ง');

    add_modal('add_pv_type_2_modal', 'ยืนยันการออกใบสำคัญจ่าย?<br>');
    $('#add_pv_type_2_modal_text').append($compile('<button type="button" class="btn btn-default btn-block mt-2" ng-click="add_pv_type_2_validate2()">ยืนยัน</button>')($scope));
    add_modal('add_pv_type_2_complete_modal', 'ยืนยันการออกใบสำคัญจ่ายสำเร็จ');
    add_modal('add_pv_type_2_error_modal', 'เกิดปัญหาระหว่างการยืนยันการออกใบสำคัญจ่าย กดใหม่อีกครั้ง');

    add_modal('add_pv_type_2_validate_modal1', 'ยังไม่ได้เลือกโครงการ');

    $scope.is_load = true;

    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {
        $scope.dvs = response.data;
        $scope.init();
        $scope.is_load = false;
    });

    $scope.init = () => {
        angular.forEach($scope.dvs, value => {

            value.menu = '';

            if (value.dv_status == 'รอยืนยันการจ่าย' && value.dv_type == '1') {
                value.menu = '<a class="dropdown-item" ng-click="confirm_paid_dv_validate1(\'' + value.dv_no + '\')"><i class="fa fa-check-double" aria-hidden="true"></i> ยืนยันการจ่ายเงิน</a>';
            } else if (value.dv_status == 'รอออกใบสำคัญจ่าย' && value.dv_type == '2') {
                value.menu = '<a class="dropdown-item" ng-click="add_pv_type_2_validate1(\'' + value.dv_no + '\')"><i class="fa fa-money-check-alt" aria-hidden="true"></i> ออกใบสำคัญจ่าย</a>';
            }
            
            if (value.dv_employee_no == employee_no) {
                value.menu += '<a class="dropdown-item" ng-click="open_rv(\'' + value.dv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบสำคัญรับเงิน</a> \
                                <a class="dropdown-item" ng-click="edit_dv(\'' + value.dv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบเบิกเงิน</a> \
                                <a class="dropdown-item" ng-click="cancel_dv_validate(\'' + value.dv_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกใบเบิกเงิน</a>';
            }

        });
    }

    $scope.order_by = 'dv_datetime';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }

    $scope.open_dv_slip = slip_no => window.open('/file/slip/' + slip_no);

    $scope.is_confirm_paid_dv_clicked = false;
    $scope.temp_confirm_paid_dv = null;
    $scope.dv_proof_date = '0';
    $scope.dv_proof_vat = 0;
    
    $('#confirm_paid_dv_modal').on('hide.bs.modal', e => {
        $scope.dv_proof_date = '0';
        $scope.dv_proof_vat = 0;
    });

    $scope.confirm_paid_dv_validate1 = dv_no => {
        var temp_dv;
        angular.forEach($scope.dvs, value => { if(value.dv_no == dv_no) temp_dv = value; })
        if (temp_dv.dv_status == 'รอยืนยันการจ่าย' && temp_dv.dv_type == '1') {
            $scope.temp_confirm_paid_dv = temp_dv;
            $('#confirm_paid_dv_modal').modal('toggle');
        } else {
            $('#confirm_paid_dv_error_modal').modal('toggle');
        }
    }
    
    $scope.confirm_paid_dv_validate2 = () => {
        $scope.dv_proof_date = $('#dv_proof_date').val();
        $scope.dv_proof_vat = $('#dv_proof_vat').val();
        if($scope.dv_proof_date == '') {
            $('#confirm_paid_dv_validate_modal1').modal('toggle');
        } else if($scope.dv_proof_vat < 0) {
            $('#confirm_paid_dv_validate_modal2').modal('toggle');
        } else if(Math.abs($scope.dv_proof_vat - (Number($scope.temp_confirm_paid_dv.dv_total_amount) / 107 * 7)) > 10 && $scope.dv_proof_vat != 0) {
            $('#confirm_paid_dv_validate_modal3').modal('toggle');
        } else {
            $scope.confirm_paid_dv();
        }
    }

    $scope.confirm_paid_dv = () => {

        if(!$scope.is_confirm_paid_dv_clicked) {

            var dv_proof_date = $scope.dv_proof_date;
            var dv_proof_vat = $scope.dv_proof_vat;

            $scope.is_confirm_paid_dv_clicked = true;

            $('#confirm_paid_dv_modal').modal('hide');
            
            $.post("/acc/disbursement_voucher_list/confirm_paid_dv", {
                post : true,
                dv_no: $scope.temp_confirm_paid_dv.dv_no,
                account_no: $scope.temp_confirm_paid_dv.dv_account_no,
                total_amount: $scope.temp_confirm_paid_dv.dv_total_amount,
                dv_proof_vat: dv_proof_vat,
                dv_proof_date: dv_proof_date
            }, function(data) {
                $scope.temp_confirm_paid_dv = null;
                if(data == 'error') {
                    $scope.is_confirm_paid_dv_clicked = false;
                    $('#confirm_paid_dv_error_modal').modal('toggle');
                } else {
                    $('#confirm_paid_dv_complete_modal').modal('toggle');
                    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {$scope.dvs = response.data; $scope.init();});
                }
            });

        }

    }

    $scope.is_add_pv_type_1_clicked = false;

    $scope.add_pv_type_1_validate = dv_no => {
        $('#add_pv_type_1_modal').modal('toggle');
    }

    $scope.add_pv_type_1 = () => {

        if(!$scope.is_add_pv_type_1_clicked) {

            $scope.is_add_pv_type_1_clicked = true;

            $('#add_pv_type_1_modal').modal('hide');
            
            $.post("/acc/disbursement_voucher_list/add_pv_type_1", {
                post : true
            }, function(data) {
                if(data == 'error') {
                    $scope.is_add_pv_type_1_clicked = false;
                    $('#add_pv_type_1_error_modal').modal('toggle');
                } else {
                    $('#add_pv_type_1_complete_modal').modal('toggle');
                    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {$scope.dvs = response.data; $scope.init();});
                }
            });

        }

    }

    $scope.is_add_pv_type_2_clicked = false;
    $scope.temp_add_pv_type_2 = null;

    $scope.add_pv_type_2_validate1 = dv_no => {
        var temp_dv;
        angular.forEach($scope.dvs, value => { if(value.dv_no == dv_no) temp_dv = value; })
        if (temp_dv.dv_status == 'รอออกใบสำคัญจ่าย' && temp_dv.dv_type == '2') {
            $scope.temp_add_pv_type_2 = temp_dv;
            $('#add_pv_type_2_modal').modal('toggle');
        } else {
            $('#add_pv_type_2_error_modal').modal('toggle');
        }
    }
    
    $scope.add_pv_type_2_validate2 = () => {
        $scope.add_pv_type_2();
    }

    $scope.add_pv_type_2 = () => {

        if(!$scope.is_add_pv_type_2_clicked) {

            $scope.is_add_pv_type_2_clicked = true;

            $('#add_pv_type_2_modal').modal('hide');
            
            $.post("/acc/disbursement_voucher_list/add_pv_type_2", {
                post : true,
                dv_no: $scope.temp_add_pv_type_2.dv_no,
                total_amount: $scope.temp_add_pv_type_2.dv_total_amount
            }, function(data) {
                $scope.temp_add_pv_type_2 = null;
                if(data == 'error') {
                    $scope.is_add_pv_type_2_clicked = false;
                    $('#add_pv_type_2_error_modal').modal('toggle');
                } else {
                    $('#add_pv_type_2_complete_modal').modal('toggle');
                    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {$scope.dvs = response.data; $scope.init();});
                }
            });

        }

    }

    $scope.open_rv = dv_no => window.open('/file/rv/' + dv_no);
    $scope.edit_dv = dv_no => window.open('/home/disbursement_voucher_list/edit_disbursement_voucher/' + dv_no);

    $scope.temp_cancel_dv_no = '';
    $scope.is_cancel_dv_clicked = false;

    $scope.cancel_dv_validate = (dv_no) => {
        $scope.temp_cancel_dv_no = dv_no;
        $('#cancel_dv_modal').modal('toggle');
    }

    $scope.cancel_dv = () => {

        if (!$scope.is_cancel_dv_clicked) {

            $scope.is_cancel_dv_clicked = true;
            $('#cancel_dv_modal').modal('hide');

            $.post("/home/disbursement_voucher_list/cancel_dv", {
                post : true,
                dv_no: $scope.temp_cancel_dv_no
            }, function(data) {
                $scope.temp_cancel_dv_no = '';
                if(data == 'error') {
                    $scope.is_cancel_dv_clicked = false;
                    $('#cancel_dv_error_modal').modal('toggle');
                } else {
                    $('#cancel_dv_complete_modal').modal('toggle');
                    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {$scope.dvs = response.data; $scope.init();});
                }
            });

        }

    }

});