<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gloves
 *
 * @author etbmx
 */
class gloves {
  
  protected $_Headers = array('Name', 'Vendor P/N', 'Material', 'Thickness');
  protected $_DisplayValues = array('Name', 'VendorPN', 'Material', 'Thickness');
  
  public function __construct() {
    
  }
  
  public function fetch_array($id) {
    $arr_query = "SELECT g.GloveID, Name, VendorPartNumber as VendorPN, Material, Thickness from Gloves as g inner join HazardEvaluationGloves as hg on hg.GloveID = g.GloveID where HazardEvaluationID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $arr_query);
    $glove_array = array();
    while ($row = odbc_fetch_array($result)) {
      $glove_array[] = $row;
    }
    odbc_free_result($result);
    odbc_close($conn);
    return (array) $glove_array;
  }
  
  public function render_table($id){
    $glove_array = $this->fetch_array($id);
    echo "<div id='glove_block'>\r\n";
    echo "<h2>Compatible Gloves</h2>\r\n<table class='hazard_summary'>\r\n";
    foreach($this->_Headers as $val){
      echo "\t\t<th>{$val}</th>\r\n";
    }
    foreach($glove_array as $row){
      echo "\t<tr>\r\n";
      foreach($this->_DisplayValues as $val){
        if($val=='Thickness'){
          echo "\t\t<td>{$row[$val]} mil</td>\r\n";
        } else{
            echo "\t\t<td>{$row[$val]}</td>\r\n";
        }
      }
      echo "\t</tr>\r\n";
    }
    echo "</table>\r\n</div>";
  }
  
}

?>
