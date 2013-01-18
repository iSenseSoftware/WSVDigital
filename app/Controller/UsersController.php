<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersController
 *
 * @author etbmx
 */
class UsersController extends AppController{
    //put your code here
    
    public function login(){
        //session_start();
        //session_set_cookie_params(60*60*4);
        // Check for the POST array and to see if the userName and password are present
        if($this->request->is('post')){
            if(isset($this->request->data['User']['UserLogOn']) && 
                    isset($this->request->data['User']['UserPassword'])){
                $userName = $this->request->data['User']['UserLogOn'];
                $password = $this->request->data['User']['UserPassword'];
                $user = $this->User->find('first', array(
                    'conditions'=>array(
                        'User.UserLogOn' => $userName,
                        'User.UserPassword' => $password
                    )
                ));
                if(!empty($user)){
                    $this->Session->write('User.UserLogOn', $user['User']['UserLogOn']);
                    $this->Session->write('User.UserID', $user['User']['UserID']);
                    if($this->User->hasSecurityLevel($user['User']['UserID'], 'Standard') || 
                            $this->User->hasSecurityLevel($user['User']['UserID'], 'Standard2') ||
                                $this->User->hasSecurityLevel($user['User']['UserID'], 'Admin')){
                        
                        $this->Session->write('User.canIssue', true);
                    }else{
                        $this->Session->write('User.canIssue', false);
                    }
                    if($this->User->hasSecurityLevel($user['User']['UserID'], 'Admin')){
                        $this->Session->write('User.canAdmin', true);
                    }else{
                        $this->Session->write('User.canAdmin', false);
                    }
                    $this->Session->setFlash('Login Successful');
                    $this->redirect(array('controller'=>'items', 'action'=>'index'));
                }else{
                    $this->Session->setFlash('User not found');
                    $this->redirect(array('action'=>'login'));
                }
                
                
            }else{
                $this->Session->setFlash('This error should never occur.  Consult admin immediately!');
                $this->redirect(array('action'=>'login'));
            }
        }else{
            $this->render();
        }
        
    }
    
    public function mobileLogin(){
        //session_start();
        //session_set_cookie_params(60*60*4);
        // Check for the POST array and to see if the userName and password are present
        if($this->request->is('post')){
            if(isset($this->request->data['User']['UserLogOn']) && 
                    isset($this->request->data['User']['UserPassword'])){
                $userName = $this->request->data['User']['UserLogOn'];
                $password = $this->request->data['User']['UserPassword'];
                $user = $this->User->find('first', array(
                    'conditions'=>array(
                        'User.UserLogOn' => $userName,
                        'User.UserPassword' => $password
                    )
                ));
                if(!empty($user)){
                    $this->Session->write('User.UserLogOn', $user['User']['UserLogOn']);
                    $this->Session->write('User.UserID', $user['User']['UserID']);
                    if($this->User->hasSecurityLevel($user['User']['UserID'], 'Standard') || 
                            $this->User->hasSecurityLevel($user['User']['UserID'], 'Standard2') ||
                                $this->User->hasSecurityLevel($user['User']['UserID'], 'Admin')){
                        
                        $this->Session->write('User.canIssue', true);
                    }else{
                        $this->Session->write('User.canIssue', false);
                    }
                    $this->Session->setFlash('Login Successful');
                    $this->redirect(array('controller'=>'items', 'action'=>'mobileIndex'));
                }else{
                    $this->Session->setFlash('User not found');
                    $this->redirect(array('action'=>'mobileLogin'));
                }
                
                
            }else{
                $this->Session->setFlash('This error should never occur.  Consult admin immediately!');
                $this->redirect(array('action'=>'mobileLogin'));
            }
        }else{
            $this->render('mobileLogin', 'mobile');
        }
        $this->render('mobileLogin', 'mobile');
        
    }
    
    public function logout(){
        CakeSession::delete('User.UserLogOn');
        CakeSession::delete('User.canIssue');
        $this->Session->setFlash('User logged out');
        $this->redirect(array('action'=>'login'));        
    }
    
    public function mobileLogout(){
        CakeSession::delete('User.UserLogOn');
        CakeSession::delete('User.canIssue');
        $this->Session->setFlash('User logged out');
        $this->redirect(array('action'=>'mobileLogin'));        
    }
    
        public function hasSecurityLevel($id = null, $level = null){
        if($id == null || $level == null){
            return false;
        }else{
            $levels = $this->User->find('first', array(
                'conditions'=>array(
                'UserID'=>$id), 'fields'=>array(
                    'User.UserID', 'User.UserLogOn', 'User.UserPassword'
                ), 'contain'=>array(
                    'SecurityLevel'=>array(
                        'fields'=>'SecurityLevelCode'
                    )
                )
            ));
            $this->set('levels', $levels);
//            $hasLevel = false;
//            echo '<pre>';
//            print_r($levels);
//            echo '</pre>';
//            foreach($levels['SecurityLevel'] as $permission){
//                if($permission['SecurityLevelCode'] == $level){
//                    $hasLevel = true;
//                }
//            }
//            return $hasLevel;
        }
    }
    
}

?>
