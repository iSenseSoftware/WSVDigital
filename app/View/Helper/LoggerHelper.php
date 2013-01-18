<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logger
 *
 * @author etbmx
 */
App::uses('AppHelper', 'View/Helper');
class LoggerHelper extends AppHelper {
    public $helpers = array('Form');
    
    public function loginForm(){
        $form = "<div id='login-form'>";
        $form .= $this->Form->create('User', array('controller'=>'users', 'action'=>'login'));
        $form .= $this->Form->input('username');
        $form .= $this->Form->input('password');
        $form .= $this->Form->end('login');
        $form .= "</div>";
        return $form;
    }
    
}

?>
