<!DOCTYPE html>
<html>

    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?= $this->title ?></title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!--<link rel="stylesheet" href="/public/fontawesome.css">
		<script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js"></script>-->
        <link rel="shortcut icon" href="/public/img/cba-logo-icon.ico" type="image/x-icon">
        <link rel="icon" href="/public/img/cba-logo-icon.ico" type="image/x-icon">        

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <script src="/app/view-layout/components/addModal.js"></script>
        <script src="/app/view-layout/components/addConfirmModal.js"></script>
        <script src="/app/view-layout/components/addModuleLink.js"></script>
        <script src="/app/view-layout/components/numToThai.js"></script>
        
        <style>

            body {
                font-family: 'Kanit', sans-serif;
            }
            h1,h2,h3,h4,h5,h6,p {
                font-family: 'Kanit', sans-serif;
            }
            a {
                color: #6aa8d9;
            }
            a:hover {
                color: #0959a2;
            }
            .whiteText{
                color: #ffffff;
            }
            .btn-default{
                background-color: #6aa8d9;
                color: #ffffff;
                border-color: #6aa8d9;
            }
            .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open .dropdown-toggle.btn-default {
                background-color: #0959a2;
                color: #ffffff;
                border-color: #0959a2;
            }
            .btn-defaultOutline{
                background-color: transparent;
                color: #6aa8d9;
                border-color: #6aa8d9;
            }
            .btn-defaultOutline:hover, .btn-defaultOutline:focus, .btn-defaultOutline:active, .btn-defaultOutline.active, .open .dropdown-toggle.btn-defaultOutline {
                background-color: transparent;
                color: #0959a2;
                border-color: #0959a2;
            }

        </style>
        
    </head>

</html>
