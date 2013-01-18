<script>
    $(document).ready(function(){
        displayFields = {}
        hiddenFields = {}
        displayFields['P/N'] = 'Part.ModelCode'
        displayFields['Name'] = 'Part.ModelName'
        displayFields['UOM'] = 'Uom.UOMCode'
        displayFields['Min on Hand'] = 'Part.MinOnHand'
        displayFields['Max in Stock'] = 'Part.MaxOrderTo'
        displayFields['Item Type'] = 'ItemType.ItemType'
        displayFields['Default Location'] = 'DefaultLocation.LocationCode'
        hiddenFields['ModelID'] = 'Part.ModelID'
        hiddenFields['LocationID'] = 'DefaultLocation.LocationID'
        hiddenFields['UOMID'] = 'Uom.UOMID'
        $sorter = new joshPaginator({
            baseUrl:'parts/fetchPage/',
            sortBy: 'Part.ModelCode',
            resultCount:25,
            displayFields:displayFields,
            hiddenFields:hiddenFields
        });
        $sorter.initialize();
    });
</script>

<h2>Stock Items</h2><br/>
<br/>
<h1>
<?
echo $this->Html->link('Add Part', array('controller'=>'parts', 'action'=>'add'));
?>
</h1>
<br/><br/>
<? echo $this->JoshPaginateImp->contentSpace(); ?>
<br/>
<br/>


