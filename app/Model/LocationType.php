<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocationType
 *
 * @author etbmx
 */
class LocationType extends AppModel{
    public $useTable = 'LocationTypes';
    public $primaryKey = 'LocationTypeID';
    
    public $hasMany = array(
        'Location'=>array(
            'className'=>'Location',
            'foreignKey'=>'LocationTypeID'
        )
    );
}

?>
