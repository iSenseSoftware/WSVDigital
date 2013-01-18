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
$title_for_layout = "WSV Digital";

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
        echo $this->Html->css('ui-lightness/jquery-ui-1.8.23.custom');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        
        echo $this->Html->script('jquery.js');
        echo $this->Html->script('jquery.tablesorter.min.js');
        echo $this->Html->script('jquery-ui-1.8.23.custom.min.js');
        echo $this->Html->script('jquery-paginate-1.0.0');
        echo $this->Html->script('tableFilter.js');
        echo $this->Html->script('encoder.js');
        echo $this->Html->script('jquery.validate.min.js');
        echo $this->Html->script('additional-methods.min.js');
        echo $this->Html->script('jquery-print');
        
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
            function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
        </script>

    </head>
    <body>
        <noscript>
            <style>
                #content{
                    display:none;
                }
                #noScriptWarning{
                    display:inline;
                    float:left;
                }
            </style>

        </noscript>
        <div id="container">
            <div id="header">
                <h1><?php echo $this->Html->link('WSV Digital', array('controller' => 'pages', 'action' => 'index')); ?></h1>
                <? //echo $this->Logger->loginForm(); ?>
                
            </div>
            <ul class="topnav">  
                <li><? echo $this->Html->link('Home', array('action' => 'index', 'controller' => 'pages')); ?></li> 
                <li>  
                    <?
                    echo $this->Html->link('Inventory', array('controller' => 'items',
                        'action' => 'index'));
                    ?> 
                    <ul class="subnav">
                        <li><? echo $this->Html->link('View All', array('controller'=>'items', 'action'=>'index'));?></li>
                        <li><? echo $this->Html->link('Receive', array('controller'=>'items', 'action'=>'receive'));?></li>
                        <li><? echo $this->Html->link('Issue', array('controller'=>'items', 'action'=>'issue'));?></li>
                        <li><? echo $this->Html->link('Move', array('controller'=>'items', 'action'=>'move'));?></li>
                        <li><? echo $this->Html->link('Adjust', array('controller'=>'items', 'action'=>'adjust'));?></li>
                    </ul>
                </li>
                <li><?
                    echo $this->Html->link('History', array('controller' => 'InventoryHistories',
                        'action' => 'index'));
                    ?>
                    <ul class="subnav">
                        <li><?
                    echo $this->Html->link('All', array(
                        'controller' => 'inventoryHistories', 'action' => 'index'
                    ));
                    ?></li>
                        <li><?
                            echo $this->Html->link('Receiving Log', array(
                                'controller' => 'inventoryHistories', 'action' => 'receivingLog'
                            ))
                    ?></li>
                        <!--<li><?
                        echo $this->Html->link('Issue History', array(
                            'controller' => 'inventoryHistories', 'action' => 'issueHistory'
                        ))
                    ?></li>-->
                    </ul>
                </li>
                <li>
                    <?
                    echo $this->Html->link('HazCom', array(
                        'action' => 'index', 'controller' => 'hazards'
                    ));
                    ?>
                </li>
                <li><a href="#">Other</a>
                    <ul class="subnav">
                        <li><? echo $this->Html->link('Parts', array('action'=>'index', 'controller'=>'parts'));?></li>
                        <li><? echo $this->Html->link('Locations', array('action'=>'index', 'controller'=>'locations'));?></li>
                        <li><? echo $this->Html->link('Assemblies', array('action'=>'index', 'controller'=>'assemblies'));?></li>
                        <li><?php  echo $this->Html->link('Hazardous Waste Labels', array('action'=>'hazardousWasteLabels', 'controller'=>'pages'))?></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#">Shipping</a>
                    <ul class="subnav">
                        <li><a href="http://www.fedex.com/us/">FedEx</a></li>
                        <li><? echo $this->Html->link('Packing List', array('action'=>'packingList', 'controller'=>'items'));?></li>
                    </ul>
                </li>
                <li>
                    <?
                    if ($this->Session->read('User.UserLogOn')) {
                        $user = $this->Session->read('User.UserLogOn');
                        echo $this->Html->link("Logout (logged in as $user)", array('controller' => 'users', 'action' => 'logout'));
                    } else {
                        echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'));
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
                <div id="noScriptWarning">
                        Javascript is required to use this page.  Enable Javascript in your browser settings and refresh the page.
                </div>
            </div>
        </div>
<?php //echo $this->element('sql_dump');    ?>
    </body>
</html>
