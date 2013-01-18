<?php

/**
 * Description of ItemsController
 *
 * @author etbmx
 */
App::uses('Sanitize', 'Utility');

class ItemsController extends AppController {

    public $modelName = 'Item';

    public function view($id = null) {
        if ($id == null) {
            
        }

        $result = $this->Item->find('first', array(
            'conditions' => array(
                'Item.ItemID' => $id
            ), 'recursive' => 2
                ));
        $this->set('result', $result);
        $this->set('partsInStock', $this->Item->query("SELECT * from WsvInventoryIndex WHERE ModelCode = '{$result['Part']['ModelCode']}' AND ItemID <> {$result['Item']['ItemID']}"));
        $query = "SELECT InvHistoryTypesInvHistoryType as InvHistoryType, InventoryHistoryHistoryDateTime as Date, ModelsModelCodeIN as PartNumber, 
            ModelsModelNameIN as PartName, ItemsItemCode as ItemCode, InventoryHistoryQuantityChange as Quantity, UOMsUOMCode as Uom, 
            LocationsLocationCode as LocationCode, IssueesIssueeCode as IssueeCode, UsersUserLogOn as UserCode, Notes, 
            TransLineItemUD5 as Supplier, TransLineItemUD4 as PurchaseOrder, TransLineItemUD6 as ExpDate, TransLineItemUD3 as Batch, 
            TransLineItemUD2 as Lot, TransLineItemUD1 as Revision from InventoryHistoryDisplayViewIN WHERE ItemsItemCode = '{$result['Item']['ItemCode']}' ORDER BY InventoryHistoryHistoryDateTime desc";
        $this->set('recentHistory', $this->Item->InventoryHistory->query($query));
    }

    public function index() {
        $this->response->header('Cache-Control', 'no-cache, must-revalidate');
    }

    public function testMe($id = null){
        $result = $this->Item->find('first', array(
            'conditions' => array(
                'Item.ItemID' => $id
            ), 'recursive' => 2
                ));
        $this->set('result', $result);
    }
    
