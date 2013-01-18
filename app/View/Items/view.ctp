<script>
    $(document).ready(function(){
        $('#labelViewForm').submit(function(event){
            event.preventDefault();
        
            //        if(qty == ''){
            //            alert('You must enter a quantity');
            //        }else{
            //            window.location.href = "../printSingleLabel/" + itemId + '/' + qty;
            //        }
            if($(this).valid()){
                itemId = $('#itemId').val();
                qty = $('#labelQuantity').val();
                window.location.href = "../printSingleLabel/" + itemId + '/' + qty;
            }   
        })
        $('#ItemGenerateCertForm').validate();
        $('#labelViewForm').validate();
//        relatedDisplayFields = {}
//        relatedHiddenFields = {}
//        relatedFilter = {}
//        historyDisplayFields = {}
//        historyHiddenFields = {}
//        historyFilter = {}
//        historyDisplayFields['Date'] = 'InventoryHistory.InventoryHistoryHistoryDateTime'
//        historyDisplayFields['Trans Type'] = 'InventoryHistory.InvHistoryTypesInvHistoryType'
//        historyHiddenFields['P/N'] = 'InventoryHistory.ModelsModelCodeIN'
//        historyHiddenFields['Rev'] = 'InventoryHistory.TransLineItemUD1'
//        historyHiddenFields['Name'] = 'InventoryHistory.ModelsModelNameIN'
//        historyHiddenFields['Inv Ctrl#'] = 'InventoryHistory.ItemsItemCode'
//        historyDisplayFields['Qty Change'] = 'InventoryHistory.InventoryHistoryQuantityChange'
//        historyDisplayFields['Location'] = 'InventoryHistory.LocationsLocationCode'
//        historyDisplayFields['Supplier'] = 'InventoryHistory.TransLineItemUD5'
//        historyDisplayFields['Lot'] = 'InventoryHistory.TransLineItemUD2'
//        historyDisplayFields['Batch'] = 'InventoryHistory.TransLineItemUD3'
//        historyHiddenFields['PO'] = 'InventoryHistory.TransLineItemUD4'
//        historyHiddenFields['Exp date'] = 'InventoryHistory.TransLineItemUD6'
//        historyDisplayFields['User'] = 'InventoryHistory.UsersUserLogOn'
//        historyHiddenFields['ItemID'] = 'InventoryHistory.ItemID'
//        historyHiddenFields['LocationID'] = 'InventoryHistory.LocationID'
//        historyHiddenFields['InventoryHistoryID'] = 'InventoryHistory.InventoryHistoryID'
//        historyHiddenFields['ModelID'] = 'InventoryHistory.ModelID'
//        historyHiddenFields['Notes'] = 'InventoryHistory.Notes'
//        historyFilter['InventoryHistory.ItemID'] = <? echo $result['Item']['ItemID'];?>;
//        
//        relatedDisplayFields['Inv Ctrl#'] = 'Item.ItemCode'
//        relatedHiddenFields['P/N'] = 'Part.ModelCode'
//        relatedDisplayFields['Rev'] = 'Item.TransLineItemUD1'
//        relatedHiddenFields['Name'] = 'Part.ModelName'
//        relatedDisplayFields['Location'] = 'Location.LocationCode'
//        relatedDisplayFields['Qty'] = 'Item.Quantity'
//        relatedDisplayFields['Uom'] = 'UOM.UOMCode'
//        relatedDisplayFields['Status'] = 'LocationType.LocationTypeCode'
//        relatedDisplayFields['Lot'] = 'Item.TransLineItemUD2'
//        relatedDisplayFields['Batch'] = 'Item.TransLineItemUD3'
//        relatedHiddenFields['Exp Date'] = 'Item.TransLineItemUD6'
//        relatedHiddenFields['Purchase Order'] = 'Item.TransLineItemUD4'
//        relatedHiddenFields['ItemID'] = 'Item.ItemID'
//        relatedHiddenFields['ModelID'] = 'Part.ModelID'
//        relatedHiddenFields['LocationID'] = 'Location.LocationID'
//        relatedFilter['Part.ModelID'] = <? echo $result['Item']['ModelID']; ?>;
        
//        historySorter = new joshPaginator({
//            baseUrl:'../../inventoryHistories/fetchPage',
//            sortBy: 'InventoryHistoryHistoryDateTime',
//            sortDirection: 'desc',
//            resultCount: 25,
//            displayFields: historyDisplayFields,
//            hiddenFields: historyHiddenFields,
//            permFilters:historyFilter,
//            contentId:'#history',
//            resultsId:'#historyResults',
//            availableColsId:'#availableHistory',
//            selectedColsId:'#selectedHistory',
//            connectClass:'.historyConnector',
//            pagingId:'#historyPaging'
//        })
        
//        relatedSorter = new joshPaginator({
//            baseUrl:'../../items/fetchPage',
//            sortBy: 'Item.ItemCode',
//            sortDirection: 'desc',
//            resultCount: 10,
//            displayFields: relatedDisplayFields,
//            hiddenFields: relatedHiddenFields,
//            permFilters:relatedFilter//,
//            contentId:'#relatedInventory',
//            resultsId:'#relatedResults',
//            availableColsId:'#availableRelated',
//            selectedColsId:'#selectedRelated',
//            connectClass:'.relatedConnector',
//            pagingId:'#relatedPaging'
//        })
//        relatedSorter.initialize()
//        historySorter.initialize()
        
    })
