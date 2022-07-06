<!DOCTYPE html>
<html>
    <?php flush()?>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
       
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">
			
            <div class="col">
                <div class="card text-white bg-secondary m-2" ng-click="getDashboardIVCR(fetchNum)">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบกำกับภาษี/ใบเสร็จรับเงิน (IV/CR)</h5>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card text-white bg-primary m-2" ng-click="getDashboardPO(1)" >
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบสั่งซื้อ (PO)</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVC_confirm()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-C (ค่าใช้จ่าย)</h5>
                    </div>
                </div>
            </div>
<!-- 
            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPV()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบสำคัญสั่งจ่าย (PV)</h5>
                    </div>
                </div>
            </div> -->

		</div>
		<div class="row row-cols-2 row-cols-md-3 mt-2 p-0">

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVA()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-A (เงินรองจ่าย)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardEXA()">
                    <div class="card-body">
                        <h5 class="card-title my-0">EX-A (เงินรองจ่าย)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVB()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-B (Supplier)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVD()">
                    <div class="card-body">
                        <h5 class="card-title my-0">PV-D </h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPre_PVD()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบลดหนี้ </h5>
                    </div>
                </div>
            </div>
        
            <div class="col">
                <div class="card text-white bg-info m-2" ng-show = 'showFilter' >
                    <select ng-model = 'selectedCompany' ng-change = "dropDownHandler()" ng-click = "clearDashboard()">
                    <option value="">Select โครงการ</option>
                    <option value="1">โครงการ 1</option>
                    <option value="2">โครงการ 2</option>
                    <option value="3">โครงการ 3</option>
                    </select>
                </div>
            </div>

            <!-- <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVC()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบเบิกค่าใช้จ่าย</h5>
                    </div>
                </div>
            </div> -->
            

            
        </div>
        
        <div id="overlay" onclick="off()" ng-show = "dashboards.length==0">
            <div class="loader">Wait a minute (รอหน่อยๆ)</div>
            
        </div>
       
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT everything not pv  -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div ng-show = "temp != 'PV-D' && temp != 'PPV-D' && temp != 'PV-A' && temp != 'PV-C' && temp != 'PPV-C' && temp != 'EX-A' " class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข {{doc}}</th>
                 
                    <th>{{temp}}</th>
                    <th ng-show="doc == 'PV'">ชื่อ Supplier</th>
					<th ng-show="doc == 'PO'">เลข SO</th>
                    <th ng-show="doc == 'IV_CR'">เลข SOX</th>
                    
                    <th>วันที่</th>
                    <th>ผู้อนุมัติ</th>
                    <th ng-show="doc == 'PV'">สถานะ</th>
                </tr>
                <tr ng-show="isLoad && doc != ''">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                    </th>
                </tr>
                <tr ng-repeat="dashboard in dashboards | orderBy:'file_no':true:[orderByPO,orderByCompany]" ng-click="viewFile(dashboard)">
                    <td>{{dashboard.file_no}} 
                        <span ng-show="doc == 'PO'">({{dashboard.rr}}{{dashboard.ci}})</span>
                        <span ng-show="doc == 'IV' && dashboard.invoice_type == 'CN'">(ลดหนี้)</span>
                    </td>
                    <td>{{dashboard.temp}}</td>
                    <td ng-show="doc == 'PV'">{{dashboard.pv_name}}</td>
					<td ng-show="doc == 'PO'">{{dashboard.so}}</td>
                    <td ng-show="doc == 'IV_CR' && dashboard.cancelled =='1' "><p style="color: red">{{dashboard.sox_no}}</p></td>
                    <td ng-show="doc == 'IV_CR' && dashboard.cancelled =='0' "><p style="color: green">{{dashboard.sox_no}}</p></td>
                    <td>{{dashboard.file_date}} {{dashboard.file_time}}</td>
                    <td>{{dashboard.file_emp_id}} {{dashboard.file_emp_name}}</td>
                    <td ng-show="doc == 'PV'">
                        <span ng-show="dashboard.slip_name == null && dashboard.receipt_name == null && dashboard.paid == 0">รอโอนเงิน</span>
                        <span ng-show="dashboard.slip_name != null && dashboard.receipt_name == null && dashboard.paid == 0">โอนเงินแล้ว</span>
                        <span ng-show="dashboard.slip_name != null && dashboard.receipt_name != null && dashboard.paid == 0">ได้ใบเสร็จแล้ว</span>
                        <span ng-show="dashboard.paid == 1">Confirmed</span>
                        <span ng-show="dashboard.slip_name != null && temp != 'PV-B'"> <a href="/acc/dashboard/pv_slip/{{dashboard.file_no}}" target="_blank" ng-click="stopEvent($event)">สลิป invoice</a></span>
                        <span ng-show="dashboard.slip_name != null && temp == 'PV-B'"> <a href="/acc/dashboard/pvb_slip/{{dashboard.file_no}}" target="_blank" ng-click="stopEvent($event)">สลิป invoice</a></span>
                        <span ng-show="dashboard.cr_name == null">ไม่มีใบ CR </span>
                        <a ng-show="dashboard.cr_name != null" href="/acc/dashboard/get_PVB_CR/{{dashboard.file_no}}" target="_blank" ng-click="stopEvent($event)">ดูใบ CR</a> </span>

                        
                        <span ng-show="pvType == 'Supplier'"> 
                            <a  href="/acc/dashboard/get_IVPC_Files_dashboard/tax/{{dashboard.file_no}}" ng-click="stopEvent($event)" target="_blank">ดูใบวางบิล </a>
                            <a href="/acc/dashboard/get_IVPC_Files_dashboard/bill/{{dashboard.file_no}}" ng-click="stopEvent($event)" target="_blank">ดูใบแจ้งหนี้</a>
                            <a href="/acc/payment_voucher/get_invoice/{{dashboard.rrci_no}}" ng-click="stopEvent($event)" target="_blank">ดูใบกำกับภาษี</a>
                            <a  href="/acc/dashboard/get_IVPC_Files_dashboard/debt/{{dashboard.file_no}}" ng-click="stopEvent($event)" target="_blank">ดูใบลดหนี้ </a> 
                        </span>
                        
                    </td>
                </tr>
            </table>
            <div ></div>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT PVD -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	    <div ng-show = "temp == 'PV-D'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-D</th>
                    <th>เลข CN</th>
                    <th>วันที่ออก PVD</th>
                    <th>เอกสาร</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> no PV-D to show</h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards">
                    <td>{{dashboard.pvd_no}}</td>
                    <td>{{dashboard.cn_no}}</td>
                    <td>{{dashboard.pvd_date}}</td>
                    <td>
                        <!-- <span ng-show="dashboard.PVD_status < 3">fin ยังไม่ upload slip</span> -->
                        <!-- todo get pvd slip -->
                        <!--<a ng-show = "dashboard.PVD_status >= 3" href="/acc/confirm_payment_voucher/get_pvdslip/{{pvd.pvd_no}}" target="_blank" ng-click="stopEvent($event)">slip</a>--> 
                        <a ng-show="dashboard.wsd_status >= 1" href="https://uaterp.cbachula.com/file/cn/{{dashboard.invoice_no}}" target="_blank" ng-click="stopEvent($event)" >CN</a>
                        &ensp;
                        <a ng-show = "dashboard.PVD_status >= 2" href="https://uaterp.cbachula.com/file/pvd/{{dashboard.cn_no}}" target="_blank" ng-click="stopEvent($event)">PV-D</a> 
                        &ensp;
                        <span ng-show="dashboard.PVD_status < 3">ยังไม่โอนเงิน</span>
                        <a ng-show = "dashboard.PVD_status >= 3" href="/acc/confirm_payment_voucher/get_pvdslip/{{dashboard.pvd_no}}" target="_blank" ng-click="stopEvent($event)">Slip</a> 
                    </td>
                    <!-- todo convert status to readable -->
                    <td>
                        <span ng-show="dashboard.wsd_status == 1">รอออก PVD</span>
                        <span ng-show="dashboard.PVD_status == 2">รอโอนเงิน</span>
                        <span ng-show="dashboard.PVD_status == 3">รอ confirm PV</span>
                        <span ng-show="dashboard.PVD_status == 4">confirmed</span>
                    </td>
                </tr>
            </table>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT Pre_PVD -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <div ng-show = "temp == 'PPV-D'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข CN</th>
                    <th>เลข IV</th>
                    <th>เลข SOX</th>
                    <th>วันที่ออก CN</th>
                    <th>เอกสาร CN</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> no CN to show</h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFilePrePVD(dashboard)">
                    <td>
                        <span ng-show="dashboard.wsd_status < 0">-</span>
                        <span ng-show="dashboard.wsd_status >= 1">{{dashboard.cn_no}}</span>
                    </td>
                    <td>{{dashboard.invoice_no}}</td>
                    <td>{{dashboard.sox_no}}</td>
                    <td>
                        <span ng-show="dashboard.wsd_status < 0">-</span>
                        <span ng-show="dashboard.wsd_status >= 1">{{dashboard.cn_date}}</span>
                    </td>
                    <td>
                        <span ng-show="dashboard.wsd_status < 0">-</span>
                        <span ng-show="dashboard.wsd_status == 0">ยังไม่ออกใบ CN</span>
                        <a ng-show="dashboard.wsd_status >= 1" href="https://uaterp.cbachula.com/file/cn/{{dashboard.invoice_no}}" target="_blank" ng-click="stopEvent($event)" >CN</a>
                    </td>
                    <!-- todo convert status to readable -->
                    <td>
                        <span ng-show="dashboard.wsd_status < 0">ยกเลิก</span>
                        <span ng-show="dashboard.wsd_status == 0">รอออกใบลดหนี้</span>
                        <span ng-show="dashboard.wsd_status >= 1">ออกใบลดหนี้สำเร็จ</span>
                    </td>
                </tr>
            </table>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT PVA -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PV-A'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-A</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>เติมเพิ่ม</th>
                    <th>จำนวนเงินจ่ายพนักงาน</th>
                    <th>จำนวนเงินรวม</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-A to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFilePVA(dashboard)">
                    <td>{{dashboard.pv_no}}</td>
                    <td>{{dashboard.pv_date}} {{dashboard.pv_time}}</td>
                    <td class = "newLine">{{dashboard.product_names}}</td>
                    <td>{{dashboard.additional_cash}} <br> {{dashboard.additional_cash_reason}}</td>
                    <td>{{dashboard.total_paid}}</td>
                    <td>{{dashboard.realTotal | number:2}}</td>
                    <td>
                        <span ng-show="dashboard.pv_status < 4">fin ยังไม่ upload slip</span>
                        <a ng-show = "dashboard.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvaslip/{{dashboard.pv_no}}" target="_blank" ng-click="stopEvent($event)">slip</a> 
                    </td>
                    <td>{{dashboard.pv_status_readable}}</td>
                </tr>
            </table>
            
        </div>


        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT EXA -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'EX-A'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข EX-A</th>
                    <th>เลข PV-A</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="dashboardsExa.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-A to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="prePva in dashboardsExa">
                    <td>{{prePva.internal_pva_no}}</td>
                    <td>{{prePva.pv_no}}</td>
                    <td>{{prePva.pv_date}} {{prePva.pv_time}}</td>
                    <td>{{prePva.product_names}}</td>
                    <td>{{prePva.total_paid}}</td>
                    <td>
                        <a target = '_blank' href="/fin/validate_petty_cash_request/get_re/{{prePva.internal_pva_no}}">Check reciept/invoice</a>
                        <a target = '_blank' href="/fin/validate_petty_cash_request/get_iv/{{prePva.internal_pva_no}}">Check slip</a>
                        <a ng-show="prePva.pv_status >= 1" href="/fin/create_pva/get_fin_slip/{{prePva.internal_pva_no}}" target="_blank">สลิปโอนให้พนักงาน</a> 
                    </td>
                    <td>{{prePva.pv_status_readable}}</td>
                </tr>
            </table>
            
        </div>
 
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div ng-show = "temp == 'PPV-C'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-C</th>
                    <th>วันที่</th>
                    <th>จำนวนเงิน</th>
                    <th>ผู้ออกใบเบิกค่าใช้จ่าย</th>
                    <th>ผู้กดยืนยัน</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> ไม่มีใบเบิกค่าใช้จ่าย แสดง </h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards" ng-click="viewFile(dashboard)">
                    <td>{{dashboard.ex_no}}</td>
                    <td>{{dashboard.withdraw_date}}</td>
                    <td>{{dashboard.total_paid}}</td>
                    <td>{{dashboard.employee_id}} {{dashboard.employee_nickname_thai}}</td>
                    <td>{{dashboard.authorizer_name}}</td>
                </tr>
            </table>
            
        </div>


        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div ng-show = "temp == 'PV-C'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-C</th>
                    <th>วันที่</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>เอกสาร IV</th>
                    <th>เอกสาร EXC</th>
                    <th>ผู้ออกใบ PVC</th>
                    <th>ผู้กดยืนยัน</th>
                </tr>
                <tr ng-show="dashboards.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> no PV-C to show </h6>
                    </th>
                </tr>
                <!-- todo view file -->
                <tr ng-repeat="dashboard in dashboards">
                    <td> <a href="/file/pvc/{{dashboard.pv_no}}">{{dashboard.pv_no}}</a></td>
                    <td>{{dashboard.pv_date}}</td>
                    <td>{{dashboard.total_paid|number:2}}</td>
                    <td>
                        <div ng-show="dashboard.slip_name===null">
                            <p>Not Uploaded</p>

                        </div>
                        <div ng-show="dashboard.slip_name!==null">
                        <a href="/acc/dashboard_acc/pv_slip/{{dashboard.pv_no}}">{{dashboard.slip_name}}</a>
                        
                        </div>
                        
                       

                    </td>
                    <td style="text-align: center ;">
                    <div ng-show="dashboard.iv_name===null">
                            <p>Not Uploaded</p>

                        </div>
                        <div ng-show="dashboard.iv_name!==null">
                        <a href="/acc/dashboard_acc/pv_iv/{{dashboard.pv_no}}">{{dashboard.iv_name}}</a>
                        
                        </div>
                     

                    </td>
                    <td style="text-align: center ;">
                        <a href="https://uaterp.cbachula.com/file/exc/{{dashboard.ex_no}}">{{dashboard.ex_no}}</a>
                    </td>
                    
                    <td>{{dashboard.approved_employee}} {{dashboard.employee_nickname_thai}}</td>

                    

                        </div>

                    <td> <div ng-show="dashboard.confirmed_employee===null">☢️
                            <p>Not Confirmed</p>

                        </div>
                        <div ng-show="dashboard.confirmed_employee!==null">
                            <p>{{dashboard.confirmed_employee}}</p>
                        </td>

                </tr>
            </table>
           
            
        </div>
        <!-- <div class ="page-selector" ng-show = "temp =='เลข SO'">
        <button ng-click = "decrement()" id= 'decrement'> << </button><h2 >{{pageNum}}</h2><button ng-click = 'increment()'> >> </button><button ng-click = 'viewAll()'> VIEW ALL (WARNING SLOW !!!) </button>
       
        </div> -->
    </div>
    
    
   

