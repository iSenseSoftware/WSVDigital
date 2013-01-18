<table>
    <thead>
        <tr>
            <?
            foreach ($data['displayFields'] as $alias => $field) {
                if (isset($data['fieldFilters'][$field])) {
                    echo '<th>' . $this->JoshPaginateImp->sortLink($alias, $field, $data['fieldFilters'][$field]) . '</th>';
                } else {
                    echo '<th>' . $this->JoshPaginateImp->sortLink($alias, $field) . '</th>';
                }
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?
        $allFields = (isset($data['hiddenFields']))?array_merge($data['hiddenFields'], $data['displayFields']):$data['displayFields'];
        foreach ($items as $key => $item) {
            if ($key !== 'Pager') {
                if ($item['ItemsItemCode'] == 'LOGENTRY') {
                    // Parse the xml in the comments field
                    $xmlElement = new SimpleXMLElement(html_entity_decode($item['Notes']));
                    $item['ModelsModelCodeIN'] = $xmlElement->partNumber;
                    $item['ModelsModelNameIN'] = $xmlElement->description;
                    $item['InventoryHistoryQuantityChange'] = $xmlElement->quantity;
                    $item['UOMsUOMCode'] = $xmlElement->uom;
                    $item['TransLineItemUD5'] = $xmlElement->supplier;
                    $item['TransLineItemUD2'] = $xmlElement->lot;
                    $item['TransLineItemUD3'] = $xmlElement->batch;
                    $item['TransLineItemUD4'] = $xmlElement->purchaseOrder; 
                    $item['Notes'] = $xmlElement->comments;
                    unset($item['ModelID']);
                }
                ?>
                <tr>
                    <?
                    foreach ($data['displayFields'] as $field) {
                        $splitField = explode('.', $field);
                        $field = $splitField[1];
                        $model = $splitField[0];
                        ?>
                        <td>
                            <?
            switch ($field) {
                case 'InventoryHistoryQuantityChange':
                    echo (float) $item[$field];
                    break;
                case 'LocationsLocationCode':
                    if (isset($item['LocationID'])) {
                        echo $this->Html->link($item['LocationsLocationCode'], array('controller' => 'locations', 'action' => 'view', $item['LocationID']));
                    } else {
                        echo $item['LocationsLocationCode'];
                    }
                    break;
                case 'ItemsItemCode':
                    if (isset($item['ItemID'])) {
                        echo $this->Html->link($item['ItemsItemCode'], array('controller' => 'items', 'action' => 'view', $item['ItemID']));
                    } else {
                        echo $item['ItemsItemCode'];
                    }
                    break;
                case 'InventoryHistoryID':
                    if(isset($item['InventoryHistoryID'])){
                        echo $this->Html->link($item['InventoryHistoryID'], array('controller'=>'inventoryHistories', 'action'=>'view', $item['InventoryHistoryID']));
                    }else{
                        echo $item['InventoryHistoryID'];
                    }
                    break;
                case 'InventoryHistoryHistoryDateTime':
                    if(isset($item['InventoryHistoryID'])){
                        echo $this->Html->link(date('m/d/Y H:i:s', strtotime($item['InventoryHistoryHistoryDateTime'])), array('controller'=>'inventoryHistories', 'action'=>'view', $item['InventoryHistoryID']));
                    }else{
                        echo $item['InventoryHistoryHistoryDateTime'];
                    }
                    break;
                case 'ModelsModelCodeIN':
                    if(isset($item['ModelID'])){
                        echo $this->Html->link($item['ModelsModelCodeIN'], array('controller'=>'parts', 'action'=>'view', $item['ModelID']));
                    }else{
                        echo $item['ModelsModelCodeIN'];
                    }
                    break;
                case 'ModelsModelNameIN':
                    if(isset($item['ModelID'])){
                        echo $this->Html->link($item['ModelsModelNameIN'], array('controller'=>'parts', 'action'=>'view', $item['ModelID']));
                    }else{
                        echo $item['ModelsModelNameIN'];
                    }
                    break;
                default:
                    echo $item[$field];
                    break;
            }
                        ?></td>
                    <? } ?>
                </tr>
                <?
            }
        }
        ?>
    </tbody>
</table>


<? echo $this->JoshPaginateImp->pageLinks($items['Pager']['total'], $items['Pager']['current'], 5); ?>


