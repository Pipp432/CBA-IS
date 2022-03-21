<!DOCTYPE html>
<html>
    <body>
        <div class="container">
            <div class="row justify-content-center px-2 mx-2 mt-4">
                <div class="col-md-5" style="padding: 0px;">
                    <h4 class="card-title font-weight-bold mt-4 mb-2">เข้าสู่ระบบ</h4> 
                    <hr>
                    <input type="text" class="form-control mt-4 mb-2" placeholder="รหัสพนักงาน" id="employee_no" style="text-transform:uppercase">
                    <input type="password" class="form-control mb-4" placeholder="รหัสผ่าน" id="employee_password">
                    <button type="button" class="btn btn-default btn-block" onclick="formValidate()" id="signInButton">เข้าสู่ระบบ</button>
                </div>
            </div>  
        </div>
    </body>
</html>

<style>

    body {
        background: url('/public/img/bg.png') no-repeat center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
    }

</style>

<script>

    add_modal('form_validate1', 'กรุณากรอกข้อมูลให้ครบถ้วน');
    add_modal('form_validate2', 'รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง');

    $('#employee_no').on('keypress', (e) => { if(e.which === 13)  formValidate(); });
    $('#employee_password').on('keypress', (e) => { if(e.which === 13)  formValidate(); });

    function formValidate() {
        if($('#employee_no').val() === '' || $('#employee_password').val() === '')
            $('#form_validate1').modal('toggle');
        else
            signIn();
    }

    function signIn() {
        $.post("/signin/signin", {
            post: true,
            employee_no: $('#employee_no').val(),
            employee_password: $('#employee_password').val()
        }, (result) => {
            if (result === 'valid') {
                location.assign('/');
            } else {
                $('#form_validate2').modal('toggle');
                $('#employee_password').val('');
            }
        });
    }

</script>