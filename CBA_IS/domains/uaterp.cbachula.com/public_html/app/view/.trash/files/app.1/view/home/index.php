<!DOCTYPE html>
<html>

    <body>
            
        <div class="container pt-2">

            <?php

                $isPosition = function ($myPosition, $position) {
                    return $myPosition == $position || $myPosition == 'is';
                };

                $echoRow = function ($position, $row) {
                    echo '<h4 class="my-2">'.$position.'</h4>';
                    echo '<div class="row row-cols-2 row-cols-md-6 mt-2" style="padding: 0;" id="'.$row.'Row"></div>';
                };

                $poss = array (
                    array('spj', 'ฝ่ายโครงการพิเศษ - Special Project'),
                    array('fin', 'ฝ่ายการเงิน - Finance'),
                    array('acc', 'ฝ่ายบัญชี - Accounting'),
                    array('ifs', 'ฝ่ายระบบสารสนเทศ - Information System')
                );

                foreach ($poss as $pos) {
                    if($isPosition($this->position, $pos[0])) $echoRow($pos[1], $pos[0]);
                }

            ?>

            <script>

                add_module_link('spjRow', '/spj/registration_list', 'comments-dollar', 'รายการการสมัคร - List of Registration');
                add_module_link('finRow', '/fin/registration_list', 'comments-dollar', 'รายการการสมัคร - List of Registration');
                add_module_link('finRow', '/fin/transfer_report', 'sync', 'รายงานการโอนเงิน - Transfer Report');
                add_module_link('accRow', '/acc/tax_invoice_list', 'file-alt', 'รายการใบกำกับภาษี - List of Tax Invoice');
                
                add_module_link('spjRow', '/home/disbursement_voucher', 'file-invoice-dollar', 'ใบเบิกเงิน - Disbursement Voucher (DV)');
                add_module_link('finRow', '/home/disbursement_voucher', 'file-invoice-dollar', 'ใบเบิกเงิน - Disbursement Voucher (DV)');
                add_module_link('accRow', '/home/disbursement_voucher', 'file-invoice-dollar', 'ใบเบิกเงิน - Disbursement Voucher (DV)');
                add_module_link('accRow', '/acc/vat_payment', 'percentage', 'การชำระภาษีมูลค่าเพิ่ม - VAT Payment');
                add_module_link('spjRow', '/home/disbursement_voucher_list', 'file-archive', 'รายการใบเบิกเงิน - List of Disbursement Voucher');
                add_module_link('finRow', '/fin/disbursement_voucher_list', 'file-archive', 'รายการใบเบิกเงิน - List of Disbursement Voucher');
                add_module_link('accRow', '/acc/disbursement_voucher_list', 'file-archive', 'รายการใบเบิกเงิน - List of Disbursement Voucher');
                add_module_link('finRow', '/fin/payment_voucher_list', 'money-check-alt', 'รายการใบสำคัญจ่าย - List of Payment Voucher');
                add_module_link('accRow', '/acc/payment_voucher_list', 'money-check-alt', 'รายการใบสำคัญจ่าย - List of Payment Voucher');
                
                add_module_link('spjRow', '/home/financial_statements', 'th-list', 'รายงานทางการเงิน - Financial Reports');
                add_module_link('finRow', '/home/financial_statements', 'th-list', 'รายงานทางการเงิน - Financial Reports');
                add_module_link('accRow', '/home/financial_statements', 'th-list', 'รายงานทางการเงิน - Financial Reports');

                add_module_link('ifsRow', '/home/disbursement_voucher', 'file-invoice-dollar', 'ใบเบิกเงิน - Disbursement Voucher (DV)');
                add_module_link('ifsRow', '/home/disbursement_voucher_list', 'file-archive', 'รายการใบเบิกเงิน - List of Disbursement Voucher');
                
            </script>

        </div>
        
    </body>

    <style>
        .itemCol { color: #666; background-color: #f8f9fa; border: 1px solid #ddd; text-decoration: none; }
        .itemCol:hover { color: #222; transform: translate(0,-4px); border: 1px solid #999; text-decoration: underline; }
    </style>

</html>

