<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/<?php echo $this->page; ?>/payment_voucher_list.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
        
    <div class="container pt-2">

        <h4 class="my-2">รายการใบสำคัญจ่าย - List of Payment Voucher</h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline ml-auto mb-0">
                <label for="search_textbox">ค้นหาใบสำคัญจ่าย&nbsp;</label>
                <input type="text" class="form-control form-control-sm" ng-model="filter1" placeholder="ค้นหาใบสำคัญจ่าย" id="search_textbox">
                &nbsp;&nbsp;
                <select class="form-control form-control-sm" ng-model="filter_pv_type">
                    <option value="">เลือกประเภทการเบิกเงิน</option>
                    <option value="1">เติมเงินรองจ่าย</option>
                    <option value="2">ยอดที่มากกว่า 5,000 บาท</option>
                    <option value="3">จ่ายภาษี</option>
                    <option value="4">ลดหนี้</option>
                </select>
                &nbsp;&nbsp;
                <select class="form-control form-control-sm" ng-model="filter2">
                    <option value="">เลือกสถานะใบสำคัญจ่าย</option>
                    <option ng-repeat="pv_status in pv_statuses" value="{{pv_status}}">{{pv_status}}</option>
                </select>
            </form>

            <table class="table mt-3 mb-0">
                <tr>
                    <th ng-click="order_by_fn('pv_no')">เลขที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='pv_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('pv_datetime')">วันที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='pv_datetime'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('pv_employee_no')">ผู้ขอเบิก <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='pv_employee_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('payee_name')">ผู้รับเงิน <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='payee_name'" aria-hidden="true"></i></th>
                    <th>จำนวนเงิน</th>
                    <th>สลิปโอนเงิน</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
                <tr ng-show="is_load">
                    <td colspan="8" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</td>
                </tr>
                <tr ng-show="pvs.length == 0">
                    <td colspan="8" style="text-align:center;">ไม่มีใบสำคัญจ่าย</td>
                </tr>
                <tr ng-repeat="pv in pvs | filter:filter1 | filter:filter2 | filter:{'pv_type':filter_pv_type} | orderBy:order_by:reverse" ng-show="pvs.length > 0">
                    <td>{{pv.pv_no}}</td>
                    <td>{{pv.pv_datetime}}</td>
                    <td>{{pv.pv_employee_no}} {{pv.employee_nickname}}</td>
                    <td>{{pv.payee_name}}</td>
                    <td>{{pv.pv_total_amount | number:2}}</td>
                    <td>
                        <small ng-show="pv.pv_slip_no != null">
                            <span style="cursor: pointer; text-decoration:underline;" ng-click="open_pv_slip(pv.pv_slip_no)"><i class="fa fa-share" aria-hidden="true"></i> {{pv.pv_slip_no}}</span>
                        </small>
                    </td>
                    <td>{{pv.pv_status}}</td>
                    <td>
                        <h5 class="my-0">
                            <i class="fa fa-ellipsis-h dropdown" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.4em;"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" compile="pv.menu"></div>
                        </h5>
                    </td>
                </tr>
            </table>

        </div>

        <?php if($this->page == 'fin') include('payment_voucher_list_fin.php'); ?>

    </div>

    <style>

        .badge {
            font-weight: normal !important;
            cursor: pointer;
            border: 1px solid transparent;
        }

        td {
            vertical-align: middle !important;
        }

    </style>

</body>
</html>