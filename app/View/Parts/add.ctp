<script>
    $(document).ready(function(){
        $('#PartAddForm').validate();
    })
</script>
<pre>
<?php print_r($itemTypes);?>
</pre>
<h1>Add New Part</h1>
<br/><br/>
<?
echo $this->Form->create('Part');
echo $this->Form->input('ModelCode', array('class'=>'required', 'label'=>'P/N'));
echo $this->Form->input('ModelName', array('class'=>'required', 'label'=>'Name/Description'));
echo $this->Form->input('ItemType', array('empty'=>true, 'class'=>'required', 'name'=>'data[Part][ItemTypeID]'));
echo $this->Form->input('UDTextModel2', array('label'=>'Storage Requirements'));
echo $this->Form->input('Uom', array('empty'=>true, 'class'=>'required'));
echo $this->Form->input('MinOnHand');
echo $this->Form->input('MaxOrderTo');
echo $this->Form->input('LeadTime', array('label'=>'Lead Time (weeks)'));
echo $this->Form->input('Location', array('empty'=>true, 'label'=>'Default Released Location', 'name'=>'data[Part][LocationIDHome]'));
echo $this->Form->input('UDNumericModel1', array('type'=>'checkbox', 'value'=>1, 'label'=>'This part is an assembly'));
echo $this->Form->end('Submit');
?>