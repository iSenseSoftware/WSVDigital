<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecurityLevel
 *
 * @author etbmx
 */
class SecurityLevel extends AppModel{
    public $useTable = 'SecurityLevels';
    public $primaryKey = 'SecurityLevelID';
    
    public $hasAndBelongsToMany = array(
        'User'=>array(
            'className'=>'User',
            'joinTable'=>'UserSecurityLevel',
            'foreignKey'=>'UserID',
            'associationForeignKey'=>'SecurityLevelID'
        )
    );
}

?>
