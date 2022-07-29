<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบสำคัญสั่งจ่าย / Payment Voucher (PV)</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PV TYPE -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-3">
                        <label for="dropdownPaymentType">ประเภทการสั่งจ่าย</label>
                        <select class="form-control" ng-model="selectedPaymentType" ng-change="selectPaymentType()" id="dropdownPaymentType">
                            <option value="">เลือกประเภทการสั่งจ่าย</option>
                            <option value="PA">เงินรองจ่าย(PV-A)</option>
                            <option value="PB">จ่าย Supplier(PV-B)</option>
                            <option value="PC">ค่าใช้จ่าย(PV-C)</option>
                            <option value="PD">ลดหนี้(PV-D)</option>
                        </select>
                    </div>
                    <div class="col-md-3" ng-show="selectedPaymentType == 'PA'">
                        <label for="pvNameTextboxPVA">สั่งจ่าย</label>
                        <input type="text" class="form-control" id="pvNameTextboxPVA" ng-model="pvaName" ng-disabled="true">
                    </div>
                    <div class="col-md-3" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType!=''&& selectedPaymentType!= 'PC'">
                        <label for="pvNameTextbox" id="pvNameLabel"></label>
                        <input type="text" class="form-control" id="pvNameTextbox" ng-model="pvName" ng-show="selectedPaymentType!='PB' || otherExpense">
                        <select class="form-control" ng-model="selectedSupplier" ng-change="selectSupplier()" id="dropdownSupplier" ng-show="selectedPaymentType=='PB'">
                            <option value="">เลือก Supplier</option>
                            <option ng-repeat="supplier in rrcinopvs | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier}}">
                                {{supplier.supplier_no}} : {{supplier.supplier_name}}
                            </option>
                        </select>
                        <select class="form-control" ng-model="selectedCompany" ng-change="selectCompany()" id="dropdownCompany" ng-show="selectedPaymentType=='PA' || selectedPaymentType=='PC' && !otherExpense">
                            <option value="">สั่งจ่ายในนาม</option>
                            <option value='{"company_name":"โครงการ 1", "company_code":"1"}'>โครงการ 1</option>
                            <option value='{"company_name":"โครงการ 2", "company_code":"2"}'>โครงการ 2</option>
                            <option value='{"company_name":"โครงการ 3", "company_code":"3"}'>โครงการ 3</option>
                            <option value='{"company_name":"โครงการพิเศษ 1", "company_code":"9"}'>โครงการพิเศษ 1</option>
                            <option value='{"company_name":"โครงการพิเศษ 2", "company_code":"8"}'>โครงการพิเศษ 2</option>
                        </select>
                        <!--<div class="custom-control custom-checkbox mt-2" ng-show="selectedPaymentType=='PC'">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" ng-model="otherExpense">
                            <label class="custom-control-label" for="customCheck1">ค่าใช้จ่ายอื่น ๆ</label>
                        </div>-->
                    </div>
                    <div class="col-md-6" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType!=''&& selectedPaymentType!= 'PC'">
                        <label for="pvAddressTextbox">ที่อยู่</label>
                        <input type="text" class="form-control" id="pvAddressTextbox" ng-model="pvAddress">
                    </div>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- ADDING PV ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType != ''">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">
                        เพิ่มรายละเอียดลงใบสำคัญสั่งจ่าย 
                        <a ng-show="pvItemRR != '' || pvItemIV != '' || pvItemPaidTotal != ''" ng-click="clearPvItem()"><span class="badge badge-secondary">Clear</span></a>
                    </h4>
                </div>
                <div class="row mx-0 mt-2">
                    <div class="col-md-3">
                        <label for="pvItemDate">ใบสำคัญลงวันที่</label>
                        <input class="form-control" type="date" id="pvItemDate" ng-model="pvItemDate">
                    </div>
                    <div class="col-md-3">
                        <label for="pvItemRR" ng-show="selectedPaymentType=='PB'">เลขที่ RR/CI</label>
                        <label for="pvItemRR" ng-show="selectedPaymentType=='PA' || selectedPaymentType=='PC'">เลขที่ใบเบิกค่าใช้จ่าย</label>
                        <input type="text" class="form-control" id="pvItemRR" ng-model="pvItemRR">
                    </div>
                   
                    <div class="col-md-3">
                        <label for="pvItemPaidTotal">จำนวนเงิน</label>
                        <input type="text" class="form-control" id="pvItemPaidTotal" ng-model="pvItemPaidTotal">
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="vatCheck" ng-model="vat" ng-click="vat_check() "checked>
                            <label class="custom-control-label" for="vatCheck">ขอคืนภาษี</label>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 mt-2">
                   
                    <div class="col-md-6">
                        <label for="pvAddress">ที่อยู่</label>
                        <input type="text" class="form-control" id="pvAddress" ng-model="pvAddress">
                    </div>
                    <div class="col-md-2" ng-show = "selectedPaymentType!='PB'">
                        <label for="pvPayto">สั่งจ่าย</label>
                        <input type="text" class="form-control" id="pvPayto" ng-model="pv_payto">
                    </div>
                    <div class="col-md-2" ng-show = "selectedPaymentType!='PB'">
                        <label for="pvPayout">จ่ายออกจาก</label>
                        <input type="text" class="form-control" id="pvPayout" ng-model="pvPayout">
                    </div>
                    <div class="col-md-2">
                        <label for="pvItemDebit">เดบิต</label>
                        <input type="text" class="form-control" id="pvItemDebit" ng-model="pvItemDebit">
                    </div>
                    <div class="col-md-8">
                        <label for="pvItemDetail">หมายเหตุ</label>
                        <input type="text" class="form-control" id="pvItemDetail" ng-model="pvItemDetail">
                    </div>
                    <div class="col-md-4">
                        <label for="addPvItemButton" style="color:white;">.</label>
                        <button type="button" class="btn btn-default btn-block my-0" id=addPvItemButton ng-click="addPvItem()">ยืนยันรายการ</button>
                    </div>
                    
                    
                </div>
				<div class="row mx-0 mt-2" ng-show="selectedPaymentType == 'PA'">
                    <table class="table table-hover my-1" ng-show="wss.length == 0">
                        <tr ng-show="!isLoad">
                            <th>ไม่มีใบเบิกค่าใช้จ่าย ที่ยังไม่ได้ออก PV</th>
                        </tr>
                        <tr ng-show="isLoad">
                            <th>
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="wss.length != 0">
                        <tr>
                            <th>เลข WS</th>
                            <th>วันที่</th>
                            <th>ใบเบิกค่าใช้จ่าย</th>
                            <th>ใบกำกับภาษี / บิลเงินสด / ใบเสนอราคา</th>
                            <th>สลิป</th>
                            <th>ผู้ขอเบิก</th>
                        </tr>
                        <tr ng-repeat="ws in wss | filter:{form_no:pvItemRR} |filter:{ws_type:'1'}" ng-click="getWsDetail(ws)">
                            <td>{{ws.ws_no}}</td>
                            <td>{{ws.date}}</td>
                            <td><a href="/acc/payment_voucher/get_ws_form/{{ws.ws_no}}" target="_blank">{{ws.form_name}}</a></td>
                            <td style="text-align: center;">
                                <a href="/acc/payment_voucher/get_ws_iv/{{ws.ws_no}}/iv1" target="_blank">{{ws.iv_name}}</a><br>
                                <a href="/acc/payment_voucher/get_ws_iv/{{ws.ws_no}}/iv2" target="_blank">{{ws.iv2_name}}</a><br>
                                <a href="/acc/payment_voucher/get_ws_iv/{{ws.ws_no}}/iv3" target="_blank">{{ws.iv3_name}}</a>
                            </td>
                            <td><a href="/acc/payment_voucher/get_ws_iv/{{ws.ws_no}}/slip" target="_blank">{{ws.slip_name}}</a></td>
                            <td>{{ws.employee_id}} {{ws.employee_nickname_thai}}</td>
                        </tr>
                    </table>
                </div>
                
                <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
                <!-- ADDING PV-B จ่าย Supplier -->
                <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        
                <div class="row mx-0 mt-2" ng-show="selectedPaymentType=='PB'">
                    <table class="table table-hover my-1" ng-show="rrcinopvs.length == 0">
                        <tr ng-show="!isLoad">
                            <th>ไม่มี RR/CI ที่ยังไม่ได้ออก PV</th>
                        </tr>
                        <tr ng-show="isLoad">
                            <th>
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="rrcinopvs.length != 0">
                        <tr>
                            <th>เลข RR/CI</th>
                            <th>เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                            <th> add</th>
                        </tr>
                        <tr ng-show="isLoad">
                            <th colspan="5">
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                        <tr ng-repeat="rrcinopv in rrcinopvs | unique:'ci_no' | filter:{supplier_no:supplierNoForFilter, ci_no:pvItemRR}" >
                            <td>
                                {{rrcinopv.ci_no}}<br>
                                <a href="/acc/payment_voucher/get_IVPC_Files/bill/{{rrcinopv.ci_no}}" target="_blank">ดูใบวางบิล</a>
                                <a href="/acc/payment_voucher/get_invoice/{{rrcinopv.ci_no}}" target="_blank">ดูใบแจ้งหนี้</a>
                                <a href="/acc/payment_voucher/get_IVPC_Files/tax/{{rrcinopv.ci_no}}" target="_blank">ดูใบกำกับภาษี</a>
                                <a href="/acc/payment_voucher/get_IVPC_Files/debt/{{rrcinopv.ci_no}}" target="_blank">ดูใบลดหนี้</a>
                            </td>
                            <td>{{rrcinopv.po_no}}</td>
                            <td>{{rrcinopv.ci_date}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="rrcinopv_item in rrcinopvs" ng-show="rrcinopv_item.ci_no===rrcinopv.ci_no">{{rrcinopv_item.product_name}} (x{{rrcinopv_item.quantity}})</li>
                            </ul></td>
                            <td></td>
                            <td style="text-align: center;"> {{ rrcinopv.confirm_total | number:2}} </td>
                            <td><button ng-click ="getrrcinopvDetail(rrcinopv)">ADD RRCI</button></td>
                        </tr>
                    </table>
                </div>
                
                <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
                <!-- ADDING PV-C ค่าใช้จ่าย -->
                <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
                 
                <div class="row mx-0 mt-2" ng-show="selectedPaymentType == 'PC'">
                    <table class="table table-hover my-1" ng-show="ReReqs.length == 0">
                       
                            <th>ไม่มีใบเบิกค่าใช้จ่าย ที่ยังไม่ได้ออก PV</th>
                        
                        
                    </table>
                    <table class="table table-hover my-1" ng-show="ReReqs.length != 0">
                        <tr>
                            <th>เลข EXC</th>
                            <th>วันที่อนุมัติ</th>
                            <th>ใบกำกับภาษี / บิลเงินสด / ใบเสนอราคา</th>
                            <th>ผู้ขอเบิก</th>
                           
                        </tr>
                        <!-- <tr ng-repeat="pvc in PVCs | filter:{form_no:pvItemRR} |filter:{ws_type:'3'}" ng-click="getWsDetail(ws)"> -->
                        <tr ng-repeat="re_req in ReReqs track by $index" ng-click="getReReqDetail(re_req,$index)">
                            <td style="text-align: center;"><a href="/file/exc/{{re_req.ex_no}} ">{{re_req.ex_no}}</a></td>
                            <td style="text-align: center;">{{re_req.authorize_date}}</td>
                            <!-- <td><a href="/acc/payment_voucher/get_PVCs_form/{{pvc.PVC_No}}" target="_blank">{{pvc.PVC_No}}</a></td> -->
                            
                            <td style="text-align: center;">
                                <a href="/acc/payment_voucher/get_quotation/{{re_req.re_req_no}}" target="_blank">{{re_req.quotation_name}}</a><br>  
                            </td>
                            <td style="text-align: center;">{{re_req.withdraw_name}} {{re_req.employee_id}}</td>
                            
                            <!-- <td><a href="/acc/payment_voucher/get_ws_iv/{{ws.ws_no}}/slip" target="_blank">{{ws.slip_name}}</a></td>
                            <td>{{ws.employee_id}} {{ws.employee_nickname_thai}}</td> -->
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING PV C ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <?php print_r($pvItems) ?>
		<?php echo $pvItems ?>
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType!= 'PB' &&selectedPaymentType!=''">
            <div class="card-body">
                
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบสำคัญสั่งจ่าย&nbsp;</h4>
                   <br><h4 class="my-1">{{pvItemRR}}</h4>
                    <table class="table table-hover my-1" ng-show="pvDetails.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มรายการสั่งจ่าย</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pvDetails.length != 0">
                        <tr>
                            <th colspan="2">เลข EXC</th>
                            <th>วันที่อนุมัติ</th>
                            <th>เดบิต</th>
                            <th colspan="2">รายละเอียด</th>
                            <th ng-show="selectedPaymentType=='PB'">เลขที่ RR/CI</th>
                            <th ng-show="selectedPaymentType=='PA'">เลขที่ใบเบิกค่าใช้จ่าย</th>
                           
                            
                        </tr>
                        <tr ng-repeat="pvItem in pvDetails track by $index">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPvItem(pvItem)"></i></td>
                            <td style="text-align: center;">{{pvItem.ex_no}}</td>
                            <td style="text-align: center;">{{pvItem.authorize_date}}</td>
                            <td style="text-align: center;">
                                <span ng-show = "pvItem.debit != null ">{{pvItem.debit}}</span>
                                <span ng-show = "pvItem.debit ==null ">ยังไม่ได้กรอก</span>
                            </td>
                           
                            <td style="text-align: center;">
                                <ul ng-repeat="entries in JSONdetails track by $index ">
                                    <p style="text-align:left">วันที่: {{entries.date}}</p>
                                    <li style="text-align:left">{{entries.details}}</li>
                                    <li style="text-align:left">จำนวนเงิน: {{entries.money}}</li>
                                    <li style="text-align:left">ภาษี: {{(entries.money*7)/107 | number:2}}</li>
                                  
                                    
                               
                                </ul>
                            </td>
                          
                            
                            
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">ภาษีสุทธิ</th>
                            <th style="text-align: right;">{{totalVat| number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">รวมสุทธิ</th>
                            <th style="text-align: right;">{{pvItemPaidTotal| number:2}}</th>
                        </tr>
                    </table>
                </div>
                
                <div class="row mx-0 mt-2">       
                    <div class="col-md-4">
                        <label for="datetime-input">วันครบกำหนดชำระเงิน</label>
                        <input class="form-control" type="date" id="datetime-input" ng-model="dueDate">
                    </div>
                    <div class="col-md-8">
                        <label for="textBoxBank">ธนาคาร</label>
                        <input type="text" class="form-control" id="textBoxBank" ng-model="bank" placeholder="ธนาคาร">
                    </div>
                </div>
                
                <hr>
                
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postPvItems()">บันทึก PV</button>
                </div>
               
                
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING PVB ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->


        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="selectedPaymentType=='PB'">
            <div class="card-body">
                
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบสำคัญสั่งจ่าย</h4>
                    <table class="table table-hover my-1" ng-show="pvItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มรายการสั่งจ่าย</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pvItems.length != 0">
                        <tr>
                            <th colspan="2">วันที่</th>
                            <th>เดบิต</th>
                            <th>เลขที่ใบกำกับภาษี</th>
                            <th>รายละเอียด</th>
                            <th ng-show="selectedPaymentType=='PB'">เลขที่ RR/CI</th>
                            <th ng-show="selectedPaymentType=='PA' || selectedPaymentType=='PC'">เลขที่ใบเบิกค่าใช้จ่าย</th>
                            <th>ภาษี</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="pvItem in pvItems">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPvItem(pvItem)"></i></td>
                            <td>{{pvItem.date}}</td>
                            <td>{{pvItem.debit}}</td>
                            <td>{{pvItem.invoice_no}}</td>
                            <td>{{pvItem.detail}}</td>
                            <td>{{pvItem.rr_no}}</td>
                            <td style="text-align: right;">{{pvItem.vat| number:2}}</td>
                            <td style="text-align: right;">{{pvItem.total_paid | number:2}}</td>
                        </tr>
                        <tr>
                            <th colspan="5" style="text-align: right;" >diff สินค้า</th>
                            <th>{{sup_diff |number:2}}</th>
                            <th style="text-align: right;" colspan="1">มูลค่าสินค้า</th>
                            <th style="text-align: right;">{{final_before_vat | number:2}}</th>
                        </tr>
                        
                        <tr>
                            <th colspan="5" style="text-align: right;" >diff ภาษี</th>
                            <th>{{vat_diff|number:2}}</th>
                            <th style="text-align: right;" colspan="1">ภาษีสุทธิ</th>
                            <th style="text-align: right;">{{final_tax| number:2}}</th>
                            
                        </tr>
                        <tr>
                            <th colspan="5" style="text-align: right;" >diff สุทธิ</th>
                            <th>{{total_diff|number:2}}</th>
                            <th style="text-align: right;" colspan="1">รวมสุทธิ</th>
                            <th style="text-align: right;">{{final_price | number:2}}</th>
                        </tr>
                    </table>
                </div>
                
                <div class="row mx-0 mt-2">       
                    <div class="col-md-4">
                        <label for="datetime-input">วันครบกำหนดชำระเงิน</label>
                        <input class="form-control" type="date" id="datetime-input" ng-model="dueDate">
                    </div>
                    <div class="col-md-8">
                        <label for="textBoxBank">ธนาคารและสาขา</label>
                        <input type="text" class="form-control" id="textBoxBank" ng-model="bank" placeholder="ธนาคาร">
                    </div>
                </div>
                
                <hr>
                
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postPVB()">บันทึก PV</button>
                </div>
                
            </div>
        </div>

        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <!-- ADDING PV-D  -->
        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show = "selectedPaymentType == 'PD' ">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="filter_anything">CN no</label>
                        <input type="text" class="form-control" id="filter_anything" ng-model="filter_anything" style="text-transform:uppercase">
                    </div>
                </div>
                <div class="col-md-8">
                <h4 class="my-1" ng-show="PVDs.length != 0">รายละเอียดใบ CN</h4>
                </div>
                <table class="table table-hover my-1" ng-show="PVDs.length == 0">
                    <tr>
                        <th>ยังไม่มีการขอใบ CN</th>
                    </tr>
                </table>
                <table class="table table-hover my-1" ng-show="PVDs.length != 0">
                    <tr>
                        <th class = "center_cell">CN no</th>
                        <th class = "center_cell">employee id</th>
                        <th class = "center_cell">total_amount</th>
                        <th class = "center_cell">vat id</th>
                        <th class = "center_cell">sox no</th>
                        <th class = "center_cell">invoice id</th>
                        <th class = "center_cell">note</th>
                        <th class = "center_cell">เอกสาร CN</th>
                    </tr>
                    <tr ng-repeat = "PVD in PVDs | unique:'cn_no'  | filter:{cn_no:filter_anything}" ng-click="selectPVD(PVD)" ng-show = "selected_PVD.length == 0">
                        <td class = "center_cell">{{PVD.cn_no}}</td>
                        <td class = "center_cell">{{PVD.employee_id}}</td>
                        <td class = "center_cell">{{PVD.sum_total_sales}}</td>
                        <td class = "center_cell">{{PVD.vat_id}}</td>
                        <td class = "center_cell">{{PVD.sox_no}}</td>
                        <td class = "center_cell">{{PVD.invoice_no}}</td>
                        <td class = "center_cell">{{PVD.note}}</td>
                        <td>
                            <a href="https://uaterp.cbachula.com/file/cn/{{PVD.invoice_no}}" target="_blank" ng-click="stopEvent($event)" >CN</a>
                        </td>
                    </tr>
                    <tr ng-show = "selected_PVD.length != 0">
                        <td class = "center_cell"><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPVD()"></i> {{selected_PVD.cn_no}}</td>
                        <td class = "center_cell">{{selected_PVD.employee_id}}</td>
                        <td class = "center_cell">{{selected_PVD.sum_total_sales}}</td>
                        <td class = "center_cell">{{selected_PVD.vat_id}}</td>
                        <td class = "center_cell">{{selected_PVD.sox_no}}</td>
                        <td class = "center_cell">{{selected_PVD.invoice_no}}</td>
                        <td class = "center_cell">{{selected_PVD.note}}</td>
                        <td>
                            <a href="https://uaterp.cbachula.com/file/cn/{{selected_PVD.invoice_no}}" target="_blank" ng-click="stopEvent($event)" >CN</a>
                        </td>
                    </tr>
                </table>
                <div ng-show = "selected_PVD.length != 0">
                    <hr>
                    <div class="col-md-12">
                        <h4 class="my-1">เพิ่มรายละเอียดใบสำคัญสั่งจ่าย&nbsp;</h4>
                    </div>
                    <br>
                    <div class="col-md-3">
                            <input type="checkbox" id = "checkboxPVD" ng-change = 'updatePVD()' ng-model = 'selected_PVD.editing'/> 
                            <label for="checkboxPVD">แก้ไขข้อมูล&nbsp;</label>
                    </div>    
                    <div class="row mx-0">
                        <div class="col-md-6">
                            
                            <label for="pvNameTextboxPVD">สั่งจ่ายในนาม</label>
                            <input type="text" class="form-control" id="pvNameTextboxPVD" ng-model="selected_PVD.recipient" ng-disabled="!selected_PVD.editing">
                        </div> 
                        <div class="col-md-2">
                            <label for="companyPVD">โครงการ</label>
                            <input type="text" class="form-control" id="companyPVD" ng-model="selected_PVD.company_code" ng-disabled="!selected_PVD.editing">
                        </div>      
                    </div>  
                    <div class="row mx-0">
                        <div class="col-md-12">
                            <label for="locationPVD">ที่อยู่</label>
                            <input type="text" class="form-control" id="locationPVD" ng-model="selected_PVD.recipient_address" ng-disabled="!selected_PVD.editing">
                        </div> 
                    </div>
                    <div class="row mx-0">
                        <div class="col-md-4">
                            <label for="bankPVD">ธนาคารและสาขา</label>
                            <input type="text" class="form-control" id="bankPVD" ng-model="selected_PVD.bank" ng-disabled="!selected_PVD.editing">
                        </div>
                        <div class="col-md-4">
                            <label for="bankNoPVD">เลขที่บัญชี</label>
                            <input type="text" class="form-control" id="bankNoPVD" ng-model="selected_PVD.bank_no" ng-disabled="!selected_PVD.editing">
                        </div>   
                    </div>  

                    <div class="row mx-0 mt-2">
                        <button type="button" class="btn btn-default btn-block my-1" ng-click="postPVD()" ng-disabled="selected_PVD.editing">บันทึก PV</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <!-- ADDING PV-A  -->
        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->


        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show = "selectedPaymentType == 'PA'">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">
                        เพิ่มรายละเอียดลงใบสำคัญสั่งจ่าย 
                    </h4>
                </div>
                <div class="row mx-0 mt-2">
                    <div class="col-md-3">
                        <label for="pvaItemDate">ใบสำคัญลงวันที่</label>
                        <input class="form-control" type="date" id="pvaItemDate" ng-model="pvaItemDate">
                    </div>
                    <div class="col-md-3">
                        <label for="pvaItemNo">เลขที่ใบเติมเงินรองจ่าย</label>
                        <input type="text" class="form-control" id="pvaItemNo" ng-model="selected_PVA.internal_bundle_no" ng-disabled="true">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-8">
                        <label for="filter_pva_no">BPA no</label>
                        <input type="text" class="form-control" id="filter_pva_no" ng-model="filter_pva_no" style="text-transform:uppercase">
                    </div>
                </div>
                <div class="col-md-8">
                <h4 class="my-1" ng-show="PVAs.length != 0">รายละเอียดใบ PV-A</h4>
                </div>
                <table class="table table-hover my-1" ng-show="PVAs.length == 0">
                    <tr>
                        <th>ยังไม่มีการขอใบ PVA</th>
                    </tr>
                </table>
                <table class="table table-hover my-1" ng-show="PVAs.length != 0">
                    <tr>
                        <th class = "center_cell">เลขที่ BPA</th>
                        <th class = "center_cell">วันที่</th>
                        <th class = "center_cell">total paid</th>
                        <th class = "center_cell">petty cash statement</th>
                        <th class = "center_cell">ผู้ออก</th>
                    </tr>
                    <tr ng-repeat = "PVA in PVAs | unique:'internal_bundle_no'  | filter:{internal_bundle_no:filter_pva_no}" ng-click="selectPVA(PVA)"">
                        <td class = "center_cell">{{PVA.internal_bundle_no}}</td>
                        <td class = "center_cell">{{PVA.pv_date}} {{PVA.pv_time}}</td>
                        <td class = "center_cell">{{PVA.total | number:2}}</td>
                        <td class = "center_cell"><a href="/acc/payment_voucher/get_petty_cash_statement/{{PVA.internal_bundle_no}}" target="_blank">statement</a></td>
                        <td class = "center_cell">{{PVA.employee_id}} {{PVA.employee_nickname_eng}}</td>
                    </tr>

                </table>

            </div>

            <div class="card-body" ng-show = "selected_PVA.length != 0">
                <!-- <i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPVA()"></i> -->
                <table class="table table-hover my-1">
                    <tr>
                        <th class = "center_cell">วันที่</th>
                        <th class = "center_cell">เลขที่ใบเบิกรองจ่าย</th>
                        <th class = "center_cell">รายการ</th>
                        <th class = "center_cell">จำนวนเงิน</th>
                        <th class = "center_cell">invoice/reciept</th>
                        <th class = "center_cell">slip</th>
                        <th class = "center_cell">เดบิต</th>
                        <th class = "center_cell">ภาษีซื้อ</th>
                    </tr>
                    <tr ng-repeat = "child in selected_PVA_child">
                        <td class = "center_cell">{{child.pv_date}}</td>
                        <td class = "center_cell">{{child.internal_pva_no}}</td>
                        <td class = "center_cell">{{child.product_names}}</td> 
                        <td class = "center_cell">{{child.total_paid | number:2}}</td>
                        <td class = "center_cell"> 
                            <a href="/fin/validate_petty_cash_request/get_re/{{child.internal_pva_no}}" target="_blank">{{child.ivrc_name}}</a>
                            <a href="/fin/validate_petty_cash_request/get_iv/{{child.internal_pva_no}}" target="_blank">{{child.slip_name}}</a>
                        </td>
                        <td class = "center_cell"> 
                            <a href="/fin/create_pva/get_fin_slip/{{child.internal_pva_no}}" target="_blank">{{child.fin_slip_name}}</a> 
                        </td>
                        <td class = "center_cell"> 
                            <input type="text" class="form-control" id="{{child.internal_pva_no}}_debit" ng-model="child.debit">
                        </td>
                        <td class = "center_cell"> 
                            <input type="checkbox" class="form-control" id="{{child.internal_pva_no}}_tax" ng-model="child.tax">
                        </td>
                    </tr>
                    <tr>
                        <td class = "center_cell">จำนวนที่ต้องการเติมเพิ่ม</td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell"> {{selected_PVA.additional_cash}} </td>
                        <td class = "center_cell" colspan="4">เหตุผล : {{selected_PVA.additional_cash_reason}}</td>
                    </tr>

                    <tr>
                        <td class = "center_cell">รวมทั้งสิ้น</td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell">{{pva_total | number:2}}</td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell"></td>
                        <td class = "center_cell"></td>
                    </tr>
                </table>

                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="notes">หมายเหตุ</label>
                        <input type="text" class="form-control" id="notes" ng-model="PVA_notes">
                    </div>
                </div>

                <div>
                    <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postPVA()" ng-disabled="selected_PVA.editing">ยืนยัน PVA</button>
                    </div>

                    <!-- <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="test()" ng-disabled="selected_PVA.editing">test</button>
                    </div> -->
                </div>
            </div>
        </div>








        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้กรอกวันที่');
            addModal('formValidate2', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'Supplier นี้สามารถขอภาษีซื้อได้');
            addModal('formValidate3', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'เลือก Supplier ก่อนครับผม');
            addModal('formValidate4', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เลือกประเภทการสั่งจ่าย');
            addModal('formValidate5', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มรายละเอียดสำหรับออกใบสำคัญสั่งจ่าย');
            // addModal('formValidate6', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มว่าสั่งจ่ายใคร');
            addModal('formValidate7', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มเลขที่ใบสำคัญ');
            addModal('formValidate8', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้ใส่จำนวนเงินสั่งจ่าย');
            addModal('formValidate9', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เลือกว่าสั่งจ่ายในนามอะไร');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
    .center_cell {
        text-align: center;
    }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.vat_check = function() {
            if (document.getElementById('vatCheck').checked==true){
					document.getElementById('vatCheck').value = '1';
			}else if(document.getElementById('vatCheck').checked==false){
					document.getElementById('vatCheck').value = '0';
			}
        }
        $scope.totalPaid
        $scope.pvItems = [];
        $scope.pvDetails = [];
        $scope.selectedPaymentType = '';
        $scope.selectedSupplier = '';
        $scope.showAfterSubmit = false;
        $scope.selectedCompany = '';
        $scope.company_code = '';
        $scope.otherExpense = false;
        $scope.pvItemDate = '';
        $scope.pvItemRR = '';
        $scope.pvItemDetail = '';  
        $scope.pvItemIV = '';  
        $scope.pvItemPaidTotal = '';
        $scope.vat = false;
        $scope.isLoad = true;
        $scope.dueDate='';
        $scope.ci_no = '';
        $scope.selected_PVD = [];
        $scope.selected_PVA = [];
        $scope.program_pva = '';
        $scope.vatPva = false;
        $scope.pva_total = 0;
        $scope.pvaName = 'พนักงานรองจ่าย';
        $scope.PVA_notes = '';
        $scope.pvPayout='';
        $scope.pvPayTo = '';
        $scope.bank='';
        $scope.dueDate='';
        $scope.tax_number = '';
        $scope.JSONdetails = ''
        $scope.total_money = 0;
        $scope.total_tax = 0;
        $scope.final_tax = 0;

        

        
        $scope.selectPaymentType = function() {
            
            $scope.pvItems = [];
            $scope.pvName = '';
            $scope.pvAddress = '';
            $('#pvNameLabel').html('สั่งจ่าย');
            $("#pvNameTextbox").prop("disabled", false);
            $("#pvAddressTextbox").prop("disabled", false);
            $("#pvItemRR").prop("disabled", false);
            $("#vatCheck").prop("disabled", false);
            $scope.vat = false;
            $scope.rrcinopvs = [];
            $scope.wss = [];
            $scope.ReReqs = [];
            $scope.isLoad = true;
            $scope.PVDs = [];
            $scope.PVAs = [];
            

            if($scope.selectedPaymentType === '') {
            
            } else if($scope.selectedPaymentType === 'PA') {
                $http.get('/acc/payment_voucher/get_PVA').then(
                    function(response){
                        $scope.PVAs = response.data; 
                        angular.forEach($scope.PVAs, function (value, key) {
                            value.total = parseFloat(value.total_paid) +  parseFloat(value.additional_cash);
                        });
                        $scope.isLoad = false;
                    });
            } else if($scope.selectedPaymentType === 'PB') {
                $http.get('/acc/payment_voucher/get_rr_ci_no_pv').then(function(response){
                    $scope.rrcinopvs = response.data;
                    console.log(response.data);
                    
                    angular.forEach($scope.rrcinopvs, items =>{
                        if(items['invoice_no'] == 'none'){
                            items['invoice_no'] = "-";
                        }
                     
                    });
                    $scope.isLoad = false;
                });
                $('#pvNameLabel').html('จ่าย Supplier');
                $("#pvAddressTextbox").prop("disabled", true);
            } else if($scope.selectedPaymentType==='PC') {
                // $http.get('/acc/payment_voucher/get_ws').then(function(response){$scope.wss = response.data; $scope.isLoad = false;});
                $http.get('/acc/payment_voucher/get_ReReqs')
                .then(function(response){
                    $scope.ReReqs = response.data;
                     $scope.pvItem = $scope.ReReqs ;
                     $scope.pvItems = $scope.ReReqs;$scope.isLoad = false;
                     console.log($scope.ReReqs.debit)});
                
                
            } else if($scope.selectedPaymentType==='PD') {
                $http.get('/acc/payment_voucher/get_PVD').then(function(response){
                    $scope.PVDs = response.data; 
                    $scope.isLoad = false;
                    console.log(JSON.stringify($scope.PVDs));
                });
            }  
            
        }
        
        $scope.selectSupplier = function() {
            $scope.pvItems = [];
            $scope.vat = false;
            if($scope.selectedSupplier === '') {
                $scope.pvName = '';
                $scope.pvAddress = '';
                $scope.supplierNoForFilter = '';
                $scope.pvItemDebit = '';
            } else {
                $scope.pvName = JSON.parse($scope.selectedSupplier).supplier_name;
                $scope.pvAddress = JSON.parse($scope.selectedSupplier).address;
                $scope.supplierNoForFilter = JSON.parse($scope.selectedSupplier).supplier_no;
                $scope.pvItemDebit = '21-1' + JSON.parse($scope.selectedSupplier).supplier_no;
                $("#pvItemDebit").prop("disabled", true);
                if(JSON.parse($scope.selectedSupplier).vat_type === "1") {
                    $scope.vat = true;
                } else {
                    $scope.vat = false;
                }
                $("#vatCheck").prop("disabled", true);
            }
            console.log($scope.selectedSupplier)
        }
        
        $scope.selectCompany = function() {
            if($scope.selectedCompany==='') {
                $scope.pvName = '';
                $scope.pvAddress = '';
            } else {
                // $scope.pvName = 'ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2563 (' + JSON.parse($scope.selectedCompany).company_name + ')'
                // $scope.pvAddress = 'อาคารไชยยศสมบัติ 1 ชั้นใต้ดิน เลขที่ 254 ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330';
                $scope.company_code = JSON.parse($scope.selectedCompany).company_code;
            }
        }

        $scope.selectPVD = function($PVD) {
            $scope.selected_PVD = $PVD;
            // console.log($scope.selected_PVD);
            //todo get customer and iv data
        }

        $scope.dropPVD = function() {
            $scope.selected_PVD = [];
        }
        // $scope.selectCompanypvd = function() {
        //     console.log($scope.selected_PVD.company_code);
        // }

        $scope.updatePVD = function() {
        //     console.log($scope.selected_PVD);
        //     console.log($scope.selected_PVD.company_code);
            if(!$scope.selected_PVD.editing) {
                $.post("/acc/payment_voucher/update_PVD", {
                    post : true,
                    cn_no : $scope.selected_PVD.cn_no,
                    wsd_no : $scope.selected_PVD.wsd_no,
                    company_code : $scope.selected_PVD.company_code,
                    recipient : $scope.selected_PVD.recipient,
                    bank : $scope.selected_PVD.bank,
                    bank_no : $scope.selected_PVD.bank_no,
                    recipient_address : $scope.selected_PVD.recipient_address,     
                }, function(data) {
                    addModal('pvdeditsuccessModalupdate', 'PV-D', 'edit ' + $scope.selected_PVD.cn_no.toUpperCase() +  data);
                    $('#pvdeditsuccessModalupdate').modal('toggle');
                });
            }
        }

        $scope.postPVD = function() {
            if(!$scope.selected_PVD.editing) {
                // console.log($scope.company_code);
                $.post("/acc/payment_voucher/post_PVD", {
                    post : true,
                    cn_no : $scope.selected_PVD.cn_no,
                    company_code : $scope.selected_PVD.company_code,
                    vat_id : $scope.selected_PVD.vat_id,
                    sum_total_sales : $scope.selected_PVD.sum_total_sales,
                    note : $scope.selected_PVD.note,
                    recipient : $scope.selected_PVD.recipient,
                    bank : $scope.selected_PVD.bank,
                    bank_no : $scope.selected_PVD.bank_no,
                    recipient_address : $scope.selected_PVD.recipient_address,     
                    wsd_no : $scope.selected_PVD.wsd_no
                }, function(data) {
                    addModal('pvdUpdateSuccessModalupdate', 'PV-D', 'insert ' + $scope.selected_PVD.cn_no.toUpperCase() +  data);
                    $('#pvdUpdateSuccessModalupdate').modal('toggle');
                    $('#pvdUpdateSuccessModalupdate').on('hide.bs.modal', function (e) {
                        window.open('/file/pvd/' + $scope.selected_PVD.cn_no);
                        location.reload();
                    });
                });
            }
        }

        $scope.selectPVA = function($PVA) {
            $scope.PVA_notes = '';
            $scope.selected_PVA = $PVA;
            $scope.pva_total =  parseFloat($PVA.total_paid) +  parseFloat($PVA.additional_cash);
            $.post("/acc/payment_voucher/get_PVA_child", { //should be using get request pls fix when have time
                post : true,
                internal_bundle_no : $PVA.internal_bundle_no
            }, (data) => {
                $scope.selected_PVA_child = JSON.parse(data);
                $scope.$apply();
            });
            
        }

        
        $scope.dropPVA = function() {
            $scope.selected_PVA = [];
            $scope.selected_PVA_child = [];
            $scope.pva_total = 0;
        }

        $scope.postPVA = function() {
            if(false) {
                $('#formValidate9').modal('toggle');
            } else {
                $.post("/acc/payment_voucher/post_PVA", {
                    post : true,
                    program : 3,
                    total_no_add : parseFloat($scope.selected_PVA.total_paid),
                    internal_bundle_no : $scope.selected_PVA.internal_bundle_no,
                    pva_child : $scope.selected_PVA_child,
                    approve_date : $scope.pvaItemDate,
                    notes : $scope.PVA_notes
                }, function(data) {
                    if(data.length == 9){
                        addModal('pvaUpdateSuccessModalupdate', 'สร้างใบสำคัญสั่งจ่ายสำเร็จ',data);
                        $('#pvaUpdateSuccessModalupdate').modal('toggle');
                        $('#pvaUpdateSuccessModalupdate').on('hide.bs.modal', function (e) { 
                            window.open('/file/pva/' + data);
                            location.reload();
                        });
                    } else {
                        addModal('failedModal', 'สร้างใบสำคัญสั่งจ่ายสำเร็จ error', 'ใบ PV-A failed ส่ง console ให้ is หน่อย');
                        $('#failedModal').modal('toggle');
                        console.log(data);
                    }
                });
            }
        }

        $scope.diff = 0;
        $scope.final_before_vat = 0;
        $scope.sup_diff = 0;
        $scope.vat_diff = 0;
        $scope.final_price =0;
        $scope.added_diff = false;
        $scope.product_vat = 0;
        $scope.total_diff = 0;

        $scope.getrrcinopvDetail = function(rrcinopv) {
            console.log(rrcinopv)
            $("#pvItemRR").prop("disabled", true);
            $("#pvItemIV").prop("disabled", true);
            $("#pvItemPaidTotal").prop("disabled", true);
            $scope.pvItemRR = rrcinopv.ci_no;
            $scope.pvItemIV = rrcinopv.tax_no;
            $scope.pvItemPaidTotal = parseFloat(rrcinopv.confirm_total);
            $scope.ci_no = rrcinopv.ci_no;
            $scope.diff = Number(rrcinopv.diff);
            $scope.diff_dr_sup = Number(rrcinopv.diff_dr_sup);
            $scope.diff_dr_tax = Number(rrcinopv.diff_dr_tax);
            // console.log($scope.diff_dr_tax);
            

            $scope.vat_diff += $scope.diff !== 0 ? $scope.diff : $scope.diff_dr_tax;
            
            
            $scope.sup_diff += $scope.diff_dr_sup
            if(!$scope.added_diff) $scope.total_diff += ($scope.vat_diff + $scope.sup_diff);
            if($scope.diff !== Number("0.00")) {
                $scope.total_diff = 0;
                $scope.sup_diff = -$scope.vat_diff;
            }
            console.log(typeof $scope.diff);
            
            // console.log(`VAT DIFF: ${$scope.vat_diff}`);
            // console.log(`SUP DIFF: ${$scope.sup_diff}`);
            // console.log(`TOTAL DIFF: ${$scope.total_diff}`);
         
           
            $scope.current = Number(rrcinopv['confirm_total'])
        
            $scope.vat_type = rrcinopv.product_no.charAt(3)
            $scope.current_price =  Number(rrcinopv['confirm_total'])
            $scope.final_price += Number(rrcinopv['confirm_total'])
            
            $scope.product_vat = $scope.current_price *7/107
            
            if($scope.vat_type!=='1'){
              
              if(!$scope.added_diff){ 
                    $scope.final_tax += (0 +  $scope.vat_diff )
                    $scope.final_before_vat += Number((($scope.sup_diff + $scope.current_price)).toFixed(2))
                    $scope.final_price += $scope.total_diff

                    $scope.added_diff = true;
                   
                    
                } else{
                    $scope.final_tax += 0
                    $scope.final_before_vat += Number(($scope.current_price).toFixed(2))
                    
                }
                

            }
            else{
                if(!$scope.added_diff){ 
                    $scope.final_before_vat += Number((($scope.sup_diff + $scope.current_price* 100/107) ).toFixed(2))
                
                    $scope.final_tax += (Number(($scope.current_price*7/107).toFixed(2)) +  $scope.vat_diff )
                    $scope.added_diff = true;
                    $scope.final_price += Number($scope.total_diff);
                    console.log(`TOTAL DIFF ${$scope.total_diff}`);
                    
                    console.log(`YO2 ${$scope.final_price}`);
                    
                    
                    
                }
                else{
                    $scope.final_tax += Number(($scope.current_price*7/107).toFixed(2));
                    $scope.final_before_vat += Number(($scope.current_price * 100/107).toFixed(2))
                    
                }
             
              

            }
           
            
          
           
        //     console.log(`Current tax:${$scope.current_price * 7/107}`);
            
        //    console.log($scope.sup_diff);
        //    console.log($scope.current_price * 100/107);
        //    console.log($scope.final_tax);
           
           
        //     console.log($scope.final_before_vat);
        //     console.log($scope.final_price);
            
            
            
             
            
        }
        
        $scope.getWsDetail = function(ws) {
            $("#pvItemRR").prop("disabled", true);
            $scope.pvItemRR = ws.form_no;
        }
        
        $scope.addPvItem = function() {
            
            if (($scope.selectedPaymentType==='PA') && ($scope.pvName==='' || $scope.pvAddress==='')) {
                $('#formValidate6').modal('toggle');
            // } else if (($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') && $scope.pvItemIV==='') {
            //     $('#formValidate7').modal('toggle');
            } else if (($scope.selectedPaymentType==='PA' ) && $scope.pvItemPaidTotal==='') {
                $('#formValidate8').modal('toggle');
                console.log($scope.pvItemPaidTotal)
            } else if ($scope.pvItemDate==='') {
                $('#formValidate1').modal('toggle');
            } else {
                
                if($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') $scope.pvItems=[];
                const company = document.getElementById("dropdownCompany").value;
                var pvItemDateStr = $scope.pvItemDate.getFullYear() + '-' + 
                                    (($scope.pvItemDate.getMonth()+1) < 10 ? '0' : '') + ($scope.pvItemDate.getMonth()+1) + '-' + 
                                    ($scope.pvItemDate.getDate() < 10 ? '0' : '') + $scope.pvItemDate.getDate();
                
                                    
                if($scope.selectedPaymentType==='PC')
                { 
                    console.log(document.getElementById("vatCheck").value);
                    if(document.getElementById("vatCheck").value==='1'){
                            $scope.vat = true;
                    }else{
                        $scope.vat = false;
                    }
                    let tax = false;
                    if($scope.vat){
                        tax  = true
                    }else{
                        tax = false
                    }
                    console.log(
                        {
                        "debit" : String($scope.pvItemDebit),
                        "cr_name": String($scope.pvItemRR),
                        "pv_date" : pvItemDateStr,
                        "total_paid" : String($scope.pvItemPaidTotal),
                        "pv_name" : String($scope.pvName),
                        "pv_address" : String($scope.pvAddress),
                        "details": String($scope.pvItemDetail),
                        "tax_number":$scope.tax_number,
                        "selected_company":String($scope.selectedCompany),
                        "confirm":"1",
                        "pv_payout": $scope.pvPayout,
                        "pv_payto": $scope.pv_payto,
                        "return_tax": true
                    }

                    );
                    
                
                    
                    $.post(`/acc/payment_voucher/confirm/${$scope.pvItemRR}`,{
                        "debit" : String($scope.pvItemDebit),
                        "cr_name": String($scope.pvItemRR),
                        "pv_date" : pvItemDateStr,
                        "total_paid" : String($scope.pvItemPaidTotal),
                        "pv_name" : String($scope.pvName),
                        "pv_address" : String($scope.pvAddress),
                        "details": String($scope.pvItemDetail),
                        "tax_number":$scope.tax_number,
                        "selected_company":String($scope.selectedCompany),
                        "confirm":"1",
                        "pv_payout": $scope.pvPayout,
                        "pv_payto": $scope.pv_payto,
                        "return_tax": true
                    }).done(function(data,status){
                        console.log(data)
                        console.log(status)
                        // window.location.reload();

                        
                    }).fail(function(e){
                        console.log(e)
                    });
                    $http.get('/acc/payment_voucher/get_ReReqs')
                        .then(function(response){
                            $scope.ReReqs = response.data;
                            $scope.pvItem = $scope.ReReqs ;
                            $scope.pvItems = $scope.ReReqs;$scope.isLoad = false;
                            console.log("Hey")
                        });
                



                }
              
                $scope.final_vat_diff = $scope.vat_diff== '0'? $scope.diff: $scope.vat_diff;
                console.log($scope.selectedSupplier);
                console.log($scope.selectedPaymentType);
                
                
                if($scope.selectedPaymentType==='PC'){
                    $scope.pvItems.push({
                    "debit" : $scope.pvItemDebit,
                    "date" : pvItemDateStr,
                    "iv_no" : $scope.pvItemIV,
                    "detail" : $scope.pvItemDetail,
                    "rr_no" : $scope.pvItemRR,
                    "total_paid" : parseFloat($scope.pvItemPaidTotal),
                    "vat" : parseFloat(($scope.pvItemPaidTotal) * 7/107).toFixed(2),
                  
                });

                }else{
                    $scope.pvItems.push({
                    "debit" : $scope.pvItemDebit,
                    "date" : pvItemDateStr,
                    "iv_no" : $scope.pvItemIV,
                    "detail" : $scope.pvItemDetail,
                    "rr_no" : $scope.pvItemRR,
                    "total_paid" : parseFloat($scope.pvItemPaidTotal),
                    "vat" : JSON.parse($scope.selectedSupplier).vat_type =="1" ? (parseFloat($scope.pvItemPaidTotal) * 7/107).toFixed(2):0
                  
                });

                }
               

                if($scope.selectedPaymentType !='PC'){
                    $scope.clearPvItem();
                $scope.calculateTotalPrice();
                }
              
                           
            }
            
           
        }
        
        $scope.clearPvItem = function() {
            $scope.pvItemRR = '';
            $scope.pvItemDetail = '';  
            if($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') $scope.pvItemDebit = '';  
            $scope.pvItemIV = '';  
            $scope.pvItemPaidTotal = 0;
            $("#pvItemRR").prop("disabled", false);
            $("#pvItemIV").prop("disabled", false);
            $("#pvItemPaidTotal").prop("disabled", false);
        }
        
        $scope.dropPvItem = function(pvItem) {
            var tempRemoved = [];
            angular.forEach($scope.pvItems, function (value, key) {
                if(value != pvItem) {
                    tempRemoved.push(value);
                }
            });
            $scope.pvItems = tempRemoved;
            $scope.calculateTotalPrice();
        }
        
        $scope.calculateTotalPrice = function() {

            // console.log(JSON.parse($scope.selectedSupplier).vat_type);
            
            $scope.totalPaidBeforeVat = 0;
            $scope.totalVat = 0;
            angular.forEach($scope.pvItems, function(value, key) {
                if($scope.selectedPaymentType != "PC"){
                    if(JSON.parse($scope.selectedSupplier).vat_type =='1'){
                    $scope.totalPaidBeforeVat += Number(((value.total_paid) * 100/107).toFixed(2));
                    $scope.totalVat += Number(((value.total_paid) * 7/107).toFixed(2));

                    }else{
                        $scope.totalPaidBeforeVat += Number(((value.total_paid)).toFixed(2));
                        $scope.totalVat += 0

                    }

                }else{
                    $scope.totalVat += Number(((value.total_paid) * 7/107).toFixed(2));

                }
               
             
            });
            console.log($scope.totalVat);
            console.log($scope.totalPaidBeforeVat)
            
        }

        $scope.postPVB = ()=> {
            console.log($scope.totalVat + $scope.vat_diff)
            var dueDateStr = $scope.dueDate.getFullYear() + '-' + 
                                (($scope.dueDate.getMonth()+1) < 10 ? '0' : '') + ($scope.dueDate.getMonth()+1) + '-' + 
                                ($scope.dueDate.getDate() < 10 ? '0' : '') + $scope.dueDate.getDate();

            if($scope.selectedPaymentType==='') {
                $('#formValidate4').modal('toggle');
            } else if ($scope.pvItems.length===0) {
                $('#formValidate5').modal('toggle');
            } else if($scope.selectedSupplier==='') {
                $('#formValidate3').modal('toggle');
            } else {
                $.post("/acc/payment_voucher/post_pvb", { 
                    post : true,
                    supplier_no : $scope.supplierNoForFilter,
                    vat_type: JSON.parse($scope.selectedSupplier).vat_type,
                    pv_name : $scope.pvName,
                    pv_address : $scope.pvAddress,
                    company_code : $scope.pvItems[0].rr_no.substr(0,1),
                    pvItems : JSON.stringify(angular.toJson($scope.pvItems)),
                    totalPaid : $scope.final_price,
                    totalPaidThai : NumToThai($scope.final_price),
                    totalVat : $scope.final_tax,
                    dueDate : dueDateStr,
                    bank : $scope.bank,
                    ci_no : $scope.ci_no,
                    diff_sup : $scope.total_diff
                }, function(data) {
                    addModal('PVBsuccessModal', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'บันทึก ' + data + ' สำเร็จ');
                    $('#PVBsuccessModal').modal('toggle');
                    $('#PVBsuccessModal').on('hide.bs.modal', function (e) {
                        window.location.assign('/');
                    });
                });
            }
        }
        $scope.pv_payto=''
        
        $scope.postPvItems = function() {
            
            var dueDateStr = $scope.dueDate.getFullYear() + '-' + 
                                (($scope.dueDate.getMonth()+1) < 10 ? '0' : '') + ($scope.dueDate.getMonth()+1) + '-' + 
                                ($scope.dueDate.getDate() < 10 ? '0' : '') + $scope.dueDate.getDate();
            
            if($scope.selectedPaymentType==='') {
                $('#formValidate4').modal('toggle');
            } else if ($scope.pvItems.length===0) {
                $('#formValidate5').modal('toggle');
            } else if($scope.selectedPaymentType==='PC') {
                
                // if($scope.otherExpense) $scope.company_code = '3';
               
                
                
                    var d = new Date();
                    var month = d.getMonth()+1;
                    var day = d.getDate();

                    var output = d.getFullYear() + '/' +
                        (month<10 ? '0' : '') + month + '/' +
                        (day<10 ? '0' : '') + day;
                        
                    $scope.detail = JSON.parse(($scope.pvItems)['details'])[0]['details'];
                    
                    
                    
                    console.log({
                        post : true,
                        pv_name : $scope.pvName,
                        pv_address : $scope.pvAddress,
                        pv_date: output,
                        pv_detail:$scope.pvItemDetail,
                        ex_no:$scope.pvItemRR,
                        re_req_no:$scope.pvItems["re_req_no"],
                        company_code : $scope.company_code,
                        detail : $scope.detail,
                        totalPaid : $scope.pvItemPaidTotal,
                        totalPaidThai : NumToThai($scope.pvItemPaidTotal),
                        totalVat : $scope.totalVat,
                        dueDate : dueDateStr,
                        bank : $scope.bank,
                        payTo :$scope.pvItems["pv_payto"],
                        payout:$scope.pvPayout,
                        excDate:$scope.pvItems["authorize_date"],
                        bankBookName:$scope.pvItems["bank_book_name"],
                        bankBookNumber:$scope.pvItems["bank_book_number"],
                        bankName:$scope.pvItems["bank_name"],
                        company_code:"3",
                        pvItems:$scope.pvItems,
                        debit:$scope.pvItemDebit,
                        return_tax: $scope.pvItems["return_tax"]
                    });
                    
                    

                    $.post("/acc/payment_voucher/post_pvc", {
                        post : true,
                        pv_name : $scope.pvName,
                        pv_address : $scope.pvAddress,
                        pv_date: output,
                        pv_detail:$scope.pvItemDetail,
                        ex_no:$scope.pvItemRR,
                        re_req_no:$scope.pvItems["re_req_no"],
                        company_code : $scope.company_code,
                        detail : $scope.detail,
                        totalPaid : $scope.pvItemPaidTotal,
                        totalPaidThai : NumToThai($scope.pvItemPaidTotal),
                        totalVat : $scope.totalVat,
                        dueDate : dueDateStr,
                        bank : $scope.bank,
                        payTo :$scope.pvItems["pv_payto"],
                        payout:$scope.pvPayout,
                        excDate:$scope.pvItems["authorize_date"],
                        bankBookName:$scope.pvItems["bank_book_name"],
                        bankBookNumber:$scope.pvItems["bank_book_number"],
                        bankName:$scope.pvItems["bank_name"],
                        company_code:"3",
                        pvItems:$scope.pvItems,
                        debit:$scope.pvItemDebit,
                        return_tax: $scope.pvItems["return_tax"]
                    }, function(data) {
                        console.log(JSON.stringify(angular.toJson($scope.pvItems)))
                        console.log(data)
                        addModal('successModal', 'Payment Voucher', `ออกใบ: ${data} สำเร็จ`);
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                           
                        });
                    }).fail(function(error){
                        console.log(error)
                    })
                    
                
                
            } else if($scope.selectedPaymentType==='PD') {
                
            }
            
            
            
        }
        $scope.pvItemDetail='';
        $scope.pvItemRR =''
        $scope.pvDetails=[]
        $scope.getReReqDetail = function(re_req,index) {
            console.log($scope.pvItems);
            
           
            $("#pvItemRR").prop("disabled", true);
            
            
           
            $scope.pvItemRR = re_req.ex_no;
            console.log($scope.pvItemRR)
            // console.log($scope.pvItemRR);
            $scope.pvDetails = JSON.parse($scope.ReReqs[index]["details"])
            console.log($scope.pvDetails);
            
            // console.log( $scope.pvDetails[0].money)
            $scope.pvItems = $scope.ReReqs[index]
            // console.log($scope.pvItems)
            // console.log( $scope.pvItems);
            $scope.pvItemPaidTotal =0;
           
            $("#vatCheck").prop('checked',true);
           
           
            var date = $scope.pvItems["authorize_date"];
            // console.log($scope.pvItems)
            $scope.pvPayto = $scope.pvItems["pv_payto"];
           
            $scope.pvPayout = 'โครงการ 3'
            
            $scope.bank = $scope.pvItems["bank_name"]
            $scope.pvAddress = $scope.pvItems["pv_address"];
            $scope.pvName = $scope.pvPayto
            // $scope.tax_number = $scope.pvItems["tax_number"]
           
            $scope.dueDate = new Date($scope.pvItems["due_date"]);
            $scope.JSONdetails =JSON.parse($scope.pvItems['details'])
          
            
            
            const input_date = document.getElementById("pvItemDate");
            // console.log(input_date.value);

            
            ($scope.ReReqs).forEach(data=>{
                if(data.ex_no===$scope.pvItemRR){
                $scope.pvDetails = [data];
                
            }
            $scope.pvItemDebit = $scope.pvDetails[0].debit;
            $scope.pvItemDetail = $scope.pvDetails[0].pv_details
            $scope.pvItemDate = new Date($scope.pvDetails[0].authorize_date)
            $scope.pvName = $scope.pvDetails[0].pv_name
            $scope.pvAddress = $scope.pvDetails[0].pv_address
            $scope.pv_payto =  $scope.pvDetails[0].pv_payto
            $scope.bankBookName = $scope.pvItems["bank_book_name"]
            $scope.bankBookNumber=$scope.pvItems["bank_book_number"]
            $scope.bankName=$scope.pvItems["bank_name"]
            $scope.return_tax = $scope.pvItems['return_tax']
            console.log($scope.pvItemDetail);
            
      
      
          
            
        });
        ($scope.JSONdetails).forEach(entry=>{
            // console.log(Number(entry.money));
            // console.log(Number($scope.pvItemPaidTotal));
            
            $scope.pvItemPaidTotal = Number((Number($scope.pvItemPaidTotal)+ Number(entry.money)).toFixed(2));
            $scope.total_tax += Number(entry.money*7/107);
        });
        // console.log($scope.JSONdetails);
        
        $scope.totalPaid =$scope.pvItemPaidTotal
        $scope.totalVat =$scope.total_tax 
            // console.log($scope.pvItemPaidTotal);

           
           
            $scope.pvItems["debit"] = $scope.pvItemDebit;
         
        }
        

        $scope.stopEvent = function(e){
            e.stopPropagation();
        }

  	});

</script>