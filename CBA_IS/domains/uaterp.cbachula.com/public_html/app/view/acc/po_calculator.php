<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">คำนวณราคารวม PO / PO Calculator</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- EDITING PO BY PO NUMBER -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="poNoTextbox">เลขที่ PO</label>
                        <textarea class="form-control" rows="3" id="poNoTextbox" ng-model="poNo" style="text-transform:uppercase"></textarea>
                    </div>
                </div>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">คำนวณราคารวม PO</button>
                </div>
                <hr>
				
                <div class="row mx-0 mt-2">
                    <div class="col-md-4">
                        <label for="pNoVatTextbox">ราคาซื้อ (no vat)</label>
                        <input type="text" class="form-control" id="pNoVatTextbox" ng-model="pNoVat" style="text-align:right;" disabled>
                    </div>
                    <div class="col-md-4">
                        <label for="pVatTextbox">ราคาซื้อ (vat)</label>
                        <input type="text" class="form-control" id="pVatTextbox" ng-model="pVat" style="text-align:right;" disabled>
                    </div>
                    <div class="col-md-4">
                        <label for="pPriceTextbox">ราคาซื้อรวม</label>
                        <input type="text" class="form-control" id="pPriceTextbox" ng-model="pPrice" style="text-align:right;" disabled>
                    </div>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'คำนวณราคารวม PO / PO Calculator', 'ยังไม่ได้ใส่เลข PO');
            addModal('formValidate2', 'คำนวณราคารวม PO / PO Calculator', 'คำนวณไม่ได้');
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
        
        $scope.poNo = '';
        
        $scope.formValidate = function() {
            if($scope.poNo == '') {
                $('#formValidate1').modal('toggle');
            } else {
                
                var pos = $scope.poNo.split(' ');
                var posString = '';
                
                for(var i = 0; i < pos.length; i++) {
                    posString += '\'' + pos[i].trim() + '\'';
                    if(i != pos.length - 1)
                        posString += ',';
                }
                
                console.log(posString);
                
                $http.post('/acc/po_calculator/calculate_po', 
                    JSON.stringify({po_no : posString})
                ).then(function(response) {
                    if(response.data === '') {
                        $('#formValidate2').modal('toggle');
                        $scope.pNoVat = '';
                        $scope.pVat = '';
                        $scope.pPrice = '';
                    } else {
                        $scope.pNoVat = response.data[0].p_no_vat;
                        $scope.pVat = response.data[0].p_vat;
                        $scope.pPrice = response.data[0].p_price;
                    }
                });
				
				
				//send po_no
                
            }
        }

  	});

</script>