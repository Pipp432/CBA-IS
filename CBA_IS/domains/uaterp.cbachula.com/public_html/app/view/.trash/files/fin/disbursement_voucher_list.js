app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('confirm_dv_modal', '');
    $('#confirm_dv_modal_text').append($compile('<span>ยืนยันการอนุมัติใบเบิกเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="confirm_dv()">ยืนยัน</button></span>')($scope));
    add_modal('confirm_dv_complete_modal', 'อนุมัติใบเบิกเงินสำเร็จ');
    add_modal('confirm_dv_error_modal', 'เกิดปัญหาระหว่างการอนุมัติ กดใหม่อีกครั้ง');

    add_modal('add_to_paid_dv_modal1', 'เลือกเฉพาะใบเบิกเงินที่อนุมัติแล้วแต่ยังไม่โอนเงินเท่านั้น');
    add_modal('add_to_paid_dv_modal2', 'เลือกผู้รับเงินคนเดียวกันเท่านั้น');
    add_modal('add_to_paid_dv_modal3', 'เพิ่มใบเบิกเงินนี้แล้ว');

    add_modal('add_slip_modal', '');
    $('#add_slip_modal_text').append($compile('<span>ยืนยันการอัปโหลดสลิปโอนเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_slip()">ยืนยัน</button></span>')($scope));
    add_modal('add_slip_complete_modal', 'อัปโหลดสลิปโอนเงินสำเร็จ');
    add_modal('add_slip_error_modal', 'เกิดปัญหาระหว่างการอัปโหลด กดใหม่อีกครั้ง');
    
    add_modal('cancel_dv_modal', '');
    $('#cancel_dv_modal_text').append($compile('<span>ยืนยันการยกเลิกใบเบิกเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_dv()">ยืนยัน</button></span>')($scope));
    add_modal('cancel_dv_complete_modal', 'ยกเลิกใบเบิกเงินสำเร็จ');
    add_modal('cancel_dv_error_modal', 'เกิดปัญหาระหว่างการยกเลิก กดใหม่อีกครั้ง');

    $scope.is_load = true;

    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {
        $scope.dvs = response.data;
        $scope.init();
        $scope.is_load = false;
    });

    $scope.init = () => {
        angular.forEach($scope.dvs, value => {
            
            value.menu = '';

            if (value.dv_status == 'รออนุมัติ') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="confirm_dv_validate(\'' + value.dv_no + '\')"><i class="fa fa-check" aria-hidden="true"></i> อนุมัติใบเบิกเงิน</a>';
            } else if (value.dv_status == 'ยังไม่ได้จ่ายเงิน' && value.dv_type == '1') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="add_to_paid_dv(\'' + value.dv_no + '\')"><i class="fa fa-plus-circle" aria-hidden="true"></i> เพิ่มเข้ารายการโอนเงิน</a>';
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

    $scope.temp_confirm_dv_no = '';
    $scope.is_confirm_dv_clicked = false;

    $scope.confirm_dv_validate = dv_no => {
        $scope.temp_confirm_dv_no = dv_no;
        $('#confirm_dv_modal').modal('toggle');
    }

    $scope.confirm_dv = () => {

        if (!$scope.is_confirm_dv_clicked) {

            $scope.is_confirm_dv_clicked = true;
            $('#confirm_dv_modal').modal('hide');

            $.post('/fin/disbursement_voucher_list/confirm_dv', {
                post : true,
                dv_no: $scope.temp_confirm_dv_no
            }, function(data) {
                $scope.temp_confirm_dv_no = '';
                if(data == 'error') {
                    $scope.is_confirm_dv_clicked = false;
                    $('#confirm_dv_error_modal').modal('toggle');
                } else {
                    $('#confirm_dv_complete_modal').modal('toggle');
                    $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {$scope.dvs = response.data; $scope.init();});
                }
            });

        }

    }

    $scope.to_paid_dvs = [];
    $scope.to_paid_price = 0;

    $scope.add_to_paid_dv = dv_no => {

        var new_dv = true;
        var temp_dv;

        angular.forEach($scope.dvs, value => { if(value.dv_no == dv_no) temp_dv = value; });

        if(temp_dv.dv_status != 'ยังไม่ได้จ่ายเงิน') {
            $('#add_to_paid_dv_modal1').modal('toggle');
        } else if($scope.to_paid_dvs.length > 0 && $scope.to_paid_dvs[0].payee_no != temp_dv.payee_no) {
            $('#add_to_paid_dv_modal2').modal('toggle');
        } else {

            
            angular.forEach($scope.to_paid_dvs, value => { if(value.dv_no == dv_no) new_dv = false; });

            if(new_dv) {
                $scope.to_paid_dvs.push(temp_dv);
                $scope.calculate_to_paid_price();
            } else {
                $('#add_to_paid_dv_modal3').modal('toggle');
            }

        }

    }

    $scope.drop_to_paid_dv = dv => {

        var temp_to_paid_dvs = [];

        angular.forEach($scope.to_paid_dvs, value => {
            if(value.dv_no != dv.dv_no) {
                temp_to_paid_dvs.push(dv);
            }
        });

        $scope.to_paid_dvs = temp_to_paid_dvs;
        $scope.calculate_to_paid_price();

    }

    $scope.calculate_to_paid_price = () => {
        $scope.to_paid_price = 0;
        angular.forEach($scope.to_paid_dvs, value => {
            $scope.to_paid_price += Number(value.dv_total_amount);
        });
    }

    $scope.is_add_slip_clicked = false;

    $scope.add_slip_validate = () => {
        $('#add_slip_modal').modal('toggle');
    }

    $scope.add_slip = () => {

        if(!$scope.is_add_slip_clicked) {

            $('#add_slip_modal').modal('hide');
            $scope.is_add_slip_clicked = true;

            var data = new FormData();

            data.append('post', true);
            data.append('to_paid_dvs', JSON.stringify(angular.toJson($scope.to_paid_dvs)));
            data.append('dv_slip', $('#slip_file')[0].files[0]);

            $.ajax({
                url: '/fin/disbursement_voucher_list/add_slip',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function (result) {
                    if(result == 'error') {
                        $scope.is_add_slip_clicked = false;
                        $('#add_slip_error_modal').modal('toggle');
                    } else {
                        $('#add_slip_complete_modal').modal('toggle');
                        $http.get('/home/disbursement_voucher_list/get_dvs').then((response) => {$scope.dvs = response.data; $scope.init();});
                    }
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