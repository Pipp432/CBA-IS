<!DOCTYPE html>
<html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบกำกับภาษีสำหรับ Counter Sales / Tax Invoice for CS (IC)</h2> 

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CS AND CS DETAIL -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="textboxCS">เลขที่ CS</label>
                        <input type="text" class="form-control" id="textboxCS" ng-model="filterCS">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                
                    <table class="table table-hover my-1">
                        <tr>
                            <th>เลข SO</th>
                            <th>เลข CS</th>
                            <th>สถานที่</th>
                            <!--<th>SP</th>-->
                            <th>เลข CE</th>
                            <th>payment date</th>
                            <th>payment time</th>
                            <th>payment amount</th>
                            <th>ราคารววม</th>
                        </tr>
                        <tr ng-show="CSs.length == 0">
                            <th colspan="8">ไม่มีเลข CS ที่ต้องออก IV</th>
                        </tr>
                        <tr ng-repeat="cs in CSs | unique:'cs_no' | filter:{cs_no:filterCS} | orderBy:['cs_no', 'cs_date']" ng-click="addCrItem(cs)" ng-show="CSs.length > 0">
                            <td>{{cs.so_no}}</td>
                            <td>{{cs.cs_no}}</td>
                            <td>{{cs.location_name}}</td>
                            <td>{{cs.employee_id}} {{cs.employee_nickname_thai}}</td>
                            <td>{{cs.slip_date}}</td>
                            <td>{{cs.slip_time}}</td>
                            <td>{{cs.so_total_sales_price}}</td>
                            
                            <!--<td ng-repeat="emp in cs | unique:'employee_id' | orderBy:['employee_id']">{{emp.Emp_nickname}} {{emp.employee_id}}</td>-->
                            <td style="text-align: right;">
                                <a href="/fin/invoice_cs/get_csslip/{{cs.so_no}}" target="_blank" ng-show="{{cs.slip_uploaded==1}}">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> ไฟล์สลิป
                                </a>
                                
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING CS ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบกำกับภาษี</h4>
                    <h5 style="color:red"> **ต้องกรอกข้อมูลให้ครบถ้วนก่อนกดออก IV** </h5>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="crItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข CS</th>
                        </tr>
                    </table>
                    <div class="col-md-12 mx-0 mt-2" ng-show="crItems.length != 0">
                        <label>ชื่อลูกค้า / บริษัท</label>
                    </div>
                    <div class="col-sm-2" ng-show="crItems.length != 0">
                        <input type="text" class="form-control" id="textBoxCusTitle" ng-model="sox.customerTitle">
                    </div>
                    <div class="col-sm-10" ng-show="crItems.length != 0">
                        <input type="text" class="form-control" id="textBoxCusName" ng-value="sox.customer_name + ' ' + sox.customer_surname">
                    </div>
                    <div class="col-md-12 mx-0 mt-2" ng-show="crItems.length != 0">
                        <label for="textboxCusAddress">สถานที่</label>
                        <input type="text" class="form-control" id="textboxCusAddress" ng-value="cs.location_name">
                    </div>
                    <div class="col-md-12 mx-0 mt-2" ng-show="crItems.length != 0">
                        <label for="textboxCusId">เลขประจำตัวผู้เสียภาษี</label>
                        <input type="text" class="form-control" id="textboxCusId" ng-value="cs.national_id">
                    </div>
                    <table class="table table-hover mb-1 mt-2" ng-show="crItems.length != 0">
                        <tr>
                            <th>เลข CS</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-repeat="CSPrinting in crItems">
                            <td>{{CSPrinting.cs_no}}</td>
                            <td>{{CSPrinting.product_name}}</td>
                            <td style="text-align: right;">{{CSPrinting.sales_price | number:2}}</td>
                            <td style="text-align: right;">{{CSPrinting.quantity}}</td>
                            <td style="text-align: right;">{{CSPrinting.sales_price * CSPrinting.quantity | number:2}}</td>
                        </tr>

                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวมก่อนภาษี</th>
                            <th id="totalPrice" style="text-align: right;">{{priceBeforeVat | number:2}}</th>
                            <!--ผิด table-->
                        </tr>
             
                        <tr>
                            <th style="text-align: right;" colspan="4">ภาษี 7%</th>
                            <th id="totalPrice" style="text-align: right;">{{vat | number:2}}</th>
                        </tr>  
                        
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวม</th>
                            <th id="totalPrice" style="text-align: right;">{{crItems[0].so_total_sales_price}}</th>
                        </tr>
                    </table>
                </div>  
                <hr>
                <div class="row mx-0 mt-2">
                    <div class="col-md-12 mx-0 mt-2">
                        <label for="textBoxNoted">หมายเหตุ</label>
                        <input type="text" class="form-control" id="textBoxNoted" ng-model="Noted" placeholder="หมายเหตุ">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึกใบเสร็จรับเงิน</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบกำกับภาษี / Invoice (IV)', 'ยังไม่ได้เพิ่มเลข CS');
            addModal('formValidate2', 'ใบกำกับภาษี / Invoice (IV)', 'ยังไม่ได้เพิ่มชื่อลูกค้า');
            addModal('formValidate3', 'ใบกำกับภาษี / Invoice (IV)', 'ยังไม่ได้เพิ่มที่อยู่ลูกค้า');
            addModal('formValidate4', 'ใบกำกับภาษี / Invoice (IV)', 'ยังไม่ได้เพิ่มเลขประจำตัวผู้เสียภาษีลูกค้า');
            addModal('formValidate5', 'ใบกำกับภาษี / Invoice (IV)', 'ยังไม่ได้เลือกธนาคาร');
            addModal('formValidate6', 'ใบกำกับภาษี / Invoice (IV)', 'ยังไม่ได้ลงวัน/เวลาการโอน');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {

        $scope.crItems = [];
        $scope.CSs = <?php echo $this->CSs; ?>;
        $scope.cs = '';
        $scope.cs_total_sales_price = 0;

        $scope.finalPrice=0;
        $scope.priceBeforeVat=0;
        $scope.vat=0;
        var first = true;
        
        $scope.addCrItem = function(cs) {
            
            $scope.crItems = [];
            $scope.cs = cs;
            angular.forEach($scope.CSs, function (value, key) {
                if(value.cs_no == cs.cs_no) {
                    $scope.crItems.push(value);
                }
            });
            $scope.calculateCrItem(cs);
        }

        $scope.processNumber = function(cs){

                $scope.actualPrice = Number(cs.so_total_sales_price);
                console.log('actual price'+$scope.actualPrice);

                $scope.creditCardfee = 0;
                $scope.actualPrice = $scope.finalPrice;
                $scope.finalPrice = (Number(cs.so_total_sales_price)).toFixed(2);
                console.log('final price'+$scope.actualPrice);
              
                if(cs.vat_type==='3') { 
                    $scope.priceBeforeVat = $scope.finalPrice;
                    $scope.vat = 0;
                }else{
                    $scope.vat =   $scope.finalPrice * 7/107;
                    $scope.priceBeforeVat = $scope.finalPrice *100/107
                }
                    

            console.log($scope.vat);
            console.log($scope.priceBeforeVat);

        }
        
        $scope.calculateCrItem = function(cs) {
            
            $scope.cs_total_sales_price = 0
            
            angular.forEach($scope.crItems, function(value, key) {
                if(value.cs_no == $scope.cs.cs_no) {
                    $scope.cs_total_sales_price += (value.sales_price * value.quantity);
                }
            });
            $scope.processNumber(cs);
            console.log($scope.cs_total_sales_price);
        }
        
        var click = false;
        
        $scope.formValidate = function() {
            if(!click){
                if($scope.crItems.length === 0) {
                    $('#formValidate1').modal('toggle');
                } else if ($('#textBoxCusName').val() === '') {
                    $('#formValidate2').modal('toggle');
                } else if ($('#textboxCusAddress').val() === '') {
                    $('#formValidate3').modal('toggle');
                } else if ($('#textboxCusId').val() === '') {
                    $('#formValidate4').modal('toggle');
                } else if ($scope.selectedBank === '') {
                    $('#formValidate5').modal('toggle');
                } else if ($scope.TransferTime === '') {
                    $('#formValidate6').modal('toggle');
                } else {
                    
                    console.log(NumToThai(parseFloat($scope.cs_total_sales_price)));
                    console.log($scope.cs_total_sales_price);
                    
                    var confirmModal = addConfirmModal('confirmModal', 'ใบเสร็จรับเงิน / Cash Receipt (CR)', 'ยืนยันการออกใบกำกับภาษีและใบเสร็จรับเงิน', 'postIvCr()');
                    $('body').append($compile(confirmModal)($scope));
                    $('#confirmModal').modal('toggle');
                }
                click = true;
                
                
            }
        }
        
        $scope.postIvCr = function() {
            
            $('#confirmModal').modal('hide');
            console.log($('#textBoxCusName').val());   
            console.log($('#textboxCusAddress').val());
            console.log($('#textboxCusId').val());
            console.log($scope.Noted);
            console.log($scope.cs_total_sales_price);                
            $.post("/fin/invoice_cs/post_iv", {
                post : true,
                priceInThai: NumToThai(parseFloat($scope.so_total_sales_price)),
                cs_total_sales_price: $scope.cs_total_sales_price,
                cusName : $('#textBoxCusName').val(),
                cusAddress : $('#textboxCusAddress').val(),
                cusId : $('#textboxCusId').val(),
                noted : $scope.Noted,
                cs_number : $scope.cs.cs_no,
                crItems : JSON.stringify(angular.toJson($scope.crItems))
            }, function(data) {
               
               addModal('successModal', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'บันทึก ' + data);
             
               $('#successModal').modal('toggle');
               $('#successModal').on('hide.bs.modal', function (e) {
                   window.open('/file/iv_cs/' + data.substring(0, 9));
                   location.reload();
                   // window.location.assign('/');
               });           
           }).done(()=>{console.log("DONE")}).fail(function(a,b,c){
               console.log(a)
               console.log(b)
               console.log(c)
           });  
            
        }
        
    });

</script>