</script>
<h2>Stock Item Summary</h2>

<div id="ItemSummary">
    <table>
        <thead>
        <th>Identification</th>
        <th>Stocking Info</th>
        </thead>
        <tbody>
            <tr>
                <td><? // Here is where the basic item summary will go   ?>
                    <strong>Inv Ctrl#: </strong><? echo $result['Item']['ItemCode']; ?><br/>
                    <strong>P/N: </strong><? echo $result['Part']['ModelCode']; ?> <strong>Rev </strong>
                    <? echo $result['Item']['TransLineItemUD1']; ?><br/>
                    <strong>Location: </strong><? echo $result['Location']['LocationCode']; ?></br>
                    <strong>Qty: </strong><? echo (float) $result['Item']['Quantity'] . ' ' . $result['Part']['Uom']['UOMCode']; ?><br/>
                    <strong>Status: </strong><? echo $result['Location']['LocationType']['LocationTypeName']; ?><br/>
                    <strong>Lot: </strong><? echo $result['Item']['TransLineItemUD2']; ?>&nbsp;&nbsp;
                    <strong>Batch: </strong><? echo $result['Item']['TransLineItemUD3']; ?><br/>
                    <strong>Exp Date: </strong><?
                    if (strtotime($result['Item']['TransLineItemUD6']) == 0) {
                        echo 'N/A';
                    } else {
                        echo date('d M Y', strtotime($result['Item']['TransLineItemUD6']));
                    }
                    ?><br/>
                    <strong>PO: </strong><? echo $result['Item']['TransLineItemUD4']; ?><br/>
                    <strong>Supplier: </strong><? echo $result['Item']['TransLineItemUD5']; ?>
                </td>
                <td>
                    <strong>Min on hand: </strong><? echo isset($result['Part']['MinOnHand']) ? (float) $result['Part']['MinOnHand'] : 'Not Set'; ?><br/>
                    <strong>Max to stock: </strong><? echo isset($result['Part']['MaxOrderTo']) ? (float) $result['Part']['MaxOrderTo'] : 'Not set'; ?><br/>
                    <strong>Lead Time: </strong><? echo isset($result['Part']['LeadTime']) ? (float) $result['Part']['LeadTime'] . ' ' . $result['Part']['TimeUnit']['TimeUnit'] : 'Not set'; ?><br/>
                    <strong>Default Location: </strong><? echo isset($result['Part']['DefaultLocation']['LocationCode']) ? $result['Part']['DefaultLocation']['LocationCode'] : 'Not set'; ?><br/>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?
if ($result['Location']['LocationType']['LocationTypeCode'] == 'REL' &&
        $result['Part']['UDNumericModel1'] == 1) {
    ?>
    <div id="certPrinting" style="border:2px solid black;padding:1em;">
        <strong>Certificate of Conformance:</strong>
        <?
        echo $this->Form->create('Item', array('action' => 'generateCert'));
        echo $this->Form->input('CustomerPO', array('label' => 'Customer PO:', 'class' => 'required'));
        echo $this->Form->input('Quantity', array('class' => 'required'));
        echo $this->Form->input('Comments', array('label' => 'Additional Info:'));
        echo $this->Form->input('ItemID', array('type' => 'hidden', 'value' => $result['Item']['ItemID']));
        echo $this->Form->end('Create CoC')
        ?>
    </div>
<? } ?>
<br/>
<h2>Create Labels:</h2>
<div id="labelPrinting" style="border:2px solid black;padding:1em;">
<? echo $this->Form->create('label'); ?>
    <label for="labelQuantity">Qty per Label:</label>
    <input id="labelQuantity" class="required number">
    <input id="itemId" type="hidden" value="<? echo $result['Item']['ItemID']; ?>">
    <? echo $this->Form->end('Submit');
//    <button type="submit" id="labelSubmit">Create</button>
    ?>
