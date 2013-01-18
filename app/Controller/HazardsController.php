<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HazardsController
 *
 * @author etbmx
 */
class HazardsController extends AppController{
    //put your code here
    
    public function index(){
       
    }
    
    public function pdoIndex(){
        $dbName = "M:\\Public\\HMIS\\Hazard Communication with Reviews.mdb";
        if(!file_exists($dbName)){
            die('Could not find database file');
        }
        $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)};DBQ=$dbName; Uid=etbmx; Pwd=kollani;");
        $query = "SELECT Name, PartNumber, OverallHealth as H, OverallFlammability as F, OverallPhysicalHazard as P, ChronicHazardStar as CH, PPE, LocationCode, IsNonHazardous, 
            SpecialPPE_Reqs as SpecialPPE, MaterialId FROM 
            frmInventoryQuery ORDER BY Name";
        $output = array();
        $i = 0;
        foreach($db->query($query) as $row){
            $output[] = $row;
            $query = "SELECT DocumentPath, DocumentTitle, Hyperlink from Materials as m INNER JOIN 
                (MaterialDocuments as md INNER JOIN Documents as d on d.DocumentId = md.DocumentId) on md.MaterialId = m.MaterialId 
                WHERE m.MaterialId = {$row['MaterialId']}";
            $docs = $db->query($query);
            $docs = $docs->fetchAll();
            $output[$i]['Documents'] = $docs;
            $i++;
        }
        $this->set('data', $output);
        $this->set('db', $db->errorInfo());
    }
    
    public function view($id = null){
        $this->set('id', $id);
    }
}

?>
