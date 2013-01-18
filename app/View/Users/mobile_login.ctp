<div data-role="header">
<? echo $this->Session->flash(); ?>
<h1>Login:</h1>
</div>
<div data-role="content">
<?
echo $this->Form->create('User');
echo $this->Form->input('UserLogOn', array('label'=>'User'));
echo $this->Form->input('UserPassword', array('type'=>'password', 'label'=>'Password'));
echo $this->Form->end('Submit');    
?>
</div>