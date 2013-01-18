<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItemType
 *
 * @author etbmx
 */
class ItemType extends AppModel{
    public $useTable = 'ItemTypes';
    public $primaryKey = 'ItemTypeID';
    
    public $hasMany = array(
        'Part'=>array(
            'className'=>'Part',
            'foreignKey'=>'ItemTypeID'
        )
    );
}

?>
