<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/<?php echo $this->page; ?>/registration_list.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
        
    <div class="container pt-2">

        <h4 class="my-2">รายการการสมัคร - List of Registration</h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline ml-auto mb-0">
                <label for="search_textbox">ค้นหาการสมัคร&nbsp;</label>
                <input type="text" class="form-control form-control-sm" ng-model="filter1" placeholder="ค้นหาการสมัคร" id="search_textbox">
                &nbsp;&nbsp;
                <select class="form-control form-control-sm" ng-model="filter2">
                    <option value="">เลือกสถานะการสมัคร</option>
                    <option value="ยกเลิกแล้ว">ยกเลิกแล้ว</option>
                    <option value="ยังไม่ได้ยืนยันการสมัคร">ยังไม่ได้ยืนยันการสมัคร</option>
                    <option value="ค้างชำระ">ค้างชำระ</option>
                    <option value="ชำระเงินแล้ว">ชำระเงินแล้ว</option>
                    <option value="การสมัครมีปัญหา">การสมัครมีปัญหา</option>
                </select>
            </form>

            <table class="table mt-3 mb-0">
                <tr>
                    <th ng-click="order_by_fn('rg_no')">เลขที่การสมัคร <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='rg_no'" aria-hidden="true"></i></th>
                    <th ng-click="order_by_fn('rg_datetime')">วันที่สมัคร <i class="fa fa-sort-amount-down{{reverse ? '' : '-alt'}}" ng-show="order_by=='rg_datetime'" aria-hidden="true"></i></th>
                    <th>ชื่อผู้สมัคร</th>
                    <th>คอร์สที่สมัคร</th>
                    <?php if ($this->page == 'spj') echo '<th>ประเภทการสมัคร</th>'; ?>
                    <th>จำนวนเงิน</th>
                    <?php if ($this->page == 'fin') echo '<th>สลิปโอนเงิน</th>'; ?>
                    <th>สถานะ</th>
                    <th></th>
                </tr>
                <tr ng-show="is_load">
                    <td colspan="8" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</td>
                </tr>
                <tr ng-show="rgs.length == 0">
                    <td colspan="8" style="text-align:center;">ไม่มีการสมัคร</td>
                </tr>
                <tr ng-repeat="rg in rgs | filter:filter1 | filter:filter2 | orderBy:order_by:reverse" ng-show="rgs.length > 0">
                    <td>{{rg.rg_no}}</td>
                    <td>{{rg.rg_datetime}}</td>
                    <td>{{rg.customer_name}}</td>
                    <td>{{rg.course_name}}</td>
                    <?php if ($this->page == 'spj') echo '<td>{{rg.rg_type}}</td>'; ?>
                    <td>{{rg.total_price | number:2}}</td>
                    <?php if ($this->page == 'fin') echo '<td><span style="cursor:pointer;" ng-click="open_rg_slip(rg.rg_slip_no)"><i class="fa fa-share" aria-hidden="true" ng-show="rg.rg_slip_no != null">&nbsp;</i>{{rg.rg_slip_no}}</span></td>'; ?>
                    <td><i class="fa fa-check-circle" aria-hidden="true" ng-show="rg.rg_status == 'ชำระเงินแล้ว'">&nbsp;</i>{{rg.rg_status_text}}</td>
                    <td>
                        <h5 class="my-0">
                            <i class="fa fa-ellipsis-h dropdown" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.4em;"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" compile="rg.menu">
                                <?php
                                    // if ($this->page == 'spj') {

                                    // } else if ($this->page == 'fin') {
                                    //     echo '<a class="dropdown-item" ng-click="add_iv_validate(rg.rg_no)"><i class="fa fa-comments-dollar" aria-hidden="true"></i> ยืนยันการชำระเงิน</a>';
                                    //     echo '<a class="dropdown-item" ng-click="report_rg_validate(rg.rg_no)"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> การสมัครมีปัญหา</a>';
                                    //     echo '<a class="dropdown-item" ng-click="cancel_iv_validate(rg.rg_no)"><i class="fa fa-trash-alt" aria-hidden="true"></i> ยกเลิกการสมัคร</a>';
                                    // }
                                ?>
                            </div>
                        </h5>
                    </td>
                </tr>
            </table>

        </div>

    </div>

</body>
</html>