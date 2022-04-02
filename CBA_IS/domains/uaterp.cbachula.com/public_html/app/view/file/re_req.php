<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="getDetail()">

        <div class="row px-2 mt-2">
            <div class="col-4 pl-0 pr-3">
                <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 60%;">
            </div>
            <div class="col-4 px-0">
                <h3 style="text-align: center;"><b>ใบขอเบิกค่าใช้จ่าย<br></b></h3>
            </div>
            <div class="col-4 px-0">
                <h5 style="text-align: right;">สำเนา</h5>
                
                    <h5 style="text-align: right;"><b>เลขที่ {{details[0].EX_No}}</b></h5>
              
                <h6 style="text-align: right;">วันที่ {{details[0].Withdraw_Date}}</h6>
            </div>
        </div>
        
        <div class="row px-2 mt-2">
            <div class="col-12 px-0">
                <p class="my-0">
                    <b>ห้างหุ้นส่วนสามัญ บริษัทจำลองจุฬาลงกรณ์มหาวิทยาลัย 2564 (โครงการ CBA 2022)</b><br>
                    อาคารไชยยศสมบัติ 1 เลขที่ 254 ชั้นใต้ดิน ถนนพญาไท แขวงวังใหม่ เขตปทุมวัน กรุงเทพมหานคร 10330<br>
                    โทร. 0-2218-5746-9 โทรสาร. 0-2218-5762
                </p>
            </div>
        </div>
        <div class="row px-2 mt-2", id="HASH">
                <span id="time-HASH"  > ผู้เบิกเงิน {{details[0].Withdraw_Name}}&nbsp;</span>
                <span  style="text-align: left;">
                   รหัสพนักงาน {{details[0].Employee_ID}}&nbsp;
                </span>
               
            
        </div>
        <div class="text">
                <br><p>ธนาคารผู้รับโอน {{details[0].Bank_Name}}</p><br>
                <p>เลขที่บัญชี {{details[0].Bank_Book_Number}}</p><br>
                <p>ชื่อบัญชีธนาคารที่รับโอน {{details[0].Bank_Book_Name}}</p>
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
                    <tr ng-repeat = "item in tableDetails" >
                        <td><b> {{item.date}}</b> </td>
						<td><b> {{item.details}}</b> </td>
                        <td><b> {{item.money}}</b></td>
                    </tr>
                    <td colspan="3">
                       รวมทั้งสิ้น {{sum}} บาท
                    </td>
                   
                </table>
            </div>
        </div> 
        <br>
        <div class="row px-2 mt-2", id="HASH">
                <span id="time-HASH"  > ผู้เบิกเงิน {{details[0].Withdraw_Name}}&nbsp;</span>
                <span  style="text-align: left;">
                   ผู้รับรอง {{details[0].Authorize_Name}}&nbsp;
                </span>
               
            
        </div>
        <hr>
        <div>
            <p><b>(สำหรับฝ่ายการเงิน)<br></b></p>
            <p><u>จ่ายออกจากโครงการ</u></p>
            <p>(&nbsp;&nbsp;)&nbsp;โครงการ&nbsp;1&emsp;&emsp;(&nbsp;&nbsp;)&nbsp;โครงการ&nbsp;2&emsp;&emsp;(&nbsp;&nbsp;)&nbsp;โครงการ&nbsp;3&emsp;&emsp;(&nbsp;&nbsp;)&nbsp;SPJ&nbsp;1&emsp;&emsp;(&nbsp;&nbsp;)&nbsp;SPJ&nbsp;2</p>
        </div>

        <div>
            
            <p><u>หลักฐานในการขอเบิกเงิน</u></p>
            <p>(&nbsp;&nbsp;)&nbsp;ใบกำกับภาษี&emsp;&emsp;(&nbsp;&nbsp;)&nbsp;บิลเงินสด + นามบัตรหริอสำเนาบัตรประชาชนเจ้าของร้าน&emsp;&emsp;(&nbsp;&nbsp;)&nbsp;ใบเสนอราคา</p>
        </div>
       
        <div class="row px-2 mt-2">
                <span>ลงชื่อ<img src="/public/img/fin_sign.jpg" width="100" 
        height="75">(ผู้รับเรื่อง)</span>
                <span  style="text-align: left;">
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;ลงชื่อ<img src="/public/img/accsign.jpg" width="100" 
        height="75">(ผู้อนุมัติ)
                </span>
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
        $scope.sum='';
        $scope.getDetail = function() {
            var url = window.location.href; 
            const PVC_No = (url.split('/'))[5];
            $http.get(`/file/re_req/get_PVC/${PVC_No}`).then(function(response){$scope.details= response.data; $scope.isLoad = false;$scope.tableDetails = JSON.parse($scope.details[0].Table_Of_Details);console.log($scope.tableDetails);($scope.tableDetails).forEach(entry => {
                $scope.sum+=parseInt(entry.money);
               
            });});
            
           
        }
    });
    
</script>