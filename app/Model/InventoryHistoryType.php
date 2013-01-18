<?php

/**
 * Description of InventoryHistoryType
 *
 * @author etbmx
 */
class InventoryHistoryType extends AppModel{
    public $useTable = 'InvHistoryTypes';
    public $primaryKey = 'InvHistoryTypeID';
    
    public $hasMany = array(
        'InventoryHistory'=>array(
            'className'=>'InventoryHistory',
            'foreignKey'=>'InvHistoryTypeID'
        )
    );
}

?>
