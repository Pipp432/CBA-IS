app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('add_wc_modal', '');
    $('#add_wc_modal_text').append($compile('<span>ยืนยันการบันทึกการส่งใบ 50 ทวิ?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_wc()">ยืนยัน</button></span>')($scope));
    add_modal('add_wc_complete_modal', 'บันทึกการส่งใบ 50 ทวิ สำเร็จ');
    add_modal('add_wc_error_modal', 'เกิดปัญหาระหว่างการบันทึก กดใหม่อีกครั้ง');

    add_modal('cancel_iv_modal', 'ยืนยันการยกเลิกใบกำกับภาษี?<br> \
                                    <form class="form-row mt-2"> \
                                        <div class="col"> \
                                            <label class="mb-1" for="cn_detail">สาเหตุการลดหนี้&nbsp;</label> \
                                            <input type="text" class="form-control" id="cn_detail"> \
                                        </div> \
                                    </form>');
    $('#cancel_iv_modal_text').append($compile('<button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_iv()">ยืนยัน</button></span>')($scope));
    add_modal('cancel_iv_complete_modal', 'ยกเลิกใบกำกับภาษีสำเร็จ');
    add_modal('cancel_iv_error_modal', 'เกิดปัญหาระหว่างการยกเลิก กดใหม่อีกครั้ง');
    add_modal('cancel_iv_validate_modal', 'ยังไม่ได้กรอกสาเหตุการลดหนี้');

    $scope.is_load = true;

    $http.get('/acc/tax_invoice_list/get_ivs').then((response) => {
        $scope.ivs = response.data;
        $scope.init();
        $scope.is_load = false;
    });

    $scope.init = () => {
        
        angular.forEach($scope.ivs, value => {

            value.iv_status_text = value.iv_status;

            if (value.iv_status == '-' && value.iv_type == 'iv') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="open_iv(\'' + value.iv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบกำกับภาษี</a> \
                    <a class="dropdown-item" ng-click="edit_iv(\'' + value.iv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบกำกับภาษี</a> \
                    <a class="dropdown-item" ng-click="cancel_iv_validate(\'' + value.iv_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกใบกำกับภาษี</a>';
            } else if (value.iv_status == 'ยังไม่ได้ส่งใบ 50 ทวิ' && value.iv_type == 'iv') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="add_wc_validate(\'' + value.iv_no + '\')"><i class="fa fa-file-archive" aria-hidden="true"></i> บันทึกการส่งใบ 50 ทวิ</a> \
                    <a class="dropdown-item" ng-click="open_iv(\'' + value.iv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบกำกับภาษี</a> \
                    <a class="dropdown-item" ng-click="edit_iv(\'' + value.iv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบกำกับภาษี</a> \
                    <a class="dropdown-item" ng-click="cancel_iv_validate(\'' + value.iv_no + '\')"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกใบกำกับภาษี</a>';
            } else if (value.iv_type == 'iv') {
                value.menu = '\
                    <a class="dropdown-item" ng-click="open_iv(\'' + value.iv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบกำกับภาษี</a> \
                    <a class="dropdown-item" ng-click="edit_iv(\'' + value.iv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบกำกับภาษี</a>';
            } else {
                value.menu = '\
                    <a class="dropdown-item" ng-click="open_cn(\'' + value.iv_no + '\')"><i class="fa fa-share" aria-hidden="true"></i> เปิดใบลดหนี้</a> \
                    <a class="dropdown-item" ng-click="edit_iv(\'' + value.iv_no + '\')"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขใบลดหนี้</a>';
            }

        });

    }

    $scope.order_by = 'iv_date';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }
    
    $scope.open_iv = iv_no => window.open('/file/iv/' + iv_no);
    $scope.open_cn = iv_no => window.open('/file/cn/' + iv_no);

    $scope.edit_iv = iv_no => window.open('/acc/tax_invoice_list/edit_tax_invoice/' + iv_no);

    $scope.temp_iv_no = '';

    $scope.is_add_wc_clicked = true;
    
    $scope.add_wc_validate = iv_no => {
        $scope.temp_iv_no = iv_no;
        $('#add_wc_modal').modal('toggle');
    }

    $scope.add_wc = () => {
        $scope.is_add_wc_clicked = true;
        $('#add_wc_modal').modal('hide');
        $.post("/acc/tax_invoice_list/add_wc", {
            post : true,
            iv_no: $scope.temp_iv_no
        }, function(data) {
            $scope.temp_iv_no = '';
            $('#add_wc_complete_modal').modal('toggle');
            $http.get('/acc/tax_invoice_list/get_ivs').then((response) => {$scope.ivs = response.data; $scope.init();});
        });
    }

    $scope.is_cancel_iv_clicked = false;

    $scope.cancel_iv_validate = iv_no => {
        $scope.temp_iv_no = iv_no;
        $('#cancel_iv_modal').modal('toggle');
    }

    $scope.cancel_iv = () => {
        if (!$scope.is_cancel_iv_clicked) {
            if ($('#cn_detail').val() == '') {
                $('#cancel_iv_validate_modal').modal('toggle');
            } else {
                $scope.is_cancel_iv_clicked = true;
                $('#cancel_iv_modal').modal('hide');
                $.post("/acc/tax_invoice_list/add_pv_type_4", {
                    post : true,
                    iv_no: $scope.temp_iv_no,
                    cn_detail: $('#cn_detail').val()
                }, function(data) {
                    $scope.temp_iv_no = '';
                    $('#cn_detail').val('');
                    $('#cancel_iv_complete_modal').modal('toggle');
                    $http.get('/acc/tax_invoice_list/get_ivs').then((response) => {$scope.ivs = response.data; $scope.init();});
                });
            }
        }
    }

});