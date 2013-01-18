<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UomsController
 *
 * @author etbmx
 */
class UomsController extends AppController{
    public $modelName = 'Uom';
    public $primaryKey = 'UOMID';
    public function index(){
        $this->set('index', $this->Uom->find('all'));
    }
            
}

?>
