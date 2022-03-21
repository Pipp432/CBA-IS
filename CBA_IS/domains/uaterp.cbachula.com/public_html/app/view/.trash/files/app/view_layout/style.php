<!DOCTYPE html>
<html>

    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <title><?= $this->title ?></title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
        <link rel="shortcut icon" href="/public/img/logo_icon.ico" type="image/x-icon">
        <link rel="icon" href="/public/img/logo_icon.ico" type="image/x-icon">      

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">  

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <script src="/app/view_layout/components/add_modal.js"></script>

        <script src="/app/view_layout/components/addModal.js"></script>
        <script src="/app/view_layout/components/addConfirmModal.js"></script>
        <script src="/app/view_layout/components/addModuleLink.js"></script>
        <script src="/app/view_layout/components/numToThai.js"></script>

        <style>

            body {
                font-family: 'Sarabun', sans-serif;
            }
            
            h1,h2,h3,h4,h5,h6,p {
                font-family: 'Sarabun', sans-serif;
            }

            a {
                color: #022d40;
            }

            a:hover {
                color: #011a25;
            }

            .whiteText{
                color: #ffffff;
            }

            .btn-default{
                background-color: #022d40;
                color: #ffffff;
                border-color: #022d40;
            }

            .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open .dropdown-toggle.btn-default {
                background-color: #011a25;
                color: #ffffff;
                border-color: #011a25;
            }

            .btn-defaultOutline{
                background-color: transparent;
                color: #022d40;
                border-color: #022d40;
            }

            .btn-defaultOutline:hover, .btn-defaultOutline:focus, .btn-defaultOutline:active, .btn-defaultOutline.active, .open .dropdown-toggle.btn-defaultOutline {
                background-color: transparent;
                color: #011a25;
                border-color: #011a25;
            }

            .table td, .table th {
                border-bottom: 1px solid #dee2e6 !important;
                padding: 10px 5px !important;
            }

            .table th {
                background-color: #eee;
            }

            .part {
                border-top: 4px solid #022d40;
                background-color: #fafafa;
            }

        </style>
        
    </head>

</html>
