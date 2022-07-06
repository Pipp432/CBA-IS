<!DOCTYPE html>
<html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>


<body>

    <div class="container mt-3" ng-controller="moduleAppController">
    <!-- <button class="btn btn-light" ng-click="test()">test</button> -->
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
                            <th>SP</th>
                            <th>ลูกค้า</th>
							<th>Payment Date</th>
							<th>Payment Time</th>
                            <th>tax</th>
							<th>Payment Amount</th>
                            <th>ราคารวม</th>
                            <th>payment method</th>
							<th>ขอใบกำกับภาษี</th>
							<th>Auto Match Status</th>
                            <!-- <th>Error Status</th> -->
                           
                        </tr>
                        <tr ng-show="soxs.length == 0">
                            <th colspan="10">ไม่มีเลข SOX ที่อัปโหลดสลิปการชำระเงิน</th>
                        </tr>
                        <tr ng-repeat="sox in soxs | unique:'sox_no' | filter:{sox_no:filterSox} | orderBy:['-slip_uploaded', 'slip_datetime']| orderBy:reverse:true" ng-click="addCrItem(sox)" ng-show="soxs.length > 0">
                            <td>{{sox.sox_no}} {{sox.product_type}}</td>
                            <td>{{sox.employee_id}} {{sox.employee_nickname_thai}}</td>
                            <td>{{sox.customer_name}} {{sox.customer_surname}}</td>
							<td style="text-align: center">{{sox.payment_date==null ? getDate(sox):sox.payment_date}}</td>
							<td style="text-align: center">{{sox.payment_time==null ? getTime(sox):sox.payment_time}}</td>
							<td style="text-align: center">{{sox.so_total_sales_vat | number:2}}</td>
                            <td style="text-align: center">{{sox.payment_amount | number:2}}</td>
                            
                            <td style="text-align: right;">
                                {{sox.sox_sales_price | number:2}}<br>
                                <a href="https://uatline.cbachula.com/public/sox_slips/{{sox.sox_no}}.jpeg" target="_blank" ng-show="{{sox.slip_uploaded==1}}">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> ไฟล์สลิป
                                </a>
                                <br>{{sox.slip_datetime}}
                            </td>
                            <td style="text-align: center">{{sox.payment_type===null ? "-":sox.payment_type }}</td>
							<td style="text-align: center"><i ng-show="sox.fin_form==1" class="bi bi-check2-circle"></i></td>
							<td style="text-align: center"><i ng-show="sox.id > 0" class="bi bi-check2-circle"></i></td>
                            <!-- <td style="text-align: center; color: green" ng-show = " sox.product_type ==='Install'">ออกได้</td>
                            <td style="text-align: center; color: green" ng-show = "sox.balance > 0 && sox.product_type ==='Stock'">ออกได้</td>
                            <td style="text-align: center; color: red" ng-show = "sox.balance <= 0 && sox.balance!==null">ออกไม่ได้</td> -->
                           
                            
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
                    <table class="table table-hover my-1" ng-show="crItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข SOX</th>
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
                        <label for="textboxCusAddress">ที่อยู่</label>
                        <input type="text" class="form-control" id="textboxCusAddress" ng-value="sox.address">
                    </div>
                    <div class="col-md-12 mx-0 mt-2" ng-show="crItems.length != 0">
                        <label for="textboxCusId">เลขประจำตัวผู้เสียภาษี</label>
                        <input type="text" class="form-control" id="textboxCusId" ng-value="sox.national_id">
                    </div>
                    <table class="table table-hover mb-1 mt-2" ng-show="crItems.length != 0">
                        <tr>
                            <th>เลข SO</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-repeat="soPrinting in crItems| unique:'product_no'">
                            <td style="text-align: center;">{{soPrinting.so_no}}</td>
                            <td style="text-align: center;">{{soPrinting.product_name}}</td>
                            <td style="text-align: center;">{{soPrinting.sales_price | number:2}}</td>
                            <td style="text-align: center;">{{soPrinting.quantity}}</td>
                            <td style="text-align: center;">{{soPrinting.sales_price * soPrinting.quantity | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวมก่อนภาษี</th>
                            <th id="totalPrice" style="text-align: right;">{{priceBeforeVat|number:2}}</th>
                            <!--ผิด table-->
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">ส่วนลด</th>
                            <th id="totalPrice" style="text-align: right;">{{crItems[0].so_total_discount | number:2}}</th>
                        </tr> 
                        <tr>
                            <th style="text-align: right;" colspan="4">ค่าธรรมเนียมบัตรเครดิต</th>
                            <th id="cardFee" style="text-align: right;">{{creditCardfee|number:2}}</th>
                        </tr>   
                        <tr>
                            <th style="text-align: right;" colspan="4">ภาษี 7%</th>
                            <th id="totalPrice" style="text-align: right;">{{vat|number:2}}</th>
                        </tr>  
                        
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวม</th>
                            <th id="totalPrice" style="text-align: right;">{{finalPrice}}</th>
                            <!--ผิด table-->
                        </tr>
                        <!--<tr>
                            <th style="text-align: right;" colspan="4">คะแนน</th>
                            <th id="totalPrice" style="text-align: right;">{{crItems[0].total_point | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">Commission</th>
                            <th id="totalPrice" style="text-align: right;">{{crItems[0].total_commission | number:2}}</th>
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
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึกใบกำกับภาษีและใบเสร็จรับเงิน</button>
                    
                </div>
            </div>
        </div>
		<div  style="position: fixed; right: 0; bottom: 0; margin-bottom: 1%; margin-right: 7%">
        <button class="btn btn-light" ng-click="scrollToTop()">เลื่อนขึ้น</button> &nbsp; &nbsp; 
		<button class="btn btn-light" ng-click="scrollToBottom()">เลื่อนลง</button>
		</div>
		
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยังไม่ได้เพิ่มเลข SOX');
            addModal('formValidate2', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยังไม่ได้เพิ่มชื่อลูกค้า');
            addModal('formValidate3', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยังไม่ได้เพิ่มที่อยู่ลูกค้า');
            addModal('formValidate4', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยังไม่ได้เพิ่มเลขประจำตัวผู้เสียภาษีลูกค้า');
            addModal('formValidate5', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยังไม่ได้เลือกธนาคาร');
            addModal('formValidate6', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยังไม่ได้ลงวัน/เวลาการโอน');
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

// We select the element we want to target


    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.sox='';
        $scope.error = false;
        
        $scope.crItems = [];
        // $scope.selectedBank = '';
        // $scope.TransferTime = '';
        $scope.soxs = <?php echo $this->soxs; ?>;
    
        $scope.creditCardfee=0;
        $scope.finalPrice=0;
        $scope.priceBeforeVat=0;
        $scope.vat=0;
        $scope.priceWithVat = 0;
       
        
        var first = true;

     
        


        // $scope.test = function() {
        //     console.log( $('#textboxCusId').val());
        //     console.log( $('#textboxCusId').val().length);
        //     console.log($('#textBoxCusName').val());
        //     console.log($('#textBoxCusName').val().length);
        //     console.log( $('#textboxCusAddress').val());
        //     console.log( $('#textboxCusAddress').val().length);
        //     console.log($scope.selectedBank);
        //     console.log($scope.Noted);
        //     console.log($scope.sox.sox_no);
        //     console.log($scope.crItems);
        // }


		$scope.scrollToTop = function() {
            
            window.scrollTo({ top: 0});
            
        }

		$scope.scrollToBottom = function() {
            
            window.scrollTo({ left: 0, top: document.body.scrollHeight});
            
        }
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      
        $scope.processNumber = function(sox){
            console.log(sox);
            

            console.log(`===================== ${sox.sox_no} ======================`);
            
           
            
                
                $scope.actualPrice = Number(sox.sox_sales_price)
                if((sox.payment_type==='MB' || sox.payment_type===null)){
                    $scope.creditCardfee = 0;
                    $scope.actualPrice = $scope.finalPrice;
                    $scope.finalPrice = (Number(sox.sox_sales_price)).toFixed(2);
              
                    if(sox.vat_type==='3') { 
                        $scope.priceBeforeVat = $scope.finalPrice;
                        $scope.vat = 0;
                    }else{
                        $scope.vat =   $scope.finalPrice * 7/107;
                        $scope.priceBeforeVat = $scope.finalPrice *100/107
                    }
                    
                }else{
                    
                    $scope.priceBeforeVat = $scope.priceWithVat*100/107;
                    $scope.actualPrice = Number(sox.sox_sales_price);
                    $scope.finalPrice = Math.ceil(Number(sox.sox_sales_price)).toFixed(0);
                   
                    if(sox.vat_type==='3') { 
                        $scope.priceBeforeVat = Number(sox.so_total_sales_no_vat) + Number(sox.so_total_sales_vat)
                        $scope.vat = 0;
                    }else{
                        $scope.vat =  $scope.finalPrice * 7/107;
                    }
                    $scope.creditCardfee =   $scope.finalPrice - $scope.priceBeforeVat - $scope.vat;
                }
            

            console.log(`Product Type: ${sox.product_type}`)
            console.log(`Price before VAT: ${($scope.priceBeforeVat)}`)
            console.log(`Price with VAT ${$scope.priceWithVat}`)
            console.log(`Payment Type: ${sox.payment_type}`);
            console.log(`VAT type: ${sox.vat_type}`);
            console.log(`VAT amount: ${($scope.vat)}`);
            console.log(`Credit Card fee: ${($scope.creditCardfee)}`);
            console.log(`Actual Price: ${$scope.actualPrice}`)
            console.log(`Final Price: ${$scope.finalPrice}`)
            
            

        }

        $scope.addCrItem = function(sox) {
            
            $scope.crItems = [];
            $scope.sox = sox;
            $scope.vat_type = $scope.sox.vat_type;
            $scope.roundedPrice =  Math.round(Number(sox.sox_sales_price));
           
            $scope.customer_title = '';
            
            
            angular.forEach($scope.soxs, function (value, key) {
                if(value.sox_no == sox.sox_no) {
                    $scope.crItems.push(value);
                   
                   if(value.balance<=0 && value.balance!=null){
                  
                        $scope.error = true;
                        addModal('successModal', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', "<h1 style= color:red;>ออกไม่ได้โว้ย</h1>");
                        
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                        location.reload();
                //     // window.location.assign('/');
                });

                   }
                   
                    
                }
            });
           

   
         console.log($scope.crItems)
        
         $scope.calculateCrItem(sox)
        
        }

        $scope.calculateVat = function(sox){
            return ((parseFloat(sox.payment_amount) * 7)/107).toFixed(2);
        }
        $scope.getDate = function(sox){
            return (sox.sox_datetime).split(' ')[0];
        }
        $scope.getTime = function(sox){
            return (sox.sox_datetime).split(' ')[1];
        }
        
        $scope.calculateCrItem = function(sox) {
            
            $scope.soCal = [];
            var tempSoNo = '';
            
            angular.forEach($scope.crItems, function(value, key) {
                
                var so_total_sales_no_vat2 = parseFloat(value.so_total_sales_no_vat) - ((value.so_total_sales_vat == 0) ? 0 : (parseFloat(value.discountso) / 1.07));
                var so_total_sales_vat2 = parseFloat(value.so_total_sales_vat) - ((value.so_total_sales_vat == 0) ? 0 : (parseFloat(value.discountso) / 107 * 7));
                var so_total_sales_price2 = parseFloat(value.so_total_sales_price) - parseFloat(value.discountso);
                
                Object.assign(value, {so_total_sales_no_vat2: so_total_sales_no_vat2});
                Object.assign(value, {so_total_sales_vat2: so_total_sales_vat2});
                Object.assign(value, {so_total_sales_price2: so_total_sales_price2});
                
               
                $scope.customer_title=value['customerTitle'];
               
                
                if(key == $scope.crItems.length - 1 && value.transportation_price != 0) {
                    var transportation = {
                        product_type : "Transport",
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
                        product_no : "X",
                        product_name : "ค่าขนส่ง"
                    }
                    tempSoNo = value.so_no;
                    $scope.crItems.push(transportation);
                }
                
                Object.assign(value, {priceInThai: NumToThai(parseFloat(value.so_total_sales_price2))});
                console.log(NumToThai(parseFloat(value.so_total_sales_price2)))
                
            });
            
            var first = true;
            var so_total_sales_no_vat = 0;
            var so_total_sales_vat = 0;
            var so_total_sales_price = 0;
            var priceInThai = '';
            
            angular.forEach($scope.crItems, function(value, key) {
                
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

            // $scope.card_fee = '';
            // $scope.new_total_price = '';
            // if($scope.crItems.length != 0) {
            //     $scope.card_fee = 0;
            //     if($scope.crItems[0].payment_type === 'CC'){
            //         $scope.card_fee = (parseFloat(sox.so_total_sales_no_vat2 )*2.45)/100 ;
            //     } else if($scope.crItems[0].payment_type === 'FB'){
            //         $scope.card_fee = (parseFloat(sox.so_total_sales_no_vat2 )*2.75)/100 ;
            //     }
            //     $scope.new_total_price = parseFloat(sox.so_total_sales_no_vat2 ) + $scope.card_fee;
            // }
            // console.log((parseFloat(sox.total_sales)*2.45)/100)
            $scope.crItems.forEach((e)=>{
            
            $scope.priceWithVat+=Number(e.sales_price)
            console.log(e.sales_price)
         })
         
         $scope.processNumber(sox)

            


            
        }

        
        var click = false;
        
        $scope.formValidate = function() {
            if(!click){
                if($scope.crItems.length === 0) {
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
                    var confirmModal = addConfirmModal('confirmModal', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'ยืนยันการออกใบกำกับภาษีและใบเสร็จรับเงิน', 'postIvCr()');
                    $('body').append($compile(confirmModal)($scope));
                    $('#confirmModal').modal('toggle');
                }
                click = true;
            }
        }
        
        $scope.postIvCr = function() {

            $('#confirmModal').modal('hide');
            
                                  
            // var transferDateStr = $scope.TransferTime.getFullYear() + '-' + 
            //                         (($scope.TransferTime.getMonth()+1) < 10 ? '0' : '') + ($scope.TransferTime.getMonth()+1) + '-' + 
            //                         ($scope.TransferTime.getDate() < 10 ? '0' : '') + $scope.TransferTime.getDate();
            // var transferTimeStr = ($scope.TransferTime.getHours() < 10 ? '0' : '') + $scope.TransferTime.getHours() + ':' + 
            //                         ($scope.TransferTime.getMinutes() < 10 ? '0' : '') + $scope.TransferTime.getMinutes() + ':00';  
                                    
            $.post("/fin/cash_receipt/post_ivcr", {
                post : true,
                cusName : $('#textBoxCusName').val(),
                customer_title: $scope.sox.customerTitle, 
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
                crItems : JSON.stringify(angular.toJson($scope.crItems)),
                payment_type:$scope.crItems[0].payment_type,
                total_sales_no_vat:$scope.crItems[0].so_total_sales_no_vat,
                payment_type: $scope.crItems[0].payment_type
            }, function(data) {
               
                addModal('successModal', 'ใบกำกับภาษีและใบเสร็จรับเงิน / Invoice(IV) and Cash Receipt(CR)', 'บันทึก ' + data);
              
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.open('/file/iv_cr/' + data.substring(0, 9));
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
