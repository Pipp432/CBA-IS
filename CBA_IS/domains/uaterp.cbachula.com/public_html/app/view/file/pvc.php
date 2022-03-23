<!DOCTYPE html>
    <html>
        <body>
            Hello World
        </body>
    </html>

<script>
    
    app.controller('moduleAppController', function($scope) {
        $scope.getDetail = function() {
            $scope.detail = <?php echo $this->pvc; ?>;
            $scope.company = $scope.detail[0].pvc_no.substring(0,1);
        }
    });
    
</script>    