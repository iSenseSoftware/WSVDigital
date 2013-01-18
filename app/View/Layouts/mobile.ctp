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
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php
            echo $this->Html->meta('icon');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
            echo $this->Html->script('jquery.js');
            echo $this->Html->script('jquery.tablesorter.min.js');
            echo $this->Html->css('jquery.mobile-1.1.1.css');
            echo $this->Html->script('jquery.mobile-1.1.1.min');
            echo $this->Html->script('jquery.validate.min.js');
            ?>

    </head>
    <body>
        <div id="content">
            <?php echo $this->fetch('content'); ?>
        </div>
    </body>
</html>
