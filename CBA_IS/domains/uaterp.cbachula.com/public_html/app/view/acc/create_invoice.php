<!DOCTYPE html>
<html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบกำกับภาษี / Tax Invoice (IV)</h2> 

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING SP AND SOX DETAIL -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="textboxSOX">เลขที่ SOX</label>
                        <input type="text" class="form-control" id="textboxSOX" ng-model="filterSox">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1">
                        <tr>
                            <th>เลข SOX</th>
							<th>เลข CI</th>
                            <th>วันที่</th>
                            <th>SP</th>
                            <th>ลูกค้า</th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-show="soxs.length == 0">
                            <th colspan="8">ไม่มีเลข SOX ที่อัปโหลดสลิปการชำระเงิน</th>
                        </tr>
                        <tr ng-repeat="sox in soxs | unique:'sox_no' | filter:{sox_no:filterSox} | orderBy:['sox_datetime']" ng-click="addIvItem(sox)" ng-show="soxs.length > 0">
                            <td>{{sox.sox_no}}</td>
							<td>{{sox.ci_no}}</td>
                            <td>{{sox.sox_datetime}}</td>
                            <td>{{sox.employee_id}} {{sox.employee_nickname_thai}}</td>
                            <td>{{sox.customer_name}} {{sox.customer_surname}}</td>
                            <td style="text-align: right;">
                                {{sox.sox_sales_price | number:2}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING CR ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบกำกับภาษี</h4>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="ivItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข SOX</th>
                        </tr>
                    </table>
                    <div class="col-md-12 mx-0 mt-2" ng-show="ivItems.length != 0">
                        <label for="textBoxCusName">ชื่อลูกค้า / บริษัท</label>
                        <input type="text" class="form-control" id="textBoxCusName" ng-value="sox.customer_name + ' ' + sox.customer_surname">
                    </div>
                    <div class="col-md-12 mx-0 mt-2" ng-show="ivItems.length != 0">
                        <label for="textboxCusAddress">ที่อยู่</label>
                        <input type="text" class="form-control" id="textboxCusAddress" ng-value="sox.address">
                    </div>
                    <div class="col-md-12 mx-0 mt-2" ng-show="ivItems.length != 0">
                        <label for="textboxCusId">เลขประจำตัวผู้เสียภาษี</label>
                        <input type="text" class="form-control" id="textboxCusId" ng-value="sox.national_id">
                    </div>
                    <table class="table table-hover mb-1 mt-2" ng-show="ivItems.length != 0">
                        <tr>
                            <th>เลข SO</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-repeat="soPrinting in ivItems">
                            <td>{{soPrinting.so_no}}</td>
                            <td>{{soPrinting.product_name}}</td>
                            <td style="text-align: right;">{{soPrinting.sales_price | number:2}}</td>
                            <td style="text-align: right;">{{soPrinting.quantity}}</td>
                            <td style="text-align: right;">{{soPrinting.sales_price * soPrinting.quantity | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">ส่วนลด</th>
                            <th id="totalPrice" style="text-align: right;">{{ivItems[0].so_total_discount | number:2}}</th>
                        </tr>  
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวม</th>
                            <th id="totalPrice" style="text-align: right;">{{sox.sox_sales_price | number:2}}</th>
                            <!--ผิด table-->
                        </tr>
                        <!--<tr>
                            <th style="text-align: right;" colspan="4">คะแนน</th>
                            <th id="totalPrice" style="text-align: right;">{{ivItems[0].total_point | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">Commission</th>
                            <th id="totalPrice" style="text-align: right;">{{ivItems[0].total_commission | number:2}}</th>
                        </tr>-->
                    </table>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <!--<div class="col-md-6">
                        <label for="dropdownBank">ธนาคาร</label>
                        <select class="form-control" ng-model="selectedBank" id="dropdownBank">
                            <option value="">เลือกธนาคาร</option>
                            <option value="ธนาคารกรุงเทพ">ธนาคารกรุงเทพ</option>
                            <option value="ธนาคารกรุงไทย">ธนาคารกรุงไทย</option>
                            <option value="ธนาคารกรุงศรีอยุธยา">ธนาคารกรุงศรีอยุธยา</option>
                            <option value="ธนาคารกสิกรไทย">ธนาคารกสิกรไทย</option>
                            <option value="ธนาคารเกียรตินาคิน">ธนาคารเกียรตินาคิน</option>
                            <option value="ธนาคารซีไอเอ็มบี">ธนาคารซีไอเอ็มบี</option>
                            <option value="ธนาคารทหารไทย">ธนาคารทหารไทย</option>
                            <option value="ธนาคารทิสโก้">ธนาคารทิสโก้</option>
                            <option value="ธนาคารไทยพาณิชย์">ธนาคารไทยพาณิชย์</option>
                            <option value="ธนาคารธนชาติ">ธนาคารธนชาติ</option>
                            <option value="ธนาคารยูโอบี">ธนาคารยูโอบี</option>
                            <option value="ธนาคารแลนด์ แอนด์ เฮ้าส์">ธนาคารแลนด์ แอนด์ เฮ้าส์</option>
                            <option value="ธนาคารสแตนดาร์ดชาร์เตอร์ด">ธนาคารสแตนดาร์ดชาร์เตอร์ด</option>
                            <option value="ธนาคารไอซีบีซี">ธนาคารไอซีบีซี</option>
                            <option value="ธนาคารออมสิน">ธนาคารออมสิน</option>
                            <option value="ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร">ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร</option>
                            <option value="ธนาคารอาคารสงเคราะห์">ธนาคารอาคารสงเคราะห์</option>
                            <option value="Wallet">Wallet</option>
                        </select>
                    </div>               
                    <div class="col-md-6">
                        <label for="datetime-input">วัน/เวลาโอน</label>
                        <input class="form-control" type="datetime-local" value="2020-01-01T13:00:00" id="datetime-input" ng-model="TransferTime">
                    </div>-->
                    <div class="col-md-12 mx-0 mt-2">
                        <label for="textBoxNoted">หมายเหตุ</label>
                        <input type="text" class="form-control" id="textBoxNoted" ng-model="Noted" placeholder="หมายเหตุ">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึกใบกำกับภาษี</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบกำกับภาษี / Invoice(IV)', 'ยังไม่ได้เพิ่มเลข SOX');
            addModal('formValidate2', 'ใบกำกับภาษี / Invoice(IV)', 'ยังไม่ได้เพิ่มชื่อลูกค้า');
            addModal('formValidate3', 'ใบกำกับภาษี / Invoice(IV)', 'ยังไม่ได้เพิ่มที่อยู่ลูกค้า');
            addModal('formValidate4', 'ใบกำกับภาษี / Invoice(IV)', 'ยังไม่ได้เพิ่มเลขประจำตัวผู้เสียภาษีลูกค้า');
            addModal('formValidate5', 'ใบกำกับภาษี / Invoice(IV)', 'ยังไม่ได้เลือกธนาคาร');
            addModal('formValidate6', 'ใบกำกับภาษี / Invoice(IV)', 'ยังไม่ได้ลงวัน/เวลาการโอน');
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

        $scope.ivItems = [];
        // $scope.selectedBank = '';
        // $scope.TransferTime = '';
        $scope.soxs = <?php echo $this->soxs; ?>;
        var first = true;
        $scope.addIvItem = function(sox) {
            
            $scope.ivItems = [];
            $scope.sox = sox;
            
            angular.forEach($scope.soxs, function (value, key) {
                if(value.sox_no == sox.sox_no) {
                    $scope.ivItems.push(value);
                }
            });
            $scope.calculateIvItem();
            
        }
        
        $scope.calculateIvItem = function() {
            
            $scope.soCal = [];
            var tempSoNo = '';
            
            angular.forEach($scope.ivItems, function(value, key) {
                
                var so_total_sales_no_vat2 = parseFloat(value.so_total_sales_no_vat) - ((value.so_total_sales_vat == 0) ? 0 : (parseFloat(value.discountso) / 1.07));
                var so_total_sales_vat2 = parseFloat(value.so_total_sales_vat) - ((value.so_total_sales_vat == 0) ? 0 : (parseFloat(value.discountso) / 107 * 7));
                var so_total_sales_price2 = parseFloat(value.so_total_sales_price) - parseFloat(value.discountso);
                
                Object.assign(value, {so_total_sales_no_vat2: so_total_sales_no_vat2});
                Object.assign(value, {so_total_sales_vat2: so_total_sales_vat2});
                Object.assign(value, {so_total_sales_price2: so_total_sales_price2});
                
                console.log(value);
                
                if(key == $scope.ivItems.length - 1 && value.transportation_price != 0) {
                    var transportation = {
                        product_type : 'Transport',
                        quantity : 1,
                        sales_no_vat : parseFloat(value.transportation_no_vat),
                        sales_vat : parseFloat(value.transportation_vat),
                        sales_price : parseFloat(value.transportation_price),
                        total_sales : parseFloat(value.transportation_price),
                        so_total_sales_no_vat : parseFloat(value.so_total_sales_no_vat),
                        so_total_sales_vat : parseFloat(value.so_total_sales_vat),
                        so_total_sales_price : parseFloat(value.so_total_sales_price),
                        transportation_no_vat : parseFloat(value.transportation_no_vat),
                        transportation_vat : parseFloat(value.transportation_vat),
                        transportation_price : parseFloat(value.transportation_price),
                        so_no : value.so_no,
                        sox_no : value.sox_no,
                        product_no : 'X',
                        product_name : 'ค่าขนส่ง'
                    }
                    tempSoNo = value.so_no;
                    $scope.ivItems.push(transportation);
                }
                
                Object.assign(value, {priceInThai: NumToThai(parseFloat(value.so_total_sales_price2))});
                
            });
            
            var first = true;
            var so_total_sales_no_vat = 0;
            var so_total_sales_vat = 0;
            var so_total_sales_price = 0;
            var priceInThai = '';
            
            angular.forEach($scope.irItems, function(value, key) {
                
                if(value.so_no == tempSoNo) {
                    
                    if(first) {
                        
                        so_total_sales_price2 = parseFloat(value.so_total_sales_price2) + parseFloat(value.transportation_price);
                        so_total_sales_no_vat2 = parseFloat(so_total_sales_price2) / 1.07;
                        so_total_sales_vat2 = parseFloat(so_total_sales_price2) / 107 * 7;
                        priceInThai = NumToThai(parseFloat(so_total_sales_price2));
                        
                        value.so_total_sales_no_vat2 = so_total_sales_no_vat2;
                        value.so_total_sales_vat2 = so_total_sales_vat2;
                        value.so_total_sales_price2 = so_total_sales_price2;
                        value.priceInThai = priceInThai;
                        
                        first = false;
                        
                    } else {
                        
                        value.so_total_sales_no_vat2 = so_total_sales_no_vat2;
                        value.so_total_sales_vat2 = so_total_sales_vat2;
                        value.so_total_sales_price2 = so_total_sales_price2;
                        value.priceInThai = priceInThai;
                        
                    }
                    
                }
                
            });
            
        }
        
        var click = false;
        
        $scope.formValidate = function() {
            if(!click){
                if($scope.ivItems.length === 0) {
                    $('#formValidate1').modal('toggle');
                } 
                // else if ($('#textBoxCusName').val() === '') {
                //     $('#formValidate2').modal('toggle');
                // } else if ($('#textboxCusAddress').val() === '') {
                //     $('#formValidate3').modal('toggle');
                // } else if ($('#textboxCusId').val() === '') {
                //     $('#formValidate4').modal('toggle');
                // } else if ($scope.selectedBank === '') {
                //     $('#formValidate5').modal('toggle');
                // } else if ($scope.TransferTime === '') {
                //     $('#formValidate6').modal('toggle');
                // } 
                else {
                    var confirmModal = addConfirmModal('confirmModal', 'ใบกำกับภาษี / Invoice(IV)', 'ยืนยันการออกใบกำกับภาษี', 'postIv()');
                    $('body').append($compile(confirmModal)($scope));
                    $('#confirmModal').modal('toggle');
                }
                click = true;
            }
        }
        
        $scope.postIv = function() {
            
            $('#confirmModal').modal('hide');
                                  
            // var transferDateStr = $scope.TransferTime.getFullYear() + '-' + 
            //                         (($scope.TransferTime.getMonth()+1) < 10 ? '0' : '') + ($scope.TransferTime.getMonth()+1) + '-' + 
            //                         ($scope.TransferTime.getDate() < 10 ? '0' : '') + $scope.TransferTime.getDate();
            // var transferTimeStr = ($scope.TransferTime.getHours() < 10 ? '0' : '') + $scope.TransferTime.getHours() + ':' + 
            //                         ($scope.TransferTime.getMinutes() < 10 ? '0' : '') + $scope.TransferTime.getMinutes() + ':00';  
                                    
            $.post("/acc/create_invoice/post_iv", {
                post : true,
                cusName : $('#textBoxCusName').val(),
                cusAddress : $('#textboxCusAddress').val(),
                cusId : $('#textboxCusId').val(),
                // cusName : '-',
                // cusAddress : '-',
                // cusId : '-',
                bank : $scope.selectedBank,
                noted : $scope.Noted,
                // transferDate : transferDateStr,
                // transferTime : transferTimeStr,
                sox_number : $scope.sox.sox_no,
                ivItems : JSON.stringify(angular.toJson($scope.ivItems))
            }, function(data) {
                console.log(data);
                addModal('successModal', 'ใบกำกับภาษี/ Invoice(IV)', 'บันทึก ' + data  +' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.open('/file/iv/' + data.substring(0, 9));
                    location.reload();
                    // window.location.assign('/');
                });           
            }); 
            
        }
        
    });

</script>