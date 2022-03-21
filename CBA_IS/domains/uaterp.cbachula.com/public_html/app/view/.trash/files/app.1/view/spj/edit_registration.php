<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/spj/edit_registration.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
        
    <div class="container pt-2">

        <h4 class="my-2">แก้ไขการสมัคร - Edit Registration</h4>

        <div class="row part mx-0 mt-3 p-3">

            <form class="form-inline">
                <label for="dv_type_select">ประเภทการเบิกเงิน </label>
                <select class="form-control" id="dv_type_select" ng-model="dv_type_chosen">
                    <option value="0">เลือกประเภทการเบิกเงิน</option>
                    <option value="A">เบิกเงินที่มียอดไม่เกิน 5,000 บาท หรือค่าวิทยากร</option>
                    <option value="B">เบิกเงินที่มียอดที่มากกว่า 5,000 บาท</option>
                </select>
            </form>

            <br>

            <table class="mt-2 table" ng-show="dv_type_chosen != '0'">
                <tr>
                    <td>ประเภทค่าใช้จ่าย</td>
                    <td>จำนวนเงิน</td>
                    <td>หลักฐานการเบิกเงิน</td>
                    <?php
                        if($this->position == 'fin') {
                            echo '<td>สลิปการโอนเงิน</td>';
                        }
                    ?>
                </tr>
                <tr>
                    <td>
                        <select class="form-control" id="expense_type_select" ng-model="expense_type_chosen">
                            <option value="0">เลือกประเภทค่าใช้จ่าย</option>
                            <script> account_nos.forEach((element) => document.write('<option value="' + element.account_no + '">' + element.account_name + '</option>')); </script>
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="number" ng-model="total_price" min="0">
                    </td>
                    <!-- <td>
                        <input class="form-control-file mt-2" type="file" id="proof_file">
                    </td> -->
                    <td>
                        <select class="form-control" ng-model="dv_item_chosen" ng-change="choose_dv_item()">
                            <option value="0">เลือกวิธีการบันทึกค่าใช้จ่าย</option>
                            <option value="1">หารเท่าทุกคอร์ส</option>
                            <option value="2">หารเท่าเฉพาะคอร์ส</option>
                            <option value="3">หารเท่าเฉพาะรุ่น</option>
                        </select>
                        <select class="form-control" ng-model="dv_course_chosen" ng-change="choose_course_name()" ng-show="dv_item_chosen == '2'">
                            <option value="0">เลือกคอร์ส</option>
                            <option ng-repeat="course_name in course_names" value="{{course_name.course_no}}">{{course_name.course_name}}</option>
                        </select>
                    </td>
                    <?php
                        if($this->position == 'fin') {
                            echo '<td>';
                            echo '<input class="form-control-file mt-2" type="file" id="slip_file">';
                            echo '</td>';
                        }
                    ?>
                </tr>
            </table>

            <button class="btn btn-default btn-block mt-2" ng-show="dv_type_chosen!='0'" ng-click="form_validate()">ยืนยันการออกใบเบิกเงิน</button>

        </div>

    </div>
    
</body>
</html>