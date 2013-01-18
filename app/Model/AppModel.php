<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
App::uses('CakeTime', 'Utility');
class AppModel extends Model {
    public $actsAs = array('containable');
    
    public function beginTransaction() {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
    }

    public function commitTransaction() {
        $dataSource = $this->getDataSource();
        $dataSource->commit();
    }

    public function rollbackTransaction() {
        $dataSource = $this->getDataSource();
        $dataSource->rollback();
    }
    
    public function softDelete($id = null){
        if($id == null){
            return false;
        }else{
            $data = $this->find('first', array('conditions'=>array("{$this->modelName}.{$this->primaryKey}"=>$id), 
                    'recursive'=>-1));
            $data[$this->modelName]['Deleted'] = 1;
            if($this->save($data)){
                return true;
            }else{
                return false;
            }
        }
    }
    
}
