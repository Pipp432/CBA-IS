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
                            <option value="PB">จ่าย Supplier</option>
                            <option value="PC">ค่าใช้จ่าย(PV-C)</option>
                            <option value="PD">PV-D คืนเงินลูกค้า</option>
                        </select>
                    </div>
                    <div class="col-md-3" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType!=''">
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
                    <div class="col-md-6" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType!=''">
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
                        <label for="pvItemIV">เลขที่ใบกำกับภาษี</label>
                        <input type="text" class="form-control" id="pvItemIV" ng-model="pvItems.tax_number">
                    </div>
                    <div class="col-md-3">
                        <label for="pvItemPaidTotal">จำนวนเงิน</label>
                        <input type="text" class="form-control" id="pvItemPaidTotal" ng-model="pvItemPaidTotal">
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="vatCheck" ng-model="vat" ng-click="vat_check()">
                            <label class="custom-control-label" for="vatCheck">ขอคืนภาษี</label>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 mt-2">
                    <div class="col-md-6">
                        <label for="pvItemDetail">รายละเอียด</label>
                        <input type="text" class="form-control" id="pvItemDetail" ng-model="pvItemDetail">
                    </div>
                    <div class="col-md-2">
                        <label for="pvItemDebit">เดบิต</label>
                        <input type="text" class="form-control" id="pvItemDebit" ng-model="pvItemDebit">
                    </div>
                    <div class="col-md-4">
                        <label for="addPvItemButton" style="color:white;">.</label>
                        <button type="button" class="btn btn-default btn-block my-0" ng-click="addPvItem()">ยืนยันรายการ</button>
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
                        </tr>
                        <tr ng-show="isLoad">
                            <th colspan="5">
                                <h6 class="my-0"><span class="spinner-border" role="status" aria-hidden="true" style="width:25px; height:25px;"></span> กำลังโหลด ...</h6>
                            </th>
                        </tr>
                        <tr ng-repeat="rrcinopv in rrcinopvs | unique:'ci_no' | filter:{supplier_no:supplierNoForFilter, ci_no:pvItemRR}" ng-click="getrrcinopvDetail(rrcinopv)">
                            <td>
                                {{rrcinopv.ci_no}}<br>
                                <a href="/acc/payment_voucher/get_invoice/{{rrcinopv.ci_no}}" target="_blank">ดูใบวางบิล</a>
                                <a href="/acc/payment_voucher/get_IVPC_Files/bill/{{rrcinopv.ci_no}}" target="_blank">ดูใบแจ้งหนี้</a>
                                <a href="/acc/payment_voucher/get_IVPC_Files/tax/{{rrcinopv.ci_no}}" target="_blank">ดูใบกำกับภาษี</a>
                                <a href="/acc/payment_voucher/get_IVPC_Files/debt/{{rrcinopv.ci_no}}" target="_blank">ดูใบลดหนี้</a>
                            </td>
                            <td>{{rrcinopv.po_no}}</td>
                            <td>{{rrcinopv.ci_date}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="rrcinopv_item in rrcinopvs" ng-show="rrcinopv_item.ci_no===rrcinopv.ci_no">{{rrcinopv_item.product_name}} (x{{rrcinopv_item.quantity}})</li>
                            </ul></td>
                            <td></td>
                            <td style="text-align: right;">{{rrcinopv.confirm_total | number:2}}</td>
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
                            <th>เลข EX</th>
                            <th>วันที่</th>
                            <th>ใบสำคัญสั่งจ่าย</th>
                            <th>ใบกำกับภาษี / บิลเงินสด / ใบเสนอราคา</th>
                            <th>ผู้ขอเบิก</th>
                           
                        </tr>
                        <!-- <tr ng-repeat="pvc in PVCs | filter:{form_no:pvItemRR} |filter:{ws_type:'3'}" ng-click="getWsDetail(ws)"> -->
                        <tr ng-repeat="re_req in ReReqs track by $index" ng-click="getReReqDetail(re_req,$index)">
                            <td style="text-align: center;">{{re_req.ex_no}}</td>
                            <td style="text-align: center;">{{re_req.withdraw_date}}</td>
                            <!-- <td><a href="/acc/payment_voucher/get_PVCs_form/{{pvc.PVC_No}}" target="_blank">{{pvc.PVC_No}}</a></td> -->
                            <td style="text-align: center;">
                            <a href="/file/re_req/{{re_req.re_req_no}}" target="_blank">{{re_req.re_req_no}}</a>
                            </td>
                            <td style="text-align: center;">
                                <a href="/acc/payment_voucher/get_quotation/{{re_req.re_req_no}}" target="_blank">{{re_req.quotation_name}}</a><br>  
                            </td>
                            <td style="text-align: center;">{{re_req.withdraw_name}}</td>
                            
                            <!-- <td><a href="/acc/payment_voucher/get_ws_iv/{{ws.ws_no}}/slip" target="_blank">{{ws.slip_name}}</a></td>
                            <td>{{ws.employee_id}} {{ws.employee_nickname_thai}}</td> -->
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING PV ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <?php print_r($pvItems) ?>
		<?php echo $pvItems ?>
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="selectedPaymentType!= 'PA' && selectedPaymentType!= 'PD' && selectedPaymentType!=''">
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
                            <th colspan="2">วันที่</th>
                            <th>เดบิต</th>
                            <th>เลขที่ใบกำกับภาษี</th>
                            <th>รายละเอียด</th>
                            <th ng-show="selectedPaymentType=='PB'">เลขที่ RR/CI</th>
                            <th ng-show="selectedPaymentType=='PA' || selectedPaymentType=='PC'">เลขที่ใบเบิกค่าใช้จ่าย</th>
                            <th>ภาษี</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="pvItem in pvDetails track by $index">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPvItem(pvItem)"></i></td>
                            <td>{{pvItem.withdraw_date}}</td>
                            <td>{{pvItemDebit}}</td>
                            <td>{{pvItem.tax_number}}</td>
                            <td>{{pvItem.pv_details}}</td>
                            <td>{{pvItem.re_req_no}}</td>
                            <td style="text-align: right;">{{(pvItemPaidTotal)*7/107| number:2}}</td>
                            <td style="text-align: right;">{{pvItemPaidTotal | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="7">ภาษีสุทธิ</th>
                            <th style="text-align: right;">{{(pvItemPaidTotal)*7/107 | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="7">รวมสุทธิ</th>
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


        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <!-- ADDING PV-D  -->
        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show = "selectedPaymentType == 'PD' ">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="filter_anything">PVD no</label>
                        <input type="text" class="form-control" id="filter_anything" ng-model="filter_anything" style="text-transform:uppercase">
                    </div>
                </div>
                <div class="col-md-8">
                <h4 class="my-1" ng-show="PVDs.length != 0">รายละเอียดใบ PV-D</h4>
                </div>
                <table class="table table-hover my-1" ng-show="PVDs.length == 0">
                    <tr>
                        <th>ยังไม่มีการขอใบ PVD</th>
                    </tr>
                </table>
                <table class="table table-hover my-1" ng-show="PVDs.length != 0">
                    <tr>
                        <th class = "center_cell">PVD no</th>
                        <th class = "center_cell">employee id</th>
                        <th class = "center_cell">employee line</th>
                        <th class = "center_cell">total_amount</th>
                        <th class = "center_cell">vat id</th>
                        <th class = "center_cell">sox no</th>
                        <th class = "center_cell">invoice id</th>
                        <th class = "center_cell">note</th>
                    </tr>
                    <tr ng-repeat = "PVD in PVDs | unique:'pvd_no'  | filter:{pvd_no:filter_anything}" ng-click="selectPVD(PVD)" ng-show = "selected_PVD.length == 0">
                        <td class = "center_cell">{{PVD.pvd_no}}</td>
                        <td class = "center_cell">{{PVD.employee_id}}</td>
                        <td class = "center_cell">{{PVD.employee_line}}</td>
                        <td class = "center_cell">{{PVD.total_amount}}</td>
                        <td class = "center_cell">{{PVD.vat_id}}</td>
                        <td class = "center_cell">{{PVD.sox_no}}</td>
                        <td class = "center_cell">{{PVD.invoice_no}}</td>
                        <td class = "center_cell">{{PVD.note}}</td>
                    </tr>
                    <tr ng-show = "selected_PVD.length != 0">
                        <td class = "center_cell"><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPVD()"></i> {{selected_PVD.pvd_no}}</td>
                        <td class = "center_cell">{{selected_PVD.employee_id}}</td>
                        <td class = "center_cell">{{selected_PVD.employee_line}}</td>
                        <td class = "center_cell">{{selected_PVD.total_amount}}</td>
                        <td class = "center_cell">{{selected_PVD.vat_id}}</td>
                        <td class = "center_cell">{{selected_PVD.sox_no}}</td>
                        <td class = "center_cell">{{selected_PVD.invoice_no}}</td>
                        <td class = "center_cell">{{selected_PVD.note}}</td>
                    </tr>
                </table>
                <div ng-show = "selected_PVD.length != 0">
                    <hr>
                    <div class="col-md-12">
                        <h4 class="my-1">เพิ่มรายละเอียดใบสำคัญสั่งจ่าย&nbsp;</h4>
                    </div>
                    <br>
                    <div class="col-md-3">
                            <label for="checkboxPVD">แก้ไขข้อมูล</label>
                            <input type="checkbox" id = "checkboxPVD" ng-change = 'updatePVD()' ng-model = 'selected_PVD.editing'/> 
                    </div>    
                    <div class="row mx-0">
                        <div class="col-md-3">
                            
                            <label for="pvNameTextboxPVD">สั่งจ่าย</label>
                            <input type="text" class="form-control" id="pvNameTextboxPVD" ng-model="selected_PVD.recipent" ng-disabled="!selected_PVD.editing">
                            <select class="form-control" ng-model="selected_PVD.company_code" ng-change="selectCompanypvd()">
                                <option value="">สั่งจ่ายในนาม</option>
                                <option value='1'>โครงการ 1</option>
                                <option value='2'>โครงการ 2</option>
                                <option value='3'>โครงการ 3</option>
                                <option value='9'>โครงการพิเศษ 1</option>
                                <option value='8'>โครงการพิเศษ 2</option>
                            </select>
                        </div>    
                        <div class="col-md-6">
                            <label for="locationPVD">ที่อยู่</label>
                            <input type="text" class="form-control" id="locationPVD" ng-model="selected_PVD.location" ng-disabled="!selected_PVD.editing">
                        </div>   
                    </div>  

                    <div class="row mx-0">
                        <div class="col-md-3">
                            <label for="bankPVD">ธนาคาร</label>
                            <input type="text" class="form-control" id="bankPVD" ng-model="selected_PVD.bank" ng-disabled="!selected_PVD.editing">
                        </div>
                        <div class="col-md-3">
                            <label for="bankNoPVD">เลขธนาคาร</label>
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
                    <div class="col-md-12">
                        <label for="filter_pva_no">PVA no</label>
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
                        <th class = "center_cell">PVA no</th>
                        <th class = "center_cell">date time</th>
                        <th class = "center_cell">total paid</th>
                        <th class = "center_cell">product names</th>
                    </tr>
                    <tr ng-repeat = "PVA in PVAs | unique:'pv_no'  | filter:{pv_no:filter_pva_no}" ng-click="selectPVA(PVA)" ng-show = "selected_PVA.length == 0">
                        <td class = "center_cell">{{PVA.pv_no}}</td>
                        <td class = "center_cell">{{PVA.pv_date}} {{PVA.pv_time}}</td>
                        <td class = "center_cell">{{PVA.total_paid}}</td>
                        <td class = "center_cell">{{PVA.product_names}}</td>
                    </tr>
                    <tr ng-show = "selected_PVA.length != 0">
                        <td class = "center_cell"><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropPVA()"></i> {{selected_PVA.pv_no}}</td>
                        <td class = "center_cell">{{selected_PVA.pv_date}} {{selected_PVA.pv_time}}</td>
                        <td class = "center_cell">{{selected_PVA.total_paid}}</td>
                        <td class = "center_cell">{{selected_PVA.product_names}}</td>
                    </tr>
                </table>
                <div ng-show = "selected_PVA.length != 0">
                    <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postPVA()" ng-disabled="selected_PVA.editing">ยืนยัน PVA</button>
                    </div>

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
            addModal('formValidate6', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'ยังไม่ได้เพิ่มว่าสั่งจ่ายใคร');
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
                $http.get('/acc/payment_voucher/get_PVA').then(function(response){$scope.PVAs = response.data; $scope.isLoad = false;});
            } else if($scope.selectedPaymentType === 'PB') {
                $http.get('/acc/payment_voucher/get_rr_ci_no_pv').then(function(response){$scope.rrcinopvs = response.data; $scope.isLoad = false;});
                $('#pvNameLabel').html('จ่าย Supplier');
                $("#pvAddressTextbox").prop("disabled", true);
            } else if($scope.selectedPaymentType==='PC') {
                // $http.get('/acc/payment_voucher/get_ws').then(function(response){$scope.wss = response.data; $scope.isLoad = false;});
                $http.get('/acc/payment_voucher/get_ReReqs').then(function(response){$scope.ReReqs = response.data; $scope.pvItem = $scope.ReReqs ;$scope.pvItems = $scope.ReReqs;$scope.isLoad = false;console.log($scope.ReReqs)});
                
                
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
                if(JSON.parse($scope.selectedSupplier).vat_type == 1) {
                    $scope.vat = true;
                } else {
                    $scope.vat = false;
                }
                $("#vatCheck").prop("disabled", true);
            }
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
                    pvd_no : $scope.selected_PVD.pvd_no,
                    company_code : $scope.selected_PVD.company_code,
                    recipent : $scope.selected_PVD.recipent,
                    bank : $scope.selected_PVD.bank,
                    bank_no : $scope.selected_PVD.bank_no,
                    address : $scope.selected_PVD.location,     
                }, function(data) {
                    addModal('pvdeditsuccessModalupdate', 'PV-D', 'edit ' + $scope.selected_PVD.pvd_no.toUpperCase() +  data);
                    $('#pvdeditsuccessModalupdate').modal('toggle');
                });
            }
        }

        $scope.postPVD = function() {
            if(!$scope.selected_PVD.editing) {
                $.post("/acc/payment_voucher/post_PVD", {
                    post : true,
                    pvd_no : $scope.selected_PVD.pvd_no,
                    company_code : $scope.selected_PVD.company_code,
                }, function(data) {
                    addModal('pvdUpdateSuccessModalupdate', 'PV-D', 'insert ' + $scope.selected_PVD.pvd_no.toUpperCase() +  data);
                    $('#pvdUpdateSuccessModalupdate').modal('toggle');
                    $('#pvdUpdateSuccessModalupdate').on('hide.bs.modal', function (e) {
                        location.reload();
                    });
                });
            }
        }

        $scope.selectPVA = function($PVA) {
            $scope.selected_PVA = $PVA;
        }

        $scope.dropPVA = function() {
            $scope.selected_PVA = [];
        }

        $scope.postPVA = function() {
            $.post("/acc/payment_voucher/post_PVA", {
                post : true,
                pv_no : $scope.selected_PVA.pv_no,
            }, function(data) {
                addModal('pvaUpdateSuccessModalupdate', 'PV-A', 'confirm ' + $scope.selected_PVA.pv_no +  data);
                $('#pvaUpdateSuccessModalupdate').modal('toggle');
                $('#pvaUpdateSuccessModalupdate').on('hide.bs.modal', function (e) {
                    window.open('/file/pva/' + $scope.selected_PVA.pv_no);
                    location.reload();
                });
            });
        }
        
        $scope.getrrcinopvDetail = function(rrcinopv) {
            $("#pvItemRR").prop("disabled", true);
            $("#pvItemIV").prop("disabled", true);
            $("#pvItemPaidTotal").prop("disabled", true);
            $scope.pvItemRR = rrcinopv.ci_no;
            $scope.pvItemIV = rrcinopv.invoice_no;
            $scope.pvItemPaidTotal = rrcinopv.confirm_total;
            $scope.ci_no = rrcinopv.ci_no;
        }
        
        $scope.getWsDetail = function(ws) {
            $("#pvItemRR").prop("disabled", true);
            $scope.pvItemRR = ws.form_no;
        }
        
        $scope.addPvItem = function() {
            
            if (($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') && ($scope.pvName==='' || $scope.pvAddress==='')) {
                $('#formValidate6').modal('toggle');
            // } else if (($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') && $scope.pvItemIV==='') {
            //     $('#formValidate7').modal('toggle');
            } else if (($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') && $scope.pvItemPaidTotal==='') {
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
                { let tax= ($scope.tax ? "Yes" : "No")
                    console.log(pvItemDateStr,$scope.pvItemDebit,$scope.pvItemPaidTotal,$scope.pvName,$scope.pvAddress,company )
                    console.log($scope.vat, tax)
                    $.post(`/acc/payment_voucher/confirm/${$scope.pvItemRR}`,{
                        "debit" : String($scope.pvItemDebit),
                        "cr_name": String($scope.pvItemRR),
                        "pv_date" : pvItemDateStr,
                        "total_paid" : String($scope.pvItemPaidTotal),
                        "pv_name" : String($scope.pvName),
                        "pv_address" : String($scope.pvAddress),
                        "details": String($scope.pvItemDetail),
                        "selected_company":String($scope.selectedCompany),
                        "confirm":"1",
                        "return_tax": tax
                    }).done(function(data,status){
                        console.log(data)
                        console.log(status)
                        
                    }).fail(function(e){
                        console.log(e)
                    });
                }


                $scope.pvItems.push({
                    "debit" : $scope.pvItemDebit,
                    "date" : pvItemDateStr,
                    "iv_no" : $scope.pvItemIV,
                    "detail" : $scope.pvItemDetail,
                    "rr_no" : $scope.pvItemRR,
                    "total_paid" : parseFloat($scope.pvItemPaidTotal),
                    "vat" : parseFloat(($scope.vat) ? $scope.pvItemPaidTotal * 7 / 107 : 0)
                });

                
                $scope.clearPvItem();
                $scope.calculateTotalPrice();

                               
            }
            location.reload();
           
        }
        
        $scope.clearPvItem = function() {
            $scope.pvItemRR = '';
            $scope.pvItemDetail = '';  
            if($scope.selectedPaymentType==='PA' || $scope.selectedPaymentType==='PC') $scope.pvItemDebit = '';  
            $scope.pvItemIV = '';  
            $scope.pvItemPaidTotal = '';
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
            $scope.totalPaid = 0;
            $scope.totalVat = 0;
            angular.forEach($scope.pvItems, function(value, key) {
                $scope.totalPaid += value.total_paid;
                $scope.totalVat += value.vat;
            });
        }
        
        $scope.postPvItems = function() {
            
            var dueDateStr = $scope.dueDate.getFullYear() + '-' + 
                                (($scope.dueDate.getMonth()+1) < 10 ? '0' : '') + ($scope.dueDate.getMonth()+1) + '-' + 
                                ($scope.dueDate.getDate() < 10 ? '0' : '') + $scope.dueDate.getDate();
            
            if($scope.selectedPaymentType==='') {
                $('#formValidate4').modal('toggle');
            } else if ($scope.pvItems.length===0) {
                $('#formValidate5').modal('toggle');
            } else if($scope.selectedPaymentType==='PA') {
                
                if($scope.selectedCompany==='') {
                    console.log("A")
                    $('#formValidate9').modal('toggle');
                } else {
                    $.post("/acc/payment_voucher/post_pva", {
                        post : true,
                        pv_name : $scope.pvName,
                        pv_address : $scope.pvAddress,
                        company_code : $scope.company_code,
                        pvItems : JSON.stringify(angular.toJson($scope.pvItems)),
                        totalPaid : $scope.totalPaid,
                        totalPaidThai : NumToThai($scope.totalPaid),
                        totalVat : $scope.totalVat,
                        dueDate : dueDateStr,
                        bank : $scope.bank
                    }, function(data) {
                        addModal('successModal', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'บันทึก ' + data + ' สำเร็จ');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            window.location.assign('/');
                        });
                    });
                }
                
            } else if($scope.selectedPaymentType==='PB') {
                
                if($scope.selectedSupplier==='') {
                    $('#formValidate3').modal('toggle');
                } else {
                    $.post("/acc/payment_voucher/post_pvb", { 
                        post : true,
                        supplier_no : $scope.supplierNoForFilter,
                        pv_name : $scope.pvName,
                        pv_address : $scope.pvAddress,
                        company_code : $scope.pvItems[0].rr_no.substr(0,1),
                        pvItems : JSON.stringify(angular.toJson($scope.pvItems)),
                        totalPaid : $scope.totalPaid,
                        totalPaidThai : NumToThai($scope.totalPaid),
                        totalVat : $scope.totalVat,
                        dueDate : dueDateStr,
                        bank : $scope.bank,
                        ci_no : $scope.ci_no,
                    }, function(data) {
                        addModal('successModal', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'บันทึก ' + data + ' สำเร็จ');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            window.location.assign('/');
                        });
                    });
                }
                
            } else if($scope.selectedPaymentType==='PC') {
                
                // if($scope.otherExpense) $scope.company_code = '3';
                
                if($scope.selectedCompany==='') {
                    console.log("C")
                    $('#formValidate9').modal('toggle');
                } else {
                    var d = new Date();
                    var month = d.getMonth()+1;
                    var day = d.getDate();

                    var output = d.getFullYear() + '/' +
                        (month<10 ? '0' : '') + month + '/' +
                        (day<10 ? '0' : '') + day;
                        


                    $.post("/acc/payment_voucher/post_pvc", {
                        post : true,
                        pv_name : $scope.pvName,
                        pv_address : $scope.pvAddress,
                        pv_date: output,
                        pv_detail:$scope.pvItemDetail,
                        ex_no:$scope.pvItemRR,
                        re_req_no:$scope.pvDetails[0].re_req_no,
                        company_code : $scope.company_code,
                        pvItems : JSON.stringify(angular.toJson($scope.pvItems)),
                        totalPaid : $scope.pvItemPaidTotal,
                        totalPaidThai : NumToThai($scope.pvItemPaidTotal),
                        totalVat : $scope.totalVat,
                        dueDate : dueDateStr,
                        bank : $scope.bank
                    }, function(data) {
                        console.log(JSON.stringify(angular.toJson($scope.pvItems)))
                        console.log(data)
                        // addModal('successModal', 'ใบสำคัญสั่งจ่าย / Payment Voucher (PV)', 'บันทึก ' + data + ' สำเร็จ');
                        // $('#successModal').modal('toggle');
                        // $('#successModal').on('hide.bs.modal', function (e) {
                        //     // window.location.assign('/');
                        // });
                    }).fail(function(error){
                        console.log(error)
                    });
                    
                }
                
            } else if($scope.selectedPaymentType==='PD') {
                
            }
            // addModal('successModal', 'Payment Voucher', 'สำเร็จ');
            //             $('#successModal').modal('toggle');
            //             $('#successModal').on('hide.bs.modal', function (e) {
            //                window.location.assign("https://uaterp.cbachula.com/"); 
            //             });
            
            
        }

        $scope.getReReqDetail = function(re_req,index) {
           
            $("#pvItemRR").prop("disabled", true);
            $scope.pvItemRR = re_req.ex_no;
            console.log($scope.pvItemRR);
            $scope.pvDetails = JSON.parse($scope.ReReqs[index]["details"])
            console.log( $scope.pvDetails[0].money)
            $scope.pvItems = $scope.ReReqs[index]
            console.log( $scope.pvItems);
            $scope.pvItemPaidTotal = $scope.pvDetails[0].money;
            
            ($scope.ReReqs).forEach(data=>{
                if(data.ex_no===$scope.pvItemRR){
                $scope.pvDetails = [data];
                
            }
            $scope.pvItemDebit = $scope.pvDetails[0].debit;
            $scope.pvItemDetail = $scope.pvDetails[0].pv_details
            
            
        })
        $scope.totalPaid = Number($scope.pvDetails[0].total_paid);
            $scope.totalVat = Number($scope.pvDetails[0].total_paid)*7/107;
            console.log($scope.totalPaid)
         
            
        }

  	});

</script>