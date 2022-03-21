app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('confirm_rg_modal', '');
    $('#confirm_rg_modal_text').append($compile('<span>ยืนยันการสมัคร?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="confirm_rg()">ยืนยัน</button></span>')($scope));
    add_modal('confirm_rg_complete_modal', 'ยืนยันการสมัครสำเร็จ');

    add_modal('cancel_rg_modal', '');
    $('#cancel_rg_modal_text').append($compile('<span>ยืนยันการยกเลิกการสมัคร?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_rg()">ยืนยัน</button></span>')($scope));
    add_modal('cancel_rg_complete_modal', 'ยกเลิกการสมัครสำเร็จ');
    add_modal('cancel_rg_error_modal', 'เกิดปัญหาระหว่างการยกเลิกการสมัคร กดใหม่อีกครั้ง');

    $scope.is_load = true;

    $http.get("/spj/registration_list/get_rgs").then((response) => {
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
                    <a class="dropdown-item" ng-click="edit_rg(\'' + value.rg_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขการสมัคร</a> \
                    <a class="dropdown-item" ng-click="cancel_rg_validate(\'' + value.rg_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกการสมัคร</a>';
            } else if (value.rg_status == 'ยังไม่ได้ยืนยันการสมัคร') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="confirm_rg_validate(\'' + value.rg_no + '\')"><i class="fa fa-check" aria-hidden="true"></i> ยืนยันการสมัคร</a> \
                    <a class="dropdown-item" ng-click="edit_rg(\'' + value.rg_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขการสมัคร</a> \
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

    $scope.edit_rg = rg_no => location.assign('/spj/edit_registration/' + rg_no);

    $scope.temp_rg_no = '';

    $scope.is_confirm_rg_clicked = true;
    
    $scope.confirm_rg_validate = rg_no => {
        $scope.temp_rg_no = rg_no;
        $('#confirm_rg_modal').modal('toggle');
    }

    $scope.confirm_rg = () => {
        $scope.is_confirm_rg_clicked = true;
        $('#confirm_rg_modal').modal('hide');
        $.post("/spj/registration_list/confirm_rg", {
            post : true,
            rg_no: $scope.temp_rg_no
        }, function(data) {
            $scope.temp_rg_no = '';
            $('#confirm_rg_complete_modal').modal('toggle');
            $http.get("/spj/registration_list/get_rgs").then((response) => {$scope.rgs = response.data; $scope.init();});
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
        $.post("/spj/registration_list/cancel_rg", {
            post : true,
            rg_no: $scope.temp_rg_no
        }, function(data) {
            $scope.temp_rg_no = '';
            $('#cancel_rg_complete_modal').modal('toggle');
            $http.get("/spj/registration_list/get_rgs").then((response) => {$scope.rgs = response.data; $scope.init();});
        });
    }

});