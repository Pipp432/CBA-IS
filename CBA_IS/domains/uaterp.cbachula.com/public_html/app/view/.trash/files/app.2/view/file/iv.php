<!DOCTYPE html>
<html>

<body>

    <script>
        app.controller('app_controller', function($scope) {
            $scope.get_detail = function() {
                $scope.detail = <?php echo $this->iv; ?>[0];
                $scope.project_no = $scope.detail.iv_no.substring(0, 1);
                $scope.company_id_no = $scope.project_no == '1' ? '099-2-00406904-1' : '099-2-00406904-1';
                $scope.iv_total_price_text = num_to_thai(Number($scope.detail.iv_total_price));
            }
        });
    </script>

    <div class="container mt-3" ng-controller="app_controller" ng-init="get_detail()">

        <div class="row px-2 mt-2">
            <div class="col-9 pl-0 pr-3">
                <div class="row">
                    <div class="col-2 pr-0">
                        <img src="/public/img/logo_icon2.png" alt="bizcube-logo" style="width:100%;">
                    </div>
                    <div class="col-10">
                        <p class="my-0">
                            <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2563 (โครงการพิเศษ {{project_no}})</b><br>
                            <small>อาคารไชยยศสมบัติ 1 เลขที่ 254 ชั้นใต้ดิน ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330</small><br>
                            โทร. 0-2218-5746-9 โทรสาร. 0-2218-5762<br>
                            เลขประจำตัวผู้เสียภาษี {{company_id_no}} (สำนักงานใหญ่)<br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 px-0">
                <h5 style="text-align: right;">ต้นฉบับ</h5>
                <h5 style="text-align: right;"><b>เลขที่ {{detail.iv_no}}</b></h5>
                <h6 style="text-align: right;">วันที่ {{detail.iv_date}}</h6>
                <h6 style="text-align: right; font-size: 16px;">เอกสารออกเป็นชุด</h6>
            </div>
        </div>
        
        <h4 class="mt-3 mb-2" style="text-align: center;"><b>ใบกำกับภาษี / ใบเสร็จรับเงิน<br><small>Tax Invoice / Cash Receipt</small></b></h4>
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td><b>ชื่อลูกค้า </b> {{detail.rg_iv_name}}</td>
                        <td><b>เลขประจำตัวผู้เสียภาษี</b> {{detail.rg_iv_id_no}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>ที่อยู่</b> {{detail.rg_iv_address}}</td>
                    </tr>
                </table>
            </div>
        </div>  

        <div class="row px-2 mt-2">
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th style="width:8%;">ลำดับ</th>
                    <th style="width:50%;">รายละเอียด</th>
                    <th style="width:8%;">จำนวน</th>
                    <th style="width:17%;">ราคา/หน่วย (บาท)<br><small>รวมภาษีมูลค่าเพิ่ม<small></th>
                    <th style="width:17%;">จำนวนเงิน (บาท)</th>
                </tr>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: left;">{{detail.course_name}}</td>
                    <td style="text-align: right;">{{detail.count}}</td>
                    <td style="text-align: right;">{{detail.rg_item_price | number:2}}</td>
                    <td style="text-align: right;">{{detail.rg_total_price | number:2}}</td>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: left;">จำนวนเงิน (ตัวอักษร) : {{iv_total_price_text}}</th>
                    <th colspan="2" style="text-align: right;">ส่วนลด</th>
                    <th style="text-align: right;">({{detail.rg_total_discount | number:2}})</th>
                </tr>
                <tr>
                    <th colspan="2" rowspan="{{detail.rg_type == '1' ? '3' : '4'}}">
                        <div class="row">
                            <div class="col">
                                ลงชื่อ .............................................<br>
                                บัญชีผู้ออกเอกสาร<br>
                                วันที่ _____/_____/_____
                            </div>
                            <div class="col">
                                ลงชื่อ .............................................<br>
                                ผู้รับบริการ<br>
                                วันที่ _____/_____/_____
                            </div>
                        </div>
                    </th>
                    <th colspan="2" style="text-align: right;">มูลค่าสินค้า/บริการ</th>
                    <th style="text-align: right;">{{detail.iv_total_price_no_vat | number:2}}</th>
                </tr>
                <tr ng-show="detail.rg_type != '1'">
                    <th colspan="2" style="text-align: right;">ภาษีหัก ณ ที่จ่าย</th>
                    <th style="text-align: right;">({{detail.iv_total_price_wht | number:2}})</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">ภาษีมูลค่าเพิ่ม 7%</th>
                    <th style="text-align: right;">{{detail.iv_total_price_vat | number:2}}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right;">จำนวนเงินรวม</th>
                    <th style="text-align: right;">{{detail.iv_total_price | number:2}}</th>
                </tr>
            </table>
        </div> 
    
    </div>

</body>

</html>

<style>
    body, h1, h2, h3, h4, h5, h6, p { font-family: 'Sarabun', sans-serif; }
    table, th, td { border: 1px solid black; padding: 5px; }
    th { text-align: center; }
</style>