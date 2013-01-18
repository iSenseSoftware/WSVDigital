<script>
    $(document).ready(function(){
        displayFields = {}
        hiddenFields = {}
        displayFields['Date'] = 'InventoryHistory.InventoryHistoryHistoryDateTime'
        displayFields['Trans Type'] = 'InventoryHistory.InvHistoryTypesInvHistoryType'
        displayFields['P/N'] = 'InventoryHistory.ModelsModelCodeIN'
        displayFields['Rev'] = 'InventoryHistory.TransLineItemUD1'
        displayFields['Name'] = 'InventoryHistory.ModelsModelNameIN'
        displayFields['Inv Ctrl#'] = 'InventoryHistory.ItemsItemCode'
        displayFields['Qty Change'] = 'InventoryHistory.InventoryHistoryQuantityChange'
        displayFields['Location'] = 'InventoryHistory.LocationsLocationCode'
        displayFields['Supplier'] = 'InventoryHistory.TransLineItemUD5'
        displayFields['Lot'] = 'InventoryHistory.TransLineItemUD2'
        displayFields['Batch'] = 'InventoryHistory.TransLineItemUD3'
        displayFields['PO'] = 'InventoryHistory.TransLineItemUD4'
        displayFields['Exp date'] = 'InventoryHistory.TransLineItemUD6'
        displayFields['User'] = 'InventoryHistory.UsersUserLogOn'
        hiddenFields['ItemID'] = 'InventoryHistory.ItemID'
        hiddenFields['LocationID'] = 'InventoryHistory.LocationID'
        hiddenFields['InventoryHistoryID'] = 'InventoryHistory.InventoryHistoryID'
        hiddenFields['ModelID'] = 'InventoryHistory.ModelID'
        hiddenFields['Notes'] = 'InventoryHistory.Notes'
        sorter = new joshPaginator({
            baseUrl: 'inventoryHistories/fetchPage/',
            sortBy: 'InventoryHistory.InventoryHistoryHistoryDateTime',
            sortDirection: 'desc',
            resultCount: 25,
            displayFields: displayFields,
            hiddenFields: hiddenFields
        });
        sorter.initialize();
    })
</script>

<h1>Inventory Transaction History</h1><br/>
<strong>Note: Field-specific filters will not detect matches with Inv Ctrl # = 'LOGENTRY' </strong>
<br/><br/>
<? echo $this->JoshPaginateImp->contentSpace(); ?>
