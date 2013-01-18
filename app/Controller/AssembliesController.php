<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssembliesController
 *
 * @author etbmx
 */
App::uses('Sanitize', 'Utility');

class AssembliesController extends AppController {

    public $modelName = 'Item';
    public $uses = array('Assembly', 'Part', 'Uom');

    public function index() {
        
    }

    public function generateBom() {
        // implement in the future
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
        if ($this->request->is('get')) {
            $this->set('parts', $this->Part->find('all', array(
                        'conditions' => array('Part.Deleted' => 0), 'recursive' => 0,
                        'order' => array('Part.ModelCode asc'),
                        'fields' => array('Part.ModelID', 'Part.ModelCode', 'Part.ModelName')
                    )));
        } else {
            $data = Sanitize::clean($this->request->data);
            //$data = $this->request->data;
            $this->Assembly->beginTransaction();
            //delete existing

            $i = 0;
            $topId = $data['Assembly']['TopID'];
            $topRev = $data['Assembly']['TopRevision'];
            $existingComponents = $this->Assembly->find('all', array(
                'conditions' => array(
                    'Assembly.TopID' => $topId,
                    'Assembly.TopRevision' => strtoupper($topRev)
                ), 'fields' => array('id'), 'recursive' => 0
                    ));
            foreach ($existingComponents as $existing) {
                if ($this->Assembly->softDelete($existing['Assembly']['id'])) {
                    continue;
                } else {
                    $this->Assembly->rollbackTransaction();
                    $this->Session->setFlash('Could not complete transaction');
                    $this->redirect(array('action' => 'add'));
                }
            }
            $success = true;
            foreach ($data['Assembly']['ComponentID'] as $componentId) {
                $tempData = array();
                $tempData['Assembly']['TopID'] = $topId;
                $tempData['Assembly']['TopRevision'] = strtoupper($topRev);
                $tempData['Assembly']['ComponentID'] = $componentId;
                $tempData['Assembly']['Deleted'] = 0;
                $tempData['Assembly']['ComponentRevision'] = strtoupper($data['Assembly']['ComponentRevision'][$i]);
                $tempData['Assembly']['QtyPerAssembly'] = $data['Assembly']['QtyPerAssembly'][$i];
                $this->log($tempData['Assembly']['ComponentID']);
                $this->Assembly->create();
                if ($this->Assembly->save($tempData)) {
                    // cool
                } else {
                    $success = false;
                    break;
                }
                $i++;
            }
            if ($success) {
                $this->Assembly->commitTransaction();
                $this->Session->setFlash('Assembly added!');
                $this->redirect(array('action' => 'index', 'controller' => 'items'));
            } else {
                $this->Assembly->rollbackTransaction();
                $this->Session->setFlash('Assembly failed!');
                $this->redirect(array('action' => 'add'));
            }
        }
    }

