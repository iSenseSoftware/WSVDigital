<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocationsController
 *
 * @author etbmx
 */
class LocationsController extends AppController {

    public $modelName = 'Location';
    public $primaryKey = 'LocationID';

    public function index(){
        
    }
    
    //put your code here
    public function ajaxGetLocations($query = null) {
        $xmlElement = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
        $locations = $this->Location->find('all', array(
            'conditions' => array(
                'Location.Deleted' => 0
            ), 'fields' => array('Location.LocationID', 'Location.LocationCode'), 'recursive' => 0,
        		'order'=>'Location.LocationCode asc'
                ));
        if ($query == null) {
            foreach ($locations as $location) {
                $xmlLocation = $xmlElement->addChild('location');
                $xmlLocation->addChild('id', $location['Location']['LocationID']);
                $xmlLocation->addChild('locationCode', $location['Location']['LocationCode']);
            }
        } else {
            foreach ($locations as $location) {
                if (strpos(strtolower($location['Location']['LocationCode']), strtolower($query)) !== false) {
                    $xmlLocation = $xmlElement->addChild('location');
                    $xmlLocation->addChild('id', $location['Location']['LocationID']);
                    $xmlLocation->addChild('locationCode', $location['Location']['LocationCode']);
                }
            }
        }


        $this->set('xml', $xmlElement);
        //$this->set('locations', $Locations);
        $this->render('ajaxGetLocations', 'ajax');
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
            $this->request->data['Location']['LocationTypeID'] = $this->request->data['Location']['LocationType'];
            $this->request->data['Location']['UpdatedBy'] = $userCode;
            $this->request->data['Location']['AddedBy'] = $userCode;
            $this->Location->beginTransaction();
            if ($this->Location->save($this->request->data)) {
                $this->Location->commitTransaction();
                $this->Session->setFlash('Location successfully created');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Location->rollbackTransaction();
                $this->Session->setFlash('Location could not be created');
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->set('locationTypes', $this->Location->LocationType->find('list', array(
                        'fields' => array(
                            'LocationType.LocationTypeID', 'LocationType.LocationTypeName'
                        ), 'conditions' => array(
                            'OR' => array(
                                'LocationType.LocationTypeCode' => array('REL', 'HOLD', 'INSP', 'WIP')
                            )
                        )
                    )));
        }
    }

    public function edit($id = null) {
        if ($this->Session->read('User.canAdmin')) {
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
            $this->request->data['Location']['LocationTypeID'] = $this->request->data['Location']['LocationType'];
            $this->request->data['Location']['UpdatedBy'] = $userCode;
            $this->Location->beginTransaction();
            if ($this->Location->save($this->request->data)) {
                $this->Location->commitTransaction();
                $this->Session->setFlash('Location successfully updated');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Location->rollbackTransaction();
                $this->Session->setFlash('Location could not be updated');
                $this->redirect(array('action' => 'index'));
            }
        } else {
            if ($id == null) {
                $this->Session->setFlash('No ID specified');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->set('locationTypes', $this->Location->LocationType->find('list', array(
                            'fields' => array(
                                'LocationType.LocationTypeID', 'LocationType.LocationTypeName'
                            ), 'conditions' => array(
                                'OR' => array(
                                    'LocationType.LocationTypeCode' => array('REL', 'HOLD', 'INSP', 'WIP')
                                )
                            )
                        )));
                $this->set('location', $this->Location->find('first', array(
                            'conditions' => array(
                                'Location.LocationID' => $id
                            )
                        )));
            }
        }
    }

}

?>
