<!DOCTYPE html>
<html>

    <head>
        <?php require 'style.php'; ?>
        <script> var app = angular.module('app', []); </script>
    </head>

    <body ng-app="app">
        <?php 
            if($this->contents != null) {
                foreach ($this->contents as $content) {
                    require 'app/view/'.$content.'.php';
                }
            }
        ?>
    </body>

</html>