    public function view($id = null, $rev = null) {
        if ($id != null && $rev != null) {
            $assemblies = $this->Assembly->find('all', array(
                'conditions' => array(
                    'Assembly.Deleted' => 0,
                    'Assembly.TopID' => $id,
                    'Assembly.TopRevision' => strtoupper($rev)
                ), 'fields' => array('QtyPerAssembly', 'TopID', 'TopRevision', 'ComponentRevision'),
                'contain' => array(
                    'Component' => array(
                        'fields' => array('Component.ModelCode', 'Component.ModelName', 'Component.ModelID', 'Component.UOMIDInventory')
                    ),
                    'TopAssembly' => array(
                        'fields' => array('TopAssembly.ModelCode', 'TopAssembly.ModelName', 'TopAssembly.ModelID', 'TopAssembly.UOMIDInventory')
                    )
                )
                    ));
            foreach ($assemblies as &$assembly) {
                $uom = $this->Uom->find('first', array(
                    'conditions' => array(
                        'Uom.UOMID' => $assembly['Component']['UOMIDInventory']
                    ), 'fields' => array('Uom.UOMCode')
                        ));
                $uom = $uom['Uom'];
                $assembly['Component']['Uom'] = $uom;
                $uom = $this->Uom->find('first', array(
                    'conditions' => array(
                        'Uom.UOMID' => $assembly['TopAssembly']['UOMIDInventory']
                    ), 'fields' => array('Uom.UOMCode')
                        ));
                $uom = $uom['Uom'];
                $assembly['TopAssembly']['Uom'] = $uom;
            }
            $this->set('assemblies', $assemblies);
        } else {
            $this->Session->setFlash('No ID or Revision specified');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function edit($id = null, $rev = null) {
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
        	 
        	$data = Sanitize::clean($this->request->data);
        	$this->log($data);
        	$this->Assembly->beginTransaction();
        	//delete existing
        
        	$i = 0;
        	$topId = $data['Assembly']['TopID'];
        	$topRev = $data['Assembly']['TopRevision'];
        	$existingComponents = $this->Assembly->find('all', array(
        			'conditions' => array(
        					'Assembly.TopID' => $topId,
        					'Assembly.TopRevision' => strtoupper($topRev)
        			), 'fields' => array('id'), 'recursive' => 0
        	));
        	foreach ($existingComponents as $existing) {
        		if ($this->Assembly->softDelete($existing['Assembly']['id'])) {
        			continue;
        		} else {
        			$this->Assembly->rollbackTransaction();
        			$this->Session->setFlash('Could not complete transaction');
        			$this->redirect(array('action' => 'add'));
        		}
        	}
        	$success = true;
        	foreach ($data['Assembly']['ComponentID'] as $componentId) {
        		$tempData = array();
        		$tempData['Assembly']['TopID'] = $topId;
        		$tempData['Assembly']['TopRevision'] = strtoupper($topRev);
        		$tempData['Assembly']['ComponentID'] = $componentId;
        		$tempData['Assembly']['Deleted'] = 0;
        		$tempData['Assembly']['ComponentRevision'] = strtoupper($data['Assembly']['ComponentRevision'][$i]);
        		$tempData['Assembly']['QtyPerAssembly'] = $data['Assembly']['QtyPerAssembly'][$i];
        		$this->log($tempData['Assembly']['ComponentID']);
        		$this->Assembly->create();
        		if ($this->Assembly->save($tempData)) {
        			// cool
        		} else {
        			$success = false;
        			break;
        		}
        		$i++;
        	}
        	if ($success) {
        		$this->Assembly->commitTransaction();
        		$this->Session->setFlash('Assembly updated!');
        		$this->redirect(array('action' => 'index', 'controller' => 'items'));
        	} else {
        		$this->Assembly->rollbackTransaction();
        		$this->Session->setFlash('Assembly failed!');
        		$this->redirect(array('action' => 'add'));
        	}
        } else {
        	if ($id != null && $rev != null) {
            $assemblies = $this->Assembly->find('all', array(
                'conditions' => array(
                    'Assembly.Deleted' => 0,
                    'Assembly.TopID' => $id,
                    'Assembly.TopRevision' => strtoupper($rev)
                ), 'fields' => array('QtyPerAssembly', 'TopID', 'TopRevision', 'ComponentRevision'),
                'contain' => array(
                    'Component' => array(
                        'fields' => array('Component.ModelCode', 'Component.ModelName', 'Component.ModelID', 'Component.UOMIDInventory')
                    ),
                    'TopAssembly' => array(
                        'fields' => array('TopAssembly.ModelCode', 'TopAssembly.ModelName', 'TopAssembly.ModelID', 'TopAssembly.UOMIDInventory')
                    )
                )
                    ));
            foreach ($assemblies as &$assembly) {
                $uom = $this->Uom->find('first', array(
                    'conditions' => array(
                        'Uom.UOMID' => $assembly['Component']['UOMIDInventory']
                    ), 'fields' => array('Uom.UOMCode')
                        ));
                $uom = $uom['Uom'];
                $assembly['Component']['Uom'] = $uom;
                $uom = $this->Uom->find('first', array(
                    'conditions' => array(
                        'Uom.UOMID' => $assembly['TopAssembly']['UOMIDInventory']
                    ), 'fields' => array('Uom.UOMCode')
                        ));
                $uom = $uom['Uom'];
                $assembly['TopAssembly']['Uom'] = $uom;
            }
            $this->set('assemblies', $assemblies);
        } else {
            $this->Session->setFlash('No ID or Rev specified');
            $this->redirect(array('action'=>'index'));
        }
        }
    }

    public function fetchPageOld() {
        if ($this->request->is('post')) {
            $data = Sanitize::clean($this->request->data);
            $start = ($data['page'] - 1) * $data['resultCount'];
            $parts = $this->Part->find('all', array(
                'conditions' => array(
                    'Part.Deleted' => 0
                ), 'fields' => array(
                    'Part.ModelID', 'Part.ModelCode', 'Part.ModelName', 'Part.MinOnHand', 'Part.MaxOrderTo'
                ), 'contain' => array('Uom' => array(
                        'fields' => array(
                            'Uom.UOMID', 'Uom.UOMCode'
                        )
                ))
                    ));
            foreach ($parts as $key => &$part) {
                $assemblies = $this->Assembly->find('all', array(
                    'conditions' => array(
                        'Assembly.Deleted' => 0,
                        'Assembly.TopID' => $part['Part']['ModelID']
                    ), 'fields' => array(
                        'Assembly.QtyPerAssembly', 'Assembly.TopRevision', 'Assembly.ComponentRevision'
                    ), 'contain' => array(
                        'Component' => array(
                            'fields' => array(
                                'Component.ModelID', 'Component.ModelCode', 'Component.ModelName', 'Component.MinOnHand', 'Component.MaxOrderTo',
                                'Component.UOMIDInventory'
                            )
                        )
                    ), 'order' => array(
                        'Assembly.TopID asc', 'Assembly.TopRevision asc'
                    )
                        ));
                if (count($assemblies) == 0) {
                    unset($parts[$key]);
                } else {
                    $assemblyCount = 0;

                    $currentRev;
                    $i = 0;
                    foreach ($assemblies as &$assembly) {
                        $currentRev = $assembly['Assembly']['TopRevision'];
                        if (!isset($assembly['Uom'])) {
                            $uom = $this->Uom->find('first', array(
                                'conditions' => array(
                                    'Uom.UOMID' => $assembly['Component']['UOMIDInventory']
                                ), 'fields' => array(
                                    'Uom.UOMCode'
                                )
                                    ));
                            $uom = $uom['Uom'];
                            $assembly['Uom'] = $uom;
                        }
                        $part['Assembly'][$currentRev][] = $assembly;
                    }
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
            $this->set('parts', $parts);
            $this->render('fetchPage', 'ajax');
        } else {
            // do nothing
        }
    }
    
    public function fetchPage() {
        if ($this->request->is('post')) {
            $data = Sanitize::clean($this->request->data);
            $start = ($data['page'] - 1) * $data['resultCount'];
            $query = 'SELECT Uom.UOMID, Uom.UOMCode, Part.ModelID, Part.ModelCode, Part.ModelName' .
                    ' from Models as Part INNER JOIN UOMs as Uom on Uom.UOMID = Part.UOMIDInventory INNER JOIN ' .
                    '(SELECT DISTINCT Assemblies.TopID from Assemblies where Assemblies.Deleted = 0) as Assembly on Part.ModelID = Assembly.TopID where Part.Deleted = 0 ORDER BY Part.ModelCode';
            $pdo = new PDO('sqlsrv:Server=localhost\SQLEXPRESS;Database=' . __DBNAME__, 'mckenzie.jw', 'kollani');
            foreach($pdo->query($query) as $row){
                $parts[]['Part'] = $row;
            }
            foreach($parts as $key => &$part){
                $query = 'SELECT Assembly.QtyPerAssembly, Assembly.TopRevision, Assembly.ComponentRevision, Component.ModelID, Component.ModelCode, ' .
                        'Component.ModelName, Component.UOMIDInventory, Uom.UOMCode from Assemblies as Assembly LEFT JOIN Models as Component ON ' .
                        'Component.ModelID = Assembly.ComponentID INNER JOIN Uoms as Uom on Uom.UOMID = Component.UOMIDInventory WHERE ' .
                        'Assembly.Deleted = 0 and Assembly.TopID = ' . Sanitize::clean($part['Part']['ModelID']) . ' ORDER BY Assembly.TopRevision asc, '.
                        ' Component.ModelCode asc';
                $results = $pdo->query($query);
                $components = $results->fetchAll();
                $currentRev;
                $output = array();
                foreach($components as &$component){
                    $currentRev = $component['TopRevision'];
                    
                    $query = 'SELECT Assembly.TopID from Assemblies as Assembly WHERE Assembly.Deleted = 0 and Assembly.TopID = ' . $component['ModelID'] .
                            ' AND Assembly.TopRevision = \'' . $component['ComponentRevision'] . "'";
                    $results = $pdo->query($query);
                    if(count($results->fetchAll()) > 0){
                        $component['IsAssembly'] = true;
                    }else{
                        $component['IsAssembly'] = false;
                    }
                    $part['Assembly'][$currentRev][] = $component;
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
            $this->set('parts', $parts);
            $this->render('fetchPage', 'ajax');
        } else {
            // do nothing
        }
    }

    public function ajaxGetComponents($id = null, $topRev = null) {
        if ($id != null && $topRev != null) {
            $topRev = strtoupper($topRev);
            $relatedComponents = $this->Assembly->find('all', array(
                'conditions' => array('Assembly.TopID' => $id, 'Assembly.Deleted' => 0, 'Assembly.TopRevision' => $topRev),
                'contain' => array(
                    'Component' => array('fields' => array('Component.ModelCode', 'Component.ModelName', 'Component.UOMIDInventory'))
                ), 'fields' => array(
                    'Assembly.id', 'Assembly.QtyPerAssembly', 'Assembly.TopRevision', 'Assembly.ComponentRevision'
                    )));

            $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
            if (count($relatedComponents > 0)) {
                foreach ($relatedComponents as $component) {

                    $uom = $this->Uom->find('first', array(
                        'conditions' => array('Uom.UOMID' => $component['Component']['UOMIDInventory'])
                            ));
                    $component['Component']['Uom'] = $uom['Uom'];
                    $xmlComp = $xml->addChild('component');
                    $xmlComp->addChild('id', $component['Component']['ModelID']);
                    $xmlComp->addChild('assemblyId', $component['Assembly']['id']);
                    $xmlComp->addChild('name', $component['Component']['ModelName']);
                    $xmlComp->addChild('partNumber', $component['Component']['ModelCode']);
                    $xmlComp->addChild('revision', $component['Assembly']['ComponentRevision']);
                    $xmlComp->addChild('quantity', $component['Assembly']['QtyPerAssembly']);
                    $xmlComp->addChild('uom', $component['Component']['Uom']['UOMCode']);
                }
            }
        } else {
            $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><root></root>");
        }
        $this->set('xml', $xml);
        $this->render('ajaxGetComponents', 'ajax');
    }

    public function getComponentTable($id = null, $rev = null, $offset = 0) {
        if ($this->request->is('get')) {
            if ($id != null && $rev != null) {
                $rev = strtoupper($rev);
                $assemblies = $this->Assembly->find('all', array(
                    'conditions' => array(
                        'Assembly.Deleted' => 0,
                        'Assembly.TopID' => $id,
                        'Assembly.TopRevision' => $rev
                    ), 'fields' => array(
                        'Assembly.QtyPerAssembly', 'Assembly.TopRevision', 'Assembly.ComponentRevision', 'Assembly.TopID'
                    ), 'contain' => array(
                        'Component' => array(
                            'fields' => array(
                                'Component.ModelID', 'Component.ModelCode', 'Component.ModelName', 'Component.MinOnHand', 'Component.MaxOrderTo',
                                'Component.UOMIDInventory'
                            )
                        )
                    )
                        ));
                if (count($assemblies) != 0) {
                    foreach ($assemblies as &$assembly) {
                        $uom = $this->Uom->find('first', array(
                            'conditions' => array(
                                'Uom.UOMID' => $assembly['Component']['UOMIDInventory']
                            ), 'fields' => array(
                                'Uom.UOMCode'
                            )
                                ));
                        $uom = $uom['Uom'];
                        $assembly['Uom'] = $uom;
                        $count = $this->Assembly->find('count', array(
                            'conditions'=>array(
                                'Assembly.Deleted' => 0,
                                'Assembly.TopID' => $assembly['Component']['ModelID'],
                                'Assembly.TopRevision'=>$assembly['Assembly']['ComponentRevision']
                            )
                        ));
                        if($count > 0){
                            $assembly['Component']['IsAssembly'] = true;
                        }else{
                            $assembly['Component']['IsAssembly'] = false;
                        }
                    }
                    $this->set('offset', $offset);
                    $this->set('assemblies', $assemblies);
                }
                $this->render('getComponentTable', 'ajax');
            }
        } else {
            // do nothing
        }
    }

    public function getTops($id = null) {
        if ($id != null) {
            $assemblies = $this->Assembly->find('all', array(
                'conditions' => array(
                    'Assembly.Deleted' => 0,
                    'Assembly.TopID' => $id
                ), 'fields' => array(
                    'Assembly.TopID', 'Assembly.TopRevision', 'Assembly.ComponentRevision'
                ), 'contain' => array(
                    'TopAssembly' => array(
                        'fields' => array(
                            'TopAssembly.ModelCode', 'TopAssembly.ModelID', 'TopAssembly.ModelName'
                        )
                    )
                )
                    ));
            $filteredAssemblies = array();
            $revs = array();
            foreach ($assemblies as &$assembly) {
                if (in_array($assembly['Assembly']['TopRevision'], $revs)) {
                    // do nothing
                } else {
                    $revs[] = $assembly['Assembly']['TopRevision'];
                    $assembly['Component'] = $this->Assembly->find('all', array(
                        'conditions' => array(
                            'Assembly.Deleted' => 0,
                            'Assembly.TopID' => $assembly['Assembly']['TopID'],
                            'Assembly.TopRevision' => $assembly['Assembly']['TopRevision']
                        ), 'fields' => array(
                            'Assembly.ComponentRevision'
                        ), 'contain' => array(
                            'Component' => array(
                                'fields' => array(
                                    'Component.ModelCode', 'Component.ModelName', 'Component.ModelID'
                                )
                            )
                        )
                            ));
                    $filteredAssemblies[] = $assembly;
                }
            }
            $this->set('assemblies', $filteredAssemblies);
            $this->render('getTops', 'ajax');
        }
    }

    public function getComponents($id = null) {
        if ($id != null) {
            $assemblies = $this->Assembly->find('all', array(
                'conditions' => array(
                    'Assembly.Deleted' => 0,
                    'Assembly.ComponentID' => $id
                ), 'fields' => array(
                    'Assembly.TopRevision', 'Assembly.TopID', 'Assembly.ComponentRevision'
                ), 'contain' => array(
                    'TopAssembly' => array(
                        'fields' => array(
                            'TopAssembly.ModelCode', 'TopAssembly.ModelName', 'TopAssembly.ModelID'
                        )
                    )
                )
                    ));
            foreach ($assemblies as &$assembly) {
                $this->addComponentsOf($assembly);
            }
        }
        $this->set('assemblies', $assemblies);
        $this->render('getComponents', 'ajax');
    }

    public function addComponentsOf(&$inputArray) {
        $componentFor = $this->Assembly->find('all', array(
            'conditions' => array(
                'Assembly.Deleted' => 0,
                'Assembly.ComponentID' => $inputArray['Assembly']['TopID'],
                'Assembly.ComponentRevision' => $inputArray['Assembly']['TopRevision']
            ), 'fields' => array(
                'Assembly.TopRevision', 'Assembly.TopID', 'Assembly.ComponentRevision'
            ), 'contain' => array(
                'TopAssembly' => array(
                    'fields' => array(
                        'TopAssembly.ModelCode', 'TopAssembly.ModelName'
                    )
                )
            )
                ));
        if (count($componentFor) > 0) {
            foreach($componentFor as &$top){
                $this->addComponentsOf($top);
            }
            $inputArray['TopAssembly']['TopAssembly'] = $componentFor;
        } else {
           //$inputArray['TopAssembly']['TopAssembly'] = $componentFor;
        }
        
    }

}

?>
