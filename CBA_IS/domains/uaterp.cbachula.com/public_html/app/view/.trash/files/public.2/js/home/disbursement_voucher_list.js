app.controller('app_controller', ($scope, $http, $compile) => {

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
            value.menu = '<a class="dropdown-item" ng-click="open_rv(\'' + value.dv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบสำคัญรับเงิน</a> \
                            <a class="dropdown-item" ng-click="edit_dv(\'' + value.dv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบเบิกเงิน</a> \
                            <a class="dropdown-item" ng-click="cancel_dv_validate(\'' + value.dv_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกใบเบิกเงิน</a>';
        });
    }

    $scope.order_by = 'dv_datetime';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }

    $scope.open_dv_slip = slip_no => window.open('/file/slip/' + slip_no);

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