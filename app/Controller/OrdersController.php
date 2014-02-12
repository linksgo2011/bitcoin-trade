<?php
App::uses('AppController', 'Controller');
/**
 * Orders Controller
 *
 * @property Order $Order
 * @property PaginatorComponent $Paginator
 */
class OrdersController extends AppController {

	public $prefix_layout = true;
    public $uses = array('Order','Account');
   	public $layout = 'user';

   	public $components = array('AutoTrade');

	public function index() {
		$user_id = $this->UserAuth->getUserId();
		$conditions = array(
			'Order.user_id'=>$user_id
		);

		$query = $this->request->query;
		$this->request->data['Order'] = $query;

		// if($query['active'] || $query['active'] !== ''){
		// 	$conditions['Order.active']=$query['active'];
		// }
		if($query['trade_type']){
			$conditions['Order.trade_type']=$query['trade_type'];
		}

		$this->Order->recursive = 0;
		$this->paginate = array(
			'conditions'=>$conditions,
			'order'=>'Order.id desc'
		);
		$data = $this->paginate();

		$active_arr = $this->Order->active_arr;
		$this->set(compact('data','active_arr'));
	}	

	/**
	 * 购买
	 * 1\记录
	 * 2\冻结计算过后的金额
	 */
	public function buy($order_type) {
		$user_id = $this->UserAuth->getUserId();
		$order_type_arr = $this->Order->order_type_arr;

		$this->set(compact('order_type_arr','order_type'));
		if ($this->request->is('post')) {
			$post_data = $this->request->data;
			$post_data['Order']['user_id'] = $user_id;
			$post_data['Order']['trade_type'] = 'buy';	
			$post_data['Order']['active'] = '0';	

			$amount_price = round($post_data['Order']['price'] * $post_data['Order']['number'],6);
			$amount_price = abs($amount_price);
			$post_data['Order']['amount_price'] = $amount_price;

			$db = $this->Order->getDataSource();
			$db->begin();
			try {
				$order_result = $this->Order->save($post_data);
				if(!$order_result){
					throw new Exception("订单创建失败", 1);
				}

				//冻结资金
				$account_type = $this->Order->getAccountType($post_data['Order']['order_type'],'buy');
				list($freeze_result,$msg) = $this->Account->freeze($user_id,$account_type,$amount_price);

				if(!$freeze_result){
					throw new Exception($msg, 2);
				}
				$this->AutoTrade->run($order_result['Order']['id'],'sell');

				$db->commit();
				$this->succ("操作成功");
			} catch (Exception $e) {
				$db->rollback();
				$this->error(addslashes($e->getMessage().' error_code:'.$e->getCode()));
			}
			$this->redirect('/Users/home/'.$order_type);
		}else{
			$this->request->data['Order']['order_type'] = $order_type;
		}
	}

	/**
	 * 出售
	 * 1\记录
	 * 2\冻结需要销售的货币
	 */
	public function sell($order_type)
	{
		$user_id = $this->UserAuth->getUserId();
		$order_type_arr = $this->Order->order_type_arr;

		$this->set(compact('order_type_arr','order_type'));
		if ($this->request->is('post')) {
			$post_data = $this->request->data;
			$post_data['Order']['user_id'] = $user_id;
			$post_data['Order']['trade_type'] = 'sell';	
			$post_data['Order']['active'] = '0';	

			$number = $post_data['Order']['number'];
			$amount_price = round($post_data['Order']['price'] * $post_data['Order']['number'],6);
			$amount_price = abs($amount_price);
			$post_data['Order']['amount_price'] = $amount_price;

			$db = $this->Order->getDataSource();
			$db->begin();
			try {
				$order_result = $this->Order->save($post_data);
				if(!$order_result){
					throw new Exception("订单创建失败", 1);
				}

				//冻结资金,冻结number,销售btc 得到ars 冻结 btc 
				$account_type = $this->Order->getAccountType($post_data['Order']['order_type'],'sell');
				list($freeze_result,$msg) = $this->Account->freeze($user_id,$account_type,$number);

				if(!$freeze_result){
					throw new Exception($msg, 2);
				}
				$this->AutoTrade->run($order_result['Order']['id'],'buy');
				$db->commit();

				$this->succ("操作成功");
			} catch (Exception $e) {
				$db->rollback();
				$this->error(addslashes($e->getMessage().' error_code:'.$e->getCode()));
			}
			$this->redirect('/Users/home/'.$order_type);
		}else{
		}
	}

