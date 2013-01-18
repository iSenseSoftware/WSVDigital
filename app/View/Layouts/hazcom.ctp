<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$title_for_layout = "WSV Inventory Management";
//$links = array(
//    'Transactions'=>array(
//        ''
//    ),
//    'Inventory'=>array(
//        
//    ),
//    'Parts'=>array(
//        
//    ),
//    'Suppliers'=>array(
//        
//    ),
//    'Misc'=>array(
//        
//    )
//);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('cake.generic');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->Html->script('jquery.js');
        echo $this->Html->script('jquery.tablesorter.min.js');
        echo $this->Html->script('jquery_paginate');
        echo $this->Html->script('tableFilter.js');
        echo $this->Html->script('encoder.js');
        ?>
        <script>
        $(document).ready(function(){
        $("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)  
      
        $("ul.topnav li span").click(function() { //When trigger is clicked...  
      
            //Following events are applied to the subnav itself (moving subnav up and down)  
            $(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click  
      
            $(this).parent().hover(function() {  
            }, function(){  
                $(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up  
            });  
      
            //Following events are applied to the trigger (Hover events for the trigger)  
            }).hover(function() {  
                $(this).addClass("subhover"); //On hover over, add class "subhover"  
            }, function(){  //On Hover Out  
                $(this).removeClass("subhover"); //On hover out, remove class "subhover"  
        });  
      
    });      
    </script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><?php echo $this->Html->link('WSV Inventory Management', array('controller' => 'pages', 'action' => 'index')); ?></h1>
                <? //echo $this->Logger->loginForm(); ?>
            </div>
                <ul class="topnav">  
    <li><? echo $this->Html->link('Home', array('action'=>'index', 'controller'=>'pages')); ?></li> 
    <li>  
        <? echo $this->Html->link('Inventory', array('controller'=>'items', 
            'action'=>'index'));?> 
    </li>
    <li><? echo $this->Html->link('History', array('controller'=>'InventoryHistories', 
        'action'=>'index'));?>
    <ul class="subnav">
        <li><? echo $this->Html->link('All', array(
            'controller'=>'inventoryHistories', 'action'=>'index'
        ));?></li>
        <li><? 
        echo $this->Html->link('Receiving Log', array(
            'controller'=>'inventoryHistories', 'action'=>'receivingLog'
        ))
        ?></li>
        <li><? 
        echo $this->Html->link('Issue History', array(
            'controller'=>'inventoryHistories', 'action'=>'issueHistory'
        ))
        ?></li>
    </ul>
    </li>
    <li>
        <?
        echo $this->Html->link('HazCom', array(
            'action'=>'hazcom', 'controller'=>'pages'
        ));
        ?>
    </li>
    <li>
        <?
        if($this->Session->read('User.UserLogOn')){
            echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'));
        }else{
            echo $this->Html->link('Login', array('controller'=>'users', 'action'=>'login'));
        }
        ?>
    </li>
                </ul> 
            <div id="content">

                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Session->flash('auth'); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">

            </div>
        </div>
        <?php echo $this->element('sql_dump');  ?>
    </body>
</html>
