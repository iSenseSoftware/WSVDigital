<script>
    $(document).ready(function(){
        displayFields = {}
        displayFields['Inv Ctrl#'] = 'Item.ItemCode'
        displayFields['P/N'] = 'Part.ModelCode'
        displayFields['Rev'] = 'Item.TransLineItemUD1'
        displayFields['Name'] = 'Part.ModelName'
        displayFields['Location'] = 'Location.LocationCode'
        displayFields['Qty'] = 'Item.Quantity'
        displayFields['Uom'] = 'UOM.UOMCode'
        displayFields['Status'] = 'LocationType.LocationTypeCode'
        displayFields['Lot'] = 'Item.TransLineItemUD2'
        displayFields['Batch'] = 'Item.TransLineItemUD3'
        displayFields['Exp Date'] = 'Item.TransLineItemUD6'
        displayFields['Purchase Order'] = 'Item.TransLineItemUD4'
        hiddenFields = {}
        hiddenFields['ItemID'] = 'Item.ItemID'
        hiddenFields['ModelID'] = 'Part.ModelID'
        hiddenFields['LocationID'] = 'Location.LocationID'
        sorter = new joshPaginator({
            baseUrl: 'items/fetchPage/',
            sortBy: 'Part.ModelCode',
            resultCount: 25,
            displayFields: displayFields,
            hiddenFields: hiddenFields
        });
        sorter.initialize();
    });
</script>

<h2>Current Stock Inventory</h2><br/>
<h1>
    <?
echo $this->Html->link('Receive Inventory', array(
    'action' => 'receive', 'controller' => 'items'
));
?>
</h1>
<br/><br/>
<? echo $this->JoshPaginateImp->contentSpace(); ?>
<br/>
<br/>
<?
//echo $this->Html->link('View History', array(
//    'action'=>'index', 'controller'=>'inventoryHistories'
//));
?>