	/**
	 * 订单作废
	 * @param 订单ID
	 * 1\标记为作废,那么系统处理交易就不处理了,2\恢复响应的冻结资金
	 * 其实这里最好分解成2个方法,分别取消卖单和买单
	 */
	public function invalid($order_id=null)
	{
		$user_id = $this->UserAuth->getUserId();
		$order = $this->Order->find('first',array(
			'conditions'=>array(
				'Order.id'=>$order_id,
				'Order.user_id'=>$user_id,
				'Order.active'=>0,
			)
		));
		if(!$order){
			$this->error('订单不存在!');
			$this->redirect($this->referer());
		}

		$db = $this->Order->getDataSource();
		$db->begin();
		try {
			$this->Order->id = $order_id;
			if(!$this->Order->saveField('active','-1')){
				throw new Exception("作废失败", 1);
			}

			//取消冻结
			$order_type = $order['Order']['order_type'];
			$trade_type = $order['Order']['trade_type'];
			$account_type = $this->Order->getAccountType($order_type,$trade_type);
			if($order['Order']['trade_type'] == 'buy'){
				//是买的话,就是算出来的那个价格取消冻结
				$amount = $order['Order']['amount_price'];
			}elseif($order['Order']['trade_type'] == 'sell'){
				//是卖的话,就是出售的货币需要取消冻结
				$amount = $order['Order']['number'];
			}

			list($unfreeze_result,$msg) = $this->Account->unFreeze($user_id,$account_type,$amount);
			if(!$unfreeze_result){
				throw new Exception($msg, 2);
			}
			$db->commit();
		} catch (Exception $e) {
			$db->rollback();
			$this->error($e->getMessage());
		}
			$this->redirect($this->referer());
	}


	public function admin_index() {
		$this->Order->recursive = 0;
		$conditions = array();

		$query = $this->request->query;
		$this->request->data['Order'] = $query;

		if($query['active']){
			$conditions['Order.active like']= '%'.$query['active'].'%';
		}
		if($query['trade_type']){
			$conditions['Order.trade_type']=$query['trade_type'];
		}

		$this->Order->recursive = 0;
		$this->paginate = array(
			'conditions'=>$conditions,
			'order'=>'Order.id desc'
		);
		$data = $this->paginate();

		$order_type_arr = $this->Order->order_type_arr;
		$active_arr = $this->Order->active_arr;
		$this->set(compact('data','active_arr','order_type_arr'));
	}

	public function admin_view($id = null) {
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
		$this->set('order', $this->Order->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Order->create();
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
			}
		}
		$users = $this->Order->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
			$this->request->data = $this->Order->find('first', $options);
		}
		$users = $this->Order->User->find('list');
		$this->set(compact('users'));
	}

	/**
	 * 情况挂单
	 */
	public function admin_clear() {
		$this->loadModel("Account");
		$this->loadModel("Order");

		$this->Account->updateAll(array(
			'Account.btc_balance'=>'Account.btc_balance+btc_balance_freeze',
			'Account.btc_balance_freeze'=>0,
			'Account.ltc_balance'=>'Account.ltc_balance+ltc_balance_freeze',
			'Account.ltc_balance_freeze'=>0,
			'Account.ars_balance'=>'Account.ars_balance+ars_balance_freeze',
			'Account.ars_balance_freeze'=>0,
		));
		$this->Order->updateAll(array('Order.active'=>-1),array('Order.active'=>0));
		$this->succ("操作完成");
		$this->redirect($this->referer());
	}
}
