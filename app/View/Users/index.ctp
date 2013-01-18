<script>
    $(document).ready(function(){
        $sorter = new joshPaginator({
            base_url:'Users/fetchPage/',
            sort_by: 'User.UserID'
        });
        $sorter.initialize();
    });
</script>

<h1>Users</h1><br/>
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

