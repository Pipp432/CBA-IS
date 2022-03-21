
// add_modal('add_to_paid_dv_modal1', 'เลือกเฉพาะใบเบิกเงินที่อนุมัติแล้วแต่ยังไม่โอนเงินเท่านั้น');
// add_modal('add_to_paid_dv_modal2', 'เลือกพนักงานผู้ขอเบิกเงินคนเดียวกันเท่านั้น');
// add_modal('add_to_paid_dv_modal3', 'เพิ่มใบเบิกเงินนี้แล้ว');


add_modal('cancel_dv_modal', 'ยืนยันการยกเลิกใบเบิกเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="cancel_dv()">ยืนยัน</button>');
add_modal('cancel_dv_complete_modal', 'ยกเลิกใบเบิกเงินสำเร็จ');
add_modal('cancel_dv_error_modal', 'เกิดปัญหาระหว่างการยกเลิก กดใหม่อีกครั้ง');

// add_modal('add_slip_modal', 'ยืนยันการอัปโหลดสลิปโอนเงิน?<br><button type="button" class="btn btn-default btn-block mt-2" ng-click="add_slip()">ยืนยัน</button>');
// add_modal('add_slip_complete_modal', 'อัปโหลดสลิปโอนเงินสำเร็จ');
// add_modal('add_slip_error_modal', 'เกิดปัญหาระหว่างการอัปโหลด กดใหม่อีกครั้ง');

// add_modal('confirm_paid_dv_modal', 'ยืนยันการจ่ายเงิน?<br> \
//                                     <form class="form-row mt-2"> \
//                                         <div class="col-5"> \
//                                             <label class="mb-1" for="dv_proof_date">วันที่ใบสำคัญ&nbsp;</label> \
//                                             <input type="date" class="form-control" id="dv_proof_date" ng-model="dv_proof_date"> \
//                                         </div> \
//                                         <div class="col-5"> \
//                                             <label class="mb-1" for="dv_proof_vat">ภาษีซื้อ&nbsp;</label> \
//                                             <input type="number" class="form-control" id="dv_proof_vat" ng-model="dv_proof_vat" value="0"> \
//                                         </div> \
//                                         <div class="col-2"> \
//                                             <label class="mb-1" for="dv_project">โครงการ&nbsp;</label> \
//                                             <select class="form-control" id="dv_project" ng-model="dv_project"> \
//                                                 <option value="-">เลือก</option> \
//                                                 <option value="1">1</option> \
//                                                 <option value="2">2</option> \
//                                             </select> \
//                                         </div> \
//                                     </form> \
//                                     <button type="button" class="btn btn-default btn-block mt-2" ng-click="confirm_paid_dv_validate2()">ยืนยัน</button>');
// add_modal('confirm_paid_dv_complete_modal', 'ยืนยันการจ่ายเงินสำเร็จ');
// add_modal('confirm_paid_dv_error_modal', 'เกิดปัญหาระหว่างการยืนยันการจ่ายเงิน กดใหม่อีกครั้ง');
// add_modal('confirm_paid_dv_validate_modal1', 'ยังไม่ได้ใส่วันที่ใบสำคัญ');
// add_modal('confirm_paid_dv_validate_modal2', 'ภาษีซื้อต้องมีค่ามากกว่า 0');
// add_modal('confirm_paid_dv_validate_modal3', 'ภาษีซื้อมีค่ามากกว่าหรือน้อยกว่าที่ควรจะเป็น');
// add_modal('confirm_paid_dv_validate_modal4', 'ยังไม่ได้เลือกโครงการ');

