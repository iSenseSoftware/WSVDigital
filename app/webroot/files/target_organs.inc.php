<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of target_organs
 *
 * @author etbmx
 */
class target_organs {
  
  protected $_Headers = array('Code', 'Name');
  protected $_DisplayValues = array('Code', 'Name');
  
  public function __construct() {
    
  }
  
  public function fetch_array($id){
    $arr_query = "SELECT t.TargetOrganID, TargetOrganCode as Code, TargetOrganName as Name, TargetOrganDescription as Description from TargetOrgans as t inner join HazardEvaluationTargetOrgans as ht on ht.TargetOrganID = t.TargetOrganID where HazardEvaluationID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $arr_query);
    $organ_array = array();
    while ($row = odbc_fetch_array($result)) {
      $organ_array[] = $row;
    }
    odbc_free_result($result);
    odbc_close($conn);
    return (array) $organ_array;
  }
  
  public function render_table($id){
    $organ_array = $this->fetch_array($id);
    echo "<div id='target_organ_block'>\r\n";
    echo "<h2>Target Organs</h2>\r\n<table class='hazard_summary'>\r\n";
    foreach($this->_Headers as $val){
      echo "\t\t<th>{$val}</th>\r\n";
    }
    foreach($organ_array as $row){
      echo "\t<tr>\r\n";
      foreach($this->_DisplayValues as $val){
        echo "\t\t<td>{$row[$val]}</td>\r\n";
      }
      echo "\t</tr>\r\n";
    }
    echo "</table>\r\n</div>";
  }
  
}

?>
