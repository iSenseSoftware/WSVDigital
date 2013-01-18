<?php


/**
 * Description of Location
 *
 * @author etbmx
 */
class Location extends AppModel{
    public $useTable = 'Locations';
    public $primaryKey = 'LocationID';
    public $modelName = 'Location';
    
    public $hasMany = array(
        'Item'=>array(
            'className'=>'Item',
            'foreignKey'=>'LocationIDCurrent'
        ),
        'Part'=>array(
            'className'=>'Part', 
            'foreignKey'=>'LocationIDHome'
        )
    );
    
    public $belongsTo = array(
        'LocationType'=>array(
            'className'=>'LocationType',
            'foreignKey'=>'LocationTypeID'
        )
    );
}

?>