    public function fetchPage() {
        if ($this->request->is('post')) {
            // do the good stuff
            $data = Sanitize::clean($this->request->data);
            $start = ($data['page'] - 1) * $data['resultCount'];
            $conditions = array(
                'Item.Deleted' => 0
            );
            if ($data['allowZero'] == 1) {
                // do nothing
            } else {
                $conditions['Item.Quantity >'] = 0;
            }
            if ($data['fullTextQuery'] == null) {
                $query = "SELECT * FROM (SELECT Row_Number() OVER (ORDER BY ";
                $fieldCount = count($data['sortFields']);
                $i = 1;
                foreach ($data['sortFields'] as $sortField => $direction) {
                    if ($i == $fieldCount) {
                        $sortField = explode('.', $sortField);
                        $sortField = $sortField[1];
                        $query .= "$sortField $direction) ";
                    } else {
                        $sortField = explode('.', $sortField);
                        $sortField = $sortField[1];
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
                $query .= " from WsvInventoryIndex) _tmpInlineView WHERE 1=1 ";
                $countQuery = $query;
                if (isset($data['fieldFilters'])) {
                    foreach ($data['fieldFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= " AND $field LIKE('%$filter%') ";
                        $countQuery .= " AND $field LIKE('%$filter%') ";
                    }
                }
                if (isset($data['permFilters'])) {
                    foreach ($data['permFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= " AND $field LIKE('%$filter%') ";
                        $countQuery .= " AND $field LIKE('%$filter%') ";
                    }
                }
                $items = $this->Item->query($countQuery);
                $this->log($items);
                $totalRecords = count($items);
                $items = $this->Item->query($query);
                //$this->log($items);
                $items = $this->trimArray($items, $data['page'], $data['resultCount']);
            } else {

                $query = "SELECT * FROM (SELECT Row_Number() OVER (ORDER BY ";
                $fieldCount = count($data['sortFields']);
                $i = 1;
                foreach ($data['sortFields'] as $sortField => $direction) {
                    if ($i == $fieldCount) {
			$sortField = explode('.', $sortField);
                        $sortField = $sortField[1];
                        $query .= "$sortField $direction) ";
                    } else {
			$sortField = explode('.', $sortField);
                        $sortField = $sortField[1];
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
                $query .= " from WsvInventoryIndex) _tmpInlineView WHERE 1=1 ";
                if (isset($data['fieldFilters'])) {
                    foreach ($data['fieldFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= " AND $field LIKE('%$filter%') ";
                    }
                }
                if (isset($data['permFilters'])) {
                    foreach ($data['permFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                        $field = explode('.', $field);
                        $field = $field[1];
                        $query .= " AND $field LIKE('%$filter%') ";
                    }
                }
                $items = $this->Item->query($query);
                $queryString = Sanitize::escape($data['fullTextQuery']);
                $items = $this->fetchFilteredData($items, $queryString);
                $totalRecords = count($items);
                $items = $this->trimArray($items, $data['page'], $data['resultCount']);
            }
            $totalPages = ceil($totalRecords / $data['resultCount']);
            $items['Pager']['total'] = $totalPages;
            $items['Pager']['current'] = $data['page'];
            $items['Pager']['results'] = $data['resultCount'];
            $this->set('query', $query);
            $this->set('items', $items);
            $this->set('data', $data);
        } else {
            
        }
	$this->response->header('Cache-Control', 'no-cache, must-revalidate');
        $this->render('fetchPage', 'ajax');
    }

    public function issue($id = null) {
        // a GET request is used to specify the Item ID of the item to be issued
        if ($this->request->is('get')) {
            if ($id != null) {
                // if the ID is specified, retrieve the matching record,
                // this will accept ids for deleted records
                $this->set('item', $this->Item->find('first', array(
                            'recursive' => 2, 'conditions' => array(
                                'Item.ItemID' => $id
                            )
                        )));
            }
        } else {
            // if the request is a POST, this indicates that data has been submitted
            // through the form and should be processed.
            // 02Jul:  Add function to handle serial issuing
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'login', 'controller' => 'users'));
            }
            if (is_array($this->request->data['Item']['ItemID'])) {
                $this->log('Multiple issue detected');
                // handle a multiple issue
                /* 1. Loop through the ItemID element of the array and populate an array
                 *    representing a single item to be issued  
                 * 2. Pass each array to a function which will perform a single item issue
                 * 3. If an error is encountered at any point, rollback the entire transaction
                 */
                $this->Item->beginTransaction();
                $items = $this->request->data['Item'];
                $success = true;
//                $this->set('items', $items);
//                $this->render();
                foreach ($items['ItemID'] as $key => $val) {
                    // attempt to issue the given id and qty
                    $this->log("ItemID $val sent to issueItem");
                    $tempArray = array(
                        'ItemID' => $val,
                        'Quantity' => (float)preg_replace('/,/', '', $items['Quantity'][$key]),
                        'Notes' => $items['Notes'][$key],
                        'UserID' => $userId,
                        'UserCode' => $userCode
                    );
                    if ($this->issueItem($tempArray)) {
                        // Do nothing
                    } else {
                        $success = false;
                    }
                }
                if ($success) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Success!');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Issue Failure!');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->log('No array detected');
                if ($this->request->data['Item']['ItemID'] == null) {
                    $this->Session->setFlash('no id specified');
                    $this->redirect(array('action' => 'index'));
                }
                $id = $this->request->data['Item']['ItemID'];
                // set model to record to be edited and retrive existing data
                $item = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.ItemID' => $id
                    )
                        ));
                $notes = $this->request->data['Item']['Notes'];
                // determine existing quantity and quantity to be subtracted
                $oldQty = $item['Item']['Quantity'];
                $changeQty = (float)preg_replace('/,/', '', $this->request->data['Item']['Quantity']);
                if ($changeQty <= 0) {
                    $this->Session->setFlash('Quantity to issue must be positive');
                    $this->redirect(array('action' => 'index'));
                }
                // adjust the quantity in the existing model instance
                $item['Item']['Quantity'] = $oldQty - $changeQty;
                // if the issue would reduce stock below zero display and error
                // and reroute to index
                if ($item['Item']['Quantity'] < 0) {
                    $this->Session->setFlash('Quantity exceeds available stock');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $item['Item']['UpdatedBy'] = $userCode;
                    $this->Item->beginTransaction();
                    if ($this->Item->save($item)) {
                        $inventoryHistory = array();
                        // historyType defines the type of transaction to be logged.  6 is issue
                        $historyType = 6;
                        $inv = $this->Item->find('first', array(
                            'conditions' => array(
                                'Item.ItemID' => $id
                            ), 'recursive' => 2
                                ));
                        // Set the values for the inventory history entry
                        $currTime = date("n/d/Y g:i:s A");
                        $this->loadModel('InventoryHistory');
                        $inventoryHistory['InventoryHistory']['ItemID'] = $id;
                        $inventoryHistory['InventoryHistory']['SiteID'] = 1;
                        $inventoryHistory['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                        $inventoryHistory['InventoryHistory']['InvHistoryTypeID'] = $historyType;
                        $inventoryHistory['InventoryHistory']['HistoryDateTime'] = $currTime;
                        $inventoryHistory['InventoryHistory']['ItemCode'] = $item['Item']['ItemCode'];
                        $inventoryHistory['InventoryHistory']['QuantityChange'] = '-' . $changeQty;
                        $inventoryHistory['InventoryHistory']['UOMID'] = $inv['Part']['UOMIDInventory'];
                        $inventoryHistory['InventoryHistory']['CostTypeID'] = 4;
                        $inventoryHistory['InventoryHistory']['LocationID'] = $inv['Item']['LocationIDCurrent'];
                        $inventoryHistory['InventoryHistory']['LocationCode'] = $inv['Location']['LocationCode'];
                        $inventoryHistory['InventoryHistory']['IssueeID'] = 40;
                        $inventoryHistory['InventoryHistory']['IssueeCode'] = 'CONSUMPTION';
                        $inventoryHistory['InventoryHistory']['UserID'] = $userId;
                        $inventoryHistory['InventoryHistory']['UserCode'] = $userCode;
                        $inventoryHistory['InventoryHistory']['Notes'] = $notes;
                        $inventoryHistory['InventoryHistory']['Added'] = $currTime;
                        $inventoryHistory['InventoryHistory']['AddedBy'] = $userCode;
                        $inventoryHistory['InventoryHistory']['Updated'] = $currTime;
                        $inventoryHistory['InventoryHistory']['UpdatedBy'] = $userCode;
                        $this->InventoryHistory->create();
                        if ($this->InventoryHistory->save($inventoryHistory)) {
                            $this->Item->commitTransaction();
                            // on successful save, redirect to index
                            $this->Session->setFlash('Issue successful');
                            $this->redirect(array('action' => 'index'));
                        } else {
                            $this->Item->rollbackTransaction();
                            // if unsuccessful, redirect to index
                            $this->Session->setFlash('Unable to issue Item');
                            $this->redirect(array('action' => 'index'));
                        }
                    } else {
                        $this->Item->rollbackTransaction();
                        // if unsuccessful, redirect to index
                        $this->Session->setFlash('Unable to issue Item');
                        $this->redirect(array('action' => 'index'));
                    }
                }
            }
        }
    }

    public function issueOld($id = null) {
        // a GET request is used to specify the Item ID of the item to be issued
        if ($this->request->is('get')) {
            if ($id != null) {
                // if the ID is specified, retrieve the matching record,
                // this will accept ids for deleted records
                $this->set('item', $this->Item->find('first', array(
                            'recursive' => 2, 'conditions' => array(
                                'Item.ItemID' => $id
                            )
                        )));
            }
        } else {
            // if the request is a POST, this indicates that data has been submitted
            // through the form and should be processed.
            // 02Jul:  Add function to handle serial issuing
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'login', 'controller' => 'users'));
            }
            if (is_array($this->request->data['Item']['ItemID'])) {
                $this->log('Multiple issue detected');
                // handle a multiple issue
                /* 1. Loop through the ItemID element of the array and populate an array
                 *    representing a single item to be issued  
                 * 2. Pass each array to a function which will perform a single item issue
                 * 3. If an error is encountered at any point, rollback the entire transaction
                 */
                $this->Item->beginTransaction();
                $items = $this->request->data['Item'];
                $success = true;
//                $this->set('items', $items);
//                $this->render();
                foreach ($items['ItemID'] as $key => $val) {
                    // attempt to issue the given id and qty
                    $this->log("ItemID $val sent to issueItem");
                    $tempArray = array(
                        'ItemID' => $val,
                        'Quantity' => $items['Quantity'][$key],
                        'Notes' => $items['Notes'][$key],
                        'UserID' => $userId,
                        'UserCode' => $userCode
                    );
                    if ($this->issueItem($tempArray)) {
                        // Do nothing
                    } else {
                        $success = false;
                    }
                }
                if ($success) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Success!');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Issue Failure!');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->log('No array detected');
                if ($this->request->data['Item']['ItemID'] == null) {
                    $this->Session->setFlash('no id specified');
                    $this->redirect(array('action' => 'index'));
                }
                $id = $this->request->data['Item']['ItemID'];
                // set model to record to be edited and retrive existing data
                $item = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.ItemID' => $id
                    )
                        ));
                $notes = $this->request->data['Item']['Notes'];
                // determine existing quantity and quantity to be subtracted
                $oldQty = $item['Item']['Quantity'];
                $changeQty = $this->request->data['Item']['Quantity'];
                if ($changeQty <= 0) {
                    $this->Session->setFlash('Quantity to issue must be positive');
                    $this->redirect(array('action' => 'index'));
                }
                // adjust the quantity in the existing model instance
                $item['Item']['Quantity'] = $oldQty - $changeQty;
                // if the issue would reduce stock below zero display and error
                // and reroute to index
                if ($item['Item']['Quantity'] < 0) {
                    $this->Session->setFlash('Quantity exceeds available stock');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $item['Item']['UpdatedBy'] = $userCode;
                    $this->Item->beginTransaction();
                    if ($this->Item->save($item)) {
                        $inventoryHistory = array();
                        // historyType defines the type of transaction to be logged.  6 is issue
                        $historyType = 6;
                        $inv = $this->Item->find('first', array(
                            'conditions' => array(
                                'Item.ItemID' => $id
                            ), 'recursive' => 2
                                ));
                        // Set the values for the inventory history entry
                        $currTime = date("n/d/Y g:i:s A");
                        $this->loadModel('InventoryHistory');
                        $inventoryHistory['InventoryHistory']['ItemID'] = $id;
                        $inventoryHistory['InventoryHistory']['SiteID'] = 1;
                        $inventoryHistory['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                        $inventoryHistory['InventoryHistory']['InvHistoryTypeID'] = $historyType;
                        $inventoryHistory['InventoryHistory']['HistoryDateTime'] = $currTime;
                        $inventoryHistory['InventoryHistory']['ItemCode'] = $item['Item']['ItemCode'];
                        $inventoryHistory['InventoryHistory']['QuantityChange'] = '-' . $changeQty;
                        $inventoryHistory['InventoryHistory']['UOMID'] = $inv['Part']['UOMIDInventory'];
                        $inventoryHistory['InventoryHistory']['CostTypeID'] = 4;
                        $inventoryHistory['InventoryHistory']['LocationID'] = $inv['Item']['LocationIDCurrent'];
                        $inventoryHistory['InventoryHistory']['LocationCode'] = $inv['Location']['LocationCode'];
                        $inventoryHistory['InventoryHistory']['IssueeID'] = 40;
                        $inventoryHistory['InventoryHistory']['IssueeCode'] = 'CONSUMPTION';
                        $inventoryHistory['InventoryHistory']['UserID'] = $userId;
                        $inventoryHistory['InventoryHistory']['UserCode'] = $userCode;
                        $inventoryHistory['InventoryHistory']['Notes'] = $notes;
                        $inventoryHistory['InventoryHistory']['Added'] = $currTime;
                        $inventoryHistory['InventoryHistory']['AddedBy'] = $userCode;
                        $inventoryHistory['InventoryHistory']['Updated'] = $currTime;
                        $inventoryHistory['InventoryHistory']['UpdatedBy'] = $userCode;
                        $this->InventoryHistory->create();
                        if ($this->InventoryHistory->save($inventoryHistory)) {
                            $this->Item->commitTransaction();
                            // on successful save, redirect to index
                            $this->Session->setFlash('Issue successful');
                            $this->redirect(array('action' => 'index'));
                        } else {
                            $this->Item->rollbackTransaction();
                            // if unsuccessful, redirect to index
                            $this->Session->setFlash('Unable to issue Item');
                            $this->redirect(array('action' => 'index'));
                        }
                    } else {
                        $this->Item->rollbackTransaction();
                        // if unsuccessful, redirect to index
                        $this->Session->setFlash('Unable to issue Item');
                        $this->redirect(array('action' => 'index'));
                    }
                }
            }
        }
    }

    private function issueItem($input) {
        // NOTE:  This function should only be used in the middle of a 
        // beginTransaction() / commit/rollbackTransaction() block
        $id = $input['ItemID'];
        if ($id == null) {
            $this->log('Issue failed at line 188' . "ItemID: {$input['ItemID']}");
            return false;
        } else {
            $changeQty = $input['Quantity'];
            $notes = $input['Notes'];
            $userId = $input['UserID'];
            $userCode = $input['UserCode'];
        }
        if ($changeQty <= 0) {
            $this->log('Issue failed at line 197' . "ItemID: {$input['Item']['ItemID']}");
            return false;
        }
        // set model to record to be edited and retrieve existing data
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.ItemID' => $id
            ), 'recursive' => 0
                ));
        // determine existing quantity and quantity to be subtracted
        $oldQty = $item['Item']['Quantity'];
        // adjust the quantity in the existing model instance
        $item['Item']['Quantity'] = $oldQty - $changeQty;
        // if the issue would reduce stock below zero display and error
        // and reroute to index
        if ($item['Item']['Quantity'] < 0) {
            $this->log('Issue failed at line 213' . "ItemID: {$input['Item']['ItemID']}");
            return false;
        } else {
            $item['Item']['UpdatedBy'] = $userCode;
            if ($this->Item->save($item)) {
                $this->log("{$item['Item']['ItemCode']} Saved");
                $inventoryHistory = array();
                // historyType defines the type of transaction to be logged.  6 is issue
                $historyType = 6;
                $inv = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.ItemID' => $id
                    ), 'fields' => array('Item.ItemID', 'Item.ItemCode', 'Item.LocationIDCurrent'), 'contain' => array(
                        'Location' => array(
                            'fields' => array(
                                'Location.LocationID', 'Location.LocationCode'
                            )
                        ), 'Part' => array(
                            'fields' => array(
                                'Part.UOMIDInventory', 'Part.ModelID'
                            )
                        )
                    )
                        ));
                // Set the values for the inventory history entry
                $currTime = date("n/d/Y g:i:s A");
                $this->loadModel('InventoryHistory');
                $inventoryHistory['InventoryHistory']['ItemID'] = $id;
                $inventoryHistory['InventoryHistory']['SiteID'] = 1;
                $inventoryHistory['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                $inventoryHistory['InventoryHistory']['InvHistoryTypeID'] = $historyType;
                $inventoryHistory['InventoryHistory']['HistoryDateTime'] = $currTime;
                $inventoryHistory['InventoryHistory']['ItemCode'] = $item['Item']['ItemCode'];
                $inventoryHistory['InventoryHistory']['QuantityChange'] = '-' . $changeQty;
                $inventoryHistory['InventoryHistory']['UOMID'] = $inv['Part']['UOMIDInventory'];
                $inventoryHistory['InventoryHistory']['CostTypeID'] = 4;
                $inventoryHistory['InventoryHistory']['LocationID'] = $inv['Item']['LocationIDCurrent'];
                $inventoryHistory['InventoryHistory']['LocationCode'] = $inv['Location']['LocationCode'];
                $inventoryHistory['InventoryHistory']['IssueeID'] = 40;
                $inventoryHistory['InventoryHistory']['IssueeCode'] = 'CONSUMPTION';
                $inventoryHistory['InventoryHistory']['UserID'] = $userId;
                $inventoryHistory['InventoryHistory']['UserCode'] = $userCode;
                $inventoryHistory['InventoryHistory']['Notes'] = $notes;
                $inventoryHistory['InventoryHistory']['Added'] = $currTime;
                $inventoryHistory['InventoryHistory']['AddedBy'] = $userCode;
                $inventoryHistory['InventoryHistory']['Updated'] = $currTime;
                $inventoryHistory['InventoryHistory']['UpdatedBy'] = $userCode;
                $this->InventoryHistory->create();
                if ($this->InventoryHistory->save($inventoryHistory)) {
                    // on successful save, redirect to index
                    $this->log("Inventory history for {$item['Item']['ItemCode']} Saved");
                    return true;
                } else {
                    $this->log('Issue failed at line 263' . "ItemID: {$input['Item']['ItemID']}");
                    return false;
                }
            } else {
                $this->log('Issue failed at line 267' . "ItemID: {$input['Item']['ItemID']}");
                return false;
            }
        }
    }

    public function receive() {
        if ($this->Session->read('User')) {
            if ($this->Session->read('User.canIssue') !== true) {
                $this->Session->setFlash('You do not have the necessary permissions for that');
                $this->redirect(array('action' => 'index'));
            }
            $userId = $this->Session->read('User.UserID');
            $userCode = $this->Session->read('User.UserLogOn');
        } else {
            $this->Session->setFlash('You must be logged in to perform that action');
            $this->redirect(array('action' => 'login', 'controller' => 'users'));
        }
        if ($this->request->is('post')) {
            //process the receive transaction
            // TODO fix so that consumable items can be received to existing location

            $newItem = $this->request->data;
            $newItem['Item']['Quantity'] = (float)preg_replace('/,/', '', $newItem['Item']['Quantity']);
            if ($newItem['Item']['Quantity'] <= 0) {
                $this->Session->setFlash('Quantity must be greater than 0');
                $this->redirect(array('action' => 'receive'));
            }
            $currTime = date("n/d/Y g:i:s A");
            $this->loadModel('InventoryHistory');
            $part = $this->Item->Part->find('first', array(
                'conditions' => array(
                    'Part.ModelID' => $newItem['Item']['ModelID']
                )
                    ));
            if ($part['Part']['ItemTypeID'] == 4) {
                $this->log('non-standard item being received');
                if ($itemCode = $this->incrementInvControl()) {
                    $this->Item->create();
                    $newItem['Item']['ItemCode'] = $itemCode;
                    $newItem['Item']['Added'] = $currTime;
                    $newItem['Item']['Updated'] = $currTime;
                    $newItem['Item']['AddedBy'] = $userCode;
                    $newItem['Item']['UpdatedBy'] = $userCode;
                } else {
                    $this->Session->setFlash('Could not assign inv ctrl#');
                    $this->redirect(array('action' => 'receive'));
                }
            } else {
                $this->log('Standard item being received');
                $existingItem = $this->Item->find('first', array(
                    'conditions' => array('Item.ItemCode' => $part['Part']['ModelCode'],
                        'Item.LocationIDCurrent' => $newItem['Item']['LocationIDCurrent'])
                        ));
                $count = $this->Item->find('count', array(
                    'conditions' => array('Item.ItemCode' => $part['Part']['ModelCode'],
                        'Item.LocationIDCurrent' => $newItem['Item']['LocationIDCurrent'])
                        ));
                $this->log($existingItem);
                if ($count > 0) {
                    $this->log("Count = " . count($existingItem));
                    $newItem['Item']['ItemCode'] = $existingItem['Item']['ItemCode'];
                    $newItem['Item']['ItemID'] = $existingItem['Item']['ItemID'];
                    $newItem['Item']['Quantity'] += $existingItem['Item']['Quantity'];
                    $newItem['Item']['Updated'] = $currTime;
                    $newItem['Item']['UpdatedBy'] = $userCode;
                } else {
                    $this->log('No existing entry found');
                    $this->Item->create();
                    $newItem['Item']['ItemCode'] = $part['Part']['ModelCode'];
                    $newItem['Item']['Added'] = $currTime;
                    $newItem['Item']['Updated'] = $currTime;
                    $newItem['Item']['AddedBy'] = $userCode;
                    $newItem['Item']['UpdatedBy'] = $userCode;
                }
            }


            $this->Item->beginTransaction();

            if ($this->Item->save($newItem)) {
                // retrieve the requisite info to populate the history entry

                $location = $this->Item->Location->find('first', array(
                    'conditions' => array(
                        'Location.LocationID' => $newItem['Item']['LocationIDCurrent']
                    ), 'fields' => array('Location.LocationID', 'Location.LocationCode')
                        ));
                // create history item
                $historyType = 1;
                $inventoryHistory = array();
                $inventoryHistory['InventoryHistory']['SiteID'] = 1;
                $inventoryHistory['InventoryHistory']['ItemID'] = $this->Item->id;
                $inventoryHistory['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                $inventoryHistory['InventoryHistory']['InvHistoryTypeID'] = $historyType;
                $inventoryHistory['InventoryHistory']['HistoryDateTime'] = $currTime;
                $inventoryHistory['InventoryHistory']['ItemCode'] = $newItem['Item']['ItemCode'];
                $inventoryHistory['InventoryHistory']['QuantityChange'] = $this->request->data['Item']['Quantity'];
                $inventoryHistory['InventoryHistory']['UOMID'] = $part['Part']['UOMIDInventory'];
                $inventoryHistory['InventoryHistory']['CostTypeID'] = 4;
                $inventoryHistory['InventoryHistory']['LocationID'] = $newItem['Item']['LocationIDCurrent'];
                $inventoryHistory['InventoryHistory']['LocationCode'] = $location['Location']['LocationCode'];
                $inventoryHistory['InventoryHistory']['UserID'] = $userId;
                $inventoryHistory['InventoryHistory']['UserCode'] = $userCode;
                //$inventoryHistory['InventoryHistory']['Notes'] = $notes;
                $inventoryHistory['InventoryHistory']['Added'] = $currTime;
                $inventoryHistory['InventoryHistory']['AddedBy'] = $userCode;
                $inventoryHistory['InventoryHistory']['Updated'] = $currTime;
                $inventoryHistory['InventoryHistory']['UpdatedBy'] = $userCode;
                if ($this->InventoryHistory->save($inventoryHistory)) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Item Received');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Item could not be received - error in transaction history');
                    $this->redirect(array('action' => 'receive'));
                }
            } else {
                $this->Item->rollbackTransaction();
                $this->Session->setFlash('Item could not be received');
                $this->redirect(array('action' => 'receive'));
            }
        } else {
            // do nothing
            $this->set('locations', $this->Item->Location->find('list', array(
                        'conditions' => array('Location.Deleted' => 0),
                        'fields' => array('Location.LocationID', 'Location.LocationCode'),
                        'order' => 'Location.LocationCode asc'
                    )));
            $this->set('parts', $this->Item->Part->find('list', array(
                        'conditions' => array('Part.Deleted' => 0),
                        'fields' => array('Part.ModelID', "Part.numberAndName"),
                        'order' => 'Part.ModelCode asc'
                    )));
        }
    }

    public function adjust($id = null){
        // a GET request is used to specify the Item ID of the item to be issued
        if ($this->request->is('get')) {
            if ($id != null) {
                $this->set('item', $this->Item->find('first', array(
                            'recursive' => 2, 'conditions' => array(
                                'Item.ItemID' => $id
                            )
                        )));
            }
        } else {
            // if the request is a POST, this indicates that data has been submitted
            // through the form and should be processed.
            // 02Jul:  Add function to handle serial issuing
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'login', 'controller' => 'users'));
            }
            if (is_array($this->request->data['Item']['ItemID'])) {
                $this->log('Multiple adjust detected');
                // handle a multiple issue
                /* 1. Loop through the ItemID element of the array and populate an array
                 *    representing a single item to be issued  
                 * 2. Pass each array to a function which will perform a single item issue
                 * 3. If an error is encountered at any point, rollback the entire transaction
                 */
                $this->Item->beginTransaction();
                $items = $this->request->data['Item'];
                $success = true;
//                $this->set('items', $items);
//                $this->render();
                foreach ($items['ItemID'] as $key => $val) {
                    // attempt to issue the given id and qty
                    $this->log("ItemID $val sent to adjustItem");
                    $tempArray = array(
                        'ItemID' => $val,
                        'Quantity' => (float)preg_replace('/,/', '', $items['Quantity'][$key]),
                        'Notes' => $items['Notes'][$key],
                        'UserID' => $userId,
                        'UserCode' => $userCode
                    );
                    if ($this->adjustItem($tempArray)) {
                        // Do nothing
                    } else {
                        $success = false;
                    }
                }
                if ($success) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Success!');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Adjust Failure!');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $tempArray = array(
                    'ItemID' => $this->request->data['Item']['ItemID'],
                    'Quantity' => (float)preg_replace('/,/', '', $this->request->data['Item']['Quantity']),
                    'Notes' => $this->request->data['Item']['Notes'],
                    'UserID' => $userId,
                    'UserCode' => $userCode
                );
                $this->Item->beginTransaction();
                if ($this->adjustItem($tempArray)) {
                    $this->Session->setFlash('Adjust successful');
                    $this->Item->commitTransaction();
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Could not complete adjust transaction');
                    $this->Item->rollbackTransaction();
                    $this->redirect(array('action' => 'adjust', $this->request->data['Item']['ItemID']));
                }
            }
        }
    }
    
    public function adjustOld($id = null) {
        // a GET request is used to specify the Item ID of the item to be issued
        if ($this->request->is('get')) {
            if ($id != null) {
                $query = "SELECT TOP 1 * from WsvInventoryIndex where ItemID = $id";
                $this->set('item', $this->Item->query($query));
            }
        } else {
            // if the request is a POST, this indicates that data has been submitted
            // through the form and should be processed.
            // 02Jul:  Add function to handle serial issuing
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'login', 'controller' => 'users'));
            }
            if (is_array($this->request->data['Item']['ItemID'])) {
                $this->log('Multiple adjust detected');
                // handle a multiple issue
                /* 1. Loop through the ItemID element of the array and populate an array
                 *    representing a single item to be issued  
                 * 2. Pass each array to a function which will perform a single item issue
                 * 3. If an error is encountered at any point, rollback the entire transaction
                 */
                $this->Item->beginTransaction();
                $items = $this->request->data['Item'];
                $success = true;
//                $this->set('items', $items);
//                $this->render();
                foreach ($items['ItemID'] as $key => $val) {
                    // attempt to issue the given id and qty
                    $this->log("ItemID $val sent to adjustItem");
                    $tempArray = array(
                        'ItemID' => $val,
                        'Quantity' => $items['Quantity'][$key],
                        'Notes' => $items['Notes'][$key],
                        'UserID' => $userId,
                        'UserCode' => $userCode
                    );
                    if ($this->adjustItem($tempArray)) {
                        // Do nothing
                    } else {
                        $success = false;
                    }
                }
                if ($success) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Success!');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Adjust Failure!');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $tempArray = array(
                    'ItemID' => $this->request->data['Item']['ItemID'],
                    'Quantity' => $this->request->data['Item']['Quantity'],
                    'Notes' => $this->request->data['Item']['Notes'],
                    'UserID' => $userId,
                    'UserCode' => $userCode
                );
                $this->Item->beginTransaction();
                if ($this->adjustItem($tempArray)) {
                    $this->Session->setFlash('Adjust successful');
                    $this->Item->commitTransaction();
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Could not complete adjust transaction');
                    $this->Item->rollbackTransaction();
                    $this->redirect(array('action' => 'adjust', $this->request->data['Item']['ItemID']));
                }
            }
        }
    }

    public function adjustItem($input) {
        // NOTE:  This function should only be used in the middle of a 
        // beginTransaction() / commit/rollbackTransaction() block
        $id = $input['ItemID'];
        if ($id == null) {

            return false;
        } else {
            $changeQty = $input['Quantity'];
            $notes = $input['Notes'];
            $userId = $input['UserID'];
            $userCode = $input['UserCode'];
        }
        if ($changeQty < 0) {

            return false;
        }
        // set model to record to be edited and retrieve existing data
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.ItemID' => $id
            ), 'recursive' => 0
                ));
        // determine existing quantity and quantity to be subtracted
        $oldQty = $item['Item']['Quantity'];
        // adjust the quantity in the existing model instance
        $item['Item']['Quantity'] = $changeQty;
        // if the issue would reduce stock below zero display and error
        // and reroute to index
        if ($item['Item']['Quantity'] < 0) {

            return false;
        } else {
            $item['Item']['UpdatedBy'] = $userCode;
            if ($this->Item->save($item)) {

                $inventoryHistory = array();
                // historyType defines the type of transaction to be logged.  6 is issue
                $historyType = 3;
                $inv = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.ItemID' => $id
                    ), 'fields' => array('Item.ItemID', 'Item.ItemCode', 'Item.LocationIDCurrent'), 'contain' => array(
                        'Location' => array(
                            'fields' => array(
                                'Location.LocationID', 'Location.LocationCode'
                            )
                        ), 'Part' => array(
                            'fields' => array(
                                'Part.UOMIDInventory', 'Part.ModelID'
                            )
                        )
                    )
                        ));
                // Set the values for the inventory history entry
                $currTime = date("n/d/Y g:i:s A");
                $this->loadModel('InventoryHistory');
                $inventoryHistory['InventoryHistory']['ItemID'] = $id;
                $inventoryHistory['InventoryHistory']['SiteID'] = 1;
                $inventoryHistory['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                $inventoryHistory['InventoryHistory']['InvHistoryTypeID'] = $historyType;
                $inventoryHistory['InventoryHistory']['HistoryDateTime'] = $currTime;
                $inventoryHistory['InventoryHistory']['ItemCode'] = $item['Item']['ItemCode'];
                $inventoryHistory['InventoryHistory']['QuantityChange'] = $changeQty - $oldQty;
                $inventoryHistory['InventoryHistory']['UOMID'] = $inv['Part']['UOMIDInventory'];
                $inventoryHistory['InventoryHistory']['CostTypeID'] = 4;
                $inventoryHistory['InventoryHistory']['LocationID'] = $inv['Item']['LocationIDCurrent'];
                $inventoryHistory['InventoryHistory']['LocationCode'] = $inv['Location']['LocationCode'];
                $inventoryHistory['InventoryHistory']['UserID'] = $userId;
                $inventoryHistory['InventoryHistory']['UserCode'] = $userCode;
                $inventoryHistory['InventoryHistory']['Notes'] = $notes;
                $inventoryHistory['InventoryHistory']['Added'] = $currTime;
                $inventoryHistory['InventoryHistory']['AddedBy'] = $userCode;
                $inventoryHistory['InventoryHistory']['Updated'] = $currTime;
                $inventoryHistory['InventoryHistory']['UpdatedBy'] = $userCode;
                $this->InventoryHistory->create();
                if ($this->InventoryHistory->save($inventoryHistory)) {
                    // on successful save, redirect to index

                    return true;
                } else {

                    return false;
                }
            } else {

                return false;
            }
        }
    }

    public function move($id = null){
        if ($this->request->is('get')) {
            $this->set('locations', $this->Item->Location->find('list', array(
                        'fields' => array('Location.LocationID', 'Location.LocationCode'),
                        'conditions' => array('Location.Deleted' => 0), 'order' => 'Location.LocationCode asc'
                    )));
            if ($id != null) {

                $this->set('item', $this->Item->find('first', array(
                            'recursive' => 2, 'conditions' => array(
                                'Item.ItemID' => $id
                            )
                        )));
            } else {
                
            }
        } else {
//            $this->set('data', $this->request->data);
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'login', 'controller' => 'users'));
            }
            if (is_array($this->request->data['Item']['ItemID'])) {
                $this->set('data', $this->request->data);
                $this->Item->beginTransaction();
                $items = $this->request->data['Item'];
                $success = true;
//                $this->set('items', $items);
//                $this->render();
                foreach ($items['ItemID'] as $key => $val) {

                    $this->log("ItemID $val sent to issueItem");
                    $tempArray = array(
                        'ItemID' => $val,
                        'Quantity' => (float)preg_replace('/,/', '', $items['Quantity'][$key]),
                        'ToLocationId' => $items['ToLocationId'][$key],
                        'Notes' => $items['Notes'][$key],
                        'UserID' => $userId,
                        'UserCode' => $userCode
                    );
                    if ($this->moveItem($tempArray)) {
                        // Do nothing
                    } else {
                        $success = false;
                    }
                }
                if ($success) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Success!');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Move Failure!');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                // issue a single item
                $tempArray = array(
                    'ItemID' => $this->request->data['Item']['ItemID'],
                    'Quantity' => (float)preg_replace('/,/', '', $this->request->data['Item']['Quantity']),
                    'ToLocationId' => $this->request->data['Item']['Location'],
                    'Notes' => $this->request->data['Item']['Notes'],
                    'UserID' => $userId,
                    'UserCode' => $userCode
                );
                $this->Item->beginTransaction();
                if ($this->moveItem($tempArray)) {
                    $this->Session->setFlash('Move successful');
                    $this->Item->commitTransaction();
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Could not complete move transaction');
                    $this->Item->rollbackTransaction();
                    $this->redirect(array('action' => 'move', $this->request->data['Item']['ItemID']));
                }
            }
        }
    }
    
    public function moveOld($id = null) {
        if ($this->request->is('get')) {
            $this->set('locations', $this->Item->Location->find('list', array(
                        'fields' => array('Location.LocationID', 'Location.LocationCode'),
                        'conditions' => array('Location.Deleted' => 0), 'order' => 'Location.LocationCode asc'
                    )));
            if ($id != null) {

                $query = "SELECT TOP 1 * from WsvInventoryIndex where ItemID = $id";
                $this->set('item', $this->Item->query($query));
            } else {
                
            }
        } else {
//            $this->set('data', $this->request->data);
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'login', 'controller' => 'users'));
            }
            if (is_array($this->request->data['Item']['ItemID'])) {
                $this->set('data', $this->request->data);
                $this->Item->beginTransaction();
                $items = $this->request->data['Item'];
                $success = true;
//                $this->set('items', $items);
//                $this->render();
                foreach ($items['ItemID'] as $key => $val) {

                    $this->log("ItemID $val sent to issueItem");
                    $tempArray = array(
                        'ItemID' => $val,
                        'Quantity' => $items['Quantity'][$key],
                        'ToLocationId' => $items['ToLocationId'][$key],
                        'Notes' => $items['Notes'][$key],
                        'UserID' => $userId,
                        'UserCode' => $userCode
                    );
                    if ($this->moveItem($tempArray)) {
                        // Do nothing
                    } else {
                        $success = false;
                    }
                }
                if ($success) {
                    $this->Item->commitTransaction();
                    $this->Session->setFlash('Success!');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Item->rollbackTransaction();
                    $this->Session->setFlash('Move Failure!');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                // issue a single item
                $tempArray = array(
                    'ItemID' => $this->request->data['Item']['ItemID'],
                    'Quantity' => $this->request->data['Item']['Quantity'],
                    'ToLocationId' => $this->request->data['Item']['Location'],
                    'Notes' => $this->request->data['Item']['Notes'],
                    'UserID' => $userId,
                    'UserCode' => $userCode
                );
                $this->Item->beginTransaction();
                if ($this->moveItem($tempArray)) {
                    $this->Session->setFlash('Move successful');
                    $this->Item->commitTransaction();
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Could not complete move transaction');
                    $this->Item->rollbackTransaction();
                    $this->redirect(array('action' => 'move', $this->request->data['Item']['ItemID']));
                }
            }
        }
    }

    private function moveItem($input) {
        // NOTE:  This function should only be used in the middle of a 
        // beginTransaction() / commit/rollbackTransaction() block
        $id = $input['ItemID'];
        if ($id == null) {
            $this->log('Move failed at line 500' . "ItemID: {$input['ItemID']}");
            return false;
        } else {
            $changeQty = $input['Quantity'];
            $toLocationId = $input['ToLocationId'];
            $notes = $input['Notes'];
            $userId = $input['UserID'];
            $userCode = $input['UserCode'];
        }
        if ($changeQty <= 0) {
            $this->log('Move failed at line 510' . "ItemID: {$input['Item']['ItemID']}");
            return false;
        }
        // set model to record to be edited and retrieve existing data
        $existingItem = $this->Item->find('first', array(
            'conditions' => array(
                'Item.ItemID' => $id
            ), 'recursive' => 0
                ));
        // determine existing quantity and quantity to be subtracted
        $oldQty = $existingItem['Item']['Quantity'];
        // adjust the quantity in the existing model instance
        $existingItem['Item']['Quantity'] = $oldQty - $changeQty;
        // if the issue would reduce stock below zero display and error
        // and reroute to index
        if ($existingItem['Item']['Quantity'] < 0) {
            $this->log('Move failed at line 213' . "ItemID: {$input['Item']['ItemID']}");
            return false;
        } else {
            /* Determine if there is an existing entry for the given itemcode
             * at the MoveTo location.
             * 1. If there is an existing entry, adjust the quantities at the new and existing
             * locations and save both records.
             * 2. If there is no existing entry at that location, create a new Item record
             * for the itemcode at the new location.  Update both records.
             * 3. Generate two Inventory History entries:  MoveFrom and MoveTo
             */
            $moveToHistoryType = 5;
            $moveFromHistoryType = 4;
            // check for an existing entry at the new location
            $existingMoveTo = $this->Item->find('first', array(
                'conditions' => array(
                    'Item.ItemCode' => $existingItem['Item']['ItemCode'],
                    'Item.LocationIDCurrent' => $toLocationId
                )
                    ));
            $currTime = date("n/d/Y g:i:s A");
            $existingItem['Item']['UpdatedBy'] = $userCode;
            if ($this->Item->save($existingItem)) {
                if (!empty($existingMoveTo)) {
                    $existingMoveTo['Item']['Quantity'] += $changeQty;
                    $existingMoveTo['Item']['Updated'] = $currTime;
                    $existingMoveTo['Item']['UpdatedBy'] = $userCode;
                } else {
                    $existingMoveTo = $existingItem;
                    unset($existingMoveTo['Item']['ItemID']);
                    $existingMoveTo['Item']['Quantity'] = $changeQty;
                    $existingMoveTo['Item']['LocationIDCurrent'] = $toLocationId;
                    $existingMoveTo['Item']['Added'] = $currTime;
                    $existingMoveTo['Item']['Updated'] = $currTime;
                    $existingMoveTo['Item']['AddedBy'] = $userCode;
                    $existingMoveTo['Item']['UpdatedBy'] = $userCode;
                    $this->Item->create();
                }
                if ($this->Item->save($existingMoveTo)) {
                    $inventoryHistoryFrom = array();
                    $inventoryHistoryTo = array();
                    $invFrom = $this->Item->find('first', array(
                        'conditions' => array(
                            'Item.ItemID' => $id
                        ), 'fields' => array('Item.ItemID', 'Item.ItemCode', 'Item.LocationIDCurrent'), 'contain' => array(
                            'Location' => array(
                                'fields' => array(
                                    'Location.LocationID', 'Location.LocationCode'
                                )
                            ), 'Part' => array(
                                'fields' => array(
                                    'Part.UOMIDInventory', 'Part.ModelID'
                                )
                            )
                        )
                            ));
                    $invTo = $this->Item->find('first', array(
                        'conditions' => array(
                            'Item.ItemID' => $this->Item->id
                        ), 'fields' => array('Item.ItemID', 'Item.ItemCode', 'Item.LocationIDCurrent'), 'contain' => array(
                            'Location' => array(
                                'fields' => array(
                                    'Location.LocationID', 'Location.LocationCode'
                                )
                            ), 'Part' => array(
                                'fields' => array(
                                    'Part.UOMIDInventory', 'Part.ModelID'
                                )
                            )
                        )
                            ));
                    // Set the values for the MoveFrom Entry
                    $this->loadModel('InventoryHistory');
                    $inventoryHistoryFrom['InventoryHistory']['ItemID'] = $id;
                    $inventoryHistoryFrom['InventoryHistory']['SiteID'] = 1;
                    $inventoryHistoryFrom['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                    $inventoryHistoryFrom['InventoryHistory']['InvHistoryTypeID'] = $moveFromHistoryType;
                    $inventoryHistoryFrom['InventoryHistory']['HistoryDateTime'] = $currTime;
                    $inventoryHistoryFrom['InventoryHistory']['ItemCode'] = $existingItem['Item']['ItemCode'];
                    $inventoryHistoryFrom['InventoryHistory']['QuantityChange'] = '-' . $changeQty;
                    $inventoryHistoryFrom['InventoryHistory']['UOMID'] = $invFrom['Part']['UOMIDInventory'];
                    $inventoryHistoryFrom['InventoryHistory']['CostTypeID'] = 4;
                    $inventoryHistoryFrom['InventoryHistory']['LocationID'] = $invFrom['Item']['LocationIDCurrent'];
                    $inventoryHistoryFrom['InventoryHistory']['LocationCode'] = $invFrom['Location']['LocationCode'];
                    $inventoryHistoryFrom['InventoryHistory']['UserID'] = $userId;
                    $inventoryHistoryFrom['InventoryHistory']['UserCode'] = $userCode;
                    $inventoryHistoryFrom['InventoryHistory']['Notes'] = $notes;
                    $inventoryHistoryFrom['InventoryHistory']['Added'] = $currTime;
                    $inventoryHistoryFrom['InventoryHistory']['AddedBy'] = $userCode;
                    $inventoryHistoryFrom['InventoryHistory']['Updated'] = $currTime;
                    $inventoryHistoryFrom['InventoryHistory']['UpdatedBy'] = $userCode;

                    $inventoryHistoryTo['InventoryHistory']['ItemID'] = $this->Item->id;
                    $inventoryHistoryTo['InventoryHistory']['SiteID'] = 1;
                    $inventoryHistoryTo['InventoryHistory']['TransactionNumber'] = $this->getTimeStamp();
                    $inventoryHistoryTo['InventoryHistory']['InvHistoryTypeID'] = $moveToHistoryType;
                    $inventoryHistoryTo['InventoryHistory']['HistoryDateTime'] = $currTime;
                    $inventoryHistoryTo['InventoryHistory']['ItemCode'] = $existingItem['Item']['ItemCode'];
                    $inventoryHistoryTo['InventoryHistory']['QuantityChange'] = $changeQty;
                    $inventoryHistoryTo['InventoryHistory']['UOMID'] = $invTo['Part']['UOMIDInventory'];
                    $inventoryHistoryTo['InventoryHistory']['CostTypeID'] = 4;
                    $inventoryHistoryTo['InventoryHistory']['LocationID'] = $invTo['Item']['LocationIDCurrent'];
                    $inventoryHistoryTo['InventoryHistory']['LocationCode'] = $invTo['Location']['LocationCode'];
                    $inventoryHistoryTo['InventoryHistory']['UserID'] = $userId;
                    $inventoryHistoryTo['InventoryHistory']['UserCode'] = $userCode;
                    $inventoryHistoryTo['InventoryHistory']['Notes'] = $notes;
                    $inventoryHistoryTo['InventoryHistory']['Added'] = $currTime;
                    $inventoryHistoryTo['InventoryHistory']['AddedBy'] = $userCode;
                    $inventoryHistoryTo['InventoryHistory']['Updated'] = $currTime;
                    $inventoryHistoryTo['InventoryHistory']['UpdatedBy'] = $userCode;

                    $this->InventoryHistory->create();
                    if ($this->InventoryHistory->save($inventoryHistoryFrom)) {
                        $this->InventoryHistory->create();
                        if ($this->InventoryHistory->save($inventoryHistoryTo)) {
                            return true;
                        } else {
                            $this->log('Issue failed at line 645' . "ItemID: {$input['Item']['ItemID']}");
                            return false;
                        }
                    } else {
                        $this->log('Issue failed at line 651' . "ItemID: {$input['Item']['ItemID']}");
                        return false;
                    }
                }
            } else {
                $this->log('Issue failed at line 655' . "ItemID: {$input['Item']['ItemID']}");
                return false;
            }
        }
    }

    /*
     * The following functions are AJAX-listeners used for returning html
     * chunks to the web client for dynamic DOM insertion
     */

    public function ajaxCurrentStock($allowZero = false) {
        // I used the built-in PDO library for database access because using the CakePHP
        // model find() or query() methods were proving to be prohibitively slow
        if ($allowZero == 1) {
            $query = "SELECT distinct Item.ModelID, Part.ModelName, Part.ModelCode, Uom.UOMCode from Items as Item Left Join Models as Part on Item.ModelID = Part.ModelID Left JOIN
                UOMs as Uom on Uom.UOMID = Part.UOMIDInventory where Item.Deleted = 0 ORDER BY Part.ModelCode asc";
            $parts = array();
            $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__, 'mckenzie.jw', 'kollani');
            foreach ($pdo->query($query) as $row) {
                $parts[] = $row;
            }
            $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root><parts></parts></root>");
            foreach ($parts as $part) {
                // iterate through parts and add each one to the xml element
                $partInArray = false;
                $xmlPart = $xml->parts->addChild('part');
                $xmlPart->addChild('id', $part['ModelID']);
                $xmlPart->addChild('name', $part['ModelName']);
                $xmlPart->addChild('number', $part['ModelCode']);
                $xmlPart->addChild('uom', $part['UOMCode']);
            }
            $this->set('xml', $xml);
            $this->set('parts', $parts);
            $this->render('ajaxCurrentStock', 'ajax');
        } else {
            $query = "SELECT distinct Item.ModelID, Part.ModelName, Part.ModelCode, Uom.UOMCode from Items as Item Left Join Models as Part on Item.ModelID = Part.ModelID Left JOIN
                UOMs as Uom on Uom.UOMID = Part.UOMIDInventory where Item.Deleted = 0 AND Item.Quantity > 0 ORDER BY Part.ModelCode asc";
            $parts = array();
            $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__, 'mckenzie.jw', 'kollani');
            foreach ($pdo->query($query) as $row) {
                $parts[] = $row;
            }
            $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root><parts></parts></root>");
            foreach ($parts as $part) {
                // iterate through parts and add each one to the xml element
                $partInArray = false;
                $xmlPart = $xml->parts->addChild('part');
                $xmlPart->addChild('id', $part['ModelID']);
                $xmlPart->addChild('name', $part['ModelName']);
                $xmlPart->addChild('number', $part['ModelCode']);
                $xmlPart->addChild('uom', $part['UOMCode']);
            }
            $this->set('xml', $xml);
            $this->set('parts', $parts);
            $this->render('ajaxCurrentStock', 'ajax');
        }
