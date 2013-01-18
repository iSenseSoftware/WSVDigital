<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vendors
 *
 * @author etbmx
 */
class vendors {
  
  protected $_DisplayValues = array('Vendor', 'VendorPN');
  protected $_Headers = array('Vendor', 'Vendor P/N');
  
  public function __construct(){
    
  }
  
  public function fetch_array($id) {
    $arr_query = "SELECT c.MaterialID, c.PartNumber as VendorPN, c.Description, v.VendorName as Vendor, AddressLine1, AddressLine2, City, State, ZipCode as Zip, Phone, Email, Fax from Catalog as c inner join Vendors as v on v.VendorID = c.VendorID where c.MaterialID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $arr_query);
    $vendor_array = array();
    while ($row = odbc_fetch_array($result)) {
      $vendor_array[] = $row;
    }
    odbc_free_result($result);
    odbc_close($conn);
    return (array) $vendor_array;
  }
  
  public function render_table($id) {
    $vendor_array = $this->fetch_array($id);
    echo "<div id='vendor_block'>\r\n";
    echo "<h2>Vendors</h2>\r\n";
    if (!empty($vendor_array)) {
      echo "<table class='hazard_summary'>\r\n";
      foreach ($this->_Headers as $val) {
        echo "\t\t<th>{$val}</th>\r\n";
      }
      foreach ($vendor_array as $row) {
        echo "\t<tr>\r\n";
        foreach ($this->_DisplayValues as $val) {
          echo "\t\t<td>{$row[$val]}</td>\r\n";
        }
        echo "\t</tr>\r\n";
      }
      echo "</table>\r\n";
    }else{
      echo "<p>No vendors identified</p>\r\n";
    }

    echo "</div>";
  }
  
}

?>
