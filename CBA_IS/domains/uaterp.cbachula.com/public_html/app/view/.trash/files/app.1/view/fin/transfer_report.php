<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script> var amount = <?php echo $this->amount; ?>; </script>
    <script> var trs = <?php echo $this->trs; ?>; </script>
    <script type="text/javascript" src="/public/js/fin/transfer_report.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
        
    <div class="container pt-2">

        <h4 class="my-2">รายงานการโอนเงิน - Transfer Report</h4>

        <div class="row part mx-0 mt-3 p-3">

            <div class="col">

                <div class="row">
                    <div class="col-4">
                        <label for="amount_1">โครงการพิเศษ 1 <small>(สูงสุด: {{tr_amount_1_before}} บาท)</small></label>
                        <input class="form-control" type="number" id="amount_1" name="amount_1" ng-model="tr_amount_1" ng-change="check_amount(1)">
                        <small ng-show="warning_1"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> จำนวนเงินที่กรอกมากเกินไป</small>
                    </div>
                    <div class="col-4">
                        <label for="amount_2">โครงการพิเศษ 2 <small>(สูงสุด: {{tr_amount_2_before}} บาท)</small></label>
                        <input class="form-control" type="number" id="amount_2" name="amount_2" ng-model="tr_amount_2" ng-change="check_amount(2)">
                        <small ng-show="warning_2"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> จำนวนเงินที่กรอกมากเกินไป</small>
                    </div>
                    <div class="col-4">
                        <label for="total_amount">รวม</label>
                        <input class="form-control" type="number" id="total_amount" name="total_amount" ng-model="tr_amount_1 + tr_amount_2" disabled>
                    </div>
                </div>
                
            </div>

            <button class="btn btn-default btn-block mt-3" ng-click="add_tr_validate()">ยืนยันการบันทึกรายงานการโอนเงิน</button>

        </div>

        <div class="row part mx-0 mt-3 p-3">

            <table class="table mt-3 mb-0">
                <tr>
                    <th ng-click="order_by_fn()">เลขที่รายงานการโอน <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" aria-hidden="true"></i></th>
                    <th>วันที่รายงาน</th>
                    <th>ชื่อผู้ออกรายงาน</th>
                    <th>ยอดโอนเงิน (โครงการพิเศษ 1)</th>
                    <th>ยอดโอนเงิน (โครงการพิเศษ 2)</th>
                </tr>
                <tr ng-show="trs.length == 0">
                    <td colspan="5" style="text-align:center;">ไม่มีประวัติรายงานการโอน</td>
                </tr>
                <tr ng-repeat="tr in trs | orderBy:order_by:reverse" ng-show="trs.length > 0">
                    <td>{{tr.tr_no}}</td>
                    <td>{{tr.tr_datetime}}</td>
                    <td>{{tr.tr_employee_no}} {{tr.employee_nickname}}</td>
                    <td>{{tr.amount_1}}</td>
                    <td>{{tr.amount_2}}</td>
                </tr>
            </table>

        </div>

    </div>
    
</body>
</html>