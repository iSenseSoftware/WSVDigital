<?php

/**
 * Description of Item
 *
 * @author etbmx
 */
require_once('phpqrcode/qrlib.php');
class Item extends AppModel {

    public $useTable = 'Items';
    public $primaryKey = 'ItemID';
    public $modelName = 'Item';
    public $belongsTo = array(
        'Part' => array(
            'className' => 'Part',
            'foreignKey' => 'ModelID',
        ),
        'Location'=>array(
            'className'=>'Location',
            'foreignKey'=>'LocationIDCurrent'
        )
    );
    
    public $hasMany = array(
        'InventoryHistory'=>array(
            'className'=>'InventoryHistory',
            'foreignKey'=>'ItemID'
        )
    );
    
    public function afterSave($created){
        if($created){
            $this->createQR();
        }
    }
    
    public function createQR() {
        $itemCode = $this->data['Item']['ItemCode'];
        unlink("img/qr$itemCode.png");
        QRcode::png("http://huswivc0219/cake_2_2/items/mobileView/$itemCode", "img/qr$itemCode.png");
    }
    
    public function initialQRCreation(){
        $i;
        // @todo still need to add QR codes for non-batch items
        set_time_limit(600);
        $parts = $this->Part->find('all', array('conditions'=>array('Part.ItemTypeID'=>2)));
//        for($i=2050;$i<=2100;$i++){
//            $itemCode = str_pad($i, 5, '0', STR_PAD_LEFT);
//            unlink("img/qr$itemCode.png");
//            QRcode::png("http://huswivc0219/cake_2_2/items/mobileView/$itemCode", "img/qr$itemCode.png");
//        }
        foreach($parts as $part){
            $itemCode = $part['Part']['ModelCode'];
            unlink("img/qr$itemCode.png");
            QRcode::png("http://huswivc0219/cake_2_2/items/mobileView/$itemCode", "img/qr$itemCode.png");
        }
    }
    
    public function beforeSave($options = array()){
    	parent::beforeSave($options);
    	$this->data['Item']['Quantity'] = (float)preg_replace('/,/', '', $this->data['Item']['Quantity']);
    }

}

?>
