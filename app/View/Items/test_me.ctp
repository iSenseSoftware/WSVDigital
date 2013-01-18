<script>
    $(document).ready(function(){
        relatedDisplayFields = {}
        relatedHiddenFields = {}
        relatedFilter = {}
        
        relatedDisplayFields['Inv Ctrl#'] = 'Item.ItemCode'
        relatedHiddenFields['P/N'] = 'Part.ModelCode'
        relatedDisplayFields['Rev'] = 'Item.TransLineItemUD1'
        relatedHiddenFields['Name'] = 'Part.ModelName'
        relatedDisplayFields['Location'] = 'Location.LocationCode'
        relatedDisplayFields['Qty'] = 'Item.Quantity'
        relatedDisplayFields['Uom'] = 'Uom.UOMCode'
        relatedDisplayFields['Status'] = 'LocationType.LocationTypeCode'
        relatedDisplayFields['Lot'] = 'Item.TransLineItemUD2'
        relatedDisplayFields['Batch'] = 'Item.TransLineItemUD3'
        relatedHiddenFields['Exp Date'] = 'Item.TransLineItemUD6'
        relatedHiddenFields['Purchase Order'] = 'Item.TransLineItemUD4'
        relatedHiddenFields['ItemID'] = 'Item.ItemID'
        relatedHiddenFields['ModelID'] = 'Part.ModelID'
        relatedHiddenFields['LocationID'] = 'Location.LocationID'
        relatedFilter['Part.ModelID'] = <? echo $result['Item']['ModelID']; ?>;
        
        relatedSorter = new joshPaginator({
            baseUrl:'../../items/fetchPage',
            sortBy: 'Item.ItemCode',
            sortDirection: 'desc',
            resultCount: 25,
            displayFields: relatedDisplayFields,
            hiddenFields: relatedHiddenFields,
            contentId:'#relatedInventory',
            resultsId:'#relatedResults',
            availableColsId:'#availableRelated',
            selectedColsId:'#selectedRelated',
            connectClass:'.relatedConnector',
            pagingId:'#relatedPaging'
        })
        relatedSorter.initialize()
    })
</script>
<div style="border:2px solid black;padding:1em;">
<? echo $this->JoshPaginateImp->contentSpace(array(
    'contentId'=>'relatedInventory',
    'resultsId'=>'relatedResults',
    'availableColsId'=>'availableRelated',
    'selectedColsId'=>'selectedRelated',
    'connectionClass'=>'relatedConnector',
    'showSearch'=>false
));?>
</div>