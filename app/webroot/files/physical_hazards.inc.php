<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of physical_hazards
 *
 * @author etbmx
 */
class physical_hazards {

  protected $_DisplayValues = array('Code', 'Name');
  protected $_Headers = array('Code', 'Name');

  public function __construct() {
    
  }

  public function fetch_array($id) {
    $arr_query = "SELECT p.HMIS_PhysicalHazardID, Code, Name, Description from HMIS_PhysicalHazards as p inner join HazardEvaluationPhysicalHazards as ph on ph.HMIS_PhysicalHazardID = p.HMIS_PhysicalHazardID where HazardEvaluationID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $arr_query);
    $phys_array = array();
    while ($row = odbc_fetch_array($result)) {
      $phys_array[] = $row;
    }
    odbc_free_result($result);
    odbc_close($conn);
    return (array) $phys_array;
  }

  public function render_table($id) {
    $physical_hazard_array = $this->fetch_array($id);
    echo "<div id='physical_hazard_block'>\r\n";
    echo "<h2>Physical Hazards</h2>\r\n";
    if (!empty($physical_hazard_array)) {
      echo "<table class='hazard_summary'>\r\n";
      foreach ($this->_Headers as $val) {
        echo "\t\t<th>{$val}</th>\r\n";
      }
      foreach ($physical_hazard_array as $row) {
        echo "\t<tr>\r\n";
        foreach ($this->_DisplayValues as $val) {
          echo "\t\t<td>{$row[$val]}</td>\r\n";
        }
        echo "\t</tr>\r\n";
      }
      echo "</table>\r\n";
    }else{
      echo "<p>No physical hazards identified</p>\r\n";
    }

    echo "</div>";
  }

}

?>
