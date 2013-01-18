<?php


/**
 * Description of InventoryHistory
 *
 * @author etbmx
 */
class InventoryHistory extends AppModel{
    public $useTable = 'InventoryHistory';
    public $primaryKey = 'InventoryHistoryID';
    
    public $belongsTo = array(
        'InventoryHistoryType'=>array(
            'className'=>'InventoryHistoryType',
            'foreignKey'=>'InvHistoryTypeID'
        ),
        'Item'=>array(
            'className'=>'Item',
            'foreignKey'=>'ItemID'
        ),
        'User'=>array(
            'className'=>'User',
            'foreignKey'=>'UserID'
        )
    );

    public function beforeSave($options = array()){
	parent::beforeSave($options);
	$this->data['InventoryHistory']['QuantityChange'] = (float)preg_replace("/,/", '', $this->data['InventoryHistory']['QuantityChange']);
    }
    
}

?>
