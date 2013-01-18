<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeUnit
 *
 * @author etbmx
 */
class TimeUnit extends AppModel{
    //put your code here
    public $useTable = 'TimeUnits';
    public $primaryKey = 'TimeUnitID';
    public $hasMany = array(
      'Part'=>array(
          'className'=>'Part',
          'foreignKey'=>'TimeUnitID'
      )  
    );
}

?>
