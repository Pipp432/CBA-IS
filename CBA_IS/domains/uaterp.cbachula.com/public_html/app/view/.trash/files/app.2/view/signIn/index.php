<!DOCTYPE html>
<html>
    <body>

        <script type="text/javascript" src="/public/js/signIn/index.js"></script>
        
        <div class="container">
            <div class="row justify-content-center px-2 mx-2 mt-4">
                <img src="/public/img/logo_horizontal.png" alt="logo" style="width: 60%;">
                <div class="col-md-5 mt-5" style="padding: 0px;">
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