//
    }

    public function ajaxStockedLocations($partId = null, $allowZero = false) {
        if ($partId == null) {
            if ($allowZero == 1) {
                $locations = $this->Item->find('all', array(
                    'conditions' => array('Item.Deleted' => 0),
                    'contain' => array('Location'), 'order' => 'Location.LocationCode'
                        ));
            } else {
                $locations = $this->Item->find('all', array(
                    'conditions' => array('Item.Quantity >' => 0,
                        'Item.Deleted' => 0),
                    'contain' => array('Location'), 'order' => 'Location.LocationCode'
                        ));
            }

            $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root><locations></locations></root>");
            foreach ($locations as $location) {
                // iterate through parts and add each one to the xml element
                $locationInArray = false;
                foreach ($xml->locations->location as $existingLocation) {
                    if ($existingLocation->id == $location['Location']['LocationID']) {
                        $locationInArray = true;
                    } else {
                        
                    }
                }
                if (!$locationInArray) {
                    $xmlLocation = $xml->locations->addChild('location');
                    $xmlLocation->addChild('id', $location['Location']['LocationID']);
                    $xmlLocation->addChild('code', $location['Location']['LocationCode']);
                }
            }
            $this->set('xml', $xml);
            $this->set('locations', $locations);
            $this->render('ajaxStockedLocations', 'ajax');
        } else {
            if ($allowZero) {
                $locations = $this->Item->find('all', array(
                    'conditions' => array('Item.Deleted' => 0, 'Item.ModelID' => $partId),
                    'contain' => array('Location'), 'order' => 'Location.LocationCode'
                        ));
            } else {
                $locations = $this->Item->find('all', array(
                    'conditions' => array('Item.Quantity >' => 0,
                        'Item.Deleted' => 0, 'Item.ModelID' => $partId),
                    'contain' => array('Location'), 'order' => 'Location.LocationCode'
                        ));
            }

            $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root><locations></locations></root>");
            foreach ($locations as $location) {
                // iterate through parts and add each one to the xml element
                $locationInArray = false;
                foreach ($xml->locations->location as $existingLocation) {
                    if ($existingLocation->id == $location['Location']['LocationID']) {
                        $locationInArray = true;
                    } else {
                        
                    }
                }
                if (!$locationInArray) {
                    if ($location['Item']['ModelID'] == $partId) {
                        $xmlLocation = $xml->locations->addChild('location');
                        $xmlLocation->addChild('id', $location['Location']['LocationID']);
                        $xmlLocation->addChild('code', $location['Location']['LocationCode']);
                    }
                }
            }
            $this->set('xml', $xml);
            $this->set('locations', $locations);
            $this->render('ajaxStockedLocations', 'ajax');
        }
    }

    public function ajaxInvCtrls($partId = null) {
        if ($partId == null) {
            return null;
        } else {
//            $items = $this->Item->find('all', array(
//                'conditions' => array(
//                    'Item.Deleted' => 0,
//                    'Item.ModelID' => $partId
//                ),
//                'fields' => array(
//                    'DISTINCT Item.ItemCode', 'Item.TransLineItemUD1', 'Item.TransLineItemUD3'
//                ), 'contain' => array('Part' => array('fields' => array('Part.ModelName')))
//                    ));
            $partId = Sanitize::clean($partId);
            $query = "SELECT DISTINCT Item.ItemCode, Item.TransLineItemUD1, Item.TransLineItemUD3, Part.ModelName from Items as Item LEFT JOIN
                Models as Part on Part.ModelID = Item.ModelID Where Item.Deleted = 0 and Item.ModelID = $partId";
            $items = array();
            $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__, 'mckenzie.jw', 'kollani');
            foreach ($pdo->query($query) as $row) {
                $items[] = $row;
            }
            $xml = new simpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
            foreach ($items as $item) {
                $invItem = $xml->addChild('Item');
                //$invItem->addChild('id', $item['Item']['ItemID']);
                $invItem->addChild('rev', $item['TransLineItemUD1']);
                $invItem->addChild('batch', $item['TransLineItemUD3']);
                $invItem->addChild('invCtrl', $item['ItemCode']);
                $invItem->addChild('name', $item['ModelName']);
            }
            $this->set('xml', $xml);
            $this->render('ajaxInvCtrls', 'ajax');
        }
    }

    public function ajaxViewItem($itemId = null) {
        if ($itemId == null) {
            return null;
        } else {
            $item = $this->Item->find('first', array(
                'conditions' => array('Item.ItemID' => $itemId),
                'contain' => array('Part' => array('Uom'))
                    ));
            $xml = new simpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
            $xmlItem = $xml->addChild('Item');
            $xmlItem->addChild('id', $itemId);
            $xmlItem->addChild('rev', $item['Item']['TransLineItemUD1']);
            $xmlItem->addChild('lot', $item['Item']['TransLineItemUD2']);
            $xmlItem->addChild('batch', $item['Item']['TransLineItemUD3']);
            $xmlItem->addChild('partNumber', $item['Part']['ModelCode']);
            $xmlItem->addChild('uom', $item['Part']['Uom']['UOMCode']);
            $this->set('xml', $xml);
            $this->render('ajaxViewItem', 'ajax');
        }
    }

    public function ajaxView($partId = null, $locationId = null, $allowZero = false) {
        if(isset($this->passedArgs['allowzero'])){$allowZero = $this->passedArgs['allowzero'];}
        if ($locationId == null) {
            if ($partId == null) {
                return null;
            } else {
                if ($allowZero == 1) {
                    $inventories = $this->Item->find('all', array(
                        'conditions' => array(
                            'Item.ModelID' => $partId,
                            'Item.Deleted' => 0
                        ), 'contain' => array('Location' => array('LocationType'), 'Part' => array(
                                'Uom'
                        )), 'order' => array(
                            'Location.LocationCode ASC'
                        )
                            ));
                } else {
                    $inventories = $this->Item->find('all', array(
                        'conditions' => array(
                            'Item.ModelID' => $partId,
                            'Item.Deleted' => 0,
                            'Item.Quantity >' => 0
                        ), 'contain' => array('Location' => array('LocationType'), 'Part' => array(
                                'Uom'
                        ))
                            ));
                }
            }
        } else {
            if ($allowZero == 1) {
                $inventories = $this->Item->find('all', array(
                    'conditions' => array(
                        'Item.LocationIDCurrent' => $locationId,
                        'Item.ModelID' => $partId,
                        'Item.Deleted' => 0
                    ), 'contain' => array('Location' => array('LocationType'), 'Part' => array(
                            'Uom'
                    ))
                        ));
            } else {
                $inventories = $this->Item->find('all', array(
                    'conditions' => array(
                        'Item.LocationIDCurrent' => $locationId,
                        'Item.ModelID' => $partId,
                        'Item.Deleted' => 0,
                        'Item.Quantity >' => 0
                    ), 'contain' => array('Location' => array('LocationType'), 'Part' => array(
                            'Uom'
                    ))
                        ));
            }
        }
        $filteredItem = new simpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
        foreach ($inventories as $item) {
            $invItem = $filteredItem->addChild('Item');
            $invItem->addChild('id', $item['Item']['ItemID']);
            $invItem->addChild('lot', $item['Item']['TransLineItemUD2']);
            $invItem->addChild('locationCode', $item['Location']['LocationCode']);
            $invItem->addChild('batch', $item['Item']['TransLineItemUD3']);
            $invItem->addChild('rev', $item['Item']['TransLineItemUD1']);
            $invItem->addChild('uom', $item['Part']['Uom']['UOMCode']);
            $invItem->addChild('partNumber', $item['Part']['ModelCode']);
            $invItem->addChild('quantity', $item['Item']['Quantity']);
            $invItem->addChild('status', $item['Location']['LocationType']['LocationTypeCode']);
            $invItem->addChild('invCtrl', $item['Item']['ItemCode']);
        }
        $this->set('xml', $filteredItem);
        $this->render('ajaxView', 'ajax');
    }

    private function incrementInvControl() {
        $this->loadModel('BarcodeGenerator');
        $incrementor = $this->BarcodeGenerator->find('first', array(
            'fields' => array(
                'BarcodeGenerator.BarcodeGeneratorID', 'BarcodeGenerator.IncrementingNumber', 'BarcodeGenerator.Format'
            )
                ));

        $incrementor['BarcodeGenerator']['IncrementingNumber']+=1;
        $output = $incrementor['BarcodeGenerator']['IncrementingNumber'];
        $format = $incrementor['BarcodeGenerator']['Format'];
        $output = str_pad($output, $format, "0", STR_PAD_LEFT);
//        $this->set('data', $incrementor);
//        $this->set('output',$output);
        if ($this->BarcodeGenerator->save($incrementor)) {
            return $output;
        } else {
            return false;
        }
    }

    public function printSingleLabel($id = null, $qty = null) {
        if ($id === null || $qty === null) {
            $this->Session->setFlash('No ID or Qty provided to print label');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Item->Location->bindModel(array(
                'belongsTo' => array(
                    'LocType' => array(
                        'className' => 'LocationType',
                        'foreignKey' => 'LocationTypeID'
                    )
                )
            ));
            // Why the hell won't containable behavior work for LocationTypes?!?!?!?!
            $item = $this->Item->find('first', array(
                'conditions' => array(
                    'Item.ItemID' => $id
                ), 'fields' => array('Item.ItemID', 'Item.TransLineItemUD1', 'Item.TransLineItemUD2', 'Item.TransLineItemUD3',
                    'Item.TransLineItemUD4', 'Item.TransLineItemUD5', 'Item.TransLineItemUD6', 'Item.ItemCode'),
                'contain' => array(
                    'Location' => array(
                        'fields' => array(
                            'Location.LocationCode', 'Location.LocationID', 'Location.LocationTypeID'
                        )
                    ),
                    'Part' => array(
                        'Uom',
                        'fields' => array(
                            'Part.ModelName', 'Part.ModelCode'
                        )
                    )
                )
                    ));
            $this->set('item', $item);
            $locType = $this->Item->Location->LocationType->find('first', array(
                'conditions' => array(
                    'LocationType.LocationTypeID' => $item['Location']['LocationTypeID']
                ), 'fields' => array('LocationType.LocationTypeName')
                    ));
            $this->set('locationType', $locType['LocationType']['LocationTypeName']);
            $this->set('quantity', $qty);
            if ($item['Part']['ModelCode'] == '20197-00') {
                $this->render('printPbsLabel', 'ajax');
            } else {
                $this->render('printSingleLabel', 'ajax');
            }
        }
    }

    /*
     * The mobile functions are, as the name suggests, for replicating the various
     * CRUD functions for movile devices
     */

    public function mobileView($itemCode = null) {
        if ($itemCode == null) {
            $this->redirect(array('action' => 'index'));
        } else {
            //$this->Item->unbindModel('InventoryHistory');
            $this->set('items', $this->Item->find('all', array(
                        'conditions' => array(
                            'Item.ItemCode' => $itemCode,
                            'Item.Quantity >' => 0
                        ), 'recursive' => 2
                    )));
        }
        $this->set('locations', $this->Item->Location->find('list', array(
                    'conditions' => array(
                        'Location.Deleted' => 0
                    ), 'fields' => array(
                        'Location.LocationID', 'Location.LocationCode'
                    )
                )));
        $this->render('mobileView', 'mobile');
    }

    public function mobileIssue() {
        if ($this->request->is('post')) {
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'mobileLogin', 'controller' => 'users'));
            }
            $data = $this->request->data;
            $tempArray = array(
                'ItemID' => $data['Item']['ItemID'],
                'Quantity' => $data['Item']['Quantity'],
                'Notes' => $data['Item']['Notes'],
                'UserID' => $userId,
                'UserCode' => $userCode
            );
            $this->Item->beginTransaction();
            if ($this->issueItem($tempArray)) {
                $this->Session->setFlash('Issue Successful');
                $this->Item->commitTransaction();
                $this->redirect(array('action' => 'mobileIndex'));
            } else {
                $this->Session->setFlash('Issue Failed');
                $this->Item->rollbackTransaction();
                $this->redirect(array('action' => 'mobileIndex'));
            }
        }
    }

    public function mobileAdjust() {
        if ($this->request->is('post')) {
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'mobileLogin', 'controller' => 'users'));
            }
            $data = $this->request->data;
            $tempArray = array(
                'ItemID' => $data['Item']['ItemID'],
                'Quantity' => $data['Item']['Quantity'],
                'Notes' => $data['Item']['Notes'],
                'UserID' => $userId,
                'UserCode' => $userCode
            );
            $this->Item->beginTransaction();
            if ($this->adjustItem($tempArray)) {
                $this->Session->setFlash('Adjust Successful');
                $this->Item->commitTransaction();
                $this->redirect(array('action' => 'mobileIndex'));
            } else {
                $this->Session->setFlash('Adjust Failed');
                $this->Item->rollbackTransaction();
                $this->redirect(array('action' => 'mobileIndex'));
            }
        }
    }

    public function mobileMove() {
        if ($this->request->is('post')) {
            if ($this->Session->read('User.canIssue')) {
                if ($this->Session->read('User.canIssue') !== true) {
                    $this->Session->setFlash('You do not have the necessary permissions for that');
                    $this->redirect(array('action' => 'index'));
                }
                $userId = $this->Session->read('User.UserID');
                $userCode = $this->Session->read('User.UserLogOn');
            } else {
                $this->Session->setFlash('You must be logged in to perform that action');
                $this->redirect(array('action' => 'mobileLogin', 'controller' => 'users'));
            }
            $data = $this->request->data;
            $tempArray = array(
                'ItemID' => $data['Item']['ItemID'],
                'Quantity' => $data['Item']['Quantity'],
                'ToLocationId' => $data['Item']['Location'],
                'Notes' => $data['Item']['Notes'],
                'UserID' => $userId,
                'UserCode' => $userCode
            );
            $this->Item->beginTransaction();
            if ($this->moveItem($tempArray)) {
                $this->Session->setFlash('Adjust Successful');
                $this->Item->commitTransaction();
                $this->redirect(array('action' => 'mobileIndex'));
            } else {
                $this->Session->setFlash('Adjust Failed');
                $this->Item->rollbackTransaction();
                $this->redirect(array('action' => 'mobileIndex'));
            }
        }
    }

    public function mobileIndex() {
        $this->set('results', $this->Item->query('SELECT DISTINCT WSV.ModelCode, TotalQty, UOMCode from (SELECT DISTINCT ModelCode, SUM(Quantity) as TotalQty from WsvInventoryIndex Group By ModelCode) as tmpTable LEFT JOIN WsvInventoryIndex as WSV on WSV.ModelCode = tmpTable.ModelCode'));
        $this->render('mobileIndex', 'mobile');
    }

    public function fetchByPart($part = null) {
        if ($part == null) {
            $this->redirect(array('action' => 'mobileIndex'));
        } else {
            $data = $this->Item->query("SELECT * from WsvInventoryIndex WHERE ModelCode = '$part'");
            $this->set('data', $data);
            $this->render('fetchByPart', 'ajax');
        }
    }

    /*
     * the generateCert function generates a generic certificate of conformance for 
     * a given part in inventory
     */

    public function generateCert() {
        if ($this->request->is('post')) {
            $data = Sanitize::clean($this->request->data);
            $id = $data['Item']['ItemID'];
            if ($id == null) {
                $this->Session->setFlash('No valid ID provided');
                $this->redirect(array('action' => 'index'));
            } else {
                $item = $this->Item->query('SELECT TOP 1 * from WsvInventoryIndex where ItemID = ' . $id);
                if (count($item) == 0) {
                    $this->Session->setFlash('Item does not exist');
                    $this->redirect(array('action' => 'index'));
                } else {
                    if ($item[0][0]['LocationTypeCode'] != 'REL') {
                        $this->Session->setFlash('Item has not been released');
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->set('item', $item);
                        $this->set('data', $data);
                        $this->render('generateCert', 'ajax');
                    }
                }
            }
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    public function packingList() {
        if ($this->request->is('post')) {
            // do some stuff
            $this->set('data', $this->request->data);
            $this->render('packingList', 'ajax');
        } else {
            $state_list = array('AL' => "Alabama",
                'AK' => "Alaska",
                'AZ' => "Arizona",
                'AR' => "Arkansas",
                'CA' => "California",
                'CO' => "Colorado",
                'CT' => "Connecticut",
                'DE' => "Delaware",
                'DC' => "District Of Columbia",
                'FL' => "Florida",
                'GA' => "Georgia",
                'HI' => "Hawaii",
                'ID' => "Idaho",
                'IL' => "Illinois",
                'IN' => "Indiana",
                'IA' => "Iowa",
                'KS' => "Kansas",
                'KY' => "Kentucky",
                'LA' => "Louisiana",
                'ME' => "Maine",
                'MD' => "Maryland",
                'MA' => "Massachusetts",
                'MI' => "Michigan",
                'MN' => "Minnesota",
                'MS' => "Mississippi",
                'MO' => "Missouri",
                'MT' => "Montana",
                'NE' => "Nebraska",
                'NV' => "Nevada",
                'NH' => "New Hampshire",
                'NJ' => "New Jersey",
                'NM' => "New Mexico",
                'NY' => "New York",
                'NC' => "North Carolina",
                'ND' => "North Dakota",
                'OH' => "Ohio",
                'OK' => "Oklahoma",
                'OR' => "Oregon",
                'PA' => "Pennsylvania",
                'RI' => "Rhode Island",
                'SC' => "South Carolina",
                'SD' => "South Dakota",
                'TN' => "Tennessee",
                'TX' => "Texas",
                'UT' => "Utah",
                'VT' => "Vermont",
                'VA' => "Virginia",
                'WA' => "Washington",
                'WV' => "West Virginia",
                'WI' => "Wisconsin",
                'WY' => "Wyoming");
            $this->set('states', $state_list);
        }
    }

}

?>
