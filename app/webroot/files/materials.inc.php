<?php

/*
 * this class contains methods for retrieving, displaying and updating
 * chemical inventory records.  these objects are called materials
 * because the chemical class will be used for pure chemical components
 * of products which may be solutions or mixtures
 * JM 16 Sep 2011 
 */

class materials {

    protected $_query;
    protected $_headers = array('Material ID', 'Name', 'P/N', 'CH', 'H', 'F', 'P', 'PPE', 'Location');

    public function getHeaders() {
        return $this->_headers;
    }

    public function getMaterials() {
        $output = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
        $conn = odbc_connect('HazComDB', 'admin', 'kollani')
                or die('Could not connect to the database');
        $this->_query = "SELECT MaterialID, PartNumber as PN, Name from Materials";
        $result = odbc_exec($conn, $this->_query);
        $matsXml = $output->addChild("Materials");
        while ($line = odbc_fetch_array($result)) {

            if ($line['PN'] == 'NA' || $line['PN'] == 'N/A' || $line['PN'] == 'N\A') {
                continue;
            } else {
                $mat = $matsXml->addChild('Material');
                foreach ($line as $key => $val) {
                    $mat->addChild($key, $val);
                }
            }
        }
        return $output->asXml();
    }

    public function __construct($where_args = '', $order_by = '') {
        $this->_query = "SELECT MaterialID, Name, PartNumber as PN, ChronicHazardStar as CH, OverallHealth as H, OverallFlammability as F, OverallPhysicalHazard as P, PPE, LocationName as Location, IsNonHazardous FROM frmInventoryQuery";
        if ($where_args != '') {
            $this->_query .= ' WHERE ' . $where_args;
        }
        if ($order_by != '' && $order_by != 'Name') {
            $this->_query .= " ORDER BY $order_by, Name";
        } else {
            $this->_query .= ' ORDER BY Name';
        }
        //echo $this->_query;
    }

    private function print_hyperlinks($item, $connection) {
        $link_query = "SELECT * from Documents as d inner join MaterialDocuments as md on md.DocumentID = d.DocumentID where md.MaterialID = $item[MaterialID]";
        $result = odbc_exec($connection, $link_query) or die('Query failed');
        while ($line = odbc_fetch_array($result)) {
            $link = $line['Hyperlink'];
            $title = $line['DocumentTitle'];
            echo "<a href='$link'>$title</a><br/>";
        }
    }

    public function render_table() {
        $conn = odbc_connect('HazComDB', 'admin', 'kollani')
                or die('Could not connect to the database');
        $result = odbc_exec($conn, $this->_query);


        // Print results into html table
        echo "<table id='chemical_list' class='tablesorter'>\r\n<thead>\r\n";
        foreach ($this->_headers as $val) {
            echo "\t<th>$val</th>\r\n";
        }
        echo "\t<th>MSDS Links</th>\r\n</thead>\r\n<tbody>\r\n";

        while ($line = odbc_fetch_array($result)) {
            if ($line['IsNonHazardous']) {
                $line['H'] = 'NA';
                $line['F'] = 'NA';
                $line['P'] = 'NA';
                $line['PPE'] = 'NA';
            }
            //echo '<pre>';
            //print_r($line);
            //echo '</pre>';
            echo "\t<tr>\r\n";

            $material_id = $line['MaterialID'];
            foreach ($line as $col_name => $col_value) {
                if ($col_name == 'IsNonHazardous') {
                    continue;
                }
                $col_value = htmlentities($col_value);
                // find the key for the current array element, this will be used to assign the class to the cell
                if (($col_name == 'MaterialID') || ($col_name == 'Name') || ($col_name == 'PN')) {
                    echo "\t\t<td class = '$col_name'><a href='http://huswivc0219/hazard_summary.php?MaterialID=$material_id'>$col_value</a></td>\r\n";
                } else {
                    echo "\t\t<td class = '$col_name'>$col_value</td>\r\n";
                }
            }
            echo "\t\t<td>";
            $this->print_hyperlinks($line, $conn);
            echo "\t\t</td>";
            echo "\t</tr>\r\n";
        }
        echo "</tbody>\r\n</table>\r\n";


        odbc_free_result($result);

        odbc_close($conn);
    }

//  public function render_label(){
//    //$query = "SELECT MaterialID, Name, OverallHealth as H, OverallFlammability as F, OverallPhysicalHazard as P, ChronicHazardStar as CH, SpecialPPE_Reqs as ppe FROM frmInventoryQuery WHERE MaterialID = $id";
//    $conn = odbc_connect('HazComDB', 'admin', 'kollani')
//            or die('Could not connect to the database');
//    $result = odbc_exec($conn, $this->_query);
//    
//    
//    
//    if($line = odbc_fetch_array($result)){
//      echo "<table>";
//      echo "<tr><td colspan='3'>$id  {$line['Name']}</td></tr>";
//      echo "<tr><td>Health</td><td id='chronic_hazard'>{$line['CH']}</td><td id='health'>{$line['H']}</td></tr>\r\n";
//      echo "<tr><td colspan='2'>Flammability</td><td>{$line['F']}</td></tr>\r\n";
//      echo "<tr><td colspan='2'>Physical Hazard</td><td id='physical_hazard'>{$line['P']}</td></tr>\r\n";
//      echo "</table>";
//    } else {
//      echo "<p>Material not found</p>";
//    }
//    
//    return true;
//  }
}

