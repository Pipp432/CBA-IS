<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบรับสินค้า / Receiving Report (RR)</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-4">
                        <label for="poNoTextbox">เลขที่ใบสั่งซื้อ (PO)</label>
                        <input type="text" class="form-control" id="poNoTextbox" ng-model="filterPo">
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownProductType">ประเภทสินค้า</label>
                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">
                            <option value="">เลือกประเภทสินค้า</option>
                            <option value="Stock">Stock</option>
                            <option value="Order">Order</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownSupplier">Supplier</label>
                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">
                            <option value="">เลือก Supplier</option>
                            <option ng-repeat="supplier in pos | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier.supplier_no}}">
                                {{supplier.supplier_no}} : {{supplier.supplier_name}}
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="pos.length == 0">
                        <tr>
                            <th>ไม่มี PO ที่ยังไม่ออก RR</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pos.length != 0">
                        <tr>
                            <th>เลข PO</th>
                            <th>วันที่</th>
                            <th>ประเภทสินค้า</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="po in pos | unique:'po_no' | filter:{supplier_no:selectedSupplier, po_no:filterPo, product_type:selectedProductType}" ng-click="addRrItem(po)">
                            <td>{{po.po_no}}</td>
                            <td>{{po.po_date}}</td>
                            <td>{{po.product_type}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="po_item in pos" ng-show="po_item.po_no===po.po_no">{{po_item.product_name}} (x{{po_item.quantity}})</li>
                            </ul></td>
                            <td style="text-align: right;">{{po.po_total_purchase_price | number:2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING RR ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบรับสินค้า</h4>
                    <table class="table table-hover my-1" ng-show="rrItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม PO</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="rrItems.length != 0">
                        <tr>
                            <th colspan="2">เลข PO</th>
                            <th>วันที่</th>
                            <th>ประเภทสินค้า</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="product in rrItems | orderBy:'po_no' | unique:'po_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropRrItem(product)"></i></td>
                            <td>{{product.po_no}}</td>
                            <td>{{product.po_date}}</td>
                            <td>{{product.product_type}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="po_item in rrItems" ng-show="po_item.po_no===product.po_no">{{po_item.product_name}} (x{{po_item.quantity}})</li>
                            </ul></td>
                            <td style="text-align: right;">{{product.po_total_purchase_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">ราคารวม</th>
                            <th style="text-align: right;">{{totalPrice | number:2}}</th>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก RR</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบรับสินค้า / Receiving Report (RR)', 'ยังไม่ได้เพิ่มเลข PO');
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

        $scope.rrItems = [];
        $scope.selectedSupplier = '';
        
        $scope.pos = <?php echo $this->pos; ?>;
            
        $scope.addRrItem = function(po) {
            var newPo = true;
            angular.forEach($scope.rrItems, function (value, key) {
                if(value.po_no == po.po_no) {
                    newPo = false;
                }
            });
            if(newPo) {
                angular.forEach($scope.pos, function (value, key) {
                    if(value.po_no == po.po_no) {
                        $scope.rrItems.push(value);
                    }
                });
            }
            $scope.calculateTotalPrice();
        }
        
        $scope.dropRrItem = function(product) {
            var tempRemoved = [];
            angular.forEach($scope.rrItems, function (value, key) {
                if(value.po_no != product.po_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.rrItems = tempRemoved;
            $scope.calculateTotalPrice();
        }
        
        $scope.calculateTotalPrice = function() {
            $scope.totalNoVat = 0;
            $scope.totalVat = 0;
            $scope.totalPrice = 0;
            angular.forEach($scope.rrItems, function(value, key) {
                $scope.totalNoVat += (value.quantity * value.purchase_no_vat);
                $scope.totalVat += (value.quantity * value.purchase_vat);
                $scope.totalPrice += (value.quantity * value.purchase_price);
            });
        }
        
        $scope.formValidate = function() {
            if($scope.rrItems.length == 0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ใบรับสินค้า / Receiving Report (RR)', 'ยืนยันใบรับสินค้า', 'postRrItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postRrItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/scm/receiving_report/post_rr_items", {
                post : true,
                rrItems : JSON.stringify(angular.toJson($scope.rrItems))
            }, function(data) {
                addModal('successModal', 'ใบรับสินค้า / Receiving Report (RR)', 'บันทึก ' + data + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
					window.open('/file/rr/' + data.substring(0, 9));
                    window.location.assign('/');
                });
            });
        }

  	});

</script>