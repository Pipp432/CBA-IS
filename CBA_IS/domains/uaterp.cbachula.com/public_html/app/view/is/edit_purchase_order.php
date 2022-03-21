<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">แก้ไขใบสั่งซื้อ / Edit Purchase Order</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- EDITING PO BY PO NUMBER -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-3">
                        <label for="poNoTextbox">เลขที่ PO</label>
                        <input type="text" class="form-control" id="poNoTextbox" ng-model="poNo" ng-change="getPo()" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-3">
                        <label for="pNoVatTextbox">ราคาซื้อ (no vat)</label>
                        <input type="text" class="form-control" id="pNoVatTextbox" ng-model="pNoVat" style="text-align:right;" disabled>
                        <input type="text" class="form-control mt-2" id="pNoVatEditedTextbox" ng-model="pNoVatEdited" style="text-align:right;">
                    </div>
                    <div class="col-md-3">
                        <label for="pVatTextbox">ราคาซื้อ (vat)</label>
                        <input type="text" class="form-control" id="pVatTextbox" ng-model="pVat" style="text-align:right;" disabled>
                        <input type="text" class="form-control mt-2" id="pVatEditedTextbox" ng-model="pVatEdited" style="text-align:right;">
                    </div>
                    <div class="col-md-3">
                        <label for="pPriceTextbox">ราคาซื้อรวม</label>
                        <input type="text" class="form-control" id="pPriceTextbox" ng-model="pPrice" style="text-align:right;" disabled>
                        <input type="text" class="form-control mt-2" id="pPriceEditedTextbox" ng-model="pPriceEdited" style="text-align:right;">
                    </div>
                </div>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">แก้ไข PO</button>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'แก้ไขใบสั่งซื้อ / Edit Purchase Order', 'ไม่มีเลข PO นี้');
            addModal('formValidate2', 'แก้ไขใบสั่งซื้อ / Edit Purchase Order', 'ยังไม่ได้กรอกเลข PO นิ');
            addModal('formValidate3', 'แก้ไขใบสั่งซื้อ / Edit Purchase Order', 'แก้ไขยอดได้ไม่เกิน 1 บาท');
//            addModal('formValidate4', 'แก้ไขใบสั่งซื้อ / Edit Purchase Order', 'no vat + vat ไม่เท่า price');
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
        
        $scope.pNoVat = '';
        $scope.pVat = '';
        $scope.pPrice = '';
        
        $scope.getPo = function() {
            if($scope.poNo.length === 9) {
                $http.post('/is/edit_purchase_order/get_po', 
                    JSON.stringify({po_no : $scope.poNo})
                ).then(function(response) {
                    if(response.data === '') {
                        $('#formValidate1').modal('toggle');
                        $scope.pNoVat = '';
                        $scope.pNoVatEdited = '';
                        $scope.pVat = '';
                        $scope.pVatEdited = '';
                        $scope.pPrice = '';
                        $scope.pPriceEdited = '';
                        $scope.received = '';
                        $scope.product_type = '';
                    } else {
                        $scope.pNoVat = response.data[0].total_purchase_no_vat;
                        $scope.pNoVatEdited = response.data[0].total_purchase_no_vat;
                        $scope.pVat = response.data[0].total_purchase_vat;
                        $scope.pVatEdited = response.data[0].total_purchase_vat;
                        $scope.pPrice = response.data[0].total_purchase_price;
                        $scope.pPriceEdited = response.data[0].total_purchase_price;
                        $scope.received = response.data[0].received;
                        $scope.product_type = response.data[0].product_type;
                    }
                });
            } else {
                $scope.pNoVat = '';
                $scope.pNoVatEdited = '';
                $scope.pVat = '';
                $scope.pVatEdited = '';
                $scope.pPrice = '';
                $scope.pPriceEdited = '';
                $scope.received = '';
                $scope.product_type = '';
            }
        }
        
        $scope.formValidate = function() {
            if($scope.pNoVat == '' || $scope.pVat == '' || $scope.pPrice == '') {
                $('#formValidate2').modal('toggle');
            } 
            // else if(Math.abs(parseFloat($scope.pNoVat) - parseFloat($scope.pNoVatEdited)) > 1) {
            //     $('#formValidate3').modal('toggle');
            // } else if(Math.abs(parseFloat($scope.pVat) - parseFloat($scope.pVatEdited)) > 1) {
            //     $('#formValidate3').modal('toggle');
            // } else if(Math.abs(parseFloat($scope.pPrice) - parseFloat($scope.pPriceEdited)) > 1) {
            //     $('#formValidate3').modal('toggle');
            // } 
//            else if(parseFloat($scope.pNoVatEdited) + parseFloat($scope.pVatEdited) != parseFloat($scope.pPriceEdited)) {
//               $('#formValidate4').modal('toggle');
//            } 
            else {
                var confirmModal = addConfirmModal('confirmModal', 'แก้ไขใบสั่งซื้อ / Edit Purchase Order', 'ยืนยันการแก้ไขใบสั่งซื้อ', 'postEditPo()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postEditPo = function() {
            $('#confirmModal').modal('hide');
            $.post("/is/edit_purchase_order/post_edit_po", {
                po_no : $scope.poNo,
                p_no_vat : $scope.pNoVat,
                p_no_vat_edited : $scope.pNoVatEdited,
                p_vat : $scope.pVat,
                p_vat_edited : $scope.pVatEdited,
                p_price : $scope.pPrice,
                p_price_edited : $scope.pPriceEdited,
                received : $scope.received,
                product_type : $scope.product_type
            }, function(data) {
                addModal('successModal', 'แก้ไขใบสั่งซื้อ / Edit Purchase Order', 'แก้ไข ' + data + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.replace('https://erp.cbachula.com/is/edit_purchase_order');
                });
            });
        }

  	});

</script>