<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/<?php echo $this->page; ?>/disbursement_voucher_list.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
    <script>var employee_no = '<?php echo $this->employee_no; ?>';</script>
        
    <div class="container pt-2">

        <h4 class="my-2">
            รายการใบเบิกเงิน - List of Disbursement Voucher 
            <?php if ($this->page == 'acc') echo '<button class="btn btn-default btn-sm float-right" ng-click="add_pa_validate()"><i class="fa fa-money-check-alt" aria-hidden="true"></i> ออกใบสำคัญจ่ายสำหรับเติมเงินรองจ่าย</button>'; ?>
        </h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline ml-auto mb-0">
            
                <label for="search_textbox">ค้นหาใบเบิกเงิน&nbsp;</label>
                <input type="text" class="form-control form-control-sm" ng-model="filter1" placeholder="ค้นหาใบเบิกเงิน" id="search_textbox">

                &nbsp;&nbsp;

                <select class="form-control form-control-sm" ng-model="filter_dv_type">
                    <option value="">เลือกประเภทการเบิกเงิน</option>
                    <option value="1">ยอดไม่เกิน 5,000 บาท หรือค่าวิทยากร</option>
                    <option value="2">ยอดที่มากกว่า 5,000 บาท</option>
                </select>

                &nbsp;&nbsp;

                <select class="form-control form-control-sm" ng-model="filter2">
                    <option value="">เลือกสถานะใบเบิกเงิน</option>
                    <option value="รออนุมัติ">รออนุมัติ</option>
                    <option value="ยังไม่ได้จ่ายเงิน">ยังไม่ได้จ่ายเงิน</option>
                    <option value="รอยืนยันการจ่าย">รอยืนยันการจ่าย</option>
                    <option value="จ่ายเงินแล้ว">จ่ายเงินแล้ว</option>
                    <option value="รอออกใบสำคัญจ่าย">รอออกใบสำคัญจ่าย</option>
                    <option value="ยกเลิกแล้ว">ยกเลิกแล้ว</option>
                </select>

            </form>

            <table class="table mt-3 mb-0">
                <tr>
                    <th ng-click="order_by_fn('dv_no')">เลขที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='dv_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('dv_datetime')">วันที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='dv_datetime'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('dv_employee_no')">ผู้ขอเบิก <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='dv_employee_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('payee_name')">ผู้รับเงิน <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='payee_name'" aria-hidden="true"></i></th>
                    <th>จำนวนเงิน</th>
                    <th>สลิปโอนเงิน</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
                <tr ng-show="is_load">
                    <td colspan="8" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</td>
                </tr>
                <tr ng-show="dvs.length == 0">
                    <td colspan="8" style="text-align:center;">ไม่มีใบเบิกเงิน</td>
                </tr>
                <tr ng-repeat="dv in dvs | filter:filter1 | filter:filter2 | filter:{'dv_type':filter_dv_type} | orderBy:order_by:reverse" ng-show="dvs.length > 0">
                    <td>{{dv.dv_no}}</td>
                    <td>{{dv.dv_datetime}}</td>
                    <td>{{dv.dv_employee_no}} {{dv.employee_nickname}}</td>
                    <td>{{dv.payee_name}}</td>
                    <td>{{dv.dv_total_amount | number:2}}</td>
                    <td>
                        <small ng-show="dv.dv_slip_no != null">
                            <span style="cursor: pointer; text-decoration:underline;" ng-click="open_dv_slip(dv.dv_slip_no)"><i class="fa fa-share" aria-hidden="true"></i> {{dv.dv_slip_no}}</span>
                        </small>
                    </td>
                    <td>{{dv.dv_status}}</td>
                    <td>
                        <h5 class="my-0">
                            <i class="fa fa-ellipsis-h dropdown" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.4em;"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" compile="dv.menu"></div>
                        </h5>
                    </td>
                </tr>
            </table>

        </div>

        <?php if($this->page == 'fin') include('disbursement_voucher_list_fin.php'); ?>

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