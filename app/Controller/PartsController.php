<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PartsController
 *
 * @author etbmx
 */
class PartsController extends AppController {

    public $modelName = 'Part';

    public function index() {
        //$this->set('index', $this->Part->find('all'));
        $this->set('results', $this->Part->find('all', array(
                    'conditions' => array('Part.Deleted' => 0),
                    'fields' => array('Part.ModelCode', 'Part.ModelID', 'Part.ModelName', 'Part.MinOnHand', 'Part.MaxOrderTo', 'Part.LeadTime'),
                    'contain' => array(
                        'DefaultLocation' => array(
                            'fields' => array('DefaultLocation.LocationCode', 'DefaultLocation.LocationID')
                        ), 'Uom' => array(
                            'fields' => array('Uom.UOMCode', 'Uom.UOMID')
                        ), 'ItemType' => array(
                            'fields' => array('ItemType.ItemTypeID', 'ItemType.ItemType')
                        )
                    )
                )));
    }

    // This is an AJAX listener function used for populating a drop-box
    public function fetchParts() {
        $this->set('parts', $this->Part->find('all', array(
                    'conditions' => array(
                        'Part.deleted' => false,
                    ), 'fields' => array(
                        'Part.ModelID', 'Part.ModelCode'
                    ), 'recursive' => 0
                )));
        $this->render('fetchParts', 'ajax');
    }

    public function ajaxGetParts($query = null) {
        $xmlElement = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
        if ($this->passedArgs['notId'] == null) {
            $parts = $this->Part->find('all', array(
                'conditions' => array(
                    'Part.Deleted' => false
                ), 'order' => array(
                    'Part.ModelCode asc'
                ), 'fields' => array('Part.ModelID', 'Part.ModelCode', 'Part.ItemTypeID', 'Part.ModelName'), 'recursive' => 0
                    ));
        } else {
            $parts = $this->Part->find('all', array(
                'conditions' => array(
                    'Part.Deleted' => false,
                        'Part.ModelID <>' => $this->passedArgs['notId'] 
                ), 'order' => array(
                    'Part.ModelCode asc'
                ), 'fields' => array('Part.ModelID', 'Part.ModelCode', 'Part.ItemTypeID', 'Part.ModelName'), 'recursive' => 0
                    ));
        }
        foreach ($parts as $part) {
            if ($query == null) {
                $xmlPart = $xmlElement->addChild('part');
                $xmlPart->addChild('id', $part['Part']['ModelID']);
                $xmlPart->addChild('name', $part['Part']['ModelName']);
                $xmlPart->addChild('partNumber', $part['Part']['ModelCode']);
            } else {
                if (strpos(strtolower($part['Part']['ModelCode']), strtolower($query)) !== false) {
                    $xmlPart = $xmlElement->addChild('part');
                    $xmlPart->addChild('id', $part['Part']['ModelID']);
                    $xmlPart->addChild('name', $part['Part']['ModelName']);
                    $xmlPart->addChild('partNumber', $part['Part']['ModelCode']);
                }
            }
        }
        $this->set('xml', $xmlElement);
        $this->set('parts', $parts);
        $this->render('ajaxGetParts', 'ajax');
    }

    public function ajaxGetPart($id = null) {
        $part = $this->Part->find('first', array(
            'conditions' => array(
                'Part.ModelID' => $id
            ), 'fields' => array(
                'Part.ModelID', 'Part.ItemTypeID'
            ), 'contain' => array('Uom' => array(
                    'fields' => array('Uom.UOMID', 'Uom.UOMCode')
            ))
                ));
        $xmlElement = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
        $xmlElement->addChild('uom', $part['Uom']['UOMCode']);
        $xmlElement->addChild('typeId', $part['Part']['ItemTypeID']);
        $this->set('xml', $xmlElement);
        $this->set('part', $part);
        $this->render('ajaxGetPart', 'ajax');
    }

    public function ajaxGetSummary($id = null) {
        $part = $this->Part->find('first', array(
            'conditions' => array(
                'Part.ModelID' => $id
            ), 'fields' => array(
                'Part.ModelID', 'Part.ItemTypeID', 'Part.ModelCode', 'Part.ModelName', 'Part.MinOnHand', 'Part.MaxOrderTo'
            ), 'contain' => array('Uom' => array(
                    'fields' => array('Uom.UOMID', 'Uom.UOMCode')
                ), 'DefaultLocation' => array(
                    'fields' => array('DefaultLocation.LocationCode')
                ), 'ItemType' => array(
                    'fields' => array(
                        'ItemType.ItemTypeCode'
                    )
            ))
                ));
        $this->set('part', $part);
        $this->render('ajaxGetSummary', 'ajax');
    }

