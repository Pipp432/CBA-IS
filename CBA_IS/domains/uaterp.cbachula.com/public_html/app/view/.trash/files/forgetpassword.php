
<!DOCTYPE html>
<html>

    <head>
        <?php require 'layout/head.php'; ?>
        <title>ลืมรหัสผ่าน - BIZ CUBE CHULA</title>
        <script type="text/javascript" src="/public/js/signin.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>
    </head>

    <body ng-app="app">
        
        <?php require 'layout/navbar.php'; ?>

        <div class="container" ng-controller="app_controller">

            <div class="row justify-content-center">

                <div class="col col-md-6">

                    <div class="card p-3 mt-3">
                        <label for="">กรุณากรอกอีเมลเพื่อเปลี่ยนรหัสผ่าน</label>
                        <label for="customer_email">อีเมล</label>
                        <input class="form-control" type="email" id="customer_email" placeholder="อีเมล">
                        <button class="btn btn-default btn-round btn-block mt-3" ng-click="forget_password_validate()"><i class="las la-redo-alt"></i> ยืนยัน</button>
                    </div>

                </div>

            </div>

        </div>
        
    </body>

</html>