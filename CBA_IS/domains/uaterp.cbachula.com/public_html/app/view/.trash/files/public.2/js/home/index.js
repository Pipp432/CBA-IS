app.controller('app_controller', ($scope, $compile) => {

    $scope.is_load = true;

    $http.get('/home/index/get_courses').then((response) => {
        $scope.courses = response.data;
        $scope.is_load = false;
        console.log(response.data);
    });

});