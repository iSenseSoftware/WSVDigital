<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of location
 *
 * @author etbmx
 */
class Location extends ActiveRecord{
  protected $idField = 'LocationID';
  protected $tableName = 'Locations';
  protected $allowedFields = array('LocationCode'=>'Code');
  
  public function getLocationCode($id){
   $conn = $this->getConnection();
   $query = "SELECT LocationCode from {$this->tableName} WHERE {$this->idField} = $id";
   $result = odbc_exec($conn, $query);
   $result = odbc_fetch_array($result);
   return $result['LocationCode'];
  }
  
}

?>
