<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Part
 *
 * @author etbmx
 */
class Part extends AppModel {

    public $useTable = 'Models';
    public $primaryKey = 'ModelID';
    public $modelName = 'Part';
    public $hasMany = array(
        'Item' => array(
            'className' => 'Item',
            'foreignKey' => 'ModelID'
        ),
        'TopAssemblyFor' => array(
            'className' => 'Assembly',
            'foreignKey' => 'TopID'
        ),
        'ComponentOf' => array(
            'className' => 'Assembly',
            'foreignKey' => 'ComponentID'
        )
    );
    public $belongsTo = array(
        'Uom' => array(
            'className' => 'Uom',
            'foreignKey' => 'UOMIDInventory'
        ),
        'TimeUnit' => array(
            'className' => 'TimeUnit',
            'foreignKey' => 'TimeUnitID'
        ),
        'DefaultLocation' => array(
            'className' => 'Location',
            'foreignKey' => 'LocationIDHome'
        ),
        'ItemType' => array(
            'className' => 'ItemType',
            'foreignKey' => 'ItemTypeID'
        ),
    );
    public $virtualFields = array(
        'numberAndName' => '(Part.ModelCode + \' \' + Part.ModelName)'
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['numberAndName'] = sprintf('%s.ModelCode + \' \' + %s.ModelName', $this->alias, $this->alias);
    }

}

?>
