<h1>Add New Unit of Measurement</h1>
<br/>
<?
echo $this->Form->create('Uom');
echo $this->Form->input('UOMCode', array('label'=>'UOM Code'));
echo $this->Form->input('UOM', array('label'=>'UOM Name'));
echo $this->Form->input('Multiplier', array('value'=>1, 'type'=>'hidden'));
echo $this->Form->end('Submit');
?>