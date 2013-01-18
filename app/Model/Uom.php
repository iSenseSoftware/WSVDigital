<?php


/**
 * Description of Uom
 *
 * @author etbmx
 */
class Uom extends AppModel{
    public $useTable = 'UOMs';
    public $primaryKey = 'UOMID';
    
    public $hasMany = array(
        'Part'=>array(
            'className'=>'Part',
            'foreignKey'=>'UOMIDInventory'
        )
    );
}

?>
