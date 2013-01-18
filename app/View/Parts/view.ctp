<script>
    $(document).ready(function(){
        // 1. Load summary of top assemblies for this part
        var id = <? echo $result['Part']['ModelID'];?>;
        $.ajax({
            url:'../../assemblies/getTops/' + id,
            dataType:'html',
            type:'GET',
            success: loadTops
        })
        // 2. Load summary of materials for which this is a component
        $.ajax({
            url:'../../assemblies/getComponents/' + id,
            dataType:'html',
            type:'GET',
            success: loadComponents
        })
        
//        displayFields = {}
//        hiddenFields = {}
//        filters = {}
//        filters['Part.ModelID'] = <? echo $result['Part']['ModelID'];?>;
//        displayFields['Inv Ctrl#'] = 'Item.ItemCode'
//        hiddenFields['P/N'] = 'Part.ModelCode'
//        displayFields['Rev'] = 'Item.TransLineItemUD1'
//        hiddenFields['Name'] = 'Part.ModelName'
//        displayFields['Location'] = 'Location.LocationCode'
//        displayFields['Qty'] = 'Item.Quantity'
//        displayFields['Uom'] = 'UOM.UOMCode'
//        displayFields['Status'] = 'LocationType.LocationTypeCode'
//        displayFields['Lot'] = 'Item.TransLineItemUD2'
//        displayFields['Batch'] = 'Item.TransLineItemUD3'
//        hiddenFields['Exp Date'] = 'Item.TransLineItemUD6'
//        hiddenFields['Purchase Order'] = 'Item.TransLineItemUD4'
//        hiddenFields['ItemID'] = 'Item.ItemID'
//        hiddenFields['ModelID'] = 'Part.ModelID'
//        hiddenFields['LocationID'] = 'Location.LocationID'
//        $sorter = new joshPaginator({
//            baseUrl:'../../items/fetchPage/',
//            sortBy: 'LocationCode',
//            resultCount:10,
//            fieldFilters:filters,
//            displayFields:displayFields,
//            hiddenFields:hiddenFields
//        });
//        $sorter.initialize();
    })
    
    function loadTops(html){
        $('#topAssemblies').children().remove();
        $('#topAssemblies').append(html);
    }
    
    function loadComponents(html){
        $('#componentOf').children().remove();
        $('#componentOf').append(html);
    }
</script>

<h1>Part/Component</h1>
<? echo $this->Html->link('Edit this part', array('action' => 'edit', $result['Part']['ModelID'])); ?>
<br/>
<br/>
<div id="PartSummary">
    <strong>P/N: </strong><? echo $result['Part']['ModelCode']; ?> <br/>
    <strong>Name: </strong><? echo $result['Part']['ModelName']; ?><br/>
    <strong>Min on hand: </strong><? echo isset($result['Part']['MinOnHand']) ? (float) $result['Part']['MinOnHand'] . " {$result['Uom']['UOMCode']}" : 'Not Set';
?><br/>
    <strong>Max to stock: </strong><? echo isset($result['Part']['MaxOrderTo']) ? (float) $result['Part']['MaxOrderTo'] . " {$result['Uom']['UOMCode']}" : 'Not set';
?><br/>
    <strong>Lead Time: </strong><? echo isset($result['Part']['LeadTime']) ? (float) $result['Part']['LeadTime'] . ' ' . $result['TimeUnit']['TimeUnit'] : 'Not set'; ?><br/>
    <strong>Default Location: </strong><? echo isset($result['DefaultLocation']['LocationCode']) ? $result['DefaultLocation']['LocationCode'] : 'Not set'; ?><br/>

</div>
<br/><br/>
<h1>Top Assemblies:</h1>
<div id="topAssemblies" style="padding:1em;">
    
<? //echo $this->JoshPaginateImp->contentSpace(); ?>
</div>
<br/>
<h1>Component For: </h1>
<div id="componentOf" style="padding:1em;">
    
</div>