</body> 


</html>


<style>
    td { border-bottom: 1px solid lightgray; text-align: center;}
    th { border-bottom: 1px solid lightgray; text-align: center; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
    .newLine {white-space: pre}
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        position: absolute;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        text-align: center;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
    .WIP{
        font-size: 30px;
    }
    .page-selector{
        display: flex;
        width: 100%;
        gap: 10px;
    
        justify-content: center

    }
    .page-selector button{
        background-color: #33f5f2;
        color:black;
        margin: 10px
        
    }
    .page-selector button:hover{
        background-color: #4339fa;
        color: white;
    }
    #overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
    cursor: pointer; /* Add a pointer on hover */
}
   
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        // $scope.isLoad = true;
        $scope.dashboards = [];
        $scope.doc = '';
        $scope.selectedCompany ='';
        $scope.showFilter = false;
    
        
       
        
        $scope.pageNum = 1;
        $scope.on =  function(){
            document.getElementById("overlay").style.display = "block";
        }
        $scope.off = function() {
  document.getElementById("overlay").style.display = "none";
}
        $scope.decrement = function(){
            if($scope.pageNum===1){
                $scope.pageNum = 1;
                
            }else{
                $scope.on()
;
                $scope.pageNum--;
                $scope.fetchNum = $scope.pageNum * 100 ===100 ? 0 :$scope.pageNum * 100;
                $scope.getDashboardIVCR($scope.fetchNum)
            }
    
        }
        $scope.increment = function(){
            $scope.dashboards = [];
            $scope.on()
            $scope.pageNum++;
            $scope.fetchNum = $scope.pageNum * 100 ===100 ? 0 :($scope.pageNum * 100)-100;
            $scope.getDashboardIVCR($scope.fetchNum)
          
        }
        $scope.viewAll = function(){
            $scope.fetchNum = 999;
            $scope.getDashboardIVCR($scope.fetchNum)

        }
        
       
        
        convert_pva_status = {
            '-1':"ยกเลิก",
            0:"รอ finance โอนให้พนักงาน",
            1:"รอ finance รวมใบขอ pva",
            2:"รอ account สร้าง pva",
            3:"รอ finance โอนเงินเข้าบัญชีเงินรองจ่าย",
            4:"รอ account confirm pva", 
            5:"เรียบร้อย",
        }





       
       $scope.dropDownHandler = function(){
            $scope.getDashboardPO($scope.selectedCompany);
       }
        
     
     
        $scope.pvType = '';
   
		$scope.getDashboardIVCR = function(fetchNum) {
            console.log(fetchNum)
            $http.get(`/acc/dashboard/getIV_CR/${fetchNum}`).then((response)=>{
                
                $scope.dashboards = response.data;
               
            })

           
            $scope.doc = 'IV_CR';
            $scope.temp = 'เลข SO';
        }
        // $scope.getDashboardIV = function() {
        
        //     $scope.dashboards = $scope.dashboardsIv;
        //     $scope.doc = 'IV';
        //     $scope.temp = 'เลข SO';
        // }
        
		$scope.getDashboardCR = function() {
            $scope.dashboardsCr = <?php echo $this->dashboardCr; ?>;
            $scope.dashboards = $scope.dashboardsCr;
            $scope.doc = 'CR';
            $scope.temp = 'เลข IV';
        }
        
        $scope.getDashboardPV = function() {
            $scope.dashboardsPv = <?php echo $this->dashboardPv; ?>;
            $scope.dashboards = $scope.dashboardsPv; 
            $scope.doc = 'PV';
            $scope.pvType = '';
            $scope.temp = 'ประเภทการสั่งจ่าย';
        }

        $scope.getDashboardPVA = function() {
            $scope.showFilter = false;
            $scope.dashboardsPva = <?php echo $this->dashboardPva;  ?>;
            $scope.dashboards = $scope.dashboardsPva; 
            $scope.doc = 'PV';
            $scope.pvType = 'pva';
            $scope.temp = 'PV-A';
            angular.forEach($scope.dashboardsPva, function(value, key) {
            value["pv_status_readable"] = convert_pva_status[value["pv_status"]];
            value["realTotal"] = parseFloat(value["total_paid"]) + parseFloat(value["additional_cash"]);
        });
        }

        $scope.getDashboardEXA = function() {
            $scope.showFilter = false;
            $scope.dashboardsExa = <?php echo $this->dashboardExa; ?>;
            $scope.dashboards = [];
            $scope.doc = 'PV';
            $scope.pvType = 'exa';
            $scope.temp = 'EX-A';
            angular.forEach($scope.dashboardsExa, function(value, key) {
            value["pv_status_readable"] = convert_pva_status[value["pv_status"]];
        });
        }

        $scope.getDashboardPVB = function() {
            $scope.dashboardsPvb = <?php echo $this->dashboardPvb; ?>;
            $scope.showFilter = false;
            $scope.dashboards = $scope.dashboardsPvb; 
            $scope.doc = 'PV';
            $scope.pvType = 'Supplier';
            $scope.temp = 'PV-B';
        }

        $scope.getDashboardPVD = function() {
            $scope.showFilter = false;
            $scope.dashboardsPvd = <?php echo $this->dashboardPvd; ?>;

            $scope.dashboards = $scope.dashboardsPvd; 
            $scope.doc = 'PV';
            $scope.pvType = 'pvd';
            $scope.temp = 'PV-D';
        }

        $scope.getDashboardPre_PVD = function() {
            $scope.showFilter = false;
            $scope.dashboardsPrePvd = <?php echo $this->dashboardPrePvd; ?>;
            $scope.dashboards = $scope.dashboardsPrePvd; 
            $scope.doc = 'PV';
            $scope.pvType = 'pvd';
            $scope.temp = 'PPV-D';
        }

        $scope.getDashboardPVC = function(){
            $scope.showFilter = false;
            $scope.dashboardsPvc = <?php echo $this->dashboardPvc; ?>;
            $scope.dashboards = $scope.dashboardsPvc; 
           
            $scope.doc = 'PV';
            $scope.pvType = 'pvc';
            $scope.temp = 'PPV-C';
        }

        $scope.getDashboardPVC_confirm = function(){
            $scope.showFilter = false;
            $scope.dashboardsPvc_confirm = <?php echo $this->dashboardPvc_confirm; ?>;
            $scope.dashboards = $scope.dashboardsPvc_confirm; 
            $scope.doc = 'PV';
            $scope.pvType = 'pvc';
            $scope.temp = 'PV-C';
        }
       
      
        
        

        
        // $scope.getDashboardPO = function() {
       
        //     $scope.dashboards = $scope.dashboardsPo;
        //     $scope.doc = 'PO';
        //     console.log($scope.dashboards);
            
        //     $scope.temp = 'Supplier';
           
        // }
        $scope.getDashboardPO = function(company) {
            $scope.showFilter = true;
           
            $http.get(`/acc/dashboard/getPO/${company}`).then((response)=>{
                
                $scope.dashboards = response.data;
                console.log($scope.dashboards)
               
            })

           
            $scope.doc = 'PO';
            $scope.temp = 'เลข SO';
           
        }
        $scope.clearDashboard = ()=>{
            $scope.dashboards = []
            console.log($scope.dashboards)
        }
        $scope.dropDownHandler = ()=>{
            
            $scope.getDashboardPO($scope.selectedCompany);
            console.log($scope.dashboards);
            
        }

        $scope.stopEvent = function(e){
            e.stopPropagation();
        }
        
        $scope.viewFile = function(file) {
            if(file.invoice_type == 'CN') {
                window.open('/file/iv/' + file.invoice_no);
            } else {
                window.open('/file/' + $scope.doc.toLowerCase() + '/' + file.file_no);
            }
        }

        $scope.viewFilePrePVD = function(file) {
            window.open('/file/iv/' + file.invoice_no);
        }

        $scope.viewFilePVA = function($dashboard) {
            window.open('/file/pva/' + $dashboard.pv_no);
        }
        $scope.orderByPO = function(p1,p2)
        {
            first = p1.value.substring(4)
            second = p2.value.substring(4);
          
            return parseInt(first)<parseInt(second) ? -1:1;
        }
        $scope.orderByCompany = function(p1,p2)
        {
            first = p1.value[0];
            second = p2.value[0];
           
            return parseInt(first)<parseInt(second) ? -1:1;
        }
         
        
        

    
        
        
    });

</script>