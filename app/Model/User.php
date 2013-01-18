<?php

/**
 * Description of User
 *
 * @author etbmx
 */
class User Extends AppModel{
    public $useTable = 'Users';
    public $primaryKey = 'UserID';
    
    public $validate = array(
        'UserLogOn'=> 'notEmpty',
        'UserPassword'=>'notEmpty'
    );
    
    public $hasMany = array(
        'InventoryHistory'=>array(
            'className' => 'User',
            'foreignKey'=>'UserID'
        )
    );
    
    public $hasAndBelongsToMany = array(
        'SecurityLevel'=>array(
            'className'=>'SecurityLevel',
            'foreignKey'=>'UserID',
            'joinTable'=>'UserSecurityLevel',
            'associationForeignKey'=>'SecurityLevelID'
        )
    );
    
    public function hasSecurityLevel($id = null, $level = null){
        if($id == null || $level == null){
            return false;
        }else{
            $levels = $this->find('first', array(
                'conditions'=>array(
                'UserID'=>$id), 'fields'=>array(
                    'User.UserID', 'User.UserLogOn', 'User.UserPassword'
                ), 'contain'=>array(
                    'SecurityLevel'=>array(
                        'fields'=>'SecurityLevelCode'
                    )
                )
            ));
            $hasLevel = false;
            foreach($levels['SecurityLevel'] as $permission){
                if($permission['SecurityLevelCode'] == $level){
                    $hasLevel = true;
                }
            }
            return $hasLevel;
        }
    }
    
}

?>
