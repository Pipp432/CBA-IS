app.controller('app_controller', ($scope, $http, $compile) => {

    add_modal('edit_iv_modal', '');
    $('#edit_iv_modal_text').append($compile('<span>ยืนยันการแก้ไขใบกำกับภาษี?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="edit_iv()">ยืนยัน</button></span>')($scope));
    add_modal('edit_iv_complete_modal', 'แก้ไขใบกำกับภาษีสำเร็จ');
    add_modal('edit_iv_error_modal', 'เกิดปัญหาระหว่างการแก้ไข กดใหม่อีกครั้ง');

    $scope.tax_invoice_detail = tax_invoice_detail[0];

    $scope.is_edit_iv_clicked = false;

    $scope.edit_iv_validate = () => {
        $('#edit_iv_modal').modal('toggle');
    }

    $scope.edit_iv = () => {
        if (!$scope.is_edit_iv_clicked) {
            $scope.is_edit_iv_clicked = true;
            $('#edit_iv_modal').modal('hide');
            $.post("/acc/tax_invoice_list/edit_tax_invoice/edit_iv", {
                post : true,
                iv : JSON.stringify(angular.toJson($scope.tax_invoice_detail)),
                rg_iv_name : $('#rg_iv_name').val(),
                rg_iv_address : $('#rg_iv_address').val(),
                rg_iv_id_no : $('#rg_iv_id_no').val()
            }, function(data) {
                $('#edit_iv_complete_modal').modal('toggle');
                $('#edit_iv_complete_modal').on('hide.bs.modal', e => window.close());
            });
        }
    }

});