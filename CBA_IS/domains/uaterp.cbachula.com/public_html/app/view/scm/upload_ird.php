<!DOCTYPE html>

<html>
   
<body>
    
    
   
    <div class="container mt-3" ng-controller="moduleAppController">
	<h1 class="text-left" style="padding:10px 0 0 0;">อัพโหลด IRD</h1>
    <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
		<div class="card-body">
			<div class="row mx-0 mt-2">
				<table class="table table-hover my-1" ng-show="irds.length == 0" style="text-align: center;">
					<tr>
						<th>ไม่มี IRD ที่ต้องอัพโหลด</th>
					</tr>
				</table>
				<table class="table table-hover my-1" ng-show="irds.length != 0" style="text-align: center;">
					<tr>
						<th>เลข IRD</th>
						<th>วันที่ IRD</th>
						<th>ผู้ออก IRD</th>
						<th>จำนวนกล่อง</th>
					</tr>
					<tr ng-repeat="ird in irds | unique:'ird_no'" ng-click="addIrdItem(ird)">
						<td>{{ird.ird_no}}</td>
						<td>{{ird.ird_date}}</td>
						<td>{{ird.approved_employee}}</td>
						<td>{{ird.box_count}}</td>
					</tr>				
				</table>
        	</div>
		</div>
	</div>
    
	<div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
		<div class="card-body">
			
			<div class="row mx-0 mt-2">
                    <div class="col-md-4">
                        <label for="irdItem">เลข IRD</label>
						<input type="text" class="form-control" id="irdItemir" ng-model="irdItemir">
                    </div>
                    <div class="col-md-4">
                        <label for="ivFile">อัพโหลดเอกสาร</label><br>
                        <input class="form-control-file" type="file" id="irdFile">
                    </div>
        	</div>
			<div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postirdItems(irdItemir)">upload IRD!</button>
        	</div>
		</div>
	</div>
        
   </div>
	
	<script>
            addModal('formValidate1', 'Upload IRD', 'ยังไม่ได้เลือก IRD/ ยังไม่ได้เพิ่มไฟล์');
    </script>
    
    


</body>
</html>
<script>

	app.controller('moduleAppController', function($scope, $http, $compile) {
		$scope.irds = <?php echo $this->irds; ?>;
		$scope.irdItemir = '';
		$scope.irdItems = [];
		
		$scope.addIrdItem = function(ird) {
            $("#irdItemir").prop("disabled", true);
            $scope.irdItemir = ird.ird_no;
        }
		
		
		$scope.postirdItems = function(ird_no) {
            
            if($scope.irdItemir == '') {
                $('#formValidate1').modal('toggle');
            } else{
				var data = new FormData();
                data.append('irdFile', $('#irdFile')[0].files[0]);
                data.append('ird_no', $scope.irdItemir);
                
			$.ajax({
				url: `/scm/upload_ird/post_ird_file/${ird_no}`,
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				success: function () {
					addModal('successModal', 'Upload IRD', 'อัพโหลดไฟล์ IRD เรียบร้อยแล้ว');
					$('#successModal').modal('toggle');
					$('#successModal').on('hide.bs.modal', function (e) {
						window.location.assign('/scm/upload_ird');
					});
				}
			});
                
            }
            
        }
	});

</script>
