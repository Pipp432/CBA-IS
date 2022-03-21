<div class="row part mx-0 mt-3 p-3" ng-show="to_paid_dvs.length > 0">

    <h5>รายการโอนเงิน</h5>

    <table class="table mt-2 mb-0">
        <tr>
            <td colspan="3"><b>ชื่อผู้รับเงิน</b> : {{to_paid_dvs[0].payee_name}}</td>
        </tr>
        <tr>
            <td><b>ธนาคารของบัญชีผู้รับเงิน</b> : {{to_paid_dvs[0].payee_bank}}</td>
            <td><b>เลขที่บัญชีผู้รับเงิน</b> : {{to_paid_dvs[0].payee_bank_no}}</td>
            <td><b>ชื่อบัญชีผู้รับเงิน</b> : {{to_paid_dvs[0].payee_bank_name}}</td>
        </tr>
    </table>

    <table class="table mt-2 mb-0">
        <tr>
            <th></th>
            <th ng-click="order_by_fn('dv_no')">เลขที่ <i class="fa fa-sort-amount-down-alt" aria-hidden="true" ng-show="order_by == 'dv_no'"></i></th>
            <th>วันที่</th>
            <th>ผู้ขอเบิก</th>
            <th>ประเภทค่าใช้จ่าย</th>
            <th>จำนวนเงิน</th>
        </tr>
        <tr ng-repeat="to_paid_dv in to_paid_dvs">
            <td>
                <i class="fa fa-times-circle" aria-hidden="true" ng-click="drop_to_paid_dv(to_paid_dv)" style="cursor: pointer;"></i>
            </td>
            <td>{{to_paid_dv.dv_no}}</td>
            <td>{{to_paid_dv.dv_datetime}}</td>
            <td>{{to_paid_dv.dv_employee_no}} {{to_paid_dv.employee_nickname}}</td>
            <td>{{to_paid_dv.account_name}}</td>
            <td style="text-align:right;">{{to_paid_dv.dv_total_price | number:2}}</td>
        </tr>
        <tr>
            <th colspan="5" style="text-align: right;">รวม</th>
            <th style="text-align:right;">{{to_paid_price | number:2}}</th>
        </tr>
    </table>

    <form class="form-inline ml-auto mt-3 mb-0">
        <label for="slip_file">อัปโหลดไฟล์สลิปการโอนเงิน&nbsp;</label>
        <input class="form-control-file" style="width: 250px;" type="file" id="slip_file">
        &nbsp;&nbsp;
        <button class="btn btn-default" ng-click="add_slip_validate()">อัปโหลด</button>
    </form>

</div>