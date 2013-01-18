<? $result = $result[0][0]; ?>
<h1>Inventory History Detail</h1>
<br/><br/>
<strong>Inv History ID: </strong><? echo $result['InventoryHistoryID'];?><br/>
<strong>History Type: </strong><? echo $result['InvHistoryType']; ?><br/>
<strong>Date: </strong><? echo date('m/d/Y H:i:s', strtotime($result['Date']));?><br/>
<strong>Inv Ctrl#: </strong><? echo $this->Html->link($result['ItemCode'], array(
    'controller'=>'items', 'action'=>'view', $result['ItemID']
)); ?><br/>
<strong>Part Number: </strong><? echo $this->Html->link($result['PartNumber'], array(
    'controller'=>'parts', 'action'=>'view', $result['ModelID']
)); ?><br/>
<strong>Revision: </strong><? echo $result['Revision']; ?><br/>
<strong>Part Name: </strong><? echo $result['PartName']; ?><br/>
<strong>Quantity Change: </strong><? echo $result['Quantity'] . ' ' . $result['Uom']; ?><br/>
<strong>Location: </strong><? echo $this->Html->link($result['LocationCode'], array(
    'action'=>'view', 'controller'=>'locations', $result['LocationID']
));?><br/>
<? if($result['InvHistoryType'] == 'Issue'){?>
<strong>Issuee: </strong><? echo $result['IssueeCode'];?><br/>
<? } ?>
<strong>Supplier: </strong><? echo $result['Supplier']; ?><br/>
<strong>Purchase Order: </strong><? echo $result['PurchaseOrder']; ?><br/>
<strong>Exp Date: </strong><? 
if(strtotime($result['ExpDate']) == 0){
    echo 'N/A';
}else{
    echo date('M Y', strtotime($result['ExpDate'])); 
}
?><br/>
<strong>Lot: </strong><? echo $result['Lot']; ?><br/>
<strong>Batch: </strong><? echo $result['Batch']; ?><br/>
<strong>User: </strong><? echo $result['UserCode']; ?><br/>
<strong>Notes: </strong><? echo $result['Notes']; ?><br/>
