<script>
$(document).ready(function(){
    $('#LocationEditForm').validate();
})
</script>
<h1>Edit Location</h1>
<br/>
<br/>
<?
echo $this->Form->create('Location');
echo $this->Form->input('LocationCode', array('class'=>'required', 'value'=>$location['Location']['LocationCode']));
echo $this->Form->input('LocationName', array('class'=>'required', 'value'=>$location['Location']['LocationName']));
echo $this->Form->input('LocationType', array('class'=>'required', 'value'=>$location['LocationType']['LocationTypeID']));
echo $this->Form->input('LocationID', array('type'=>'hidden', 'value'=>$location['Location']['LocationID']));
echo $this->Form->end('Submit');
?>