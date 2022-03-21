<!DOCTYPE html>
<html>

    <body>
            
        <div class="container pt-2">

            <?php

                $isPosition = function ($myPosition, $position) {
                    return $myPosition == $position || $myPosition == 'adm';
                };

                $echoRow = function ($position, $row) {
                    echo '<h4 class="my-2">'.$position.'</h4>';
                    echo '<div class="row row-cols-2 row-cols-md-6 mt-2" style="padding: 0;" id="'.$row.'Row"></div>';
                };

                $poss = array (
                    array('spj', 'ฝ่ายขายและการตลาด - Sales & Marketing'),
                    array('acc', 'ฝ่ายบัญชี - Accounting'),
                    array('fin', 'ฝ่ายการเงิน - Finance')
                );

                foreach ($poss as $pos) {
                    if($isPosition($this->position, $pos[0])) $echoRow($pos[1], $pos[0]);
                }

            ?>

            <script>

                addModuleLink('spjRow', '/', 'file-alt', 'ใบแจ้งหนี้ - Sales Order (SO)');
                addModuleLink('finRow', '/', 'money-bill', 'ยืนยันการจ่ายเงิน');
                addModuleLink('accRow', '/', 'folder-open', 'บันทึกการส่งใบทวิ 50');
                addModuleLink('accRow', '/', 'comment-dollar', 'ใบกำกับภาษี - Tax Invoice (IV)');

                addModuleLink('spjRow', '/home/cash_disbursement', 'comment-dollar', 'ใบเบิกเงิน - Cash Disbursement (CD)');
                addModuleLink('finRow', '/home/cash_disbursement', 'comment-dollar', 'ใบเบิกเงิน - Cash Disbursement (CD)');
                addModuleLink('accRow', '/home/cash_disbursement', 'comment-dollar', 'ใบเบิกเงิน - Cash Disbursement (CD)');
                addModuleLink('finRow', '/', 'comment-dollar', 'อนุมัติใบเบิกเงิน');
                addModuleLink('accRow', '/', 'money-check-alt', 'ใบสำคัญจ่าย - Payment Voucher (PV)');
                
            </script>

        </div>
        
    </body>

    <style>
        .itemCol { color: #666; background-color: #f8f9fa; border: 1px solid #ddd; text-decoration: none; }
        .itemCol:hover { color: #222; transform: translate(0,-4px); border: 1px solid #999; text-decoration: underline; }
    </style>

</html>

