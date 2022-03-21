<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">
        
        <table id="table1"></table>
        
        <script>
            
            var ivs = <?php echo $this->ivs; ?>;
            
            ivs.forEach(function(value){
                
                if(!(value.sales_price_thai == NumToThai(parseFloat(value.total_sales_price)))) {
                    $('#table1').append('<tr>' + 
                        '<td>'+ value.invoice_date+'</td>' + 
                    '<td>'+ value.invoice_no+'</td>' + 
                    '<td>'+ value.total_sales_price+'</td>' + 
                    '<td>'+ NumToThai(parseFloat(value.total_sales_price))+'</td>'+ 
                    '<td>'+ (value.sales_price_thai == NumToThai(parseFloat(value.total_sales_price))) +'</td>'+ 
                    '</tr>');
                }
            });
            
        </script>
        
    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

</script>