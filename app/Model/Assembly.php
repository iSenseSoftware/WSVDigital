<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Assembly
 *
 * @author etbmx
 */
class Assembly extends AppModel{
    public $useTable = 'Assemblies';
    public $primaryKey = 'id';
    public $modelName = 'Assembly';
    public $belongsTo = array(
        'TopAssembly' => array(
            'className' => 'Part',
            'foreignKey' => 'TopID'
        ),
        'Component'=>array(
            'className'=>'Part',
            'foreignKey'=>'ComponentID'
        )
    );
}

?>
