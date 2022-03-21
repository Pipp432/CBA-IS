<!DOCTYPE html>
<html>

    <body>

        <script>
            addModal('formValidate1', 'Get Username and Password', 'กรอกเลขบัตรประชาชนก่อนนะครับผม');
            addModal('formValidate2', 'Get Username and Password', 'ไม่มีเลขบัตรประชาชนนี้ในระบบ ติดต่อ IS ด่วนๆ');
        </script>

        <div class="container">

            <div class="row justify-content-center px-2 mx-2 mt-4">
                <div class="col-lg-6" style="padding: 0px;">
                    <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 100%;">
                </div>
            </div>  

            <div class="row justify-content-center px-2 mx-2 mt-4">
                <div class="col-lg-5" style="padding: 0px;">
                    <h4 class="card-title font-weight-bold mt-4 mb-2">ตรวจสอบรหัสพนักงานและรหัสผ่านเพื่อเข้าระบบ</h4> 
                    <hr>
                    <input type="text" class="form-control mt-4 mb-2" placeholder="เลขบัตรประจำตัวประชาชน" id="national_id">
                    <button type="button" class="btn btn-default btn-block" onclick="formValidate()" id="checkButton">ตรวจสอบ</button>
                </div>
            </div>  
            
            <div class="row justify-content-center px-2 mx-2 mt-4">
                <div class="col-lg-5" style="padding: 0px;">
                    <h5>ชื่อ-นามสกุล : <label style="color:#888" id="labelName"></label></h5>
                    <h5>รหัสพนักงาน : <label style="color:#888" id="labelEmployeeId"></label></h5>
                    <h5>รหัสผ่าน : <label style="color:#888" id="labelPassword"></label></h5>
                </div>
            </div> 

        </div> <!-- container -->

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

</style>

<script>

    $('#national_id').on('keypress', function (e) {
        if(e.which === 13){
            formValidate();
        }
    });

    function formValidate() {
        if($("#national_id").val()==='') {
            $('#formValidate1').modal('toggle');
        } else {
            check();
        }
    }

    function check() {
        $.post("/home/get_password/get", {
            post: true,
            national_id: $("#national_id").val()
        }, function(result) {
            if(result!='0') {
                $('#labelName').html(result[0].employee_name_thai);
                $('#labelEmployeeId').html(result[0].employee_id);
                $('#labelPassword').html(result[0].password);
            } else {
                $('#formValidate2').modal('toggle');
                $('#labelName').html('');
                $('#labelEmployeeId').html('');
                $('#labelPassword').html('');
            }
        }, 'json');
    }

</script>