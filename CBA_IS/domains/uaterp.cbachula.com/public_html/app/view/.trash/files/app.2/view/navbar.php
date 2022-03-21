<!DOCTYPE html>
<html>

<body>

    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top sticky-top">

        <div class="container px-1">
            <div class="row mx-0" style="width:100%;">
                <div class="col px-0 py-1">
                    <a class="navbar-brand" href="/">
                        <img src="/public/img/logo_horizontal_white.png" height="40">
                    </a>
                </div>
                <div class="col px-0 my-auto" style="text-align:right;">
                    <span style="color:white;"><?php echo $this->employee_name;?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span> 
                    <small><a class="nav-link2 p-0" href="/signin/signout">ออกจากระบบ ></a></small>
                </div>
            </div>
        </div>

    </nav>

</body>

</html>

<style>
    .navbar {
        background-color: #022d40 !important;
    }
    .nav-link2 {
        color: #fff !important; 
    }
    .nav-link2:hover {
        color: #ddd !important;
        text-decoration: underline;
    }
</style>