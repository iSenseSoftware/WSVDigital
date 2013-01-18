<script>
    $(document).ready(function(){
        displayFields = {}
        hiddenFields = {}
        displayFields['Code'] = 'Location.LocationCode'
        displayFields['Name'] = 'Location.LocationName'
        displayFields['Type'] = 'LocationType.LocationTypeName'
        hiddenFields['LocationID'] = 'Location.LocationID'
        $sorter = new joshPaginator({
            baseUrl:'locations/fetchPage/',
            sortBy: 'Location.LocationCode',
            displayFields: displayFields,
            hiddenFields: hiddenFields
        });
        $sorter.initialize();
    });
</script>

<h2>Inventory Locations</h2><br/>
<h1>
<? echo $this->Html->link('Create new location', array('action'=>'add')); ?>
</h1><br/>
<? echo $this->JoshPaginateImp->contentSpace(); ?>
<br/>


