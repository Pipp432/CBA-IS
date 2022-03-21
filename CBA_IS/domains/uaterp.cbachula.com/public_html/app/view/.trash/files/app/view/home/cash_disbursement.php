<!DOCTYPE html>
<html>
<body ng-controller="app_controller">
        
    <div class="container pt-2">

        <h4 class="my-2">ใบเบิกเงิน - Cash Disbursement (CD)</h4>

        <script>
            add_modal('error_modal', 'เกิดปัญหาระหว่างการบันทึก กดใหม่อีกครั้ง');
        </script>

        <div class="row part mx-0 mt-3 p-3">
            <form class="form-inline mb-2">
                <label for="cd_type_select">ประเภทการเบิกเงิน&nbsp;</label>
                <select class="form-control" id="cd_type_select" ng-model="cd_type_chosen">
                    <option ng-repeat="cd_type in cd_types" value="{{cd_type.cd_type_no}}">{{cd_type.cd_type_name}}</option>
                </select>
            </form>
        </div>

        {{message}}

    </div>

    <style>
    </style>

    <script>
        app.controller('app_controller', ($scope) => {
            $scope.cd_type_chosen = '0';
            $scope.cd_types = [
                {cd_type_no: '0', cd_type_name: 'เลือกประเภทการเบิกเงิน'},
                {cd_type_no: 'A', cd_type_name: 'เบิกเงินที่มียอดไม่เกิน 5,000 บาท'},
                {cd_type_no: 'B', cd_type_name: 'เบิกเงินจ่ายค่าวิทยากร'},
                {cd_type_no: 'C', cd_type_name: 'เบิกเงินที่มียอดที่มากกว่า 5,000 บาท'}
            ];
            $scope.message = 'สวัสดี';
        });
    </script>
    
</body>
</html>