<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC) </h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CI/RR -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-4">
                        <label for="rrCiNoTextbox">เลขที่ RR/CI</label>
                        <input type="text" class="form-control" id="rrCiNoTextbox" ng-model="filterRRCI">
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownProductType">ประเภทสินค้า</label>
                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">
                            <option value="">เลือกประเภทสินค้า</option>
                            <option value="Stock">Stock</option>
                            <option value="Order">Order</option>
                            <option value="Install">Install</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownSupplier">Supplier</label>
                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">
                            <option value="">เลือก Supplier</option>
                            <option ng-repeat="supplier in rrcis | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier.supplier_no}}">
                                {{supplier.supplier_no}} : {{supplier.supplier_name}}
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="rrcis.length == 0">
                        <tr>
                            <th>ไม่มีใบรับสินค้าหรือการยืนยันการติดตั้งที่ยังไม่วางบิล</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="rrcis.length != 0">
                        <tr>
                            <th>เลข RR/CI</th>
                            <th>เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="rrci in rrcis | unique:'ci_no' | filter:{supplier_no:selectedSupplier, ci_no:filterRRCI, product_type:selectedProductType}" ng-click="addIvrcItem(rrci)">
                            <td>{{rrci.ci_no}}</td>
                            <td>{{rrci.po_no}}</td>
                            <td>{{rrci.ci_date}}</td>
                            <td><ul class="my-0">
                                <!--<li ng-repeat="rrci_item in rrcis" ng-show="rrci_item.ci_no===rrci.ci_no">{{rrci_item.product_name}} (x{{rrci_item.quantity}})</li>-->
                            </ul></td>
                            <td style="text-align: right;">{{rrci.confirm_total | number:2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING IVRC ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดยืนยันการวางบิล</h4>
                    <table class="table table-hover my-1" ng-show="ivrcItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข RR/CI</th>
                        </tr>
                    </table>
                    
                    <table class="table table-hover my-1" ng-show="ivrcItems.length != 0">

                        <tr>
                            <th colspan="2">เลข RR/CI</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-repeat="ivrcItem in ivrcItems">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropIvrcItem(ivrcItem)"></i></td>
                            <td>{{ivrcItem.ci_no}}</td>
                            <td>{{ivrcItem.product_name}}</td>
                            <td style="text-align: right;">{{ivrcItem.purchase_price | number:2}}</td>
                            <td style="text-align: right;">{{ivrcItem.quantity}} {{ivrcItem.unit}}</td>
                            <td style="text-align: right;">{{ivrcItem.total_purchase_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">รวม</th>
                            <th style="text-align: right;">{{final_purchase_no_vat | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">ภาษีมูลค่าเพิ่ม</th>
                            <th style="text-align: right;">{{final_purchase_vat | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">รวมสุทธิ</th>
                            <th style="text-align: right;">{{total_purchase_price | number:2}}</th>
                        </tr>
                    </table>
                </div>
                <hr>
                <div>
                    <!-- <div class="col-md-4">
                        <label for="textboxIv">เลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี</label>
                        <input type="text" class="form-control" id="textboxIv" ng-model="iv" placeholder="เลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี">
                    </div> -->
                    <!-- //! FIRST 2 -->
                    <i style="color:red"> กรณี vat diff อย่างเดียว</i>
                    <div class = "row mx-0 mt-2">
                        <div class="col-md-4">
                            <label for="DR_text">DR เศษเงิน</label>
                            <input type="text" class="form-control" id="DR_text" ng-model="DR" placeholder="DR เศษเงิน" ng-change = "autoDRCR('DR')">
                        </div> 
                        <div class="col-md-4">
                            <label for="DR_cash_text">จำนวนเงิน</label>
                            <input type="text" class="form-control" id="DR_cash_text" ng-model="DRCR_cash">
                        </div> 
                    </div> 
                    <div class = "row mx-0 mt-2">
                        <div class="col-md-4">
                            <label for="CR_text">CR เศษเงิน</label>
                            <input type="text" class="form-control" id="CR_text" ng-model="CR" placeholder="CR เศษเงิน" ng-change = "autoDRCR('CR')">
                        </div> 
                        <div class="col-md-4">
                            <label for="CR_cash_text">จำนวนเงิน</label>
                            <input type="text" class="form-control" id="CR_cash_text" ng-model="DRCR_cash">
                        </div> 
                    </div>

                    <!-- //! ELSE -->
                    <br>
                    <i style="color:red">กรณีทุกอย่าง diff</i>
                    <div class = "row mx-0 mt-2">
                        <div class="col-md-4">
                            <label for="DR_text">DR เศษเงิน</label>
                            <input type="text" class="form-control" id="all_DR_1_text" ng-model="all_DR_1" placeholder="DR เศษเงิน" ng-change = "autoDRCR('allDR')">
                        </div> 
                        <div class="col-md-4">
                            <label for="DR_cash_text">จำนวนเงิน</label>
                            <input type="text" class="form-control" id="all_DR_1_cash_text" ng-model="all_DR_1_cash">
                        </div> 
                    </div> 
                    <div class = "row mx-0 mt-2">
                        <div class="col-md-4">
                            <label for="CR_text">DR เศษเงิน</label>
                            <input type="text" class="form-control" id="all_DR_2_text" ng-model="all_DR_2" placeholder="DR เศษเงิน" ng-change = "autoDRCR('allDR')">
                        </div> 
                        <div class="col-md-4">
                            <label for="CR_cash_text">จำนวนเงิน</label>
                            <input type="text" class="form-control" id="all_DR_2_cash_text" ng-model="all_DR_2_cash">
                        </div> 
                    </div>  
                    <div class = "row mx-0 mt-2">
                        <div class="col-md-4">
                            <label for="CR_text">CR เศษเงิน</label>
                            <input type="text" class="form-control" id="all_CR_text" ng-model="all_CR" placeholder="CR เศษเงิน" ng-change = "autoDRCR('allDR')">
                        </div> 
                        <div class="col-md-4">
                            <label for="CR_cash_text">จำนวนเงิน</label>
                            <input type="text" class="form-control" id="all_CR_cash_text" ng-model="all_CR_cash">
                        </div> 
                    </div>
                    
                    

                    <!-- <div class="col-md-4">
                        <label for="datetime-input">วันที่</label>
                        <input class="form-control" type="date" id="datetime-input" ng-model="ivrcDate">
                    </div> -->
                    <form id="form">
                        <div class = "row mx-0 mt-2">
                            <div class="col-md-4">
                                <label for="bill_no">เลขที่ใบวางบิล</label>
                                <input type="text" class="form-control" id="bill_no" ng-model="bill_no" placeholder="ใบวางบิล">
                            </div> 
                            <div class="col-md-4">
                                <label for="billFormUpload">อัปโหลดใบวางบิล</label><br>
                                <input class="form-control-file" type="file" id="billFormUpload" name='billFormUpload'>
                            </div>  
                        </div>  
                        <br>
                        <div class = "row mx-0 mt-2">
                            <div class="col-md-4">
                                <label for="tax_form_no">เลขที่ใบกำกับภาษี</label>
                                <input type="text" class="form-control" id="tax_form_no" ng-model="tax_form_no" placeholder="ใบกำกับภาษี">
                            </div> 
                            <div class="col-md-4">
                            <label for="taxFormUplaod">อัปโหลดใบกำกับภาษี</label><br>
                            <input class="form-control-file" type="file" id="taxFormUpload" name='taxFormUpload'>
                            </div>  
                        </div>  


                        <br>
                        <div class = "row mx-0 mt-2">
                            <div class="col-md-4">
                                <label for="textboxIv">เลขที่ใบแจ้งหนี้ (invoice)</label>
                                <input type="text" class="form-control" id="textboxIv" ng-model="iv" placeholder="ใบแจ้งหนี้">
                            </div> 
                            <div class="col-md-4">
                                <label for="taxInvoiceUpload">อัปโหลดใบแจ้งหนี้</label><br>
                                <input class="form-control-file" type="file" id="taxInvoiceUpload" name='taxInvoiceUpload'>
                            </div>
                        </div> 

                        <br>
                        <div class = "row mx-0 mt-2">
                            <div class="col-md-4">
                                <label for="tax_reduce_no">เลขที่ใบลดหนี้</label>
                                <input type="text" class="form-control" id="tax_reduce_no" ng-model="tax_reduce_no" placeholder="ใบแจ้งหนี้">
                            </div> 
                            <div class="col-md-4">
                                <label for="taxReduceUpload">อัปโหลดใบลดหนี้</label><br>
                                <input class="form-control-file" type="file" id="taxReduceUpload" name='taxReduceUpload'>
                            </div>
                        </div> 


                    </form>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postivrcItems()">บันทึกการยืนยันการวางบิล</button>
                </div>
                <!-- <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="debug()">DEBUG (IS กดเท่านั้น acc pls ignore)</button>
                </div> -->
            </div>
            <!-- <button type="button" class="btn btn-default btn-block my-1" ng-click="test()">test</button> -->
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'ยังไม่ได้เพิ่ม RR/CI');
            addModal('formValidate2', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'ยังไม่ได้กรอกเลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี');
            addModal('formValidate3', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'RR/CI ที่เพิ่มเป็นคนละ Supplier กัน');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
    .input_same_line{
        float:left;
    }
</style>

<script>




    app.controller('moduleAppController', function($scope, $http, $compile) {
        // ivrcItems is basically the รายละเอียดยืนยันการวางบิล
        $scope.selectedProductType = '';
        $scope.selectedSupplier = '';
        $scope.filterRRCI = '';
        $scope.iv = '';
        $scope.ivrcItems = [];
        $scope,vat = 0;
        $scope.rrcis = <?php echo $this->rrcis; ?>;
        // console.log($scope.rrcis)
        $scope.supplier_no = '';

        // $scope.test = function() {
        //     console.log($('#billFormUpload')[0].files[0]);
        // }
        
        $scope.addIvrcItem = function(rrci) {
            if ($scope.ivrcItems.length != 0 && $scope.ivrcItems[0].supplier_no != rrci.supplier_no) {
                $('#formValidate3').modal('toggle');
            } else {
                var newRRCI = true;
                angular.forEach($scope.ivrcItems, function (value, key) {
                    if(value.ci_no == rrci.ci_no) {
                        newRRCI = false;
                    }
                });
                if(newRRCI) {
                    angular.forEach($scope.rrcis, function (value, key) {
                        if(value.ci_no == rrci.ci_no) {
                            $scope.ivrcItems.push(value);
                        }
                    });
                }
                $scope.calculateTotalPrice();
            }
           
          
            $scope.all_CR = "21-1" + rrci.supplier_no
            if(0<rrci.product_line && rrci.product_line<=3){
                $scope.company = '1';
            }
            if(3<rrci.product_line && rrci.product_line<=5){
                $scope.company = '2';
            } 
            if(5<rrci.product_line || rrci.product_line =='0'){
                $scope.company = '3';
            }
            $scope.all_DR_2 = "61-1" + $scope.company + '00'
            $scope.all_DR_1 = "51-1" +  $scope.company + '00'
            $scope.all_DR_1_cash = "0.00";
            $scope.all_DR_2_cash = "0.00";
            $scope.all_CR_cash = "0.00";
            $scope.DRCR_cash = '0.00' 
            console.log($scope.ivrcItems)
          
            
            
        }
        
        $scope.dropIvrcItem = function(rrci) {
            $scope.deleted_data = Number(rrci.confirm_total)
            console.log(rrci);
            $scope.final_purchase_no_vat-=Number(( $scope.deleted_data*100/107).toFixed(2))
            if(rrci.vat_type =='1') $scope.final_purchase_vat -= Number(( $scope.deleted_data*7/107).toFixed(2))
            $scope.total_purchase_price-= $scope.deleted_data
            var tempRemoved = [];
            angular.forEach($scope.ivrcItems, function (value, key) {
                if(value.ci_no != rrci.ci_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.ivrcItems = tempRemoved;


            
            
            $scope.ci_old.filter((entry)=>entry.ci_no!=rrci.ci_no)
          
            console.log($scope.ci_old)
        }
            $scope.final_purchase_no_vat = 0;
           $scope.final_purchase_vat = 0;
           $scope.final_purchase_price = 0;
           $scope.ci_old = [];
        
        $scope.calculateTotalPrice = function() {
            console.log($scope.selectedSupplier)
           
           
           $scope.total_purchase_no_vat = 0;
           $scope.total_purchase_vat = 0;
           $scope.total_purchase_price = 0;
           $scope.ci = ''
          
           
           
           
           
           angular.forEach($scope.ivrcItems, function (value, key) {
               $scope.total_purchase_price += (value.purchase_price * value.quantity);
               // console.log(!$scope.ci_old.includes($scope.ivrcItems[$scope.index].ci_no));
               console.log(value)

               if(!$scope.ci_old.includes(value.ci_no )){
                   console.log(value.ci_no);
                   $scope.ci = value.ci_no
                   
                   
                   
                   
                   $scope.current =Number(value.purchase_price * value.quantity);
                   
                   
               
                       if(value.product_no.charAt(3) == '1'){
                       $scope.total_purchase_no_vat += Number(($scope.current * 100/107));
                       $scope.total_purchase_vat += Number(($scope.current * 7/107));
                       
                       }else{
                           $scope.total_purchase_vat += 0;
                           $scope.total_purchase_no_vat+= $scope.current;
                           
                       }

               }else{  
               $scope.index++;

               }
             
              
             
               


           });
           console.log($scope.ci_old)
           $scope.ci_old.push($scope.ci);
           console.log($scope.ci_old)
           $scope.final_purchase_no_vat += Number(($scope.total_purchase_no_vat).toFixed(2));
           $scope.final_purchase_vat += Number(($scope.total_purchase_vat).toFixed(2));
           $scope.total_purchase_no_vat = 0;
           $scope.total_purchase_vat = 0;
           // console.log(`PRICE NO VAT: ${ $scope.final_purchase_no_vat}`);
           // console.log(`VAT: ${$scope.final_purchase_vat }`);
           
           
          
         
           
           // console.log($scope.total_purchase_price);
           
           // console.log(`With VAT: ${$scope.total_purchase_vat}`);
           // console.log($scope.total_purchase_price);
           // console.log(`before vat Sum: ${$scope.sum}`);
          
           
       }

        $scope.autoDRCR = (type)=>{
            
            if($("all_DR_1_text").val!=null){
                $scope.DRCR_cash = "0.00"
               
                
            }else{
                $scope.DRCR_cash = ''
            }
           
          

            
          
          if($scope.all_CR.length == 7&&($scope.all_DR_1.charAt(0) == 2) && $scope.all_DR_1.substring(1, 4) == "1-1"){
              console.log('hey');
              
              $scope.all_DR_1 =  "21-2"+$scope.all_CR.substring(4,$scope.all_CR.length);
          } else $scope.allCR = "";

      
            firstChar = {5:6,6:5};
            if(type == 'DR'){
                if($scope.DR.length == 7&&($scope.DR.charAt(0) == 5||$scope.DR.charAt(0) == 6) && $scope.DR.substring(1, 4) == "1-1" && $scope.DR.substring(5, 7) == "00"){
                    $scope.CR = firstChar[$scope.DR.charAt(0)] + "1-1"+$scope.DR.charAt(4)+"00";
                } else $scope.CR = "";
            }
            if(type == 'CR'){
                if($scope.CR.length == 7&&($scope.CR.charAt(0) == 5||$scope.CR.charAt(0) == 6) && $scope.CR.substring(1, 4) == "1-1" && $scope.CR.substring(5, 7) == "00"){
                    $scope.DR = firstChar[$scope.CR.charAt(0)] + "1-1"+$scope.CR.charAt(4)+"00";
                } else $scope.DR = "";
            }
           

        }
        
        $scope.postivrcItems = function() {
            
            if($scope.ivrcItems.length === 0) {
                // blank ยังไม่ได้กรอกเลขที่ใบสำคัญ/ใบวางบิล/ใบกำกับภาษี
                $('#formValidate1').modal('toggle');
            } else if ($scope.iv === '') {
                // blank day NOTE: breaks the page
                $('#formValidate2').modal('toggle');
            } else {
                
                // Creating PVB here
                // Create date for ivrc
                // var ivrcDateStr = $scope.ivrcDate.getFullYear() + '-' + 
                //                     (($scope.ivrcDate.getMonth()+1) < 10 ? '0' : '') + ($scope.ivrcDate.getMonth()+1) + '-' + 
                //                     ($scope.ivrcDate.getDate() < 10 ? '0' : '') + $scope.ivrcDate.getDate();
                // Creation of data
                // FormData is a web page interface where (key: value)
               
                var formData = new FormData();
                formData.append('bill', $('#billFormUpload')[0].files[0]);
                formData.append('tax', $('#taxFormUpload')[0].files[0]);
                formData.append('taxIV', $('#taxInvoiceUpload')[0].files[0]);
                formData.append('tax_reduce_upload', $('#taxReduceUpload')[0].files[0]);
                formData.append('final_purchase_no_vat',$scope.final_purchase_no_vat)
                formData.append('final_purchase_vat',$scope.final_purchase_vat)
                formData.append('total_purchase_price',$scope.total_purchase_price)
                
                
                if($scope.iv == '-') {
                    var fullPath = document.getElementById('taxReduceUpload').value;
                    if (fullPath) {
                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                        var filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        formData.append('iv', filename);
                    } else formData.append('iv', "none");
                } else formData.append('iv', $scope.iv);
                
                formData.append('bill_no', $scope.bill_no);
                formData.append('tax_form_no', $scope.tax_form_no);
                formData.append('tax_reduce_no', $scope.tax_reduce_no);
                formData.append('ivrcItems', JSON.stringify(angular.toJson($scope.ivrcItems)));
                // formData.append('ivrcDate', ivrcDateStr);
                if($scope.DR.length == 7&&($scope.DR.charAt(0) == 5||$scope.DR.charAt(0) == 6) && $scope.DR.substring(1, 4) == "1-1" && $scope.DR.substring(5, 7) == "00"){
                    formData.append('DR',$scope.DR);
                } else formData.append('DR',false); 
                formData.append('DRCR_cash',parseFloat($scope.DRCR_cash).toFixed(2));
                formData.append('CR',$scope.CR);
                formData.append('diff_dr_sup_cash',parseFloat($scope.all_DR_1_cash).toFixed(2));
                formData.append('diff_dr_1_sup',$scope.all_DR_1)
                formData.append('diff_dr_tax_cash',parseFloat($scope.all_DR_2_cash).toFixed(2));
                formData.append('diff_dr_2_sup',$scope.all_DR_2)
                formData.append('diff_cr_sup_cash',parseFloat($scope.all_CR_cash).toFixed(2));
                formData.append('diff_cr_sup',$scope.all_CR)
             
                
                $.ajax({ 
                    url: '/acc/invoice_receipt_confirm/post_ivrc',  
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                }).done(function (data) {
                    addModal('successModal', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', data);
                    $('#successModal').modal('toggle');
                    $('#successModal').on('hide.bs.modal', function (e) {
                        // location.assign("/");
                    });
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('ajax.fail');
                    console.log(jqXHR)
                    console.log(`textStatus: ${textStatus}`)
                    console.log(`errorThrown ${errorThrown}`)

                    addModal('uploadFailModal', 'upload fail', 'fail');
                    $('#uploadFailModal').modal('toggle');
                    $('#uploadFailModal').on('hide.bs.modal', function (e) {
                            // location.assign("/");
                    });
                });
                
            }
        
            
        }
        $scope.debug = function(){
              // Creating PVB here
                // Create date for ivrc
                // var ivrcDateStr = $scope.ivrcDate.getFullYear() + '-' + 
                //                     (($scope.ivrcDate.getMonth()+1) < 10 ? '0' : '') + ($scope.ivrcDate.getMonth()+1) + '-' + 
                //                     ($scope.ivrcDate.getDate() < 10 ? '0' : '') + $scope.ivrcDate.getDate();
                // Creation of data
                // FormData is a web page interface where (key: value)
               
                var formData = new FormData();
                formData.append('bill', $('#billFormUpload')[0].files[0]);
                formData.append('tax', $('#taxFormUpload')[0].files[0]);
                formData.append('taxIV', $('#taxInvoiceUpload')[0].files[0]);
                formData.append('tax_reduce_upload', $('#taxReduceUpload')[0].files[0]);
                formData.append('final_purchase_no_vat',$scope.final_purchase_no_vat)
                formData.append('final_purchase_vat',$scope.final_purchase_vat)
                formData.append('total_purchase_price',$scope.total_purchase_price)
                
                
                if($scope.iv == '-') {
                    var fullPath = document.getElementById('taxReduceUpload').value;
                    if (fullPath) {
                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                        var filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        formData.append('iv', filename);
                    } else formData.append('iv', "none");
                } else formData.append('iv', $scope.iv);
                
                formData.append('bill_no', $scope.bill_no);
                formData.append('tax_form_no', $scope.tax_form_no);
                formData.append('tax_reduce_no', $scope.tax_reduce_no);
                formData.append('ivrcItems', JSON.stringify(angular.toJson($scope.ivrcItems)));
                // formData.append('ivrcDate', ivrcDateStr);
                if($scope.DR!=null&&$scope.DR.length == 7&&($scope.DR.charAt(0) == 5||$scope.DR.charAt(0) == 6) && $scope.DR.substring(1, 4) == "1-1" && $scope.DR.substring(5, 7) == "00"){
                    formData.append('DR',$scope.DR);
                } else formData.append('DR',false); 
                formData.append('DRCR_cash',parseFloat($scope.DRCR_cash).toFixed(2));
                formData.append('CR',$scope.CR);
                formData.append('diff_dr_sup_cash',parseFloat($scope.all_DR_1_cash).toFixed(2));
                formData.append('diff_dr_sup_no',$scope.all_DR_1)
                formData.append('diff_dr_tax_cash',parseFloat($scope.all_DR_2_cash).toFixed(2));
                formData.append('diff_dr_tax_no',$scope.all_DR_2)
                formData.append('diff_cr_sup_cash',parseFloat($scope.all_CR_cash).toFixed(2));
                formData.append('diff_cr_sup_no',$scope.all_CR)
                

                // for(const pair of formData.entries()) {
                //     console.log(`${pair[0]}, ${pair[1]}`);
                // }
                
                $.ajax({ 
                    url: '/acc/invoice_receipt_confirm/debug',  
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                }).done(function (data) {
                    console.log(data)

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('ajax.fail');
                    addModal('uploadFailModal', 'upload fail', 'fail');
                    $('#uploadFailModal').modal('toggle');
                    $('#uploadFailModal').on('hide.bs.modal', function (e) {
                            location.assign("/");
                    });
                });
                

        }

  	});

</script>