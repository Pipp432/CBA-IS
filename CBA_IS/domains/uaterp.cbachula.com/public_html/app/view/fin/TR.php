<!DOCTYPE html>
<html>
<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
</style>
	
<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">TR</h2>
     
		<div class="row row-cols-2 row-cols-md-3 mt-2 p-0" style="text-align: center;">
				<div class="col p-0" ng-repeat="t in tr">
					<div class="card text-white bg-info m-2" style="border-radius:10px;">
						<div class="card-body" style="margin-left: 10px; margin-right: 10px;">
							<h5 class="card-title my-0">{{t.project_no}}</h5>
							<h1 class="card-text mt-1">฿ {{t.total | number:2}}</h1>
						</div>
					</div>
				</div>
		</div>

			<div class="row row-cols-2 row-cols-md-3 mt-2 p-0">
				<div class="col p-0" ng-repeat="r in tr_range" style="padding-left: 15px; padding-right: 15px; text-align: center;">
					<h4 class="card-text mt-0">{{r.min_cr}} ถึง {{r.max_cr}}</h1>
				</div>
			</div>
			<hr>
			<h5 class="mt-3" style="text-align: center;">หมายเหตุ</h5>
			<table class="table table-hover mb-1 mt-2">
                        <tr>
                            <th>CR</th>
                            <th>รายละเอียด</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="n in tr_note">
                            <td style="text-align: left;">{{n.cr_no}}</td>
                            <td style="text-align: left;">ยกเลิก</td>
                            <td style="text-align: left;">{{n.total_price | number:2}}</td>
                        </tr>
			</table>
			
			<div class="row mx-0 mt-2">
				<div class="card-body">
					 <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ตัดรอบ TR</button>
				</div>
			</div>

		<div class="card shadow p-1 mt-2  mb-3" style="border:none; border-radius:10px;  text-align: center; padding-left: 15px; padding-right: 15px;">
			<h3 class="mt-3">รายการ TR</h3>
			<table class="table table-hover mb-1 mt-2" >
                        <tr>
                            <th>หมายเลข TR</th>
							<th>วันที่ออก</th>
                            <th>ยอดโครงการ 1</th>
							<th>ยอดโครงการ 2</th>
							<th>ยอดโครงการ 3</th>
                            <th>ยอดรวม</th>
							<th>ผู้อนุมัติ</th>
                        </tr>
                        <tr ng-repeat="l in tr_list" ng-click="viewFile(l)" style="text-align: left;">
                            <td>{{l.tr_no}}</td>
							<td>{{l.tr_date}}</td>
							<td>{{l.tot1 | number:2}}</td>
							<td>{{l.tot2 | number:2}}</td>
							<td>{{l.tot3 | number:2}}</td>
                            <td>{{l.tot | number:2}}</td>
							<td>{{l.approved_employee}}</td>
                        </tr>
			</table>
		</div>
	</div>
		<script>
            addModal('formValidate1', 'เพิ่มTR', 'เกิดข้อผิดพลาด กรุณาติดต่อ IS');
        </script>
</body>


</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.tr = <?php echo $this->tr; ?>;
		$scope.tr_list = <?php echo $this->tr_list; ?>;
		$scope.tr_range = <?php echo $this->tr_range; ?>;
		$scope.tr_note = <?php echo $this->tr_note; ?>;
        $scope.formValidate = function() {
                var confirmModal = addConfirmModal('confirmModal', 'โปรแกรมออกใบTR', 'ยืนยันการออกใบTR', 'postTR()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
        }
        
        $scope.postTR = function() {
            $('#confirmModal').modal('hide');   
			 $.post("/fin/TR/post_tr", {}, function(data) {
                addModal('successModal', 'โปรแกรมออกใบTR', 'บันทึกสำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    location.reload();
                });
            });
            
        }
		$scope.viewFile = function(l) {
            // var iv = cr.cr_no.substring(0,1) + 'IV-' + cr.cr_no.substring(4,9);
            window.open('/file/tr/' + l.tr_no);
        }
        
    });

</script>