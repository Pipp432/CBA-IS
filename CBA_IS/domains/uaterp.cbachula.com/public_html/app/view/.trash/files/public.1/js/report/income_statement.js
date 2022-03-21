app.controller('app_controller', ($scope, $http, $compile) => {

    $scope.logs = logs;

    $scope.revenues_1 = 0;
    $scope.revenues_2 = 0;
    $scope.expenses_1 = 0;
    $scope.expenses_2 = 0;

    $scope.expense_list = [];

    angular.forEach($scope.logs, log => {

        var temp = Math.abs(Number(log.credit) - Number(log.debit));

        if (log.account_no == '4-01') {

            if (log.project_no == '1') {
                $scope.revenues_1 += temp;
            } else if (log.project_no == '2') {
                $scope.revenues_2 += temp;
            }

        } else {

            if (!$scope.expense_list.includes(log.account_no)) {
                $('#expense_rows').append('\
                    <tr class="expense_added_row">\
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;' + log.account_name + '</td>\
                        <td class="value" id="' + log.account_no + '-1">0.00</td>\
                        <td class="value" id="' + log.account_no + '-2">0.00</td>\
                    </tr>');
                $scope.expense_list.push(log.account_no);
            }

            console.log('#' + log.account_no + '-' + log.project_no);

            var new_value = Number($('#' + log.account_no + '-' + log.project_no).val()) + temp;
            $('#' + log.account_no + '-' + log.project_no).val(new_value.toFixed(2));

            console.log($('#' + log.account_no + '-' + log.project_no).val());
            console.log(new_value);

            if (log.project_no == '1') {
                $scope.expenses_1 += temp;
            } else if (log.project_no == '2') {
                $scope.expenses_2 += temp;
            }

        }
        
    });

});