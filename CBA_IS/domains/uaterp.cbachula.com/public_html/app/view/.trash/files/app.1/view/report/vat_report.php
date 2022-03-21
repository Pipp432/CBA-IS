<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script> var vats = <?php echo $this->vats; ?>; </script>
        
    <div class="container pt-2">

        <h4 class="my-2">รายงานภาษีมูลค่าเพิ่ม - VAT Report</h4>

        <table class="table" style="width:100%; table-layout:fixed;">
            <tr>
                <td style="vertical-align:bottom;"><b>เดือน/ปีภาษี</b></td>
                <td style="vertical-align:bottom; text-align:right;"><b>จำนวนภาษีขาย</b></td>
                <td style="vertical-align:bottom; text-align:right;"><b>จำนวนภาษีซื้อ</b></td>
                <td style="vertical-align:bottom; text-align:right;"><b>จำนวนภาษีมูลค่าเพิ่ม</b></td>
                <td style="vertical-align:bottom; text-align:right;"><b>ค้างชำระ</b></td>
            </tr>
            <script>

                var getMonths = function(startDate, endDate){

                    var months = [];
                    var startDate = new Date(startDate);
                    var endDate = new Date(endDate);

                    while (startDate >= endDate) {

                        var firstDay = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
                        var lastDay = new Date(startDate.getFullYear(), startDate.getMonth() - 1, 0);
                        
                        months.push((startDate.getMonth() + 1) + '_' + startDate.getFullYear());
                        startDate.setMonth(startDate.getMonth() - 1);

                    }
                    
                    return months;

                };

                var months = getMonths(new Date(),'1-1-2021');

                months.forEach(value => {
                    document.write('<tr>\
                            <td>' + value + '</td>\
                            <td id="' + value + '_sales" class="value">0.00</td>\
                            <td id="' + value + '_income" class="value">0.00</td>\
                            <td id="' + value + '_vat" class="value">0.00</td>\
                            <td id="' + value + '_not_paid" class="value">0.00</td>\
                        </tr>');
                });

                vats.forEach(value => {
                    if (value.account_no == '6-01') {
                        $('#' + value.month + '_income').html(value.debit);
                    } else if (value.account_no == '6-02') {
                        $('#' + value.month + '_sales').html(value.credit);
                    } else if (value.account_no == '2-03') {
                        $('#' + value.month + '_not_paid').html(value.credit);
                    }
                })

            </script>

        </table>

    </div>

    <style>
        .value {
            text-align: right;
        }
    </style>

    <script>

    </script>

</body>
</html>