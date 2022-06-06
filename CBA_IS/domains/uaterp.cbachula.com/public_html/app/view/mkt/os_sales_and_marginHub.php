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

    
    <div class="container mt-3" ng-controller="S&OHeadController">
        <select class="form-control" ng-model="page" ng-change = "goto(page)">
            <option value='s'>เลือก product line</option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            <option value='6'>6</option>
            <option value='7'>7</option>
            <option value='8'>8</option>
            <option value='9'>9</option>
            <option value='a'>all</option>
            
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


    app.controller('S&OHeadController', function($scope, $http, $compile) {
        $scope.page = window.location.href.slice(-1);
        $scope.goto = function(link){
            if($scope.page != 's') window.location.assign(link);
        }
    });

    app.controller('navbarController', function($scope, $http, $compile) {
        $http.get("/home/employeeDetail").then(function(response) {
            $scope.employeeDetail = response.data;
        });
    });


</script>