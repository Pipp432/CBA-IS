<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/acc/tax_invoice_list.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
        
    <div class="container pt-2">

        <h4 class="my-2">รายการใบกำกับภาษี - List of Tax Invoice</h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline ml-auto mb-0">
                <label for="search_textbox">ค้นหาใบกำกับภาษี&nbsp;</label>
                <input type="text" class="form-control form-control-sm" ng-model="filter1" placeholder="ค้นหาใบกำกับภาษี" id="search_textbox">
                &nbsp;&nbsp;
                <select class="form-control form-control-sm" ng-model="filter2">
                    <option value="">เลือกสถานะใบกำกับภาษี</option>
                    <option value="ยังไม่ได้ส่งใบ 50 ทวิ">ยังไม่ได้ส่งใบ 50 ทวิ</option>
                    <option value="อยู่ในขั้นตอนลดหนี้">อยู่ในขั้นตอนลดหนี้</option>
                    <option value="ลดหนี้แล้ว">ลดหนี้แล้ว</option>
                </select>
            </form>

            <table class="table mt-3 mb-0">
                <tr>
                    <th ng-click="order_by_fn('iv_no')">เลขที่ใบกำกับภาษี <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='iv_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('iv_date')">วันที่ <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='iv_date'" aria-hidden="true"></i></th>
                    <th>เลขที่การสมัคร</th>
                    <th>ประเภทการสมัคร</th>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
                <tr ng-show="is_load">
                    <td colspan="7" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</td>
                </tr>
                <tr ng-show="ivs.length == 0">
                    <td colspan="7" style="text-align:center;">ไม่มีใบกำกับภาษี</td>
                </tr>
                <tr ng-repeat="iv in ivs | filter:filter1 | filter:filter2 | orderBy:order_by:reverse" ng-show="ivs.length > 0" on-finish-render="ng_repeat_finished">
                    <td>{{iv.iv_no}}</td>
                    <td>{{iv.iv_date}}</td>
                    <td>{{iv.rg_no}}</td>
                    <td>{{iv.rg_type}}</td>
                    <td>{{iv.iv_status_text}}</td>
                    <td>
                        <h5 class="my-0">
                            <i class="fa fa-ellipsis-h dropdown" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.4em;"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" compile="iv.menu"></div>
                        </h5>
                    </td>
                </tr>
            </table>

        </div>

    </div>

</body>
</html>