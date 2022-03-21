app.controller('app_controller', ($scope, $compile) => {

    add_modal('form_validate_1_1', 'ยังไม่ได้เลือกประเภทการเบิกเงิน');
    add_modal('form_validate_1_2', 'ยังไม่ได้เลือกโครงการ');
    add_modal('form_validate_2', 'ยังไม่ได้เลือกประเภทค่าใช้จ่าย');
    add_modal('form_validate_3', 'ยังไม่ได้กรอกจำนวนเงิน');
    add_modal('form_validate_4', 'ยังไม่ได้เลือกผู้รับเงิน');
    add_modal('form_validate_5', 'กรอกข้อมูลผู้รับเงินไม่ครบถ้วน');
    add_modal('form_validate_6', 'จำนวนเงินแต่ละรายการรวมกันไม่เท่ากับจำนวนเงินรวม');

    add_modal('confirm_modal', '');
    $('#confirm_modal_text').append($compile('<span>ยืนยันการบันทึกใบเบิกเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_dv()">ยืนยัน</button></span>')($scope));
    add_modal('error_modal', 'เกิดปัญหาระหว่างการบันทึก กดใหม่อีกครั้ง');

    $scope.dv_type_chosen = '0';
    $scope.dv_project_no = '0';

    $scope.expense_type_chosen = '0';
    $scope.total_amount = 0;

    $scope.payees = payees;
    $scope.batches = batches;

    $scope.i_am_payee_check = true;
    $scope.payee_chosen = '0';

    $scope.payee_name = '';
    $scope.payee_address = '';
    $scope.payee_id_no = '';
    $scope.payee_bank = '';
    $scope.payee_bank_no = '';
    $scope.payee_bank_name = '';
    
    $scope.dv_items = [];
    $scope.dv_item_chosen = '0';
    
    $scope.choose_dv_item = () => {
        if ($scope.dv_item_chosen == '1') {
            $scope.dv_items = [{'course_no':'00', 'batch_no':'000'}];
        } else {
            $scope.dv_items = [];
            $scope.dv_course_chosen = '0';
        }
    }

    $scope.course_names = [];
    $scope.course_name_list = [];
    $scope.dv_course_chosen = '0';

    angular.forEach($scope.batches, value => {
        value.batch_name = value.course_name + ' รุ่นที่ ' + Number(value.batch_no)
        if (!$scope.course_name_list.includes(value.course_name)) {
            $scope.course_name_list.push(value.course_name);
            $scope.course_names.push({'course_no':value.course_no, 'course_name':value.course_name});
        }
    });

    $scope.choose_course_name = () => {
        if ($scope.dv_item_chosen == '2' && $scope.dv_course_chosen != '0') {
            $scope.dv_items = [{'course_no':$scope.dv_course_chosen, 'batch_no':'000'}];
        } else {
            $scope.dv_items = [];
        }
    }

    $scope.dv_batch_chosen = '0';

    $scope.choose_course_batch = () => {
        if ($scope.dv_item_chosen == '3' && $scope.dv_batch_chosen != '0') {
            var detail = $scope.dv_batch_chosen.split(':');
            $scope.dv_items = [{'course_no':detail[0], 'batch_no':detail[1]}];
        } else {
            $scope.dv_items = [];
        }
    }

    $scope.is_clicked = false;

    $scope.form_validate = () => {
        if ($scope.dv_type_chosen == '0') {
            $('#form_validate_1_1').modal('toggle');
        } else if ($scope.dv_project_no == '0') {
            $('#form_validate_1_2').modal('toggle');
        } else if ($scope.expense_type_chosen == '0') {
            $('#form_validate_2').modal('toggle');
        } else if ($scope.total_amount == 0) {
            $('#form_validate_3').modal('toggle');
        } else if (!$scope.i_am_payee_check) {
            if ($scope.payee_chosen == '0') {
                $('#form_validate_4').modal('toggle');
            } else if ($scope.payee_chosen == 'add' && ($scope.payee_name == '' || $scope.payee_address == '' || $scope.payee_id_no == '' || $scope.payee_bank == '' || $scope.payee_bank_no == '' || $scope.payee_bank_name == '')) {
                $('#form_validate_5').modal('toggle');
            } else {
                $scope.dv_items[0].dv_item_amount = $scope.total_amount;
                $('#confirm_modal').modal('toggle');
            }
        } else {
            if ($scope.dv_item_chosen == '4') {
                var total_amount = 0;
                $scope.dv_items = [];
                angular.forEach($scope.batches, value => {
                    var item_amount = Number($('#amount_' + value.course_no + '_' + value.batch_no).val());
                    total_amount += item_amount;
                    if (item_amount != 0) {
                        $scope.dv_items.push({'course_no':value.course_no, 'batch_no':value.batch_no, 'dv_item_amount':item_amount});
                    }
                });
                if (total_amount != $scope.total_amount) {
                    $('#form_validate_6').modal('toggle');
                } else {
                    $('#confirm_modal').modal('toggle');
                }
            } else {
                $scope.dv_items[0].dv_item_amount = $scope.total_amount;
                $('#confirm_modal').modal('toggle');
            }
        }
    }

    $scope.add_dv = () => {

        if(!$scope.is_clicked) {

            $scope.is_clicked = true;
            $('#confirm_modal').modal('hide');

            $.post("/home/disbursement_voucher/add_dv", {
                post: true,
                dv_type: $scope.dv_type_chosen,
                dv_project_no: $scope.dv_project_no,
                dv_account_no: $scope.expense_type_chosen,
                i_am_payee_check: $scope.i_am_payee_check,
                payee_chosen: $scope.payee_chosen,
                payee_name: $scope.payee_name,
                payee_address: $scope.payee_address,
                payee_id_no: $scope.payee_id_no,
                payee_bank: $scope.payee_bank,
                payee_bank_no: $scope.payee_bank_no ,
                payee_bank_name: $scope.payee_bank_name,
                dv_items: JSON.stringify(angular.toJson($scope.dv_items))
            }, function(data) {
                if(data == 'error') {
                    $scope.is_clicked = false;
                    $('#error_modal').modal('toggle');
                } else {
                    add_modal('complete_modal', 'บันทึกใบเบิกเงินสำเร็จ (' + data + ')');
                    $('#complete_modal').modal('toggle');
                    $('#complete_modal').on('hide.bs.modal', e => location.assign('/'));
                }
            });

        }

    }

});