<!DOCTYPE html>
<html>

    <head>
        <?php require 'style.php'; ?>
        <script> 
            var app = angular.module('app', []); 
            app.filter('unique', function() {
                return function(collection, keyname) {
                    var output = [],
                    keys = [];
                    angular.forEach(collection, function(item) {
                    var key = item[keyname];
                    if (keys.indexOf(key) === -1) {
                        keys.push(key);
                        output.push(item);
                    }
                    });
                    return output;
                };
            });
        </script>
    </head>

    <body ng-app="app">
        <div id="wrapper">
            <div id="page-wrapper">
                <?php require $this->pathHeader; ?>
                <?php require $this->pathContent; ?>
            </div>
        </div>
    </body>

</html>

