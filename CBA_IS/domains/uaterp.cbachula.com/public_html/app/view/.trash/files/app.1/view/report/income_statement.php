<!DOCTYPE html>
<html>
<body ng-controller="app_controller">

    <script type="text/javascript" src="/public/js/report/income_statement.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
    <script type="text/javascript" src="/public/data/expense_account_chart.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
    <script>var logs = <?php echo $this->logs; ?>;</script>
        
    <div class="container pt-2">

        <h4 class="my-2">งบกำไรขาดทุน - Income Statement</h4>{{revenues}}

        <h6 class="mb-3" style="text-align: right;">
            <button class="btn btn-default btn-sm" onclick="prev()"><</button>
            <!-- <script>
                var input = '';
                var month = input.substring(0, 2);
                var year = input.substring(2, 6);
                document.write('&nbsp;&nbsp;' + month + ' / ' + year + '&nbsp;&nbsp;');
            </script> -->
            <button class="btn btn-default btn-sm" onclick="next()">></button>
        </h6>
        
        <table class="table mt-2" style="width:100%; table-layout:fixed;">
            <tr>
                <td class="total"></td>
                <td class="total" style="vertical-align:bottom; text-align:right;"><b>โครงการพิเศษ 1</b></td>
                <td class="total" style="vertical-align:bottom; text-align:right;"><b>โครงการพิเศษ 2</b></td>
            </tr>
            <tr>
                <td colspan="3"><b>รายได้</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;รายได้จากค่าบริการ</td>
                <td class="value">{{revenues_1 | number:2}}</td>
                <td class="value">{{revenues_2 | number:2}}</td>
            </tr>
            <tr>
                <td class="total">&nbsp;&nbsp;&nbsp;&nbsp;<b>รวมรายได้</b></td>
                <td class="value total"><b>{{revenues_1 | number:2}}</b></td>
                <td class="value total"><b>{{revenues_2 | number:2}}</b></td>
            </tr>
            <tr>
                <td colspan="3"><b>ค่าใช้จ่าย</b></td>
            </tr>
            <tbody id="expense_rows"></tbody>
            <!-- <script>
                account_nos.forEach(account => {
                    document.write('\
                        <tr>\
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;' + account.account_name + '</td>\
                            <td class="value" id="' + account.account_no + '-1">0.00</td>\
                            <td class="value" id="' + account.account_no + '-2">0.00</td>\
                        </tr>\
                    ');
                });
            </script> -->
            <tr>
                <td class="total">&nbsp;&nbsp;&nbsp;&nbsp;<b>รวมค่าใช้จ่าย</b></td>
                <td class="value total"><b>{{expenses_1 | number:2}}</b></td>
                <td class="value total"><b>{{expenses_2 | number:2}}</b></td>
            </tr>
            <tr>
                <td class="total"><b>กำไรขั้นต้น</b></td>
                <td class="value total"><b>{{revenues_1 - expenses_1 | number:2}}</b></td>
                <td class="value total"><b>{{revenues_2 - expenses_2 | number:2}}</b></td>
            </tr>
            <!-- <script>

                var revenues = new Map();
                revenues.set('1', 0);
                revenues.set('2', 0);

                var expenses = new Map();
                expenses.set('1', 0);
                expenses.set('2', 0);

                income_statement.forEach(log => {
                    var value = Math.abs(Number(log.credit) - Number(log.debit));
                    if (log.account_no == '4-01') {
                        $('#4-01-' + log.project_no).html(value.toFixed(2));
                        var temp = value + Number(revenues.get(log.project_no));
                        revenues.set(log.project_no, temp);
                    } else {
                        $('#' + log.account_no + '-' + log.project_no).html(value.toFixed(2));
                        var temp = value + Number(expenses.get(log.project_no));
                        expenses.set(log.project_no, temp);
                    }
                });

                $('#revenue-1').html(revenues.get('1').toFixed(2));
                $('#revenue-2').html(revenues.get('2').toFixed(2));

                $('#expense-1').html(expenses.get('1').toFixed(2));
                $('#expense-2').html(expenses.get('2').toFixed(2));

                $('#margin-1').html((revenues.get('1') - expenses.get('1')).toFixed(2));
                $('#margin-2').html((revenues.get('2') - expenses.get('2')).toFixed(2));

            </script> -->
        </table>

    </div>

    <style>

        .value {
            text-align: right;
        }

        .table tbody+tbody {
            border-top: 2px solid transparent;
        }

        .table td, .table th {
            border-top: 1px solid transparent;
            border-bottom: 1px solid transparent;
            padding: 4px 0px;
            vertical-align: middle;
        }

        .total {
            border-top: 1px solid #dee2e6 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

    </style>

    <script>

        function prev() {
            if (Number(month) == 1) {
                month = '12';
                year = Number(year) - 1;
            } else {
                month = Number(month) - 1;
            }
            show(month, year);
        }

        function next() {
            if (Number(month) == 12) {
                month = '01';
                year = Number(year) + 1;
            } else {
                month = Number(month) + 1;
            }
            show(month, year);
        }

        function show(month, year) {
            location.assign('/report/income_statement/' + (Number(month) >= '10' ? '' : '0') + Number(month) + year);
        }

    </script>

</body>
</html>