app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('add_to_paid_pv_modal1', 'เลือกเฉพาะใบสำคัญจ่ายที่ยังไม่โอนเงินเท่านั้น');
    add_modal('add_to_paid_pv_modal2', 'เลือกผู้รับเงินคนเดียวกันเท่านั้น');
    add_modal('add_to_paid_pv_modal3', 'เพิ่มใบสำคัญจ่ายนี้แล้ว');

    add_modal('add_slip_modal', '');
    $('#add_slip_modal_text').append($compile('<span>ยืนยันการอัปโหลดสลิปโอนเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_slip()">ยืนยัน</button></span>')($scope));
    add_modal('add_slip_complete_modal', 'อัปโหลดสลิปโอนเงินสำเร็จ');
    add_modal('add_slip_error_modal', 'เกิดปัญหาระหว่างการอัปโหลด กดใหม่อีกครั้ง');

    $scope.is_load = true;

    $http.get('/fin/payment_voucher_list/get_pvs').then((response) => {
        $scope.pvs = response.data;
        $scope.init();
        $scope.is_load = false;
    });

    $scope.init = () => {
        angular.forEach($scope.pvs, value => {
            if (value.pv_status == 'ยังไม่ได้จ่ายเงิน') {
                value.menu = '<a class="dropdown-item" ng-click="add_to_paid_pv(\'' + value.pv_no + '\')"><i class="fa fa-plus-circle" aria-hidden="true"></i> เพิ่มเข้ารายการโอนเงิน</a>';
            }
        });
    }

    $scope.order_by = 'pv_datetime';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }

    $scope.open_pv_slip = slip_no => window.open('/file/slip/' + slip_no);

    $scope.to_paid_pvs = [];
    $scope.to_paid_amount = 0;

    $scope.add_to_paid_pv = pv_no => {

        var new_pv = true;
        var temp_pv;

        angular.forEach($scope.pvs, value => { if(value.pv_no == pv_no) temp_pv = value; });

        if(temp_pv.pv_status != 'ยังไม่ได้จ่ายเงิน') {
            $('#add_to_paid_pv_modal1').modal('toggle');
        } else if($scope.to_paid_pvs.length > 0 && $scope.to_paid_pvs[0].payee_no != temp_pv.payee_no) {
            $('#add_to_paid_pv_modal2').modal('toggle');
        } else {

            angular.forEach($scope.to_paid_pvs, value => { if(value.pv_no == pv_no) new_pv = false; });

            if(new_pv) {
                $scope.to_paid_pvs.push(temp_pv);
                $scope.calculate_to_paid_amount();
            } else {
                $('#add_to_paid_pv_modal3').modal('toggle');
            }

        }

    }

    $scope.drop_to_paid_pv = pv => {

        var temp_to_paid_pvs = [];

        angular.forEach($scope.to_paid_pvs, value => {
            if(value.pv_no != pv.pv_no) {
                temp_to_paid_pvs.push(pv);
            }
        });

        $scope.to_paid_pvs = temp_to_paid_pvs;
        $scope.calculate_to_paid_amount();

    }

    $scope.calculate_to_paid_amount = () => {
        $scope.to_paid_amount = 0;
        angular.forEach($scope.to_paid_pvs, value => {
            $scope.to_paid_amount += Number(value.pv_total_amount);
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
            data.append('to_paid_pvs', JSON.stringify(angular.toJson($scope.to_paid_pvs)));
            data.append('pv_slip', $('#slip_file')[0].files[0]);

            $.ajax({
                url: '/fin/payment_voucher_list/add_slip',
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
                        $scope.to_paid_pvs = [];
                        $('#add_slip_complete_modal').modal('toggle');
                        $http.get('/fin/payment_voucher_list/get_pvs').then((response) => {$scope.pvs = response.data; $scope.init();});
                    }
                }
            });
        }

    }

});