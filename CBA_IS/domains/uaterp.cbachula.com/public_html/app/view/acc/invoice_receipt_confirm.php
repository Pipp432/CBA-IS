<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CI/RR -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-4">
                        <label for="rrCiNoTextbox">เลขที่ RR/CI</label>
                        <input type="text" class="form-control" id="rrCiNoTextbox" ng-model="filterRRCI">
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownProductType">ประเภทสินค้า</label>
                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">
                            <option value="">เลือกประเภทสินค้า</option>
                            <option value="Stock">Stock</option>
                            <option value="Order">Order</option>
                            <option value="Install">Install</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownSupplier">Supplier</label>
                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">
                            <option value="">เลือก Supplier</option>
                            <option ng-repeat="supplier in rrcis | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier.supplier_no}}">
                                {{supplier.supplier_no}} : {{supplier.supplier_name}}
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="rrcis.length == 0">
                        <tr>
                            <th>ไม่มีใบรับสินค้าหรือการยืนยันการติดตั้งที่ยังไม่วางบิล</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="rrcis.length != 0">
                        <tr>
                            <th>เลข RR/CI</th>
                            <th>เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="rrci in rrcis | unique:'ci_no' | filter:{supplier_no:selectedSupplier, ci_no:filterRRCI, product_type:selectedProductType}" ng-click="addIvrcItem(rrci)">
                            <td>{{rrci.ci_no}}</td>
                            <td>{{rrci.po_no}}</td>
                            <td>{{rrci.ci_date}}</td>
                            <td><ul class="my-0">
                                <!--<li ng-repeat="rrci_item in rrcis" ng-show="rrci_item.ci_no===rrci.ci_no">{{rrci_item.product_name}} (x{{rrci_item.quantity}})</li>-->
                            </ul></td>
                            <td style="text-align: right;">{{rrci.confirm_total | number:2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING IVRC ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดยืนยันการวางบิล</h4>
                    <table class="table table-hover my-1" ng-show="ivrcItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข RR/CI</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="ivrcItems.length != 0">
                        <tr>
                            <th colspan="2">เลข RR/CI</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-repeat="ivrcItem in ivrcItems">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropIvrcItem(ivrcItem)"></i></td>
                            <td>{{ivrcItem.ci_no}}</td>
                            <td>{{ivrcItem.product_name}}</td>
                            <td style="text-align: right;">{{ivrcItem.purchase_price | number:2}}</td>
                            <td style="text-align: right;">{{ivrcItem.quantity}} {{ivrcItem.unit}}</td>
                            <td style="text-align: right;">{{ivrcItem.total_purchase_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">รวม</th>
                            <th style="text-align: right;">{{total_purchase_no_vat | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">ภาษีมูลค่าเพิ่ม</th>
                            <th style="text-align: right;">{{total_purchase_vat | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">รวมสุทธิ</th>
                            <th style="text-align: right;">{{total_purchase_price | number:2}}</th>
                        </tr>
                    </table>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <div class="col-md-4">
                        <label for="textboxIv">เลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี</label>
                        <input type="text" class="form-control" id="textboxIv" ng-model="iv" placeholder="เลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี">
                    </div>
                    <div class="col-md-4">
                        <label for="datetime-input">วันที่</label>
                        <input class="form-control" type="date" id="datetime-input" ng-model="ivrcDate">
                    </div>
                    <div class="col-md-4">
                        <label for="ivFile">อัปโหลดเอกสาร</label><br>
                        <input class="form-control-file" type="file" id="ivFile">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postivrcItems()">บันทึกการยืนยันการวางบิล</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'ยังไม่ได้เพิ่ม RR/CI');
            addModal('formValidate2', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'ยังไม่ได้กรอกเลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี');
            addModal('formValidate3', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'RR/CI ที่เพิ่มเป็นคนละ Supplier กัน');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.selectedProductType = '';
        $scope.selectedSupplier = '';
        $scope.filterRRCI = '';
        $scope.iv = '';
        $scope.ivrcItems = [];
        $scope.rrcis = <?php echo $this->rrcis; ?>;
        
        $scope.addIvrcItem = function(rrci) {
            if ($scope.ivrcItems.length != 0 && $scope.ivrcItems[0].supplier_no != rrci.supplier_no) {
                $('#formValidate3').modal('toggle');
            } else {
                var newRRCI = true;
                angular.forEach($scope.ivrcItems, function (value, key) {
                    if(value.ci_no == rrci.ci_no) {
                        newRRCI = false;
                    }
                });
                if(newRRCI) {
                    angular.forEach($scope.rrcis, function (value, key) {
                        if(value.ci_no == rrci.ci_no) {
                            $scope.ivrcItems.push(value);
                        }
                    });
                }
                $scope.calculateTotalPrice();
            }
        }
        
        $scope.dropIvrcItem = function(rrci) {
            var tempIvrcItem = [];
            angular.forEach($scope.ivrcItems, function (value, key) {
                if(value.ci_no != rrci.ci_no) {
                    tempIvrcItem.push(value);
                }
            });
            $scope.ivrcItems = tempIvrcItem;
            $scope.calculateTotalPrice();
        }
        
        $scope.calculateTotalPrice = function() {
            $scope.total_purchase_no_vat = 0;
            $scope.total_purchase_vat = 0;
            $scope.total_purchase_price = 0;
            angular.forEach($scope.ivrcItems, function (value, key) {
                $scope.total_purchase_no_vat += (value.purchase_no_vat * value.quantity);
                $scope.total_purchase_vat += (value.purchase_vat * value.quantity);
                $scope.total_purchase_price += (value.purchase_price * value.quantity);
            });
        }
        
        $scope.postivrcItems = function() {
            
            if($scope.ivrcItems.length === 0) {
                $('#formValidate1').modal('toggle');
            } else if ($scope.iv === '') {
                $('#formValidate2').modal('toggle');
            } else {
                
                var ivrcDateStr = $scope.ivrcDate.getFullYear() + '-' + 
                                    (($scope.ivrcDate.getMonth()+1) < 10 ? '0' : '') + ($scope.ivrcDate.getMonth()+1) + '-' + 
                                    ($scope.ivrcDate.getDate() < 10 ? '0' : '') + $scope.ivrcDate.getDate();
                
                var data = new FormData();
                data.append('ivFile', $('#ivFile')[0].files[0]);
                data.append('iv', $scope.iv);
                data.append('ivrcItems', JSON.stringify(angular.toJson($scope.ivrcItems)));
                data.append('ivrcDate', ivrcDateStr);
                
                $.ajax({
                    url: '/acc/invoice_receipt_confirm/post_ivrc',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success: function () {
                        addModal('successModal', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'บันทึกการวางบิลเรียบร้อยแล้ว');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            window.location.assign('/');
                        });
                    }
                });
                
            }
            
            
        }

  	});

</script>