</div>
<br/>
<br/>
<div id="RelatedItems">
    <div id="SamePartInInventory">
        <strong>Related Inventory</strong><br/>
        <?
        if (count($partsInStock) == 0) {
            echo 'No related inventory found';
        } else {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Inv Ctrl#</th>
                        <th>P/N</th>
                        <th>Rev</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Qty</th>
                        <th>UOM</th>
                        <th>Status</th>
                        <th>Lot</th>
                        <th>Batch</th>
                        <th>Exp Date</th>
                        <th>PO</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    foreach ($partsInStock as $key => $item) {
                        if ($key !== 'Pager') {
                            ?>
                            <tr>
                                <td><? echo $this->Html->link($item[0]['ItemCode'], array('action' => 'view', $item[0]['ItemID'])); ?></td>
                                <td><? echo $item[0]['ModelCode']; ?></td>
                                <td><? echo $item[0]['TransLineItemUD1']; ?></td>
                                <td><? echo $item[0]['ModelName']; ?></td>
                                <td><? echo $item[0]['LocationCode']; ?></td>
                                <td><? echo (float) $item[0]['Quantity']; ?></td>
                                <td><? echo $item[0]['UOMCode']; ?></td>
                                <td><? echo $item[0]['LocationTypeCode']; ?></td>
                                <td><? echo $item[0]['TransLineItemUD2']; ?></td>
                                <td><? echo $item[0]['TransLineItemUD3']; ?></td>
                                <?
                                if (strtotime($item[0]['TransLineItemUD6']) == 0) {
                                    echo '<td>N/A</td>';
                                } else {
                                    if (strtotime($item[0]['TransLineItemUD6']) < Time()) {
                                        echo "<td class='expired'>" . date('M Y', strtotime($item[0]['TransLineItemUD6'])) . '</td>';
                                    } else {
                                        echo '<td>' . date('M Y', strtotime($item[0]['TransLineItemUD6'])) . '</td>';
                                    }
                                }
                                ?></td>

                                <td><? echo $item[0]['TransLineItemUD4']; ?></td>
                            </tr>
                            <?
                        }
                    }
                    ?>
                </tbody>
            </table>
<? } ?>
    </div>
    <br/><br/>
    <div id="RecentHistory">       
        <strong>Recent Transactions:</strong>
        <?
        if (count($recentHistory) == 0) {
            echo 'No transactions found';
        } else {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Trans Type</th>
                        <th>P/N</th>
                        <th>Name</th>
                        <th>Inv Ctrl#</th>
                        <th>Quantity Change</th>
                        <th>Location</th>
                        <th>Supplier</th>
                        <th>Lot</th>
                        <th>Batch</th>
                        <th>PO</th>
                        <th>Exp Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    foreach ($recentHistory as $key => $result) {
                        if ($key !== 'Pager') {
                            if ($result[0]['ItemCode'] == 'LOGENTRY') {
                                // Parse the xml in the comments field
                                $xmlElement = new SimpleXMLElement(html_entity_decode($result[0]['Notes']));
                                ?>
                                <tr>  
                                    <td><? echo date('m/d/Y H:i:s', strtotime($result[0]['Date'])); ?></td>
                                    <td><? echo $result[0]['InvHistoryType']; ?></td>
                                    <td><? echo $xmlElement->partNumber; ?></td>
                                    <td><? echo $xmlElement->description; ?></td>
                                    <td><? echo $result[0]['ItemCode']; ?></td>
                                    <td><? echo (float) $xmlElement->quantity; ?></td>
                                    <td><? echo $result[0]['LocationCode']; ?></td>
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
                        if ($materialId == '') {
                            $materialId = 'N/A';
                        }
                        echo $materialId;
                        ?></td>
                                    <td>N/A</td>     
                                    <td><? echo $xmlElement->purchaseOrder; ?></td>
                                    <td>N/A</td>
                                </tr>
                                <?
                            } else {
                                ?>
                                <tr>
                                    <td><? echo date('m/d/Y H:i:s', strtotime($result[0]['Date'])); ?></td>
                                    <td><? echo $result[0]['InvHistoryType']; ?></td>
                                    <td><? echo $result[0]['PartNumber']; ?></td>
                                    <td><? echo $result[0]['PartName']; ?></td>
                                    <td><? echo $result[0]['ItemCode']; ?></td>
                                    <td><? echo (float) $result[0]['Quantity'] . ' ' . $result[0]['Uom']; ?></td>
                                    <td><? echo $result[0]['LocationCode']; ?></td>
                                    <td><? echo $result[0]['Supplier']; ?></td>
                                    <td><? echo $result[0]['Lot']; ?></td>
                                    <td><? echo $result[0]['Batch']; ?></td>
                                    <td><? echo $result[0]['PurchaseOrder']; ?></td>
                                    <td><?
                        if (strtotime($result[0]['ExpDate']) == 0) {
                            echo 'N/A';
                        } else {
                            echo date('M Y', strtotime($result[0]['ExpDate']));
                        }
                                ?></td>
                                </tr>
                                <?
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
<? } ?>
    </div>
</div>