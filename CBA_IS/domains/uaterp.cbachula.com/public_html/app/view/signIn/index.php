<!DOCTYPE html>
<html>

    <body>

        <script>
            addModal('formValidate1', 'เข้าสู่ระบบ', 'กรอกรหัสพนักงานกับรหัสผ่านให้ครบก่อนนะครับผม');
            addModal('formValidate2', 'เข้าสู่ระบบ', 'รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง');
        </script>

        <div class="container">

            <div class="row justify-content-center px-2 mx-2 mt-4">
                <div class="col-lg-6" style="padding: 0px;">
                    <img src="/public/img/cba-logo.png" alt="cba-logo" style="width: 100%;">
                </div>
            </div>  

            <div class="row justify-content-center px-2 mx-2 mt-4">
                <div class="col-lg-5" style="padding: 0px;">
                    <h4 class="card-title font-weight-bold mt-4 mb-2">เข้าสู่ระบบ</h4> 
                    <hr>
                    <input type="text" class="form-control mt-4 mb-2" placeholder="รหัสพนักงาน" id="signInId" style="text-transform:uppercase">
                    <input type="password" class="form-control mb-4" placeholder="รหัสผ่าน" id="signInPassword">
                    <button type="button" class="btn btn-default btn-block" onclick="formValidate()" id="signInButton">เข้าสู่ระบบ</button>
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

    $('#signInId').on('keypress', function (e) {
        if(e.which === 13){
            formValidate();
        }
    });

    $('#signInPassword').on('keypress', function (e) {
        if(e.which === 13){
            formValidate();
        }
    });

    function formValidate() {
        if($("#signInId").val()=="" || $("#signInPassword").val()=="") {
            $('#formValidate1').modal('toggle');
        } else {
            signIn();
        }
    }

    function signIn() {
        $.post("/signin/signin", {
            post: true,
            employee_id: $("#signInId").val(),
            employee_password: $("#signInPassword").val()
        }, function(result) {
            if (result != "invalid") {
                location.assign("/");
            } else {
                $('#formValidate2').modal('toggle');
                $("#signInPassword").val("");
            }
        });
    }

</script>