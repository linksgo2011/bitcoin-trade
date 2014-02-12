<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property nComponent $n
 */
class UsersController extends AppController {

	public $prefix_layout = true;
    public $allow = array('register','login','register','forget_password');
    public $uses = array('User');
   	public $layout = 'user';

    public function register($action='/Users/home') {
        $this->layout = '';
        $this->site_title = '快速注册 ';

        if ( $this->UserAuth->isLogged() ) {
            $uri = $this->Session->read( UserAuthComponent::originAfterLogin );
            $this->Session->delete( UserAuthComponent::originAfterLogin );
            if ( $uri )
                $this->redirect( $uri );
            else
                $this->redirect($action);
        }

        if ( $this->request->isPost() ) {
            $postData = $this->data;
            $postData['User']['role'] = 'user';
            $postData['User']['created'] = time();
            $postData['User']['last_login'] = time();

            if ( @$postData['User']['password'] !== @$postData['User']['cpassword'] ) {
                $this->User->validationErrors = array(
                    'cpassword' => array( '两次输入密码不一致' )
                );
                return;
            }
            $this->User->create( $postData );
            $user = $this->User->save( $postData );
            if ($user) {
                $this->succ("注册成功!");

                //创建账户
                $this->loadModel('Account');
                $this->Account->create();
                $this->Account->save( array( 'user_id' => $user['User']['id'] ) );

                //分配比特币地址
                $this->loadModel('PurseAddress');
                $this->PurseAddress->setUser($user['User']['id'],'btc');
                $this->PurseAddress->setUser($user['User']['id'],'ltc');

                $this->UserAuth->flashUser( $user['User']['id'] );
                if ( $uri )
                    $this->redirect( $uri );
                else
                    $this->redirect( $action );
            }
        }
    }

    public  function login( $action = '/Users/home' ) {
        $this->layout = '';
        if ( $this->request->isPost() ) {

            $this->request->data['User']['email'] = trim( $this->request->data['User']['email'] );
            $this->request->data['User']['password'] = trim( $this->request->data['User']['password'] );

            $postData = $this->data;

            $email = $postData['User']['email'];
            $password = $postData['User']['password'];
            $ga_password = $postData['User']['ga_password'];

            $this->User->recursive = -1;
            $this->User->cache=false;
            $user = $this->User->findByEmail( $email );
            if (!$user) {
                $user = $this->User->findByEmail( $email );
                if ( empty( $user ) ) {
                    $this->User->validationErrors = array(
                        'email' => array( "用户不存在" )
                    );
                    $this->error( '用户不存在' );
                    return;
                }
            }
            if ($user['User']['lock_time'] >= (time()-30*60)){
                $this->error( '密码输入次数错误过多,当前IP被锁定30分钟,无法登陆此用户!' );
                return ;
            }
            if($user['User']['active'] == -2){
                $this->error( '账户被锁定,无法登陆!' );
                return ;
            }
            if ( $user['User']['password'] !== $password){
                $this->User->validationErrors = array(
                    'password' => array( "密码错误" )
                );
                $this->error( '密码错误' );
                $this->ip($user['User']['id']);
                return;
            }
            if ($ga_password && $user['User']['used_ga'] == $ga_password) {
                $this->error( 'GA 密码已经使用' );
                return ;
            }
            if ($user['User']['use_ga'] && $user['User']['ga_code']) {
                App::uses('Google2FA', 'vendors');
                $ga = new Google2FA();
                $ga_result = Google2FA::verify_key($user['User']['ga_code'],$ga_password);
                if(!$ga_result){
                    $this->User->validationErrors = array(
                        'ga_password' => array( "google authenticator密码错误" )
                    );
                    $this->error( 'google authenticator密码错误' );
                    return;  
                }
            }

            $this->UserAuth->login( $user );
            $this->User->id = $user['User']['id'];
            $this->User->saveField('used_ga',$ga_password);

            $uri = $this->Session->read(UserAuthComponent::originAfterLogin );
            if ( !$uri) {
                $uri = $action;
            }
            CakeSession::delete( 'Message.flash' );
            $this->Session->delete( UserAuthComponent::originAfterLogin );
            $this->redirect( $uri );
        }
    }

    public function ip($user_id)
    {
        $this->loadModel("Ip");
        $ip = $this->request->clientIp();
        $this->Ip->save(array(
            'ip'=>$ip,
            'user_id'=>$user_id,
            'created'=>time()
        ));

        //是否需要锁定
        $time_out = 30; //超时30分钟
        $number = 10;
        $this->loadModel("Ip");
        $ip = $this->request->clientIp();

        $count = $this->Ip->find('count',array(
            'conditions'=>array(
                'ip'=>$ip,
                'user_id'=>$user_id,
                'created >=' => time()-$number*30
            )
        ));
        if ($count >= $number) {
            $this->User->id = $user_id;
            $this->User->saveField('lock_time',time());
        }
    }

    public function logout() {
        $this->layout = 'user';
        $this->UserAuth->logout();

        $this->Session->delete( UserAuthComponent::originAfterLogin );

        $this->redirect( array( 'action' => 'login' ) );
    }