app.controller('app_controller', $scope => {

    $scope.dvs = dvs;

    $scope.order_by = 'dv_datetime';
    $scope.reverse = true;
    $scope.order_by_fn = (feature) => {
        $scope.reverse = ($scope.order_by == feature) ? !$scope.reverse : true;
        $scope.order_by = feature;
    }
    
    // $scope.dv_statuses = ['ยกเลิกแล้ว', 'ยืนยันการจ่ายเงินแล้ว', 'โอนเงินแล้ว', 'ยังไม่ได้โอนเงิน', 'รออนุมัติ', 'รอออกใบสำคัญจ่าย'];

    // angular.forEach($scope.dvs, value => {
    //     if(value.dv_cancelled == '1') {
    //         value.dv_status = 0;
    //     } else if (value.dv_paid == '1') { 
    //         value.dv_status = 1;
    //     } else if (value.dv_slip_no != null) { 
    //         value.dv_status = 2;
    //     } else if (value.dv_approve_employee_no != null) { 
    //         if (value.dv_type == 'A') {
    //             value.dv_status = 3;
    //         } else if (value.dv_type == 'B' && value.pv_no == null) {
    //             value.dv_status = 5;
    //         } else {
    //             value.dv_status = 6;
    //         }
    //     } else { 
    //         value.dv_status = 4;
    //     }
    //     value.dv_status_text = value.dv_status == 6 ? value.pv_no : $scope.dv_statuses[value.dv_status];
    // });

    $scope.open_db_proof = dv_no => window.open('/file/dv_proof/db/' + dv_no);
    $scope.open_dv_proof = dv_no => window.open('/file/dv_proof/dv/' + dv_no);
    $scope.open_dv_slip = slip_no => window.open('/file/slip/' + slip_no);

    // $scope.is_confirm_paid_dv_clicked = false;
    // $scope.temp_confirm_paid_dv = null;
    // $scope.dv_proof_date = '0';
    // $scope.dv_proof_vat = 0;
    // $scope.dv_project = '0';
    
    // $('#confirm_paid_dv_modal').on('hide.bs.modal', e => {
    //     $scope.dv_proof_date = '0';
    //     $scope.dv_proof_vat = 0;
    //     $scope.dv_project = '0';
    // });

    // $scope.confirm_paid_dv_validate1 = (dv) => {
    //     if (dv.dv_type == 'A' && dv.dv_status == 2) {
    //         $scope.temp_confirm_paid_dv = dv;
    //         $('#confirm_paid_dv_modal').modal('toggle');
    //     } else {
    //         $('#confirm_paid_dv_error_modal').modal('toggle');
    //     }
    // }
    
    // $scope.confirm_paid_dv_validate2 = () => {
    //     if($scope.dv_proof_date == '0') {
    //         $('#confirm_paid_dv_validate_modal1').modal('toggle');
    //     } else if($scope.dv_proof_vat < 0) {
    //         $('#confirm_paid_dv_validate_modal2').modal('toggle');
    //     } else if(Math.abs($scope.dv_proof_vat - (Number($scope.temp_confirm_paid_dv.dv_total_price) / 107 * 7)) > 10 && $scope.dv_proof_vat != 0) {
    //         $('#confirm_paid_dv_validate_modal3').modal('toggle');
    //     } else if($scope.dv_project == '0') {
    //         $('#confirm_paid_dv_validate_modal4').modal('toggle');
    //     } else {
    //         $scope.confirm_paid_dv();
    //     }
    // }

    // $scope.confirm_paid_dv = () => {

    //     if(!$scope.is_confirm_paid_dv_clicked) {

    //         var dv_proof_date = $scope.dv_proof_date.getFullYear() + '-' + 
    //                             (($scope.dv_proof_date.getMonth() + 1) < 10 ? '0' : '') + ($scope.dv_proof_date.getMonth() + 1) + '-' + 
    //                             ($scope.dv_proof_date.getDate() < 10 ? '0' : '') + $scope.dv_proof_date.getDate();
    //         var dv_proof_vat = $scope.dv_proof_vat;
    //         var dv_project = $scope.dv_project;

    //         $scope.is_confirm_paid_dv_clicked = true;

    //         $('#confirm_paid_dv_modal').modal('hide');
            
    //         $.post("/acc/disbursement_voucher_list/confirm_paid_dv", {
    //             post : true,
    //             dv_no: $scope.temp_confirm_paid_dv.dv_no,
    //             account_no: $scope.temp_confirm_paid_dv.dv_account_no,
    //             total_price: $scope.temp_confirm_paid_dv.dv_total_price,
    //             dv_proof_vat: dv_proof_vat,
    //             dv_proof_date: dv_proof_date,
    //             dv_project: dv_project
    //         }, function(data) {
    //             $scope.temp_confirm_paid_dv = null;
    //             if(data == 'error') {
    //                 $scope.is_confirm_paid_dv_clicked = false;
    //                 $('#confirm_paid_dv_error_modal').modal('toggle');
    //             } else {
    //                 $('#confirm_paid_dv_complete_modal_text').append(' (' + data + ')');
    //                 $('#confirm_paid_dv_complete_modal').modal('toggle');
    //                 $('#confirm_paid_dv_complete_modal').on('hide.bs.modal', e => location.reload());
    //             }
    //         });

    //     }

    // }

    // $scope.to_paid_dvs = [];
    // $scope.to_paid_price = 0;

    // $scope.add_to_paid_dv = dv => {

    //     var new_dv = true;

    //     if(dv.dv_status != 3) {
    //         $('#add_to_paid_dv_modal1').modal('toggle');
    //     } else if($scope.to_paid_dvs.length > 0 && $scope.to_paid_dvs[0].dv_employee_no != dv.dv_employee_no) {
    //         $('#add_to_paid_dv_modal2').modal('toggle');
    //     } else {

    //         angular.forEach($scope.to_paid_dvs, value => {
    //             if(value.dv_no == dv.dv_no) {
    //                 new_dv = false;
    //             }
    //         });

    //         if(new_dv) {
    //             $scope.to_paid_dvs.push(dv);
    //             $scope.calculate_to_paid_price();
    //         } else {
    //             $('#add_to_paid_dv_modal3').modal('toggle');
    //         }

    //     }

    // }

    // $scope.drop_to_paid_dv = (dv) => {

    //     var temp_to_paid_dvs = [];

    //     angular.forEach($scope.to_paid_dvs, value => {
    //         if(value.dv_no != dv.dv_no) {
    //             temp_to_paid_dvs.push(dv);
    //         }
    //     });

    //     $scope.to_paid_dvs = temp_to_paid_dvs;
    //     $scope.calculate_to_paid_price();

    // }

    // $scope.calculate_to_paid_price = () => {
    //     $scope.to_paid_price = 0;
    //     angular.forEach($scope.to_paid_dvs, value => {
    //         $scope.to_paid_price += Number(value.dv_total_price);
    //     });
    // }

    // $scope.is_add_slip_clicked = false;

    // $scope.add_slip_validate = () => {
    //     if(!$scope.is_add_slip_clicked) {
    //         $('#add_slip_modal').modal('toggle');
    //     }
    // }

    // $scope.add_slip = () => {

    //     $('#add_slip_modal').modal('hide');
    //     $scope.is_add_slip_clicked = true;

    //     var data = new FormData();

    //     data.append('post', true);
    //     data.append('to_paid_dvs', JSON.stringify(angular.toJson($scope.to_paid_dvs)));
    //     data.append('dv_slip', $('#slip_file')[0].files[0]);

    //     $.ajax({
    //         url: '/fin/disbursement_voucher_list/add_slip',
    //         data: data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         method: 'POST',
    //         type: 'POST',
    //         success: function (result) {
    //             if(result == 'error') {
    //                 $scope.is_add_slip_clicked = false;
    //                 $('#add_slip_error_modal').modal('toggle');
    //             } else {
    //                 $('#add_slip_complete_modal').modal('toggle');
    //                 $('#add_slip_complete_modal').on('hide.bs.modal', e => location.reload());
    //             }
    //         }
    //     });

    // }

    

    // $scope.is_cancel_dv_clicked = false;
    // $scope.temp_cancel_dv_no = '';

    // $scope.cancel_dv_validate = (dv_no) => {
    //     if (!$scope.is_cancel_dv_clicked) {
    //         $scope.temp_cancel_dv_no = dv_no;
    //         $('#cancel_dv_modal').modal('toggle');
    //     }
    // }

    // $scope.cancel_dv = () => {
    //     $scope.is_cancel_dv_clicked = true;
    //     $('#cancel_dv_modal').modal('hide');
    //     $.post("/fin/disbursement_voucher_list/cancel_dv", {
    //         post : true,
    //         dv_no: $scope.temp_cancel_dv_no
    //     }, function(data) {
    //         $scope.temp_cancel_dv_no = '';
    //         if(data == 'error') {
    //             $scope.is_cancel_dv_clicked = false;
    //             $('#cancel_dv_error_modal').modal('toggle');
    //         } else {
    //             $('#cancel_dv_complete_modal_text').append(' (' + data + ')');
    //             $('#cancel_dv_complete_modal').modal('toggle');
    //             $('#cancel_dv_complete_modal').on('hide.bs.modal', e => location.reload());
    //         }
    //     });
    // }


});