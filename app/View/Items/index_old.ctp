<script>
    $(document).ready(function(){
        $sorter = new joshPaginator({
            base_url:'Items/fetchPage/',
            sort_by: 'Part.ModelCode',
            result_count: 25
        });
        $sorter.initialize();
    });
</script>

<h1>Current Stock Inventory</h1><br/>
<p>
    Welcome to the new stock inventory page.  Things work mostly the same as with the old version, with a few exceptions:
<ul style="list-style:lower-alpha;">
    <li>There is now a notes field in the issue form.</li>
    <li>Query results are now paginated.  You can choose the number of results per page and navigate through pages using the links below the table</li>
    <li>You must now hit enter in the search field to perform a search</li>
    <li>The results below are no longer stored in memory.  This means that they will load a bit more slowly than before but will be more up-to-date.</li>
    <li>Expired materials are now highlighted.  Do not use them!</li>
</ul>
<?
//echo $this->Html->link('Receive Inventory', array(
//    'action' => 'receive', 'controller' => 'inventories'
//));
?>
<br/><br/>
<? echo $this->JoshPaginate->contentSpace(); ?>
<br/>
<br/>
<?
//echo $this->Html->link('View History', array(
//    'action'=>'index', 'controller'=>'inventoryHistories'
//));
?>