    /**
     * 根据价格的K线
     * 我的挂单
     * 网站买单卖记录
     * 成交记录
     */
    public function home($order_type = 'ARS_BTC')
    {
        $this->loadModel("Order");
        $this->loadModel("Trade");
        $this->loadModel("Charge");
    	$this->loadModel("Account");

        $this->layout = 'user';

        $user_id = $this->UserAuth->getUserId();
        $account = $this->Account->findByUserId($user_id);

        //全站买卖单
        $buy_orders = $this->Order->getLastCombine($order_type,'buy',50,null,'Order.price desc');
        $sell_orders = $this->Order->getLastCombine($order_type,'sell',50,null,'Order.price asc');

        //我的买单
        $my_orders = $this->Order->getLast($order_type,null,50,$user_id);

        //最近成交
        $last_trades = $this->Trade->getLast($order_type,50);
        $trade_type_arr = $this->Order->trade_type;

        //K线统计
        $statistic = $this->Trade->frontStatic(strtolower($order_type));

        $rate = $this->User->feeRate($user_id,$order_type);
        $stata = $this->Trade->minMax($order_type);

        $this->set(compact(
            'account',
            'buy_orders',
            'sell_orders',
            'my_orders',
            'order_type',
            'last_trades',
            'trade_type_arr',
            'statistic',
            'rate',
            'stata'
        ));

    }

    public function forget_password()
    {
        $this->layout = '';
    }

    /**
     * 修改密码
     */
    public function password()
    {
        $user = $this->UserAuth->getUser();

        if ($this->request->isPost() || $this->request->isPut()) {
            $post_data = $this->request->data['User'];
            if($user['User']['password'] != $post_data['password']){
                $this->User->validationErrors['password'] = "旧的密码错误";
                return ;
            }
            if($post_data['newpassword'] != $post_data['cpassword']){
                $this->User->validationErrors['cpassword'] = "两次输入密码不一致";
                return ;
            }
            if (!preg_match('/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,30}$/',$post_data['newpassword'])) {
                $this->error("操作失败");
                $this->User->validationErrors['newpassword'] = '正确的密码为6-30个常用字符';
                return ;
            }
            $this->User->id = $user['User']['id'];
            if($this->User->saveField('password',$post_data['newpassword'])){
                $this->UserAuth->flashUser();
                $this->succ('修改成功!');
                $this->redirect(array('action'=>'home'));
            }else{
                $this->error("修改失败");
            }
        }

    }

    /**
     * 修改支付密码
     */
    public function pay_password()
    {
        $user = $this->UserAuth->getUser();
        
        if ($this->request->isPost() || $this->request->isPut()) {
            $post_data = $this->request->data['User'];
            if($user['User']['paypassword'] != $post_data['pay_password']){
                $this->User->validationErrors['paypassword'] = "旧的密码错误";
                return ;
            }
            if($post_data['newpaypassword'] != $post_data['cpassword']){
                $this->User->validationErrors['cpassword'] = "两次输入密码不一致";
                return ;
            }
            if (!preg_match('/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,30}$/',$post_data['newpaypassword'])) {
                $this->error("操作失败");
                $this->User->validationErrors['newpaypassword'] = '正确的密码为6-30个常用字符';
                return ;
            }
            $this->User->id = $user['User']['id'];
            if($this->User->saveField('pay_password',$post_data['newpaypassword'])){
                $this->UserAuth->flashUser();
                $this->succ('修改成功!');
                $this->redirect(array('action'=>'home'));
            }else{
                $this->error("修改失败");
            }
        }

    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				return $this->flash(__('The user has been saved.'), array('action' => 'index'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				return $this->flash(__('The user has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}

    /**
     * ga设置
     */
    public function ga()
    {
        App::uses('Google2FA', 'vendors');
        $this->loadModel("User");
        $ga = new Google2FA;
        $user_id = $this->UserAuth->getUserId();

        $user = $this->Session->read('user');

        if(!$user['User']['ga_code']){
            $init_key = $ga->generate_secret_key();
            $this->User->id = $user_id;
            $this->User->saveField("ga_code",$init_key);
            $user['User']['ga_code'] = $init_key;
            $this->Session->write('user',$user);
        }
        if ($this->request->isPost() || $this->request->isPut()) {
            $post_data = $this->request->data;
            $post_data['User']['id'] = $user_id;

            $ga_result = Google2FA::verify_key($user['User']['ga_code'],$post_data['User']['ga_password']);
            if(!$ga_result){
                $this->User->validationErrors = array(
                    'ga_password' => array( "google authenticator密码错误" )
                );
                $this->error( 'google authenticator密码错误' );
                return;  
            }

            if($this->User->save($post_data)){
                $this->UserAuth->flashUser();
                $this->succ("保存成功");
            }else{
                $this->error("保存失败");
            }
            $this->redirect($this->referer());
        }else{
            $this->request->data = $user;
        }
    }   

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			return $this->flash(__('The user has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The user could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
        $this->paginate = array(
            'order'=>'User.id desc'
        );
		$this->set('data', $this->paginate());
	}


/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				return $this->flash(__('The user has been saved.'), array('action' => 'index'));
			}
		}
	}

	public function admin_edit($id = null) {
        $active_arr = $this->User->active_arr;
        $this->set(compact('active_arr'));
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('无效用户!'));
		}
		if ($this->request->is(array('post', 'put'))) {
            $post_data = $this->request->data;
			if ($this->User->save($post_data)) {
			     $this->succ('修改成功!');
			     $this->redirect($this->referer());
            }else{
                $this->error('修改失败');
            }
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			return $this->flash(__('The user has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The user could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}}
