<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of chemicals
 *
 * @author etbmx
 */
class compositions {
  
  protected $_Headers = array('Name', 'Min%', 'Max%');
  protected $_DisplayValues = array('Name', 'Min', 'Max');
  
  public function __contruct(){
    
  }
  
  public function fetch_array($id) {
    $arr_query = "SELECT c.ChemicalID, ShortName as Name, ConcentrationLowerLimit as Min, ConcentrationUpperLimit as Max from Chemicals as c inner join Compositions as co on c.ChemicalID = co.ChemicalID where MaterialID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $arr_query);
    $chem_array = array();
    while ($row = odbc_fetch_array($result)) {
      $chem_array[] = $row;
    }
    odbc_free_result($result);
    odbc_close($conn);
    return (array) $chem_array;
  }
  
  public function render_table($id){
    $composition_array = $this->fetch_array($id);
    echo "<div id='composition_block'>\r\n";
    echo "<h2>Composition</h2>\r\n<table class='hazard_summary'>\r\n";
    foreach($this->_Headers as $val){
      echo "\t\t<th>{$val}</th>\r\n";
    }
    foreach($composition_array as $row){
      echo "\t<tr>\r\n";
      foreach($this->_DisplayValues as $val){
        if($val=='Name'){
          $url_end = $row['Name'];
          $url_end = rawurlencode($url_end);
          echo "\t\t<td><a href='http://wikipedia.org/wiki/$url_end'>{$row[$val]}</a></td>\r\n";
        }else{
          echo "\t\t<td>{$row[$val]}</td>\r\n";
        }
      }
      echo "\t</tr>\r\n";
    }
    echo "</table>\r\n</div>";
  }
  
}

?>
