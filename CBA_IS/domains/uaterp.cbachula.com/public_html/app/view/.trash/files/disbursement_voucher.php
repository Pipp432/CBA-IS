<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/home/disbursement_voucher.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
    <script type="text/javascript" src="/public/data/expense_account_chart.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
    <script> var payees = <?php echo $this->payees; ?>; </script>
    <script> var batches = <?php echo $this->batches; ?>; </script>
        
    <div class="container pt-2">

        <h4 class="my-2">ใบเบิกเงิน - Disbursement Voucher (DV)</h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline">

                <label for="dv_type_select">ประเภทการเบิกเงิน&nbsp;</label>
                <select class="form-control" id="dv_type_select" ng-model="dv_type_chosen">
                    <option value="0">เลือกประเภทการเบิกเงิน</option>
                    <option value="1">เบิกเงินที่มียอดไม่เกิน 5,000 บาท หรือค่าวิทยากร</option>
                    <option value="2">เบิกเงินที่มียอดที่มากกว่า 5,000 บาท</option>
                </select>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="dv_project_no">โครงการ&nbsp;</label>
                <select class="form-control" id="dv_type_select" ng-model="dv_project_no">
                    <option value="0">เลือกโครงการ</option>
                    <option value="1">โครงการพิเศษ 1</option>
                    <option value="2">โครงการพิเศษ 2</option>
                </select>

            </form>

            <br>

            <table class="mt-2 table" ng-show="dv_type_chosen != '0' && dv_project_no != '0'">
                <tr>
                    <td style="width: 25%;">ประเภทค่าใช้จ่าย</td>
                    <td style="width: 25%;">จำนวนเงิน</td>
                    <td style="width: 25%;">ผู้รับเงิน</td>
                    <td style="width: 25%;">วิธีการบันทึกค่าใช้จ่าย</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <select class="form-control" id="expense_type_select" ng-model="expense_type_chosen">
                            <option value="0">เลือกประเภทค่าใช้จ่าย</option>
                            <script> account_nos.forEach((element) => document.write('<option value="' + element.account_no + '">' + element.account_name + '</option>')); </script>
                        </select>
                    </td>
                    <td style="vertical-align: top;">
                        <input class="form-control" type="number" ng-model="total_amount" min="0">
                    </td>
                    <td style="vertical-align: top;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="i_am_payee_check" ng-model="i_am_payee_check">
                            <label class="form-check-label" for="i_am_payee_check">เป็นผู้รับเงินเอง</label>
                        </div>
                        <select class="form-control mt-1" ng-model="payee_chosen" ng-change="choose_payee()" ng-show="!i_am_payee_check">
                            <option value="0">เลือกชื่อผู้รับเงิน</option>
                            <option value="add">เพิ่มข้อมูลผู้รับเงิน</option>
                            <option ng-repeat="payee in payees" value="{{payee.payee_no}}">{{payee.payee_name}}</option>
                        </select>
                        <span ng-show="!i_am_payee_check && payee_chosen == 'add'">
                            <input class="form-control form-control-sm mt-1" type="text" ng-model="payee_name" placeholder="ชื่อผู้รับเงิน">
                            <textarea class="form-control form-control-sm mt-1" row="3" ng-model="payee_address" placeholder="ที่อยู่ผู้รับเงินตามบัตรประจำตัวประชาชน"></textarea>
                            <input class="form-control form-control-sm mt-1" type="text" ng-model="payee_id_no" placeholder="เลขประจำตัวผู้เสียภาษีผู้รับเงิน">
                            <input class="form-control form-control-sm mt-1" type="text" ng-model="payee_bank" placeholder="ธนาคารของบัญชีผู้รับเงิน">
                            <input class="form-control form-control-sm mt-1" type="text" ng-model="payee_bank_no" placeholder="เลขที่บัญชีผู้รับเงิน">
                            <input class="form-control form-control-sm mt-1" type="text" ng-model="payee_bank_name" placeholder="ชื่อบัญชีผู้รับเงิน">
                        </span>
                    </td>
                    <td style="vertical-align: top;">
                        <select class="form-control" ng-model="dv_item_chosen" ng-change="choose_dv_item()">
                            <option value="0">เลือกวิธีการบันทึกค่าใช้จ่าย</option>
                            <option value="1">ทุกคอร์ส</option>
                            <option value="2">เฉพาะคอร์ส</option>
                            <option value="3">เลือกเฉพาะรุ่น</option>
                            <option value="4">กำหนดเอง</option>
                        </select>
                        <select class="form-control mt-1" ng-model="dv_course_chosen" ng-change="choose_course_name()" ng-show="dv_item_chosen == '2'">
                            <option value="0">เลือกคอร์ส</option>
                            <option ng-repeat="course_name in course_names" value="{{course_name.course_no}}">{{course_name.course_name}}</option>
                        </select>
                        <select class="form-control mt-1" ng-model="dv_batch_chosen" ng-change="choose_course_batch()" ng-show="dv_item_chosen == '3'">
                            <option value="0">เลือกรุ่น</option>
                            <option ng-repeat="batch in batches" value="{{batch.course_no}}:{{batch.batch_no}}">{{batch.batch_name}}</option>
                        </select>
                        <div class="row mt-1" ng-repeat="batch in batches" ng-show="dv_item_chosen == '4'">
                            <div class="col-7"><span style="vertical-align: middle;">{{batch.batch_name}}</span></div> 
                            <div class="col-5"><input class="form-control form-control-sm" type="number" style="vertical-align: middle;" id="amount_{{batch.course_no}}_{{batch.batch_no}}" value="0"></div> 
                        </form>
                    </td>
                </tr>
            </table>

            <button class="btn btn-default btn-block mt-2" ng-show="dv_type_chosen!='0' && dv_project_no != '0'" ng-click="form_validate()">ยืนยันการออกใบเบิกเงิน</button>

        </div>

    </div>
    
</body>
</html>