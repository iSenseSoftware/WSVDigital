<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');
App::uses('Sanitize', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $modelName;

    public function beforeFilter() {
        parent::beforeFilter();
        switch ($this->action) {
            case 'issue':
                if (strpos(env('HTTP_USER_AGENT'), 'MSIE')) {
                    $this->Session->setFlash('That action cannot be performed in Internet Explorer');
                    $this->redirect(array('action' => 'index', 'controller' => 'pages'));
                    $this->render();
                }
                break;
            case 'move':
                if (strpos(env('HTTP_USER_AGENT'), 'MSIE')) {
                    $this->Session->setFlash('That action cannot be performed in Internet Explorer');
                    $this->redirect(array('action' => 'index', 'controller' => 'pages'));
                    $this->render();
                }
                break;
            case 'adjust':
                if (strpos(env('HTTP_USER_AGENT'), 'MSIE')) {
                    $this->Session->setFlash('That action cannot be performed in Internet Explorer');
                    $this->redirect(array('action' => 'index', 'controller' => 'pages'));
                    $this->render();
                }
                break;
            case 'receive':
                if (strpos(env('HTTP_USER_AGENT'), 'MSIE')) {
                    $this->Session->setFlash('That action cannot be performed in Internet Explorer');
                    $this->redirect(array('action' => 'index', 'controller' => 'pages'));
                    $this->render();
                }
                break;
            case 'add':
                if (strpos(env('HTTP_USER_AGENT'), 'MSIE')) {
                    $this->Session->setFlash('That action cannot be performed in Internet Explorer');
                    $this->redirect(array('action' => 'index', 'controller' => 'pages'));
                    $this->render();
                }
                break;
            case 'edit':
                if (strpos(env('HTTP_USER_AGENT'), 'MSIE')) {
                    $this->Session->setFlash('That action cannot be performed in Internet Explorer');
                    $this->redirect(array('action' => 'index', 'controller' => 'pages'));
                    $this->render();
                }
                break;
            default:
                break;
        }
    }

    public function delete($id = null) {
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
            $this->Session->setFlash('No Id provided');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->{$this->modelName}->beginTransaction();
            if ($this->{$this->modelName}->softDelete($id)) {
                $this->{$this->modelName}->commitTransaction();
                $this->Session->setFlash('Delete successful');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->{$this->modelName}->rollbackTransaction();
                $this->Session->setFlash('Unable to complete deletion');
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function index() {
        $this->set('index', $this->{$this->modelName}->find('all'));
    }

    public function view($id = null) {
        if ($id != null) {
            $this->set(lcfirst($this->modelName), $this->{$this->modelName}->find('first', array(
                        'conditions' => array(
                            "{$this->modelName}.{$this->primaryKey}" => $id
                        ), 'recursive' => 2
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
        if ($this->request->is('post')) {
            $this->request->data[$this->modelName]['UpdatedBy'] = $userCode;
            $this->{$this->modelName}->beginTransaction();
            if ($this->{$this->modelName}->save($this->request->data)) {
                $this->{$this->modelName}->commitTransaction();
                $this->Session->setFlash('Update Successful');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->{$this->modelName}->rollbackTransaction();
                $this->Session->setFlash('Unable to save');
                $this->redirect(array('action' => 'add'));
            }
        } else {
            if ($id == null) {
                $this->Session->setFlash('ID not specified');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->set(lcfirst($this->modelName), $this->{$this->modelName}->find('first', array(
                            'conditions' => array(
                                "{$this->modelName}.{$this->primaryKey}" => $id
                            )
                        )));
            }
        }
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
            // attempt to save the new record
            $this->request->data[$this->modelName]['UpdatedBy'] = $userCode;
            $this->{$this->modelName}->beginTransaction();
            if ($this->{$this->modelName}->save($this->request->data)) {
                $this->{$this->modelName}->commitTransaction();
                $this->Session->setFlash('Add Successful');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->{$this->modelName}->rollbackTransaction();
                $this->Session->setFlash('Unable to save');
                $this->redirect(array('action' => 'add'));
            }
        } else {
            // Do nothing.  The Add view renders implicitly
        }
    }

    public function fetchPage() {
        if ($this->request->is('post')) {
            // do the good stuff
            $data = Sanitize::clean($this->request->data);
            $start = ($data['page'] - 1) * $data['resultCount'];
            $conditions = array(
                "{$this->modelName}.Deleted" => 0
            );
            if ($data['fullTextQuery'] == null) {
                $sort = array();
                foreach ($data['sortFields'] as $sortField => $direction) {
                    $sort[] = "$sortField $direction";
                }
                $displayFields = $data['displayFields'];
                if (isset($data['hiddenFields'])) {
                    $queryFields = array_merge($data['hiddenFields'], $displayFields);
                } else {
                    $queryFields = $displayFields;
                }
                $findFields = array();
                foreach ($queryFields as $alias => $field) {
                    $findFields[] = $field;
                }
                if (isset($data['fieldFilters'])) {
                    foreach ($data['fieldFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                    }
                }
                $totalRecords = $this->{$this->modelName}->find('count', array('conditions' => $conditions));
                $items = $this->{$this->modelName}->find('all', array(
                    'conditions'=>$conditions,
                    'order'=>$sort,
                    'page'=>$data['page'],
                    'limit'=>$data['resultCount']
                ));
            } else {

                $sort = array();
                foreach ($data['sortFields'] as $sortField => $direction) {
                    $sort[] = "$sortField $direction";
                }
                $displayFields = $data['displayFields'];
                if (isset($data['hiddenFields'])) {
                    $queryFields = array_merge($data['hiddenFields'], $displayFields);
                } else {
                    $queryFields = $displayFields;
                }
                $findFields = array();
                foreach ($queryFields as $alias => $field) {
                    $findFields[] = $field;
                }
                if (isset($data['fieldFilters'])) {
                    foreach ($data['fieldFilters'] as $field => $filter) {
                        $conditions["$field LIKE"] = "%$filter%";
                    }
                }
                //$totalRecords = $this->{$this->modelName}->find('count', array('conditions' => $conditions));
                $items = $this->{$this->modelName}->find('all', array(
                    'conditions'=>$conditions,
                    'order'=>$sort
                ));
                $totalRecords = count($items);
                //$items = $this->{$this->modelName}->query($query);
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

    public function fetchPageOld($page = 1, $resultCount = 10, $sort = null, $direction = 'asc', $queryString = null, $id = null) {
        $start = ($page - 1) * $resultCount;
        $sort = ($sort == null) ? "{$this->modelName}.id" : $sort;
        $totalRecords = $this->{$this->modelName}->find('count', array(
            'conditions' => array(
                "{$this->modelName}.deleted" => 0
            )
                ));
        //$totalRecords = count($results);
        $conditions = array("{$this->modelName}.deleted" => 0);
        if ($id != 'null') {
            // if a non-default filter condition is given, render a summary
            // snippet for matching records
            $conditions = array_merge(array("{$this->modelName}.id" => $id), $conditions);
            if ($queryString == 'null' || $queryString == 'undefined') {
                $results = $this->{$this->modelName}->find('first', array(
                    'conditions' => $conditions, 'limit' => $resultCount,
                    'offset' => $start,
                    'order' => $sort . ' ' . $direction
                        ));
                $totalRecords = count($results);
            } else {
                $results = $this->{$this->modelName}->find('first', array(
                    'conditions' => $conditions, 'order' => $sort . ' ' . $direction
                        ));
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
            $this->render('fetchSummary', 'ajax');
        } else {
            if ($queryString == 'null' || $queryString == 'undefined') {
                $results = $this->{$this->modelName}->find('all', array(
                    'conditions' => $conditions, 'limit' => $resultCount,
                    'offset' => $start,
                    'order' => $sort . ' ' . $direction
                        ));
            } else {
                $results = $this->{$this->modelName}->find('all', array(
                    'conditions' => $conditions, 'order' => $sort . ' ' . $direction
                        ));
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
            $this->render('fetchPage', 'ajax');
        }
    }

    /*
     * Searches a multi-dimensional array for matches to a given string
     * 
     * Checks each row of a data array for a match using fetchFilteredRecursiveSearch 
     * 
     * @author Joshua McKenzie <joshua.mckenzie@bayer.com>
     * @param array $array The haystack
     * @param string $queryString The needle
     * 
     * @return array
     * 
     */

    public function fetchFilteredData($array, $queryString) {
        $outArray = array();
        foreach ($array as $key => $val) {
            if ($this->fetchFilteredRecursive($val, $queryString)) {
                $outArray[] = $val;
            }
        }
        return $outArray;
    }

    /*
     * Performs the heavy lifting for fetchFilteredData.  Checks for the query string
     * within the given array and returns a boolean result
     * 
     * Function supports recursion at any level but will cease recursion once a match has been
     * found because any match in a lower-level array saves the parent from
     * being filtered out.  Only elements of the top-level array are removed.  Currently 
     * uses a simple strpos()
     * 
     * @author Joshua McKenzie <joshua.mckenzie@bayer.com>
     * @param array $array The haystack
     * @param string $queryString The needle
     * @todo Support regular expression searches
     * @todo Support parsing with punctuation like a proper search engine
     * 
     * @return boolean
     * 
     */

    public function fetchFilteredRecursive($array, $queryString) {
        foreach ($array as $val) {
            if (is_array($val)) {
                if ($this->fetchFilteredRecursive($val, $queryString)) {
                    return true;
                } else {
                    continue;
                }
            } else {
                if (strpos(strtolower($val), strtolower($queryString)) !== false) {
                    return true;
                } else {
                    continue;
                }
            }
        }
        return false;
    }

    /*
     * Paginates an array and returns the requested page
     * 
     * @author Joshua McKenzie <joshua.mckenzie@bayer.com>
     * @param array $array The array to be paginated
     * @param integer $page Page to be returned
     * @param integer $resultCount Results per page
     * @return array  
     *
     * 
     */

    public function trimArray($array, $page, $resultCount) {
        $outArray = array_chunk($array, $resultCount);
        if (!isset($outArray[$page - 1])) {
            return array();
        } else {
            $outArray = $outArray[$page - 1];
            return $outArray;
        }
    }

    public function getTimeStamp() {
        return date("Y-m-d\TH:i:s") . substr((string) microtime(), 1, 8) . date("P");
    }

}
