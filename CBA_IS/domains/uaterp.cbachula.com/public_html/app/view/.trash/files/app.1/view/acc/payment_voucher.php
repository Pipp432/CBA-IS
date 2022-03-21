<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/acc/payment_voucher.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>

    <div class="container pt-2">

        <h4 class="my-2">ใบสำคัญจ่าย - Payment Voucher (PV)</h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline">

                <label for="pv_project_no">สั่งจ่ายในนาม&nbsp;</label>
                <select class="form-control" id="pv_project_no" ng-model="pv_project_no_chosen">
                    <option value="0">เลือกโครงการ</option>
                    <option value="1">โครงการพิเศษ 1</option>
                    <option value="2">โครงการพิเศษ 2</option>
                </select>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="pv_type_select">ประเภทใบสำคัญจ่าย&nbsp;</label>
                <select class="form-control" id="pv_type_select" ng-model="pv_type_chosen" ng-change="choose_pv_type()">
                    <option value="0">เลือกประเภทใบสำคัญจ่าย</option>
                    <option value="A">จ่ายเงินเพื่อเติมเงินรองจ่าย</option>
                    <option value="B">จ่ายเงินที่มียอดที่มากกว่า 5,000 บาท</option>
                    <option value="C">จ่ายเงินเพื่อลดหนี้</option>
                </select>

            </form>

            <br>

            <table class="table mt-3 mb-0" ng-show="pv_type_chosen != '0'">
                <tr>
                    <th ng-click="order_by_fn('dv_no')">เลขที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='dv_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('dv_datetime')">วันที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='dv_datetime'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('dv_employee_no')">ผู้ขอเบิก <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='dv_employee_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('payee_name')">ผู้รับเงิน <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='payee_name'" aria-hidden="true"></i></th>
                    <th>จำนวนเงิน</th>
                    <th></th>
                </tr>
                <tr ng-show="is_load">
                    <td colspan="6" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</td>
                </tr>
                <tr ng-show="items.length == 0">
                    <td colspan="6" style="text-align:center;">ไม่มีข้อมูลที่ใช้ออกใบสำคัญจ่าย</td>
                </tr>
                <tr ng-repeat="item in items | orderBy:order_by:reverse" ng-show="items.length > 0">
                    <td>{{item.dv_no}}</td>
                    <td>{{item.dv_datetime}}</td>
                    <td>{{item.dv_employee_no}} {{item.employee_nickname}}</td>
                    <td>{{item.payee_name}}</td>
                    <td>{{item.dv_total_price | number:2}}</td>
                    <td>
                        <h5 class="my-0">
                            <i class="fa fa-ellipsis-h dropdown" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.4em;"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" compile="item.menu"></div>
                        </h5>
                    </td>
                </tr>
            </table>

            <button class="btn btn-default btn-block mt-2" ng-show="item_type_chosen!='0'" ng-click="form_validate()">ยืนยันการออกใบสำคัญจ่าย</button>

        </div>

    </div>

    <style>
    </style>
    
</body>
</html>