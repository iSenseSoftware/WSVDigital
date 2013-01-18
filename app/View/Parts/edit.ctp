<script>
    $(document).ready(function(){
        $('#PartAddForm').validate();
    })
</script>
<h1>Edit Part</h1>
<br/><br/>
<?
echo $this->Form->create('Part');
echo $this->Form->input('ModelCode', array('class'=>'required', 'value'=>$part['Part']['ModelCode']));
echo $this->Form->input('ModelName', array('class'=>'required', 'value'=>$part['Part']['ModelName']));
echo $this->Form->input('ItemType', array('empty'=>true, 'class'=>'required', 'value'=>$part['Part']['ItemTypeID'], 'name'=>'data[Part][ItemTypeID]'));
echo $this->Form->input('UDTextModel2', array('label'=>'Storage Requirements', 'value'=>$part['Part']['UDTextModel2']));
echo $this->Form->input('Uom', array('empty'=>true, 'class'=>'required', 'value'=>$part['Uom']['UOMID']));
echo $this->Form->input('MinOnHand', array('value'=>$part['Part']['MinOnHand']));
echo $this->Form->input('MaxOrderTo', array('value'=>$part['Part']['MaxOrderTo']));
echo $this->Form->input('LeadTime', array('label'=>'Lead Time (weeks)', 'value'=>$part['Part']['LeadTime']));
echo $this->Form->input('Location', array('empty'=>true, 'label'=>'Default Released Location', 'value'=>$part['DefaultLocation']['LocationID'], 'name'=>'data[Part][LocationIDHome]'));
if($part['Part']['UDNumericModel1'] == 1){
   echo $this->Form->input('UDNumericModel1', array('type'=>'checkbox', 'value'=>1, 'label'=>'This part is an assembly', 'checked'=>'checked')); 
}else{
  echo $this->Form->input('UDNumericModel1', array('type'=>'checkbox', 'value'=>1, 'label'=>'This part is an assembly'));  
}

echo $this->Form->input('ModelID', array('type'=>'hidden', 'value'=>$part['Part']['ModelID']));
echo $this->Form->end('Submit');
?>