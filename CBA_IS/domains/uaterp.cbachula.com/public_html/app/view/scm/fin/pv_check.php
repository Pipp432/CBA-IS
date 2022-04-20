<!DOCTYPE html>
<html>
<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
</style>
	
<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">PV Check</h2>
     

		<div class="card shadow p-1 mt-2  mb-3" style="border:none; border-radius:10px;  text-align: center; padding-left: 15px; padding-right: 15px;">
			<h3 class="mt-3">รายการ PV</h3>
			<table class="table table-hover mb-1 mt-2" >
                        <tr>
                            <th>rrci_no</th>
                            <th>pv_no</th>
							<th>iv_no</th>
							<th>paid_total</th>
                            <th>thai_text</th>
							<th>สลิป</th>
                        </tr>
                        <tr ng-repeat="l in pv_list" style="text-align: left;">
                            <td>{{l.rrci_no}}</td>
							<td>{{l.pv_no}}</td>
							<td>{{l.iv_no}}</td>
							<td>{{l.paid_total}}</td>
                            <td>{{l.thai_text}}</td>
							<td><a href="/fin/pv_check/pv/{{l.rrci_no}}" target="_blank">สลิปครับ</a></td>
                        </tr>
			</table>
		</div>
	</div>
	
</body>


</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

    app.controller('moduleAppController', function($scope) {
        
        $scope.pv_list = <?php echo $this->pv_list; ?>;
        
        
    });

</script>