app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('amount_warning_modal', 'จำนวนเงินที่กรอกมากเกินไป');

    add_modal('add_tr_modal', '');
    $('#add_tr_modal_text').append($compile('<span>ยืนยันการบันทึกรายงานการโอนเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_tr()">ยืนยัน</button></span>')($scope));
    add_modal('add_tr_complete_modal', 'บันทึกรายงานการโอนเงินสำเร็จ');
    add_modal('add_tr_error_modal', 'เกิดปัญหาระหว่างการบันทึก กดใหม่อีกครั้ง');
    
    $scope.trs = trs;
    
    $scope.reverse = true;
    $scope.order_by_fn = () => $scope.reverse = !$scope.reverse;

    $scope.amount = amount;
    $scope.tr_amount_1_before = 0;
    $scope.tr_amount_2_before = 0;

    angular.forEach($scope.amount, value => {
        if (value.project_no == '1') {
            $scope.tr_amount_1_before = Number(value.amount);
        } else if (value.project_no == '2') {
            $scope.tr_amount_2_before = Number(value.amount);
        }
    })

    $scope.tr_amount_1 = $scope.tr_amount_1_before;
    $scope.tr_amount_2 = $scope.tr_amount_2_before;

    $scope.warning_1 = false;
    $scope.warning_2 = false;

    $scope.check_amount = project_no => {
        if (project_no == 1) {
            if ($scope.tr_amount_1 > Number(tr[0] == undefined ? 0 : tr[0].amount)) {
                $('#amount_warning_modal').modal('toggle');
                $scope.warning_1 = true;
            } else {
                $scope.warning_1 = false;
            }
        } else if (project_no == 2) {
            if ($scope.tr_amount_2 > Number(tr[1] == undefined ? 0 : tr[1].amount)) {
                $('#amount_warning_modal').modal('toggle');
                $scope.warning_2 = true;
            } else {
                $scope.warning_2 = false;
            }
        }
    }

    $scope.is_add_tr_clicked = false;

    $scope.add_tr_validate = () => {
        if ($scope.warning_1 || $scope.warning_2) {
            $('#amount_warning_modal').modal('toggle');
        } else {
            $('#add_tr_modal').modal('toggle');
        }
    }

    $scope.add_tr = () => {
        if (!$scope.is_add_tr_clicked) {
            $scope.is_add_tr_clicked = true;
            $('#add_tr_modal').modal('hide');
            $.post("/fin/transfer_report/add_tr", {
                post : true,
                tr_amount_1 : $scope.tr_amount_1.toFixed(2),
                tr_amount_2 : $scope.tr_amount_2.toFixed(2)
            }, function(data) {
                console.log(data);
                // $('#add_tr_complete_modal').modal('toggle');
                // $('#add_tr_complete_modal').on('hide.bs.modal', e => location.assign('/'));
            });
        }
    }

});