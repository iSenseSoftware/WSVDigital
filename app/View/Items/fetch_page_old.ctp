<script>
$(document).ready(function(){
    $('.expired').parent().css('background-color', 'tomato');
});
</script>

<table>
    <thead>
        <tr>
            <th><? echo $this->JoshPaginate->sortLink('Inv Ctrl#', 'Item.ItemCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('P/N', 'Part.ModelCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Rev', 'Item.TransLineItemUD1'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Name', 'Part.ModelName'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Location', 'Location.LocationCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Qty', 'Item.Quantity'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('UOM', 'Uom.UOMCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Status', 'LocationType.LocationTypeCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Lot', 'Item.TransLineItemUD2'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Batch', 'Item.TransLineItemUD3'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Exp Date', 'Item.TransLineItemUD6'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('PO', 'Item.TransLineItemUD4'); ?></th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?
        foreach ($items as $key => $item) {
            if ($key !== 'Pager') {
                ?>
                <tr>
                    <td><? echo $this->Html->link($item[0]['ItemCode'], array('action'=>'view', $item[0]['ItemID'])); ?></td>
                    <td><? echo $this->Html->link($item[0]['ModelCode'], array('action'=>'view', 'controller'=>'parts', $item[0]['ModelID'])); ?></td>
                    <td><? echo $item[0]['TransLineItemUD1']; ?></td>
                    <td><? echo $item[0]['ModelName']; ?></td>
                    <td><? 
                    echo $this->Html->link($item[0]['LocationCode'], array('action'=>'view', 'controller'=>'locations', $item[0]['LocationID'])); 
                    ?></td>
                    <td><? echo (float) $item[0]['Quantity']; ?></td>
                    <td><? echo $item[0]['UOMCode']; ?></td>
                    <td><? echo $item[0]['LocationTypeCode']; ?></td>
                    <td><? echo $item[0]['TransLineItemUD2']; ?></td>
                    <td><? echo $item[0]['TransLineItemUD3']; ?></td>
                    <?
                if (strtotime($item[0]['TransLineItemUD6']) == 0) {
                    echo '<td>N/A</td>';
                } else {
                    if(strtotime($item[0]['TransLineItemUD6']) < Time()){
                        echo "<td class='expired'>" . date('M Y', strtotime($item[0]['TransLineItemUD6'])) . '</td>';
                    }else{
                    echo '<td>' . date('M Y', strtotime($item[0]['TransLineItemUD6'])) . '</td>';
                    }
                }
                ?></td>

                    <td><? echo $item[0]['TransLineItemUD4']; ?></td>
                    <td>
                        <?
                        if ($this->Session->read('User.canIssue') == true) {
                            echo $this->Html->link('Issue', array('action' => 'issue', $item[0]['ItemID']));
                            echo '&nbsp;&nbsp;&nbsp;';
                            echo $this->Html->link('Move', array('action'=>'move', $item[0]['ItemID']));
                            echo '&nbsp;&nbsp;&nbsp;';
                            echo $this->Html->link('Adjust', array('action'=>'adjust', $item[0]['ItemID']));
                           
                        }else{
                            echo $this->Html->link('Login', array('controller'=>'users', 'action'=>'login'));
                        }
                        echo '&nbsp;&nbsp;&nbsp;';
                        echo $this->Html->link('View', array('action'=>'view', $item[0]['ItemID']));
                        ?>
                    </td>
                </tr>
            <? }
        }
        ?>
    </tbody>
</table>
<span id="pageLinks">
<? echo $this->JoshPaginate->pageLinks($items['Pager']['total'], $items['Pager']['current'], 6); ?>
</span>

