<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script> var tax_invoice_detail = <?php echo $this->tax_invoice_detail; ?>; </script>
    <script type="text/javascript" src="/public/js/acc/edit_tax_invoice.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
        
    <div class="container pt-2">

        <h4 class="my-2">แก้ไขใบกำกับภาษี - Edit Tax Invoice</h4>

        <div class="row part mx-0 mt-3 p-3">

            <div class="col">

                <div class="row">
                    <div class="col-6">
                        <label for="rg_iv_name">ชื่อลูกค้า</label>
                        <input class="form-control" type="text" id="rg_iv_name" name="rg_iv_name" value="{{tax_invoice_detail.rg_iv_name}}">
                    </div>
                    <div class="col-6">
                        <label for="rg_iv_id_no">เลขประจำตัวผู้เสียภาษี</label>
                        <input class="form-control" type="text" id="rg_iv_id_no" name="rg_iv_id_no" value="{{tax_invoice_detail.rg_iv_id_no}}">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <label for="rg_iv_address">ที่อยู่</label>
                        <input class="form-control" type="text" id="rg_iv_address" name="rg_iv_address" value="{{tax_invoice_detail.rg_iv_address}}">
                    </div>
                </div>
                
            </div>

            <button class="btn btn-default btn-block mt-3" ng-click="edit_iv_validate()">ยืนยันการแก้ไขใบกำกับภาษี</button>

        </div>

    </div>
    
</body>
</html>