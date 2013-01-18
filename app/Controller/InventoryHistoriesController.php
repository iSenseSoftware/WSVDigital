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

    public function fetchPageOld($page = 1, $resultCount = 10, $sort = 'Date', $direction = 'asc', $queryString = null, $id = null) {
        // This is broken in this implementation because Microsoft apparently didn't see the point of supporting pagination in SQL queries
        $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__, 'mckenzie.jw', 'kollani');
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
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, ModelID,LocationID,
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision, UsersUserLogOn as UserLogOn from InventoryHistoryDisplayViewIN WHERE Deleted = 0) _tmpInlineView WHERE RowNumber >= $start";
            $results = array();
            foreach ($pdo->query($query) as $row) {
                $results[] = $row;
            }
        } else {
            $query = "SELECT InvHistoryTypesInvHistoryType as InvHistoryType, InventoryHistoryID, ItemID, InventoryHistoryHistoryDateTime as Date, ModelsModelCodeIN as PartNumber, 
            ModelsModelNameIN as PartName, ItemsItemCode as ItemCode, InventoryHistoryQuantityChange as Quantity, UOMsUOMCode as Uom, 
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, ModelID,LocationID,
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision, UsersUserLogOn as UserLogOn from InventoryHistoryDisplayViewIN WHERE Deleted = 0 ORDER BY $sort $direction";
            $results = array();
            foreach ($pdo->query($query) as $row) {
                $results[] = $row;
            }
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

    public function fetchPage() {
        if ($this->request->is('post')) {
            // do the good stuff
            $data = Sanitize::clean($this->request->data);
            $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__, 'mckenzie.jw', 'kollani');
            $start = ($data['page'] - 1) * $data['resultCount'];
            $conditions = array();
            if ($data['fullTextQuery'] == null) {
                $countQuery = "SELECT * FROM (SELECT Row_Number() OVER (ORDER BY ";
                $query = "SELECT * FROM (SELECT Row_Number() OVER (ORDER BY ";
                $fieldCount = count($data['sortFields']);
                $i = 1;
                foreach ($data['sortFields'] as $sortField => $direction) {
                    $sortField = explode('.', $sortField);
                    $sortField = $sortField[1];
                    if ($i == $fieldCount) {
                        $query .= "$sortField $direction) ";
                        $countQuery .= "$sortField $direction) ";
                    } else {
                        $query .= "$sortField $direction, ";
                        $countQuery .= "$sortField $direction, ";
                    }
                    $i++;
                }
                $query .= "AS RowNumber, ";
                $countQuery .= "AS RowNumber, ";

                $displayFields = $data['displayFields'];
                if (isset($data['hiddenFields'])) {
                    $queryFields = array_merge($data['hiddenFields'], $displayFields);
                } else {
                    $queryFields = $displayFields;
                }

                $fieldCount = count($queryFields);
                $i = 1;
                foreach ($queryFields as $alias => $field) {
                    if ($i == $fieldCount) {
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= "$field ";
                        $countQuery .= "$field ";
                    } else {
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= "$field, ";
                        $countQuery .= "$field, ";
                    }

                    $i++;
                }
                $query .= " from InventoryHistoryDisplayViewIN) _tmpInlineView WHERE 1=1 ";
                $countQuery .= " from InventoryHistoryDisplayViewIN) _tmpInlineView WHERE 1=1 ";
                if (isset($data['fieldFilters'])) {
                    foreach ($data['fieldFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= " AND $field LIKE('%$filter%') ";
                        $countQuery .= " AND $field LIKE('%$filter%') ";
                    }
                }

                $counter = $pdo->query($countQuery);
                $totalRecords = count($counter->fetchAll());
                $items = $pdo->query($query);
                $items = $items->fetchAll();
                $items = $this->trimArray($items, $data['page'], $data['resultCount']);
            } else {

                $query = "SELECT * FROM (SELECT Row_Number() OVER (ORDER BY ";
                $fieldCount = count($data['sortFields']);
                $i = 1;
                foreach ($data['sortFields'] as $sortField => $direction) {
                    $sortField = explode('.', $sortField);
                    $sortField = $sortField[1];
                    if ($i == $fieldCount) {
                        $query .= "$sortField $direction) ";
                    } else {
                        $query .= "$sortField $direction, ";
                    }
                    $i++;
                }
                $query .= "AS RowNumber, ";

                $displayFields = $data['displayFields'];
                if (isset($data['hiddenFields'])) {
                    $queryFields = array_merge($data['hiddenFields'], $displayFields);
                } else {
                    $queryFields = $displayFields;
                }

                $fieldCount = count($queryFields);
                $i = 1;
                foreach ($queryFields as $alias => $field) {
                    if ($i == $fieldCount) {
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= "$field ";
                    } else {
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= "$field, ";
                    }

                    $i++;
                }
                $query .= " from InventoryHistoryDisplayViewIN) _tmpInlineView WHERE 1=1 ";
                if (isset($data['fieldFilters'])) {
                    foreach ($data['fieldFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= " AND $field LIKE('%$filter%') ";
                    }
                }

                $items = $pdo->query($query);
                $items = $items->fetchAll();
                $queryString = Sanitize::escape($data['fullTextQuery']);
                $items = $this->fetchFilteredData($items, $queryString);
                $totalRecords = count($items);
                $items = $this->trimArray($items, $data['page'], $data['resultCount']);
            }
            $totalPages = ceil($totalRecords / $data['resultCount']);
            $items['Pager']['total'] = $totalPages;
            $items['Pager']['current'] = $data['page'];
            $items['Pager']['results'] = $data['resultCount'];
            $this->set('items', $items);
            $this->set('data', $data);
        } else {
            
        }
        $this->render('fetchPage', 'ajax');
    }

    public function index() {
        
    }

    public function receivingLog() {
        if ($this->Session->read('User.UserID')) {
            $userId = $this->Session->read('User.UserID');
            $userCode = $this->Session->read('User.UserLogOn');
        } else {
            $this->Session->setFlash('You must be logged in to do that');
            $this->redirect(array('action' => 'login', 'controller' => 'users'));
        }

        if ($this->request->is('get')) {
            // set variables for the receiving log form
            $this->loadModel('Uom');
            $this->set('uoms', $this->Uom->find('list', array(
                        'conditions' => array(
                            'Uom.deleted' => false
                        ), 'fields' => array(
                            'Uom.UOMCode', 'Uom.UOMCode'
                        ), 'order' => 'Uom.UOMCode'
                    )));
        } else {
            if ($this->request->data['InventoryHistory']['Quantity'] <= 0) {
                $this->Session->setFlash('Quantity must be greater than zero');
                $this->redirect(array('action' => 'receivingLog'));
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
            if ($this->InventoryHistory->save($inventoryHistory)) {
                $this->InventoryHistory->commitTransaction();
                $this->Session->setFlash('Log entry added');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->InventoryHistory->rollbackTransaction();
                $this->Session->setFlash('ERROR: Log entry failed');
                $this->redirect(array('action' => 'receivingLog'));
            }
        }
    }

    public function view($id = null) {
        if ($id == null) {
            $this->Session->setFlash('ID not specified or could not be found');
            $this->redirect(array('action' => 'index'));
        } else {
            $id = Sanitize::clean($id);
            $query = "SELECT InventoryHistoryID, InvHistoryTypesInvHistoryType as InvHistoryType, InventoryHistoryHistoryDateTime as Date, ModelsModelCodeIN as PartNumber, 
            ModelsModelNameIN as PartName, ItemsItemCode as ItemCode, InventoryHistoryQuantityChange as Quantity, UOMsUOMCode as Uom, 
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, ModelID, LocationID,ItemID,
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision, UsersUserLogOn as UserLogOn from InventoryHistoryDisplayViewIN WHERE InventoryHistoryID = $id";
            $this->set('result', $this->InventoryHistory->query($query));
        }
    }

}

?>
