<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InventoryHistoriesController
 *
 * @author etbmx
 */
class InventoryHistoriesController extends AppController {

    public function fetchPage($page = 1, $resultCount = 10, $sort = 'Date', $direction = 'asc', $queryString = null, $id = null) {
        // This is broken in this implementation because Microsoft apparently didn't see the point of supporting pagination in SQL queries
        $start = ($page - 1) * $resultCount;
        $totalRecords = $this->InventoryHistory->find('count', array(
            'conditions' => array(
                'Item.Deleted' => 0
            ), 'fields' => array('InventoryHistory.InventoryHistoryID')
                ));
        $tempArray = explode('.', $sort);
        $outerSort = $sort;
        //$totalRecords = count($inv);
        if ($queryString == 'null' || $queryString == 'undefined') { 
           $query = "SELECT TOP $resultCount * FROM (SELECT Row_Number() OVER (ORDER BY $outerSort $direction) AS RowNumber, 
            InvHistoryTypesInvHistoryType as InvHistoryType, InventoryHistoryID, ItemID, InventoryHistoryHistoryDateTime as Date, ModelsModelCodeIN as PartNumber, 
            ModelsModelNameIN as PartName, ItemsItemCode as ItemCode, InventoryHistoryQuantityChange as Quantity, UOMsUOMCode as Uom, 
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, 
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision, UsersUserLogOn as UserLogOn from InventoryHistoryDisplayViewIN WHERE Deleted = 0) _tmpInlineView WHERE RowNumber >= $start";
            $results = $this->InventoryHistory->query($query);
        } else {
            $query = "SELECT InvHistoryTypesInvHistoryType as InvHistoryType, InventoryHistoryID, ItemID, InventoryHistoryHistoryDateTime as Date, ModelsModelCodeIN as PartNumber, 
            ModelsModelNameIN as PartName, ItemsItemCode as ItemCode, InventoryHistoryQuantityChange as Quantity, UOMsUOMCode as Uom, 
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, 
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision, UsersUserLogOn as UserLogOn from InventoryHistoryDisplayViewIN WHERE Deleted = 0 ORDER BY $sort $direction";
            $results = $this->InventoryHistory->query($query);
            $queryString = Sanitize::escape($queryString);
            $results = $this->fetchFilteredData($results, $queryString);
            $totalRecords = count($results);
            $results = $this->trimArray($results, $page, $resultCount);
        }
        $totalPages = ceil($totalRecords / $resultCount);
        $results['Pager']['total'] = $totalPages;
        $results['Pager']['current'] = $page;
        $results['Pager']['results'] = $resultCount;
        $results['Pager']['direction'] = $direction;
        $results['Pager']['sortBy'] = $sort;
        $this->set('results', $results);
        $this->set('query', $query);

        $this->render('fetchPage', 'ajax');
    }

    public function index() {
        
    }

    public function receivingLog() {
        if($this->Session->read('User.UserID')){
            $userId = $this->Session->read('User.UserID');
            $userCode = $this->Session->read('User.UserLogOn');
        }else{
            $this->Session->setFlash('You must be logged in to do that');
            $this->redirect(array('action'=>'index'));
        }
        
        if ($this->request->is('get')) {
            // set variables for the receiving log form
            $this->loadModel('Uom');
            $this->set('uoms', $this->Uom->find('list', array(
                'conditions'=>array(
                    'Uom.deleted'=>false
                ),'fields'=>array(
                    'Uom.UOMCode', 'Uom.UOMCode'
                ), 'order'=>'Uom.UOMCode'
            )));
        } else {    
            if($this->request->data['InventoryHistory']['Quantity'] <=0){
                $this->Session->setFlash('Quantity must be greater than zero');
                $this->redirect(array('action'=>'receivingLog'));
            }
            $historyEntry = array();
            $xmlElement = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
            $xmlElement->addChild('carrier', $this->request->data['InventoryHistory']['Carrier']);
            $xmlElement->addChild('supplier', $this->request->data['InventoryHistory']['Supplier']);
            $xmlElement->addChild('purchaseOrder', $this->request->data['InventoryHistory']['PurchaseOrder']);
            $xmlElement->addChild('partNumber', $this->request->data['InventoryHistory']['PartNumber']);
            $xmlElement->addChild('lot', $this->request->data['InventoryHistory']['Lot']);
            $xmlElement->addChild('batch', $this->request->data['InventoryHistory']['Batch']);
            $xmlElement->addChild('quantity', $this->request->data['InventoryHistory']['Quantity']);
            $xmlElement->addChild('comments', $this->request->data['InventoryHistory']['Comments']);
            $xmlElement->addChild('description', $this->request->data['InventoryHistory']['Description']);
            $xmlElement->addChild('uom', $this->request->data['InventoryHistory']['Uom']);
            $notes = $xmlElement->asXML();
            $notes = htmlentities($notes);
 
            $historyType = 1;
            $currTime = date("n/d/Y g:i:s A");
            $inventoryHistory['InventoryHistory']['ItemID'] = 3775;
            $inventoryHistory['InventoryHistory']['SiteID'] = 1;
            $inventoryHistory['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
            $inventoryHistory['InventoryHistory']['InvHistoryTypeID'] = $historyType;
            $inventoryHistory['InventoryHistory']['HistoryDateTime'] = $currTime;
            $inventoryHistory['InventoryHistory']['ItemCode'] = 'LOGENTRY';
            $inventoryHistory['InventoryHistory']['QuantityChange'] = $this->request->data['InventoryHistory']['Quantity'];
            $inventoryHistory['InventoryHistory']['UOMID'] = 1;
            $inventoryHistory['InventoryHistory']['CostTypeID'] = 4;
            $inventoryHistory['InventoryHistory']['LocationID'] = 5;
            $inventoryHistory['InventoryHistory']['LocationCode'] = 'WHS-RCV';
            $inventoryHistory['InventoryHistory']['UserID'] = $userId;
            $inventoryHistory['InventoryHistory']['UserCode'] = $userCode;
            $inventoryHistory['InventoryHistory']['Notes'] = $notes;
            $inventoryHistory['InventoryHistory']['Added'] = $currTime;
            $inventoryHistory['InventoryHistory']['AddedBy'] = $userCode;
            $inventoryHistory['InventoryHistory']['Updated'] = $currTime;
            $inventoryHistory['InventoryHistory']['UpdatedBy'] = $userCode;
            $this->InventoryHistory->beginTransaction();
            if($this->InventoryHistory->save($inventoryHistory)){
                $this->InventoryHistory->commitTransaction();
                $this->Session->setFlash('Log entry added');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->InventoryHistory->rollbackTransaction();
                $this->Session->setFlash('ERROR: Log entry failed');
                $this->redirect(array('action'=>'receivingLog'));
            }
        }
    }
    
    public function view($id = null){
        if($id == null){
            $this->Session->setFlash('ID not specified or could not be found');
            $this->redirect(array('action'=>'index'));
        }else{
            $query = "SELECT InventoryHistoryID, InvHistoryTypesInvHistoryType as InvHistoryType, InventoryHistoryHistoryDateTime as Date, ModelsModelCodeIN as PartNumber, 
            ModelsModelNameIN as PartName, ItemsItemCode as ItemCode, InventoryHistoryQuantityChange as Quantity, UOMsUOMCode as Uom, 
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, 
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision, UsersUserLogOn as UserLogOn from InventoryHistoryDisplayViewIN";
            $this->set('result', $this->InventoryHistory->query($query));
        }
    }
    
    

}

?>