    public function add() {
        if ($this->Session->read('User')) {
            if ($this->Session->read('User.canAdmin') !== true) {
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
            // Process the new record
            $this->request->data['Part']['TimeUnitID'] = 5;
            $this->request->data['Part']['UOMIDInventory'] = $this->request->data['Part']['Uom'];
            $this->request->data['Part']['UOMIDIssue'] = $this->request->data['Part']['Uom'];
            $this->request->data['Part']['UOMIDReceive'] = $this->request->data['Part']['Uom'];
            $this->request->data['Part']['CostTypeID'] = 4;
            $this->request->data['Part']['SiteID'] = 1;
            $this->request->data['Part']['AddedBy'] = $userCode;
            $this->request->data['Part']['UpdatedBy'] = $userCode;
            $this->request->data['Part']['AddedBy'] = $userCode;
            $this->Part->beginTransaction();
            if ($this->Part->save($this->request->data)) {
                $this->Part->commitTransaction();
                $this->Session->setFlash('Part successfully added');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Part->rollbackTransaction();
                $this->Session->setFlash('Part could not be added');
                $this->redirect(array('action' => 'index'));
            }
        } else {
            // set variables for the add form
            $this->set('uoms', $this->Part->Uom->find('list', array(
                        'conditions' => array(
                            'Uom.Deleted' => 0
                        ), 'fields' => array('Uom.UOMID', 'Uom.UOMCode'),
                        'order' => array('Uom.UOMCode')
                    )));
            $this->set('itemTypes', $this->Part->ItemType->find('list', array(
                        'fields' => array('ItemType.ItemTypeID', 'ItemType.ItemType'),
                        'order' => array('ItemType.ItemType'), 'conditions' => array(
                            'OR' => array('ItemType.ItemTypeID' => array(2, 4))
                        )
                    )));
            $this->set('locations', $this->Part->DefaultLocation->find('list', array(
                        'conditions' => array('DefaultLocation.Deleted' => 0),
                        'fields' => array('DefaultLocation.LocationID', 'DefaultLocation.LocationCode'),
                        'order' => array('DefaultLocation.LocationCode')
                    )));
        }
    }

    public function view($id = null) {
        if ($id == null) {
            $this->Session->setFlash('No Id specified');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->set('result', $this->Part->find('first', array(
                        'conditions' => array('Part.ModelID' => $id),
                        'contain' => array('DefaultLocation', 'TimeUnit', 'Uom')
                    )));
        }
    }

    public function edit($id = null) {
        if ($this->Session->read('User')) {
            if ($this->Session->read('User.canAdmin') !== true) {
                $this->Session->setFlash('You do not have the necessary permissions for that');
                $this->redirect(array('action' => 'index'));
            }
            $userId = $this->Session->read('User.UserID');
            $userCode = $this->Session->read('User.UserLogOn');
        } else {
            $this->Session->setFlash('You must be logged in to perform that action');
            $this->redirect(array('action' => 'login', 'controller' => 'users'));
        }
        if ($id == null) {
            $this->Session->setFlash('No ID provided');
            $this->redirect(array('action' => 'index'));
        } else {
            if ($this->request->is('post')) {
                // Process the updated record
                $this->request->data['Part']['TimeUnitID'] = 5;
                $this->request->data['Part']['UOMIDInventory'] = $this->request->data['Part']['Uom'];
                $this->request->data['Part']['UOMIDIssue'] = $this->request->data['Part']['Uom'];
                $this->request->data['Part']['UOMIDReceive'] = $this->request->data['Part']['Uom'];
                $this->request->data['Part']['CostTypeID'] = 4;
                $this->request->data['Part']['SiteID'] = 1;
                $this->request->data['Part']['UpdatedBy'] = $userCode;
                $this->log($this->request->data['Part']['UDNumericModel1']);
                $this->Part->beginTransaction();
                if ($this->Part->save($this->request->data)) {
                    $this->Part->commitTransaction();
                    $this->Session->setFlash('Part successfully updated');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Part->rollbackTransaction();
                    $this->Session->setFlash('Part could not be updated');
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->set('part', $this->Part->find('first', array(
                            'conditions' => array(
                                'Part.ModelID' => $id
                            ), 'fields' => array(
                                'Part.ModelID', 'Part.ItemTypeID', 'Part.ModelCode', 'Part.ModelName',
                                'Part.MinOnHand', 'Part.MaxOrderTo', 'Part.UDTextModel2', 'Part.LeadTime',
                                'Part.UDNumericModel1'
                            ), 'contain' => array('Uom' => array(
                                    'fields' => array('Uom.UOMID', 'Uom.UOMCode')
                                ), 'DefaultLocation' => array(
                                    'fields' => array('DefaultLocation.LocationCode')
                                ), 'ItemType' => array(
                                    'fields' => array(
                                        'ItemType.ItemTypeCode'
                                    )
                            ))
                        )));
                $this->set('uoms', $this->Part->Uom->find('list', array(
                            'conditions' => array(
                                'Uom.Deleted' => 0
                            ), 'fields' => array('Uom.UOMID', 'Uom.UOMCode'),
                            'order' => array('Uom.UOMCode')
                        )));
                $this->set('itemTypes', $this->Part->ItemType->find('list', array(
                            'fields' => array('ItemType.ItemTypeID', 'ItemType.ItemType'),
                            'order' => array('ItemType.ItemType'), 'conditions' => array(
                                'OR' => array('ItemType.ItemTypeID' => array(2, 4))
                            )
                        )));
                $this->set('locations', $this->Part->DefaultLocation->find('list', array(
                            'conditions' => array('DefaultLocation.Deleted' => 0),
                            'fields' => array('DefaultLocation.LocationID', 'DefaultLocation.LocationCode'),
                            'order' => array('DefaultLocation.LocationCode')
                        )));
            }
        }
    }

}

?>
