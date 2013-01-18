<script>
    $(document).ready(function(){
        $sorter = new joshPaginator({
            base_url:'InventoryHistories/fetchPage/',
            sort_by: 'InventoryHistoryHistoryDateTime',
            result_count: 25,
            sort_direction: 'desc'
        });
        $sorter.initialize();
    });
</script>

<h1>Stock Inventory Transaction History</h1><br/>
<p>
   Below is the new inventory transaction history table.  Please note that because 
   there are over 10000 records to be searched, it will take several seconds to complete a search.
</p>
<br/><br/>
<? echo $this->JoshPaginate->contentSpace(); ?>
<br/>
<br/>


