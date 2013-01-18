<table>
  <thead>
  <th><? echo $this->JoshPaginate->sortLink('ID', 'Location.LocationID');?></th>
  <th><? echo $this->JoshPaginate->sortLink('Code', 'Location.LocationCode');?></th>
  <th><? echo $this->JoshPaginate->sortLink('Name', 'Location.LocationName');?></th>
  <th><? echo $this->JoshPaginate->sortLink('Type', 'LocationType.LocationTypeName');?></th>
  <th colspan="2">Actions</th>
  </thead>
  <tbody>
    <? foreach($results as $key=>$result){ 
        if($key !== 'Pager'){
        ?>
    <tr>
        <td>
            <? echo $this->Html->link($result['Location']['LocationID'], array(
            'action'=>'view', $result['Location']['LocationID']
        ));?>
        </td>
        <td>
            <? echo $this->Html->link($result['Location']['LocationCode'], array(
                'action'=>'view', $result['Location']['LocationID']
            ));
            ?>
        </td>
        <td>
            <? echo $this->Html->link($result['Location']['LocationName'], array(
                'action'=>'view', $result['Location']['LocationID']
            ));
            ?>
        </td>
        <td>
            <? echo $result['LocationType']['LocationTypeName']; ?>
        </td>
        <td>
            <? echo $this->Html->link('Edit', array('action'=>'edit', $result['Location']['LocationID'])); ?>
        </td>
        <td>
            <? echo $this->Form->postLink('Delete', array('action'=>'delete', $result['Location']['LocationID']), array('confirm'=>'Are you sure?')); ?>
        </td>
    </tr>
    <? }
    } ?>
  </tbody>
</table>
<span id="pageLinks">
<? echo $this->JoshPaginate->pageLinks($results['Pager']['total'], $results['Pager']['current'], 2); ?>
</span>

