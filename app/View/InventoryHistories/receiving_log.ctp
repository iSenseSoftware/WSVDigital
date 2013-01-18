<script>
$(document).ready(function(){
     $('#InventoryHistoryReceivingLogForm').validate();
})
</script>
<h2>Receiving Log</h2>
<p>
    <strong>Note: </strong>For materials received to a Bayer specification you must use the 
    <? echo $this->Html->link('Receive', array('action'=>'receive', 'controller'=>'items'));?> transaction.
</p>
<br/>

<?
echo $this->Form->create('InventoryHistory');
echo $this->Form->input('Carrier', array('class'=>'required'));
echo $this->Form->input('Supplier', array('class'=>'required'));
echo $this->Form->input('PurchaseOrder', array('label'=>'PO# / PCARD', 'class'=>'required'));
echo $this->Form->input('PartNumber', array('label'=>'P/N or Cat#'));
echo $this->Form->input('Description', array('class'=>'required'));
echo $this->Form->input('Lot');
echo $this->Form->input('Batch');
echo $this->Form->input('Quantity', array('label'=>'Qty', 'class'=>'required number'));
echo $this->Form->input('Uom', array('label'=>'UOM', 'class'=>'required'));
echo $this->Form->input('Comments', array('type'=>'textarea'));
echo $this->Form->end('Submit');
?>