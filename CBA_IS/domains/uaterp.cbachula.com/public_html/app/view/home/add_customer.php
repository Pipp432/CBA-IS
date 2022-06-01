<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">เพิ่มข้อมูลลูกค้า / Add Customer</h2>

        <div class="card p-1 my-3" style="border:none; border-radius:10px; background-color:rgba(255, 255, 255, 0.6);">

            <div class="card-body">

                <label for="inputNameTh">ชื่อ-นามสกุล (ภาษาไทย)</label>

                <div class="form-row" id="inputNameTh">

                    <div class="col-md-2">
                        <select class="form-control mb-2" id="customerTitle">
                            <option selected value="">คำนำหน้าชื่อ</option>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="text" class="form-control mb-2" placeholder="ชื่อ" id="customerFirstName">
                    </div>

                    <div class="col-md-4">
                        <input type="text" class="form-control mb-2" placeholder="นามสกุล" id="customerLastName">
                    </div>
                    
                    <div class="col-md-2">
                        <input type="text" class="form-control mb-2" placeholder="ชื่อเล่น" id="customerNickName">
                    </div>

                </div>

                <div class="form-row">
                    <div class="col-md-3">
                        <label for="customerIdNo">เลขประจำตัวประชาชน</label>
                        <input type="text" class="form-control mb-2" placeholder="เลขประจำตัวประชาชน" id="customerIdNo">
                    </div>

                    <div class="col-md-3">
                        <label for="customerTel">เบอร์โทรศัพท์มือถือ</label>
                        <input type="tel" class="form-control mb-2" placeholder="เบอร์โทรศัพท์มือถือ" id="customerTel">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="customerEmail">อีเมล</label>
                        <input type="email" class="form-control mb-2" placeholder="อีเมล" id="customerEmail">
                    </div>
					
                </div>
                
                <div class="form-row">

                    <div class="col-md-12">
                        <label for="customerAddress">ที่อยู่สำหรับจัดส่งสินค้า</label>
                        <textarea cols="30" rows="4" class="form-control mb-2" placeholder="เลขที่, หมู่ที่, อาคาร, หมู่บ้าน, ถนน, ตำบล/แขวง, อำเภอ/เขต !!ห้าม!! ใส่จังหวัดกับรหัสไปรษณีย์ในช่องนี้ ใครใส่มาจะเก็บค่าปรับนะคะ IS ตามแก้ไม่ไหวแล้วค่ะ" id="customerAddress"></textarea>
                    </div>
                    
                    <!--<div class="col-md-3">
                        <input type="text" class="form-control mb-2" placeholder="ตำบล/แขวง" id="customerDistrict">
                    </div>
                    
                    <div class="col-md-3">
                        <input type="text" class="form-control mb-2" placeholder="อำเภอ/เขต" id="customerCity">
                    </div>-->

                    <div class="col-md-6">
                        <select class="form-control mb-2" id="customerProvince">
                            <option selected value="">เลือกจังหวัด</option>
                            <?php 
                                
                                $provinces = ['กระบี่', 'กรุงเทพมหานคร', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร', 'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท', 'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง', 'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม', 'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส', 'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์', 'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พะเยา', 'พังงา', 'พัทลุง', 'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน', 'ยโสธร', 'ยะลา', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง', 'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย', 'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ', 'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี', 'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย', 'หนองบัวลำภู', 'อ่างทอง', 'อำนาจเจริญ', 'อุดรธานี', 'อุตรดิตถ์', 'อุทัยธานี', 'อุบลราชธานี'];
                                
                                foreach($provinces as $province) echo '<option value="'.$province.'">'.$province.'</option>';
                                
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input type="tel" class="form-control mb-2" placeholder="รหัสไปรษณีย์" id="customerPostCode">
                    </div>

                </div>

            </div>

        </div>

        <div class="card p-1 my-3" style="border:none; border-radius:10px; background-color:rgba(255, 255, 255, 0.6);">

            <div class="card-body"> 

                <h5 class="card-title mt-0 mb-3">กรุณาตรวจสอบข้อมูลว่าถูกต้องหรือไม่ก่อนกดยืนยันการเพิ่มข้อมูลลูกค้า</h5> 
                <button type="button" class="btn btn-default btn-block" ng-click="formValidate()">เพิ่มข้อมูลลูกค้า</button>

            </div>

        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'เพิ่มข้อมูลลูกค้า / Add Customer', 'ยังกรอกรายละเอียดไม่ครบถ้วน');
			addModal('formValidate2', 'เพิ่มข้อมูลลูกค้า / Add Customer', 'กรุณากรอกชื่อเป็นภาษาไทย');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    
    body {
        background: url('/public/img/cbs-background.png') no-repeat center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
    }
	
	::placeholder {
	  color: blue;
	}

