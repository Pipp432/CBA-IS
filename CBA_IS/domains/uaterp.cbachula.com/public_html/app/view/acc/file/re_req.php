<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบเบิกค่าใช้จ่าย<br></b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">ต้นฉบับ</h5>
                
                    <h5 style="text-align: right;"><b>เลขที่ {{details[0].ex_no==null ? "ยังไม่ confirm" : details[0].ex_no}}</b></h5>
              
                <h6 style="text-align: right;">วันที่อนุมัติ {{details[0].authorize_date}}</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2565 (โครงการ 3)</b><br>
                    อาคารไชยยศสมบัติ 1 ห้องเลขที่ 315 ชั้นที่ 3 เลขที่ 254 ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330<br>
                    โทร. 0-2218-5762 โทรสาร. 0-2218-5762<br>
                </p>
            </div>
        </div>
        <div class="row px-2 mt-2", id="HASH">
                <span id="time-HASH"> 
                    ผู้เบิกเงิน {{details[0].employee_id}} {{details[0].withdraw_name}}&nbsp;
                </span>
            
        </div><br>
        <div class="text">
                
               
                <p>ชื่อบัญชีธนาคารที่รับโอน {{details[0].bank_book_number}}  ธนาคารผู้รับโอน {{details[0].bank_name}} เลขที่บัญชี {{details[0].bank_book_name}}</p>
        </div>        
        
        <hr>

        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th>วัน/เดือน/ปี ที่เกิดค่าใช้จ่าย</th>
                        <th>วัตถุประสงค์/รายละเอียดค่าใช้จ่าย</th>
                        <th>จำนวนเงิน(บาท)</th>
                    </tr>
                    <tr ng-repeat = "item in tableDetails track by $index" >
                        <td><b> {{item.date}}</b> </td>
						<td><b> {{item.details}}</b> </td>
                        <td><b> {{item.money | number:2 }}</b></td>
                    </tr>
                    <td colspan="3">
                       รวมทั้งสิ้น {{sum}} บาท
                    </td>
                   
                </table>
            </div>
        </div> 
        <br>
       
        <hr>
        <div>
            <p><b>(สำหรับฝ่ายการเงิน)<br></b></p>
            <p><u>จ่ายออกจากโครงการ</u></p>
            <p>(&nbsp;{{details[0].company =="project1" ? "X" : "" }}&nbsp;)&nbsp;โครงการ&nbsp;1&emsp;&emsp;(&nbsp; {{details[0].company =="project2" ? "X" : "" }} &nbsp;)&nbsp;โครงการ&nbsp;2&emsp;&emsp;(&nbsp;X&nbsp;)&nbsp;โครงการ&nbsp;3</p>
        </div>

        <div>
            
            <p><u>หลักฐานในการขอเบิกเงิน</u></p>
            <p>(&nbsp;{{details[0].evidence == "quotation" ? "X" : "" }}&nbsp;)&nbsp;ใบกำกับภาษี&emsp;&emsp;(&nbsp;{{details[0].evidence == "billAndID" ? "X" : "" }}&nbsp;)&nbsp;บิลเงินสด + นามบัตรหริอสำเนาบัตรประชาชนเจ้าของร้าน&emsp;&emsp;(&nbsp;{{details[0].evidence == "tax" ? "X" : "" }}&nbsp;)&nbsp;ใบเสนอราคา</p>
        </div>
       
        <div class="row px-2 mt-2">
                <span>ลงชื่อ<img src="/public/img/fin_sign.jpg" width="100" 
        height="75">(ผู้อนุมัติ)</span>
               
        </div>
       
       
        
       
    
    </div>

</body>

</html>

<style>
    .text{
        line-height: 75%;
    }
#HASH {
  display: flex;
  justify-content: space-between;
}
    body, h1, h2, h3, h4, h5, h6, p { font-family: 'Sarabun', sans-serif; }
    table, th, td { border: 1px solid black; padding: 5px; }
    th { text-align: center; }
</style>

<script>
    
    app.controller('moduleAppController', function($scope,$http) {
        $scope.details=[]
        $scope.tableDetails={}
        $scope.sum=0;
        $scope.getDetail = function() {
            var url = window.location.href; 
            $scope.details = <?php echo $this->re_req; ?>;
            $scope.company = $scope.details[0].re_req_no.substring(0,1);
            $scope.tableDetails = JSON.parse($scope.details[0].details)
            console.log($scope.details[0].bank_name)
            if(($scope.details[0].bank_name).includes('-')) $scope.details[0].bank_name = $scope.details[0].bank_name.slice(0,-1)
            
            $scope.tableDetails.forEach(data => {
                $scope.sum += Number(data.money);
                
            });
            
           
        }
       

    });
    
</script>