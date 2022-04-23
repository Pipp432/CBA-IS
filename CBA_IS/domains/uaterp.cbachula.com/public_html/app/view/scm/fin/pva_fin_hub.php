<!DOCTYPE html>
<html>

<body>

    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top sticky-top" ng-controller="navbarController">
        <div class="container px-0">

            <a class="navbar-brand" href="/home">
                <img src="/public/img/cba-logo.png" height="50" style="color: #e2c569;">
            </a>

            <ul class="navbar-nav">
                <h6 class="m-0"><li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{employeeDetail.employee_id}} - {{employeeDetail.employee_nickname_eng | uppercase}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/">หน้าแรก</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/signin/signout">ออกจากระบบ</a>
                    </div>
                </li></h6>
            </ul>

        </div>
    </nav>

    
    <div class="container mt-3" ng-controller="pvaHeadController">
        <select class="form-control" ng-model="page" ng-change = "goto(page)">
            <option value='pva_fin_hub'>เลือกหน้า PV-A</option>
            <option value='validate_petty_cash_request'>โอนเงินให้พนักงาน</option>
            <option value='create_pva'>รวมใบเบิกเงินรองจ่ายเพื่อขอ PV-A</option>
            <option value='top_up_pva'>โอนเงินเข้าบัญชี PV-A</option>
            <option value='pva_status'>ประวัติ PV-A</option>
        </select>
    </div>

</body>

</html>

<style>
    .navbar {
        background-color: #A58FAA;
        box-shadow: 0 -6px 6px 4px #888;
    }
</style>

<script>


    app.controller('pvaHeadController', function($scope, $http, $compile) {
        $scope.page = 'pva_fin_hub';
        $scope.goto = function(link){
            if($scope.page != 'pva_fin_hub') window.location.assign(link);
        }
    });

    app.controller('navbarController', function($scope, $http, $compile) {
        $http.get("/home/employeeDetail").then(function(response) {
            $scope.employeeDetail = response.data;
        });
    });


</script>