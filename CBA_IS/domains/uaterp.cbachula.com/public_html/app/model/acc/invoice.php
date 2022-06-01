<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">
        
        <table class="table table-hover" id="table1" style="width:100%"></table>
        
        <script>
            
            var ivs = <?php echo $this->ivs; ?>;
            
            ivs.forEach(function(value){
                $('#table1').append('<tr>' + 
                    '<td>' + value.invoice_no + '</td>' + 
                    '<td>' + value.invoice_date + '</td>' + 
                    '<td>' + value.employee_id + '</td>' + 
                    '<td>' + value.customer_name +'</td>'+ 
                    '<td>' + value.id_no +'</td>'+ 
                    '<td>' + value.product_type +'</td>'+ 
                    '<td>' + value.vat_type +'</td>'+ 
                    '<td>' + value.total_sales_no_vat +'</td>'+ 
                    '<td>' + value.total_sales_vat +'</td>'+ 
                    '<td>' + value.total_sales_price +'</td>'+ 
                    '<td>' + value.sales_price_thai +'</td>'+ 
                    '<td>' + value.file_no +'</td>'+ 
                    '</tr>');
            });
            
        </script>
        
    </div>

</body>

</html>

<script>

</script>