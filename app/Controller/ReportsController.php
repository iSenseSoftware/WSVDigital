<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportsController
 *
 * @author etbmx
 */
class ReportsController extends AppController {

    public $modelName = 'Report';
    public $uses = array('Assembly', 'Uom');

    public function index() {
        
    }
    
    public function printPage(){
        if($this->request->is('post')){
            $this->set('data', $this->request->data);
            $this->render('printPage', 'ajax');
        }else{
            
        }
    }
    
    public function allStock(){
        if($this->request->is('post')){
            
        }else{
            //do nothing
        }
            
    }
    
    public function stockByAssembly() {
        if ($this->request->is('post')) {
            $data = Sanitize::clean($this->request->data);
            $start = ($data['page'] - 1) * $data['resultCount'];
            $query = 'SELECT Uom.UOMID, Uom.UOMCode, Part.ModelID, Part.ModelCode, Part.ModelName, Part.MinOnHand, Part.MaxOrderTo' .
                    ' from Models as Part INNER JOIN UOMs as Uom on Uom.UOMID = Part.UOMIDInventory INNER JOIN ' .
                    '(SELECT DISTINCT Assemblies.TopID from Assemblies where Assemblies.Deleted = 0) as Assembly on Part.ModelID = Assembly.TopID where Part.Deleted = 0 ORDER BY Part.ModelCode';
            $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__ , 'mckenzie.jw', 'kollani');
            foreach ($pdo->query($query) as $row) {
                $parts[]['Part'] = $row;
            }
            foreach ($parts as $key => &$part) {
                $query = 'SELECT Assembly.QtyPerAssembly, Assembly.TopRevision, Assembly.ComponentRevision, Component.ModelID, Component.ModelCode, ' .
                        'Component.ModelName, Component.UOMIDInventory, Component.MinOnHand, Component.MaxOrderTo, Uom.UOMCode from Assemblies as Assembly LEFT JOIN Models as Component ON ' .
                        'Component.ModelID = Assembly.ComponentID INNER JOIN Uoms as Uom on Uom.UOMID = Component.UOMIDInventory WHERE ' .
                        'Assembly.Deleted = 0 and Assembly.TopID = ' . Sanitize::clean($part['Part']['ModelID']) . ' ORDER BY Assembly.TopRevision asc, ' .
                        ' Component.ModelCode asc';
                $results = $pdo->query($query);
                $components = $results->fetchAll();
                $i = 0;
                $currentRev;
                $output = array();
                foreach ($components as &$component) {
                    $currentRev = $component['TopRevision'];

                    $query = 'SELECT Assembly.TopID from Assemblies as Assembly WHERE Assembly.Deleted = 0 and Assembly.TopID = ' . $component['ModelID'] .
                            ' AND Assembly.TopRevision = \'' . $component['ComponentRevision'] . "'";
                    $results = $pdo->query($query);
                    if (count($results->fetchAll()) > 0) {
                        $component['IsAssembly'] = true;
                    } else {
                        $component['IsAssembly'] = false;
                    }
                    $part['Assembly'][$currentRev][] = $component;
                    $query = 'SELECT Item.TransLineItemUD1, Item.Quantity, LocationType.LocationTypeCode from Items as Item INNER JOIN Locations as Location ' .
                            'ON Location.LocationID = Item.LocationIDCurrent INNER JOIN LocationTypes as LocationType on LocationType.LocationTypeID = Location.LocationTypeID ' .
                            'WHERE Item.Quantity > 0 AND Item.ModelID = ' . $part['Part']['ModelID'] . " AND Item.TransLineItemUD1 = '$currentRev'";
                    $results = $pdo->query($query);
                    $part['Assembly'][$currentRev]['Item'] = $results->fetchAll();
                    $query = 'SELECT Sum(Item.Quantity) as Sum, LocationType.LocationTypeCode from Items as Item LEFT JOIN Locations as Location on Item.LocationIDCurrent = Location.LocationID LEFT JOIN ' .
                            'LocationTypes as LocationType on LocationType.LocationTypeID = Location.LocationTypeID where Item.Deleted = 0 and Item.Quantity > 0 AND Item.ModelID = ' . $part['Part']['ModelID'] .
                            " AND Item.TransLineItemUD1 = '$currentRev' GROUP BY LocationType.LocationTypeCode";
                    $results = $pdo->query($query);
                    $sums = $results->fetchAll();
                    foreach ($sums as $sum) {
                        $part['Assembly'][$currentRev]['Item']['Sums'][$sum['LocationTypeCode']] = $sum['Sum'];
                    }
                    $query = 'SELECT Sum(Item.Quantity) as Sum, LocationType.LocationTypeCode from Items as Item LEFT JOIN Locations as Location on Item.LocationIDCurrent = Location.LocationID LEFT JOIN ' .
                            'LocationTypes as LocationType on LocationType.LocationTypeID = Location.LocationTypeID where Item.Quantity > 0 and Item.Deleted = 0 AND Item.ModelID = ' . $component['ModelID'] .
                            " AND Item.TransLineItemUD1 = '{$component['ComponentRevision']}' GROUP BY LocationType.LocationTypeCode";
                    $results = $pdo->query($query);
                    $sums = $results->fetchAll();
                    foreach ($sums as $sum) {
                        $part['Assembly'][$currentRev][$i]['Sums'][$sum['LocationTypeCode']] = $sum['Sum'];
                    }
                    $i++;
                }
            }
            if (isset($data['fullTextQuery']) && $data['fullTextQuery'] != '') {
                $queryString = $data['fullTextQuery'];
                $parts = $this->fetchFilteredData($parts, $queryString);
            }
            $totalRecords = count($parts);
            $parts = $this->trimArray($parts, $data['page'], $data['resultCount']);
            $totalPages = ceil($totalRecords / $data['resultCount']);
            $parts['Pager']['total'] = $totalPages;
            $parts['Pager']['current'] = $data['page'];
            $parts['Pager']['results'] = $data['resultCount'];
            $parts['Pager']['pagingId'] = $data['pagingId'];
            $this->set('assemblies', $parts);
            $this->render('stockByAssembly', 'ajax');
        } else {
            // do nothing
        }
    }

    public function getComponentRows($id = null, $rev = null, $offset = 0) {
        $query = 'SELECT Assembly.QtyPerAssembly, Assembly.TopRevision, Assembly.TopID, Assembly.ComponentID, Assembly.ComponentRevision, Component.ModelCode, Component.ModelName, ' .
                'Component.MinOnHand, Component.MaxOrderTo, Uom.UOMCode from Assemblies as Assembly LEFT JOIN Models as Component on Component.ModelID = Assembly.ComponentID ' .
                'LEFT JOIN Uoms as Uom on Uom.UOMID = Component.UOMIDInventory ' .
                'WHERE Assembly.Deleted = 0 AND Assembly.TopID = ' . $id . ' AND Assembly.TopRevision = \'' . $rev . '\'';
        $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__ , 'mckenzie.jw', 'kollani');
        $results = $pdo->query($query);
        $parts = $results->fetchAll();
        foreach ($parts as &$part) {
            $query = 'SELECT Sum(Item.Quantity) as Sum, LocationType.LocationTypeCode from Items as Item LEFT JOIN Locations as Location on Item.LocationIDCurrent = Location.LocationID LEFT JOIN ' .
                    'LocationTypes as LocationType on LocationType.LocationTypeID = Location.LocationTypeID where Item.Deleted = 0 and Item.Quantity > 0 AND Item.ModelID = ' . $part['ComponentID'] .
                    " AND Item.TransLineItemUD1 = '{$part['ComponentRevision']}' GROUP BY LocationType.LocationTypeCode";
            $results = $pdo->query($query);
            foreach($results->fetchAll() as $sum){
                $part['Sums'][$sum['LocationTypeCode']] = $sum['Sum'];
            }
            $query = 'SELECT Assembly.TopID from Assemblies as Assembly WHERE Assembly.Deleted = 0 and Assembly.TopID = ' . $part['ComponentID'] .
                            ' AND Assembly.TopRevision = \'' . $part['ComponentRevision'] . "'";
                    $results = $pdo->query($query);
                    if (count($results->fetchAll()) > 0) {
                        $part['IsAssembly'] = true;
                    } else {
                        $part['IsAssembly'] = false;
                    }
        }

        $this->set('assemblies', $parts);
        $this->set('offset', $offset);
        $this->render('getComponentRows', 'ajax');
    }

}

?>
