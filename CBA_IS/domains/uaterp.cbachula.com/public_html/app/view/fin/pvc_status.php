<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">

            <div class="col">
                
                    <div class="button" ng-click="selectPVC()">
                        <h5 class="card-title my-0">ใบ PVC (PVC)</h5>
                    </div>
                
            </div>

            <div class="col">
                
                    <div class="button" ng-click="selectReReq()">
                        <h5 class="card-title my-0">ใบเบิกค่าใช้จ่าย (EXC)</h5>
                    </div>
                
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT pvc -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PVC'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th style= "text-align:center">เลข PV-C</th>
                    <th style= "text-align:center">วันที่ออก</th>
                    <th style= "text-align:center">วันที่ครบกำหนดชำระ</th>
                    <th style= "text-align:center">รายการ</th>
                    <th style= "text-align:center">จำนวนเงิน</th>
                    <th style= "text-align:center">Slip</th>
                    <th style= "text-align:center">Invoice</th>
                    <th style= "text-align:center">สถานะ</th>
                </tr>
                <tr ng-show="pvcs.length === 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-C to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="pvc in pvcs">
                    <td style= "text-align:center"><a href ="https://uaterp.cbachula.com/file/pvc/{{pvc.pv_no}}">{{pvc.pv_no}}</a></td>
                    <td style= "text-align:center">{{pvc.pv_date}} {{pvc.pv_time}}</td>
                    <td style= "text-align:center">{{pvc.pv_due_date}}</td>
                    <td style= "text-align:center">{{pvc.pv_details}}</td>
                    <td style= "text-align:center">{{pvc.total_paid | number : 2}}</td>
                    <td style= "text-align:center">
                        <span ng-show="pvc.slip_name == null">fin ยังไม่ upload slip</span>
                        <a ng-show = "pvc.slip_name != null" href="/acc/confirm_payment_voucher/get_pvcslip/{{pvc.pv_no}}" target="_blank" ng-click="stopEvent($event)">Slip</a> 

                    </td>
                    <td style= "text-align:center">
                        <span ng-show="pvc.iv_name == null">fin ยังไม่ upload ใบ iv</span>
                        <a ng-show = "pvc.iv_name!= null" href="/acc/confirm_payment_voucher/get_pvciv/{{pvc.pv_no}}" target="_blank" ng-click="stopEvent($event)">IV</a> 

                    </td>
                  
                    <td style= "text-align:center">
                        <span ng-show="pvc.iv_name == null && pvc.slip_name == null && pvc.confirmed == 0">fin ยังไม่ upload ใบ IV และ Slip</span>
                        <span ng-show="pvc.iv_name == null && pvc.slip_name != null && pvc.confirmed == 0">fin ยังไม่ upload ใบ IV</span>
                        <span ng-show="pvc.iv_name != null && pvc.slip_name == null && pvc.confirmed == 0">fin ยังไม่ upload ใบ Slip</span>
                        <span ng-show="pvc.iv_name != null && pvc.slip_name != null && pvc.confirmed == 0 ">acc ยังไม่ confirm</span>
                        <span ng-show="pvc.iv_name != null && pvc.slip_name != null && pvc.confirmed == 1 ">acc confirmed</span>
                    </td>
                </tr>
            </table>
            
        </div>
        <div ng-show = "temp == 'ReReq'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th style= "text-align:center">เลข EXC</th>
                    <th style= "text-align:center">ผู้กดออก</th>
                    <th style= "text-align:center">วันที่อนุมัติ</th>
                    <th style= "text-align:center">ผู้อนุมัติ</th>
                    <th style= "text-align:center">รายการ</th>
                    <th style= "text-align:center">จำนวนเงิน</th>
                    
                    <th style= "text-align:center">สถานะ</th>
                </tr>
                <tr ng-show="reReqs.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no Re-Req to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="reReq in reReqs" ng-click="viewFile(reReq.re_req_no)">
                    <td style= "text-align:center">{{reReq.ex_no}}</td>
                    <td style= "text-align:center">{{reReq.withdraw_name}} {{reReq.employee_id}}</td>
                    <td style= "text-align:center">{{reReq.authorize_date}}</td>
                    <td style= "text-align:center">{{reReq.authorizer_name}}</td>
                    <td style= "text-align:center">{{(JSONConverter(reReq.details))[0].date}} {{(JSONConverter(reReq.details))[0].details}}</td>
                    <td style= "text-align:center">{{(JSONConverter(reReq.details))[0].money| number : 2}}</td>
                    
                  
                    <td style= "text-align:center">{{reReq.confirmed==0 ? "awaiting confirmation": "confirmed"}}</td>
                </tr>
            </table>
            
        </div>


    </div>

</body> 

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
    .button {
        padding: 10px 20px;
        font-size: 24px;
        text-align: center;
        cursor: pointer;
        outline: none;
        color: #fff;
        background-color: #87CEFA;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px #999;
    }
    .button:hover {
        background-color:#87CEEB
    }
    .button:active {
        background-color: #87CEEB;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.doc = '';
       
        $scope.pvType = '';
        $scope.temp=''
        $scope.selectPVC = function(){
            $scope.temp='PVC'
            $scope.pvcs = <?php echo $this->pvcs; ?>;
            console.log($scope.pvcs)

        }
        $scope.selectReReq = function(){
            $scope.temp='ReReq'
            $scope.reReqs = <?php echo $this->reReqs; ?>;
            console.log($scope.reReqs[0].authorizer_name)
            
        }
        $scope.JSONConverter = function(str){
            return JSON.parse(str)

        }
       
        $scope.viewFile  = function(re_req_no){
            window.open(`https://uaterp.cbachula.com/file/re_req/${re_req_no}`)

        }
        $scope.viewFilePVC  = function(pv_no){
            window.open(`https://uaterp.cbachula.com/file/pvc/${pv_no}`)

        }
        
        


      


    
        
    });

</script>