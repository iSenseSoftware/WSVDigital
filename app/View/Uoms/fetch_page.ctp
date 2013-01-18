<table>
  <thead>
    <th><? echo $this->JoshPaginate->sortLink('ID', 'Uom.UOMID');?></th>
    <th><? echo $this->JoshPaginate->sortLink('UOM Code', 'Uom.UOMCode');?></th>
    <th><? echo $this->JoshPaginate->sortLink('UOM Name', 'Uom.UOM');?></th>
    <th colspan="2">Actions</th>
  </thead>
  <tbody>
    <? foreach($results as $key=>$result){ 
        if($key !== 'Pager'){
        ?>
    <tr>
        <td><? echo $result['Uom']['UOMID'];?></td>
        <td><? echo $result['Uom']['UOMCode'];?></td>
        <td><? echo $result['Uom']['UOM'];?><td/>
        <td><? echo $this->Html->link('Edit', array('action'=>'edit', $result['Uom']['UOMID']));?></td>
        <td><? echo $this->Form->postLink('Delete', array('action'=>'delete', $result['Uom']['UOMID']), 
                array('confirm'=>'Are you sure?'));?></td>
    </tr>
    <? }
    } ?>
  </tbody>
</table>
<? echo $this->JoshPaginate->pageLinks($results['Pager']['total'], $results['Pager']['current'], 2, $data['pagingId']); ?>


