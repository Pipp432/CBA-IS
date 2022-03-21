<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">สมุดรายวันทั่วไป / General Journal</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING ACCOUNT DETAIL -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px; font-family: 'Sarabun';">
            <div class="card-body">
                <div class="row mx-0">
                    <table class="table table-hover table-bordered my-1" id="accountDetailTable">
                        <tr>
                            <th>วันที่</th>
                            <th>รายการ</th>
                            <th>เลขที่บัญชี</th>
                            <th>เดบิต</th>
                            <th>เครดิต</th>
                            <th>note</th>
                        </tr>
                        <!--<tr ng-show="isLoad">
                            <th colspan="6">
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>-->
                        
                        <style>
                            p { font-family: 'Sarabun', sans-serif; }
                            td { border-bottom: 1px solid lightgray; }
                            th { border-bottom: 1px solid lightgray; text-align: center; }
                        </style>
                        
                        <script>
                            
                            var accountDetails = <?php echo $this->accountDetails; ?>;
                            var distinctFileNo = [];
                            
                            for (var i = 0; i < accountDetails.length; i++) {
                                if(!distinctFileNo.includes(accountDetails[i].file_no)) {
                                    $('#accountDetailTable').append('<tr> \
                                            <td>' + accountDetails[i].date + '</td> \
                                            <td id="account_name' + accountDetails[i].file_no.replace(/\//g, "-") + '"></td> \
                                            <td id="account_no' + accountDetails[i].file_no.replace(/\//g, "-") + '"></td> \
                                            <td id="account_debit' + accountDetails[i].file_no.replace(/\//g, "-") + '" style="text-align: right;"></td> \
                                            <td id="account_credit' + accountDetails[i].file_no.replace(/\//g, "-") + '" style="text-align: right;"></td> \
                                            <td>' + accountDetails[i].file_no + '</td> \
                                        </tr>');
                                    distinctFileNo.push(accountDetails[i].file_no);
                                }
                            }
                            
                            for (var i = 0; i < accountDetails.length; i++) {
                                
                                if(parseFloat(accountDetails[i].debit) == 0 && parseFloat(accountDetails[i].credit) != 0) 
                                    $('#account_name' + accountDetails[i].file_no.replace(/\//g, "-")).append('<p>        ' + accountDetails[i].account_name + '</p>');
                                else 
                                    $('#account_name' + accountDetails[i].file_no.replace(/\//g, "-")).append('<p>' + accountDetails[i].account_name + '</p>');
                                
                                var debit = (parseFloat(accountDetails[i].debit) == 0) ? '-' : accountDetails[i].debit;
                                var credit = (parseFloat(accountDetails[i].credit) == 0) ? '-' : accountDetails[i].credit;
                                
                                $('#account_no' + accountDetails[i].file_no.replace(/\//g, "-")).append('<p>' + accountDetails[i].account_no + '</p>');
                                $('#account_debit' + accountDetails[i].file_no.replace(/\//g, "-")).append('<p>' + debit + '</p>');
                                $('#account_credit' + accountDetails[i].file_no.replace(/\//g, "-")).append('<p>' + credit + '</p>');
                                
                            }
                            
                            app.controller('moduleAppController', function($scope, $http, $compile) {});
                            
                        </script>
                        
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>