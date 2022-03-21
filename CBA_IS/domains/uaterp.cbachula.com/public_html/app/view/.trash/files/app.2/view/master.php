<!DOCTYPE html>
<html>

    <?php require 'head.php'; ?>
    
    <script> 
    
        var app = angular.module('app', ['ngSanitize']); 
        
        app.directive('onFinishRender', function ($timeout) {
            return {
                restrict: 'A',
                link: (scope, element, attr) => { if (scope.$last) $timeout(() => scope.$emit('ng_repeat_finished')); }
            }
        });

        app.directive('compile', ['$compile', function ($compile) {
            return function(scope, element, attrs) {
                scope.$watch(
                function(scope) {
                    return scope.$eval(attrs.compile);
                },
                function(value) {
                    element.html(value);
                    $compile(element.contents())(scope);
                }
            )};
        }]);

    </script>

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