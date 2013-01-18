<h1>Update Unit of Measurement</h1>
<br/>
<?
echo $this->Form->create('Uom');
echo $this->Form->input('UOMCode', array('label'=>'UOM Code', 'value'=>$uom['Uom']['UOMCode']));
echo $this->Form->input('UOM', array('label'=>'UOM Name', 'value'=>$uom['Uom']['UOM']));
echo $this->Form->input('Multiplier', array('value'=>1, 'type'=>'hidden'));
echo $this->Form->input('UOMID', array('type'=>'hidden', 'value'=>$uom['Uom']['UOMID']));
echo $this->Form->end('Submit');
?>