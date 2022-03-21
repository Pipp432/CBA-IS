<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Search PO/RR/CI</h2>
        
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
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">Search PO/RR/CI</button>
                </div>
                <hr>
				
               
				<div class="row mx-0 mt-2">
					<div class="col-md">
						
						<label for="exampleFormControlTextarea1">เลข RR/CI (RR/CI No.)</label>
						<textarea class="form-control" id="rrciNoTextbox" ng-model="rrciNo" disabled style = "text-align:left;height:200px;"></textarea>
						
                        <!--<label for="rrciNoTextbox">เลข RR/CI (RR/CI No.)</label>
						<textarea id="w3review" name="w3review" rows="4" cols="50" ng-model="rrciNo"></textarea>
						<input type="textarea" class="form-control" id="rrciNoTextbox" ng-model="rrciNo" style="text-align:left;height:120px" rows="4">

                        <input type="textarea" class="form-control" id="rrciNoTextbox" ng-model="rrciNo" style="text-align:left;height:120px" disabled rows="100">-->
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
                
                $http.post('/acc/search_porrci/search', 
                    JSON.stringify({po_no : posString})
                ).then(function(response) {
                    if(response.data === '') {
                        $('#formValidate2').modal('toggle');
						$scope.rrciNo = '';
                    } else {
						
						$scope.rrciNo = '';
						//$scope.rrciNo += response.data[i].po_no + ' (' + response.data[i].po_approver + ') ' + ' : ' + response.data[i].rrci;
						for(var i = 0; i <= pos.length; i++){
							$scope.rrciNo += response.data[i].po_no + ' (' + response.data[i].po_approver + ') ' + ' : ' + response.data[i].rrci+'\n';
						}
                    }
                });
				
				
				//send po_no
                
            }
        }

  	});

</script>