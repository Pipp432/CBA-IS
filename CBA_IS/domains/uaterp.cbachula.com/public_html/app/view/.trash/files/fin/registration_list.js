app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('add_iv_modal', '');
    $('#add_iv_modal_text').append($compile('<span>ยืนยันการชำระเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_iv()">ยืนยัน</button></span>')($scope));
    add_modal('add_iv_complete_modal', 'ยืนยันการชำระเงินเงินสำเร็จ');
    add_modal('add_iv_error_modal', 'เกิดปัญหาระหว่างการยืนยัน กดใหม่อีกครั้ง');

    add_modal('report_rg_modal', '');
    $('#report_rg_modal_text').append($compile('<span>ยืนยันปัญหาการสมัคร?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="report_rg()">ยืนยัน</button></span>')($scope));
    add_modal('report_rg_complete_modal', 'ยืนยันปัญหาการสมัครสำเร็จ');

    add_modal('cancel_rg_modal', '');
    $('#cancel_rg_modal_text').append($compile('<span>ยืนยันการยกเลิกการสมัคร?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_rg()">ยืนยัน</button></span>')($scope));
    add_modal('cancel_rg_complete_modal', 'ยกเลิกการสมัครสำเร็จ');

    $scope.is_load = true;

    $http.get("/fin/registration_list/get_rgs").then((response) => {
        $scope.rgs = response.data;
        $scope.init();
        $scope.is_load = false;
    });

    $scope.init = () => {
        angular.forEach($scope.rgs, value => {
            value.rg_status_text = value.rg_status;
            if (value.rg_status == 'ค้างชำระ') {
                value.rg_status_text += ' ' + (value.time_diff > 24 ? ((value.time_diff/24).toFixed(0) + ' วัน') : (value.time_diff + ' ชั่วโมง'));
                value.menu = '\
                    <a class="dropdown-item" ng-click="add_iv_validate(\'' + value.rg_no + '\')"><i class="fa fa-comments-dollar" aria-hidden="true"></i> ยืนยันการชำระเงิน</a> \
                    <a class="dropdown-item" ng-click="report_rg_validate(\'' + value.rg_no + '\')"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> การสมัครมีปัญหา</a> \
                    <a class="dropdown-item" ng-click="cancel_rg_validate(\'' + value.rg_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกการสมัคร</a>';
            } else if (value.rg_status == 'การสมัครมีปัญหา') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="add_iv_validate(\'' + value.rg_no + '\')"><i class="fa fa-comments-dollar" aria-hidden="true"></i> ยืนยันการชำระเงิน</a> \
                    <a class="dropdown-item" ng-click="cancel_rg_validate(\'' + value.rg_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกการสมัคร</a>';
            }
        });
    }

    $scope.order_by = 'rg_datetime';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }
    
    $scope.open_rg_slip = slip_no => window.open('/file/slip/' + slip_no);

    $scope.is_add_iv_clicked = false;
    $scope.temp_rg = '';

    $scope.add_iv_validate = rg_no => {
        angular.forEach($scope.rgs, value => { if (value.rg_no == rg_no) $scope.temp_rg = value });
        $('#add_iv_modal').modal('toggle');
    }

    $scope.add_iv = () => {
        $scope.is_add_iv_clicked = true;
        $('#add_iv_modal').modal('hide');
        $.post("/fin/registration_list/add_iv", {
            post : true,
            rg: JSON.stringify(angular.toJson($scope.temp_rg))
        }, function(data) {
            $scope.temp_rg = '';
            if(data == 'error') {
                $scope.is_add_iv_clicked = false;
                $('#add_iv_error_modal').modal('toggle');
            } else {
                $('#add_iv_complete_modal').modal('toggle');
                $http.get("/fin/registration_list/get_rgs").then((response) => {$scope.rgs = response.data; $scope.init();});
            }
        });
    }

    $scope.temp_rg_no = '';

    $scope.is_report_rg_clicked = true;

    $scope.report_rg_validate = rg_no => {
        $scope.temp_rg_no = rg_no;
        $('#report_rg_modal').modal('toggle');
    }

    $scope.report_rg = () => {
        $scope.is_report_rg_clicked = true;
        $('#report_rg_modal').modal('hide');
        $.post("/fin/registration_list/report_rg", {
            post : true,
            rg_no: $scope.temp_rg_no
        }, function(data) {
            $scope.temp_rg_no = '';
            $('#report_rg_complete_modal').modal('toggle');
            $http.get("/fin/registration_list/get_rgs").then((response) => {$scope.rgs = response.data; $scope.init();});
        });
    }

    $scope.is_cancel_rg_clicked = true;
    
    $scope.cancel_rg_validate = rg_no => {
        $scope.temp_rg_no = rg_no;
        $('#cancel_rg_modal').modal('toggle');
    }

    $scope.cancel_rg = () => {
        $scope.is_cancel_rg_clicked = true;
        $('#cancel_rg_modal').modal('hide');
        $.post("/fin/registration_list/cancel_rg", {
            post : true,
            rg_no: $scope.temp_rg_no
        }, function(data) {
            $scope.temp_rg_no = '';
            $('#cancel_rg_complete_modal').modal('toggle');
            $http.get("/fin/registration_list/get_rgs").then((response) => {$scope.rgs = response.data; $scope.init();});
        });
    }

});