</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        $scope.formValidate = function() {
			var input_name_th = document.getElementById("customerFirstName").value;
			var input_last_name_th = document.getElementById("customerLastName").value;
            if($('#customerTitle').val() == '' || $('#customerFirstName').val() == '' || $('#customerLastName').val() == '' || $('#customerLastName').val() == '' || 
                $('#customerIdNo').val() == '' || $('#customerTel').val() == '' || $('#customerEmail').val() == '' || $('#customerAddress').val() == '' || 
                $('#customerDistrict').val() == '' || $('#customerCity').val() == '' || $('#customerProvince').val() == '' || $('#customerPostCode').val() == '') {
                $('#formValidate1').modal('toggle');
				
            } else if(!input_name_th.match(/^[ก-๏\s]+$/)||!input_last_name_th.match(/^[ก-๏\s]+$/)){
            	$('#formValidate2').modal('toggle');
			}else {
                var confirmModal = addConfirmModal('confirmModal', 'เพิ่มข้อมูลลูกค้า / Add Customer', 'ยืนยันการเพิ่มข้อมูลลูกค้า', 'postCustomer()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postCustomer = function() {
            
            $('#confirmModal').modal('hide');
            
            //var date = new Date($('#customerBirthday').val());
            //var customerBirthdayStr = date.getFullYear() + '-' + 
                                        //((date.getMonth()+1) < 10 ? '0' : '') + (date.getMonth()+1) + '-' + 
                                        //(date.getDate() < 10 ? '0' : '') + date.getDate();
                                        
            // var districtStr = (($('#customerProvince').val() == 'กรุงเทพมหานคร') ? 'แขวง' : 'ตำบล') + $('#customerDistrict').val();
            // var cityStr = (($('#customerProvince').val() == 'กรุงเทพมหานคร') ? 'เขต' : 'อำเภอ') + $('#customerCity').val();
            var provinceStr = (($('#customerProvince').val() == 'กรุงเทพมหานคร') ? '' : 'จังหวัด') + $('#customerProvince').val();
            // var customerAddressStr = $('#customerAddress').val() + ' ' + districtStr + ' ' + cityStr + ' ' + provinceStr + ' ' + $('#customerPostCode').val();
            
            $.post("/home/add_customer/post_customer", {
                customerTitle : $('#customerTitle').val(),
                customerFirstName : $('#customerFirstName').val(),
                customerLastName : $('#customerLastName').val(),
                customerNickName : $('#customerNickName').val(),
                customerIdNo : $('#customerIdNo').val(),
                customerTel : $('#customerTel').val(),
                customerEmail : $('#customerEmail').val(),
                customerAddress : $('#customerAddress').val() + ' ' + provinceStr + ' ' + $('#customerPostCode').val(),
                customerProvince : $('#customerProvince').val()
            }, function(data) {
                addModal('successModal', 'เพิ่มข้อมูลลูกค้า / Add Customer', 'บันทึกข้อมูลลูกค้าสำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.replace('https://uaterp.cbachula.com/home/add_customer');
                });
            });
            
        }

  	});

</script>