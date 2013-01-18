<table>
    <thead>
        <tr>
            <th><? echo $this->JoshPaginate->sortLink('Date', 'InventoryHistoryHistoryDateTime'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Trans Type', 'InvHistoryTypesInvHistoryType'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('P/N', 'ModelsModelCodeIN'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Rev', 'TransLineItemUD1'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Name', 'ModelsModelNameIN'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Inv Ctrl#', 'ItemsItemCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Quantity Change', 'InventoryHistoryQuantityChange'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Location', 'LocationsLocationCode'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Supplier', 'TransLineItemUD5'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Lot', 'TransLineItemUD2'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Batch', 'TransLineItemUD3'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('PO', 'TransLineItemUD4'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('Exp Date', 'TransLineItemUD6'); ?></th>
            <th><? echo $this->JoshPaginate->sortLink('User', 'UsersUserLogOn');?></th>
        </tr>
    </thead>
    <tbody>
        <?
        foreach ($results as $key => $result) {
            if ($key !== 'Pager') {
                if ($result['ItemCode'] == 'LOGENTRY') {
                    // Parse the xml in the comments field
                    $xmlElement = new SimpleXMLElement(html_entity_decode($result['Notes']));
                    ?>
                    <tr>  
                        <td><? echo date('m/d/Y H:i:s', strtotime($result['Date'])); ?></td>
                        <td><? echo $result['InvHistoryType']; ?></td>
                        <td><? echo $xmlElement->partNumber; ?></td>
                        <td>N/A</td>
                        <td><? echo $xmlElement->description; ?></td>
                        <td><? echo $result['ItemCode']; ?></td>
                        <td><? echo (float) $xmlElement->quantity . ' ' . $xmlElement->uom; ?></td>
                        <td><? echo $result['LocationCode']; ?></td>
                        <td><? echo $xmlElement->supplier; ?></td>
                        <td><?
                                            $materialId = '';
                                            if (strtolower($xmlElement->lot) == 'na' || strtolower($xmlElement->lot) == 'n/a' || strtolower($xmlElement->lot) == 'none') {
                                                $materialId = '';
                                            } else {
                                                $materialId = $xmlElement->lot;
                                            }
                                            if (strtolower($xmlElement->batch) == 'na' || strtolower($xmlElement->batch) == 'n/a' || strtolower($xmlElement->batch) == 'none') {
                                                $materialId .= '';
                                            } else {
                                                if ($materialId == '') {
                                                    $materialId = $xmlElement->batch;
                                                } else {
                                                    $materialId .= "/{$xmlElement->batch}";
                                                }
                                            }
                                            if($materialId == ''){$materialId = 'N/A';}
                                            echo $materialId;
                                            ?></td>
                        <td>N/A</td>     
                        <td><? echo $xmlElement->purchaseOrder; ?></td>
                        <td>N/A</td>
                        <td><? echo $result['UserLogOn'];?></td>
                    </tr>
                    <?
                } else {
                    ?>
                    <tr>
                        <td><? 
                        echo $this->Html->link(date('m/d/Y H:i:s', strtotime($result['Date'])), array(
                            'action'=>'view', $result['InventoryHistoryID']
                        )); 
                        ?></td>
                        <td><? 
                        echo $this->Html->link($result['InvHistoryType'], array(
                            'action'=>'view', $result['InventoryHistoryID']
                        )); 
                        ?></td>
                        <td><? echo $this->Html->link($result['PartNumber'], array(
                            'action'=>'view', 'controller'=>'parts', $result['ModelID']
                        )); ?></td>
                        <td><? echo $result['Revision']; ?></td>
                        <td><? echo $result['PartName']; ?></td>
                        <td><? echo $this->Html->link($result['ItemCode'], array(
                            'controller'=>'items', 'action'=>'view', $result['ItemID']
                        )); ?></td>
                        <td><? echo (float) $result['Quantity'] . ' ' . $result['Uom']; ?></td>
                        <td><? 
                        echo $this->Html->link($result['LocationCode'], array(
                            'action'=>'view', 'controller'=>'locations', $result['LocationID']
                        )); 
                        ?></td>
                        <td><? echo $result['Supplier']; ?></td>
                        <td><? echo $result['Lot']; ?></td>
                        <td><? echo $result['Batch']; ?></td>
                        <td><? echo $result['PurchaseOrder']; ?></td>
                        <td><?
            if (strtotime($result['ExpDate']) == 0) {
                echo 'N/A';
            } else {
                echo date('M Y', strtotime($result['ExpDate']));
            }
                    ?></td>
                        <td><? echo $result['UserLogOn'];?></td>
                    </tr>
                    <?
                }
            }
        }
        ?>
    </tbody>
</table>

<span id="pageLinks">
    <? echo $this->JoshPaginate->pageLinks($results['Pager']['total'], $results['Pager']['current'], 5); ?>
</span>

