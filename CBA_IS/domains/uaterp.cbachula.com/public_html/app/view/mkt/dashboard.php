<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        <div class="row row-cols-2 row-cols-md-4 mt-2 p-0">
            
            <div class="col p-0"><a href="#so">
                <div class="card text-white bg-secondary m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">SO</h5>
                    </div>
                </div>
            </a></div>
            
            <div class="col p-0"><a href="#po">
                <div class="card text-white bg-info m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">PO</h5>
                    </div>
                </div>
            </a></div>
            
            <div class="col p-0"><a href="#cs">
                <div class="card text-white bg-primary m-2">
                    <div class="card-body">
                        <h5 class="card-title my-0">CS</h5>
                    </div>
                </div>
            </a></div>
            
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <h4 class="mt-3 mb-0" id="so">Sales Order</h4>
        
        <div class="row row-cols-1 row-cols-md-3 mt-2 p-0" id="soRow"></div>
            
        <script>
            
            var sos = <?php echo $this->sos; ?>;
            var distinctSo = [];
            
            for (var i = 0; i < sos.length; i++) {
                
                if(!distinctSo.includes(sos[i].so_no)) {
                    
                    var spanText = '';
                    if(sos[i].cancelled == 1) spanText = '<span class="text-danger"><i class="fa fa-square" aria-hidden="true"></i></span> ยกเลิก</h6>';
                    else if(sos[i].invoice_no == null && sos[i].cancelled == 0) spanText = '<span class="text-dark"><i class="fa fa-square" aria-hidden="true"></i></span> ยังไม่ชำระเงิน</h6>';
                    else if(sos[i].invoice_no != null && sos[i].cancelled == 0) spanText = '<span class="text-primary"><i class="fa fa-square" aria-hidden="true"></i></span> ชำระเงินแล้ว</h6>';
                    
                    var discountText = '';
                    if(sos[i].discountso != 0) discountText =  '<h6 class="my-0 textLight2" style="text-align:right;"><b>ราคา</b> ฿' + sos[i].total_sales_price + '</h6> \
                                                                <h6 class="my-0 textLight2" style="text-align:right;"><b>ส่วนลด</b> -฿' + sos[i].discountso + '</h6>';
                    
                    $('#soRow').append('<div class="container col bg-white mx-0 mt-2 p-0 textDarkLight"> \
                                            <div class="m-2 p-2" style="border:1px solid lightgray"> \
                                                <h6 class="textLight" style="text-align:right;">' + spanText +
                                                '<h4 class="my-0 blue"><b>' + sos[i].so_no + ' , ' + sos[i].sox_no + ' </b></h4> \
                                                <p class="my-0 textLight"><b>สร้างเมื่อ</b> ' + sos[i].so_date + ' ' + sos[i].so_time + '</p> \
                                                <p class="my-0"><b>รหัสผู้ขาย</b> ' + sos[i].employee_id  + ' ' + sos[i].employee_nickname_thai + '</p> \
                                                <p class="my-0"><b>Tracking No.</b> ' + sos[i].tracking_number  + ' ' + sos[i].note + '</p> \
                                                <hr> \
                                                <div id="so' + sos[i].so_no + '"></div> \
                                                <hr>' + 
                                                discountText +
                                                '<h6 class="mt-1 mb-0" style="text-align:right;"><b>ราคารวม</b> ฿' + (sos[i].total_sales_price - sos[i].discountso) + '</h6> \
                                            </div> \
                                        </div>');
                    distinctSo.push(sos[i].so_no);
                }
                
            }
            
            for (var i = 0; i < sos.length; i++) {
                
                $('#so' + sos[i].so_no).append('<div class="row my-0"> \
                                                    <div class="col"> \
                                                        <h6 class="my-1">- <b>' + sos[i].product_name + '</b> (x' + sos[i].quantity + ')</h6> \
                                                    </div> \
                                                </div>');
                
            }
            
        </script>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <h4 class="mt-3 mb-0" id="po">Purchase Order</h4>
        
        <div class="row row-cols-1 row-cols-md-3 mt-2 p-0">
            <div class="container col bg-white mx-0 mt-2 p-0 textDarkLight" ng-repeat="po in pos">
                <a href="/file/po/{{po.po_no}}" target="_blank">
                <div class="m-2 p-2" style="border:1px solid lightgray">
                    <h6 class="textLight" style="text-align:right;" ng-show="po.cancelled == 1"><span class="text-danger"><i class="fa fa-square" aria-hidden="true"></i></span> ยกเลิก</h6>
                    <h6 class="textLight" style="text-align:right;" ng-show="po.received == 0 && po.cancelled == 0"><span class="text-dark"><i class="fa fa-square" aria-hidden="true"></i></span> ยังไม่ได้รับของ ({{po.so_no}})</h6>
                    <h6 class="textLight" style="text-align:right;" ng-show="po.received == 1 && po.cancelled == 0"><span class="text-primary">
                        <i class="fa fa-square" aria-hidden="true"></i></span> ได้รับของแล้ว ({{po.ci_no}}{{po.rr_no}} : {{po.so_no}})
                    </h6>
                    <h4 class="my-0 blue"><b>{{po.po_no}}</b></h4>
                    <p class="my-0 textLight"><b>สร้างเมื่อ</b> {{po.po_date}}</p>
                    <h6 class="mt-1 mb-0" style="text-align:right;"><b>ราคารวม</b> ฿{{po.total_purchase_price | number:2}}</h6>
                </div>
                </a>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- CS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <h4 class="mt-3 mb-0" id="cs">Counter Sales</h4>
        
        <div class="row row-cols-1 row-cols-md-3 mt-2 p-0">
            <div class="container col bg-white mx-0 mt-2 p-0 textDarkLight" ng-repeat="cs in css">
                <a href="/file/cs/mkt/{{cs.cs_no}}" target="_blank">
                <div class="m-2 p-2" style="border:1px solid lightgray">
                    <h4 class="my-0 blue"><b>{{cs.cs_no}}</b></h4>
                    <p class="my-0 textLight"><b>วันที่ออก CS</b> {{cs.cs_date}}</p>
                </div>
                </a>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    a:hover { text-decoration: none; }
    .blue { color: #6aa8d9; }
    .textLight { color: #aaa; }
    .textLight2 { color: #777; }
    .textDarkLight { color: #444; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.pos = <?php echo $this->pos; ?>;
        $scope.css = <?php echo $this->css; ?>;

        $http.get('/mkt/dashboard/get_dashboard').then(function(response) {
            $scope.dashboards = response.data;
        });

    });

</script>