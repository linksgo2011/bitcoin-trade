<?php

class UserAuthComponent extends Component {

    var $components = array('Session');

    /**
     * session key
     *
     * @var string
     */

    const sesskey = 'user';

    /**
     * 登录后转跳地址
     *
     * @var unknown_type
     */
    const originAfterLogin = 'origin_after_login';

    
    /**
     * 退出后的登录页面
     *
     * @var unknown_type
     */
    const pageAfterLogout = 'page_after_logout';

    /**
     * 验证方法名
     *
     * @var unknown_type
     */
    private $isAuthorized = 'isAuthorized';

    function initialize(Controller $controller) {
        static $_initialized;
        if ($_initialized) {
            return;
        }
        if (!in_array(get_class($controller), array('CakeErrorController')) 
            && is_callable(array($controller, $this->isAuthorized))) {
            $user = $this->getUser();
            $controller->{$this->isAuthorized}($user);
        }
        $_initialized = true;
    }

    function isLogged() {
        return ($this->getUser());
    }

    /**
     * 刷新用户数据
     *
     * @param int $user_id
     */
    function flashUser($user_id) {
        $controller = $this->_Collection->getController();
        $controller->loadModel('User');
        $controller->User->recursive = 0;
        $user = $controller->User->read(null, $user_id);
        $this->login($user);
    }

    function getUser() {
        static $u = null;
        if ($u === null) {
            $u = $this->Session->read(self::sesskey);
        }
        return $u;
    }

    function getUserId() {
        $u = $this->getUser();
        return isset($u['User']['id']) ? $u['User']['id'] : 0;
    }
    
    public function getAreaId()
    {
        $u = $this->getUser();
        return $u['User']['area_id'];
    }

    function login($data) {
        $this->Session->write(self::sesskey, $data);
    }

    function logout() {
        $this->Session->delete(self::sesskey);
        $this->Session->destroy();
    }

    /*/**
     * 是否拥有权限
     */
    function hasPurview($feature_alias){
        $controller = $this->_Collection->getController();
        $controller->loadModel('Feature');
         $controller->Feature->cache = false;
        $own_feature_list = $controller->Feature->getOwnList($this->getUserId());
        if(!in_array($feature_alias,$own_feature_list)){
            $controller->error('你没有拥有本次操作权限！');
            $controller->redirect($controller->referer());
        }
        $controller->set('feature_alias',$feature_alias);
    }

    /**
     * 设置用户组
     *
     * @return void
     * @author 
     **/
    public function setGroup($alias)
    {
        if (!$alias) {
            return ;
        }
        $user = $this->getUser();
        if (!isset($user['UserGroup']['alias'])) {
            throw new Exception("查询用户权限信息失败", 1);
        }
        if ($user['UserGroup']['alias'] == 'admin' || $user['UserGroup']['alias'] == $alias) {
            return ;
        }
        $controller = $this->_Collection->getController();
        $controller->loadModel('UserGroup');
        $group = $controller->UserGroup->findByAlias($alias);
        if (!$group) {
            return ;
        }
        $controller->loadModel('User');
        $controller->User->id = $user['User']['id'];
        $rs = $controller->User->saveField('user_group_id', $group['UserGroup']['id']);
        if ($rs) {
            $this->flashUser($user['User']['id']);
        }
        return true;
    }
}