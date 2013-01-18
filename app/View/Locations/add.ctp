<script>
$(document).ready(function(){
    $('#LocationAddForm').validate();
})
</script>
<h1>Create New Location</h1>
<br/>
<br/>
<?
echo $this->Form->create('Location');
echo $this->Form->input('LocationCode', array('class'=>'required'));
echo $this->Form->input('LocationName', array('class'=>'required'));
echo $this->Form->input('LocationType', array('class'=>'required', 'empty'=>true));
echo $this->Form->end('Submit');
?>