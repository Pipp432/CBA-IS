<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Upload slip PV-D</h2>
 
        <div class="mt-2 p-0">
            <table class="table table-hover my-1">
					<tr>
						<th style="text-align: center;">เลขที่ PV-D</th>
						<th style="text-align: center;">วันที่</th>
                        <th style="text-align: center;">จำนวนเงิน</th>
                        <th style="text-align: center;">เลขที่บัญชี</th>
                        <th style="text-align: center;">ชื่อบัญชี</th>
                        <th style="text-align: center;">อัปสลิป</th>
						<th style="text-align: center;">เอกสาร PV-D</th> 
                        <th style="text-align: center;">สถานะ</th>
					</tr>
					<tr ng-repeat="pvd in pvds | unique:'pvd_no'">
						<td style="text-align: center;">{{pvd.pvd_no}}</td>
						<td style="text-align: center;">{{pvd.pvd_date}}</td>
                        <td style="text-align: center;">{{pvd.sum_total_sales}}</td>
                        <td style="text-align: center;">{{pvd.bank_no}} <br> {{pvd.bank}}</td>
                        <td style="text-align: center;">{{pvd.recipient}}</td>
                        <td style="text-align: center;">
                            <input id='{{pvd.pvd_no}}' type="file" class="form-control-file" name={{pvd.pvd_no}}>
                        </td>

                        <td style="text-align: center;"><button type="button" class="btn btn-info" ng-click = "seePVD(pvd)">PV-D</button></td>
                        <td style="text-align: center;">
                                <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail"
                                    ng-click="done(pvd)">Confirm</button>
                        </td>
                    </tr>				
			</table>
            
            
        </div>
        <script>
            addModal('formValidate1', 'confirm เบิกเงินรองจ่าย', 'ยังไม่ได้อัปรูป');
        </script>
    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

    

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.cpvdItems = [];


		$scope.pvds = <?php echo $this->pvds; ?>
        // var first = true;
        // console.log($scope.pvds);

        $scope.done = function ($pvd) {
            //console.log($minor_request);
            var upload = true;
            var formData = new FormData();
            var mod = '';
            formData.append('slip_file', $('#' + $pvd['pvd_no'])[0].files[0]);
            formData.append('pvd_no', $pvd.pvd_no);

            for (var pair of formData.entries()) {
                //console.log(pair[0]+ ', ' + pair[1]); 
                if (pair[1] === 'undefined') {
                    upload = false;
                    mod = mod + 'ยังไม่ได้อัปสลิป<br>';
                };
            }


            if (upload) {
                //todo upload data
                $.ajax({
                    url: '/fin/upslip_pvd/conpvdItems', //todo
                    type: "POST",
                    dataType: 'text',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                }).done(function (data) {
                    // console.log(data);
                    // console.log(data['success']);
                    if (data=='success') {
                        addModal('succModal', 'upload slip PVD', 'Upload successful');
                        $('#succModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) { location.assign('/home') });

                    } else {
                        addModal('uploadFailModal', 'upload image', 'fail');
                        $('#uploadFailModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) { location.assign('/home') });
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('ajax.fail');
                    addModal('uploadFailModal', 'upload image', 'fail');
                    $('#uploadFailModal').modal('toggle');
                    $('#successModal').on('hide.bs.modal', function (e) { location.assign('/home') });
                });


            } else {
                addModal('formValidate10', 'confirm PVD', mod);
                $('#formValidate10').modal('toggle');
            }
        }


        //confirm button//
        $scope.conpvdItems = function(pvd_no) {

            $.post("/fin/upslip_pvd/conpvdItems", {
                pvd_no: pvd_no
            }, function(data) {
                addModal('successModal', 'Confirm upload slip PV-D', 'Confirm Successful');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) { location.reload(); });
            });
        }
     
        //see file PVD// 
        $scope.seePVD = function(file) {
			window.open('/file/pvd/' + file.cn_no);
		}



    });

</script>