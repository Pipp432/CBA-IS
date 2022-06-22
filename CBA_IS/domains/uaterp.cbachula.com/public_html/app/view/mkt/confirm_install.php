<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยืนยันการติดตั้ง / Confirm Install (CI)</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-6">
                        <label for="poNoTextbox">เลขที่ใบสั่งซื้อ (PO)</label>
                        <input type="text" class="form-control" id="poNoTextbox" ng-model="filterPo">
                    </div>
                    <div class="col-md-6">
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
                            <th>ไม่มี PO ใน Supplier นี้</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pos.length != 0">
                        <tr>
							<th>เลข SO</th>
                            <th>เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="po in pos | unique:'po_no' | filter:{supplier_no:selectedSupplier, po_no:filterPo}" ng-click="addCiItem(po)">
							<td style="text-align: center;">{{po.so_no}}</td>
                            <td style="text-align: center;">{{po.po_no}}</td>
                            <td style="text-align: center;">{{po.po_date}}</td>
                            <!--<td><ul class="my-0">
                                <li ng-repeat="po_item in pos" ng-show="po_item.po_no===po.po_no">{{po_item.product_name}} (x{{po_item.quantity}})</li>
                            </ul></td>-->
                            <td  style="text-align: center;">-</td>
                            <td style="text-align: right;">{{po.total_purchase_price | number:2}}</td>
                        </tr>
                    </table>
                </div>
             </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING CI ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการติดตั้ง</h4>
                    <table class="table table-hover my-1" ng-show="ciItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข PO</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="ciItems.length != 0">
                        <tr>
                            <th colspan="2">เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="product in ciItems | orderBy:'po_no' | unique:'po_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCiItem(product)"></i></td>
                            <td>{{product.po_no}}</td>
                            <td>{{product.po_date}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="po_item in ciItems" ng-show="po_item.po_no===product.po_no">{{po_item.product_name}} (x{{po_item.quantity}})</li>
                            </ul></td>
                            <td style="text-align: right;">{{product.total_purchase_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวม</th>
                            <th style="text-align: right;">{{totalPrice | number:2}}</th>
                        </tr>
                        
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก CI</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันการติดตั้ง / Confirm Install (CI)', 'ยังไม่ได้เพิ่มเลข PO');
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

        $scope.ciItems = [];
        $scope.selectedSupplier = '';
        $scope.pos = <?php echo $this->pos; ?>;
            
        $scope.addCiItem = function(po) {
            var newPo = true;
            angular.forEach($scope.ciItems, function (value, key) {
                if(value.po_no == po.po_no) {
                    newPo = false;
                }
            });
            console.log($scope.ciItems)
            if(newPo) {
                angular.forEach($scope.pos, function (value, key) {
                    if(value.po_no == po.po_no) {
                        $scope.ciItems.push(value);
                    }
                });
            }
            $scope.calculateTotalPrice();
        }
        
        $scope.dropCiItem = function(product) {
            var tempRemoved = [];
            angular.forEach($scope.ciItems, function (value, key) {
                if(value.po_no != product.po_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.ciItems = tempRemoved;
            
            $scope.calculateTotalPrice();
        }
        
        $scope.calculateTotalPrice = function() {
            $scope.totalNoVat = 0;
            $scope.totalVat = 0;
            $scope.totalPrice = 0;
            angular.forEach($scope.ciItems, function(value, key) {
                $scope.totalNoVat += (value.quantity * value.poprinting_purchase_no_vat);
                $scope.totalVat += (value.quantity * value.poprinting_purchase_vat);
                $scope.totalPrice += (value.quantity * value.poprinting_purchase_price);
            });
            console.log($scope.ciItems)
        }
        
        $scope.formValidate = function() {
            if($scope.ciItems.length===0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันการติดตั้ง / Confirm Install (CI)', 'ยืนยันการติดตั้ง', 'postCiItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        var clicked = false;
        
        $scope.postCiItems = function() {
           
            
            $('#confirmModal').modal('hide');
            
            if(!clicked){
                clicked = true;
                $.post("confirm_install/post_ci_items", {
                    post : true,
                    supplierNo : $scope.selectedSupplier,
                    ciItems : JSON.stringify(angular.toJson($scope.ciItems))
                }, function(data) {
                    addModal('successModal', 'ยืนยันการติดตั้ง / Confirm Install (CI)', 'บันทึก ' + data + ' สำเร็จ');
                    $('#successModal').modal('toggle');
                    $('#successModal').on('hide.bs.modal', function (e) {
                        window.location.assign('/');
                    });
                });   
            }
            
        }

    });

</script>