<?php
App::uses('AppController', 'Controller');

class AccountsController extends AppController {

	public $components = array('Paginator');

   	public $layout = 'user';

   	public $uses = array('Account','Charge','ReCharge');

	public function index() {
		$user_id = $this->UserAuth->getUserId();
		$account = $this->Account->findByUserId($user_id);
		if(!$account){
			$account = $this->Account->init($user_id);
		}
		$this->set(compact('account'));
	}

	/**
	 * 充值
	 */
	public function charge($account_type='btc')
	{
		$user_id = $this->UserAuth->getUserId();
		$this->Charge->recursive = -1;
		$this->paginate = array(
			'conditions'=>array(
				'Charge.user_id'=>$user_id,
				'Charge.charge_type'=>'charge'
			),
			'order'=>'Charge.id desc'
		);
		$data = $this->paginate('Charge');
		$account_type_arr = $this->Account->account_type_arr;
		$v_account_type_arr = $this->Account->v_account_type_arr;
		if(in_array($account_type,array_keys($v_account_type_arr))){
			$this->loadModel('PurseAddress');
			$address  = $this->PurseAddress->getUserAddress($user_id,$account_type);
		}
		$active_arr = $this->Charge->active_arr;
		$this->set(compact('account_type_arr','data','active_arr','v_account_type_arr','account_type','address'));

		if($this->request->isPost()){
			$post_data = $this->request->data;
			$post_data['Charge']['user_id'] = $user_id;
			$post_data['Charge']['charge_type'] = 'charge';
			$post_data['Charge']['address'] = $address; 
			if(in_array($account_type,array_keys($v_account_type_arr)) && !$address){
				$this->error("钱包无效!");
				return ;
			}
			$this->loadModel("PurseAddress");
			$this->PurseAddress->finish($user_id,$account_type);

			if(!$this->Charge->save($post_data)){
				$this->error("操作失败!");
			}else{
				$this->succ("申请成功,等待管理员审核!");
				$this->redirect($this->referer());
			}
		}

	}

	/**
	 * 提现
	 * @ln
	 */
	public function recharge($account_type='btc')
	{
		$user_id = $this->UserAuth->getUserId();
		$this->Charge->recursive = -1;
		$this->paginate = array(
			'conditions'=>array(
				'Charge.user_id'=>$user_id,
				'Charge.charge_type'=>'takeout'
			),
			'order'=>'Charge.id desc'
		);
		$data = $this->paginate('Charge');
		$account = $this->Account->findByUserId($user_id);
		$account_type_arr = $this->Account->account_type_arr;
		$active_arr = $this->Charge->active_arr;
		$v_account_type_arr = $this->Account->v_account_type_arr;
		$this->set(compact(
			'account_type_arr',
			'v_account_type_arr',
			'data',
			'active_arr',
			'account',
			'account_type'
		));

		if($this->request->isPost()){
			$post_data = $this->request->data;
			$post_data['Charge']['user_id'] = $user_id;
			$post_data['Charge']['charge_type'] = 'takeout';
			$user = $this->UserAuth->getUser();

			if($user['User']['pay_password'] != $post_data['Charge']['pay_password']){
				$this->error("支付密码错误!");
				return ;
			}

			$account_type = $post_data['Charge']['account_type'];
			$amount = $post_data['Charge']['amount'];
			list($result,$msg) = $this->Account->reduceBlance($user_id,$account_type,$amount);
			if (!$result) {
				$this->error($msg);
				return ;
			}
			if(!$this->Charge->save($post_data)){
				$this->error("操作失败!");
			}else{
				$this->succ("申请成功,等待管理员审核!");
				$this->redirect($this->referer());
			}
		}
	}

	/**
	 * 管理员直接充值/扣除
	 * 写入充值记录
	 */
	public function admin_alter($user_id=0)
	{
		$account_type_arr = $this->Account->account_type_arr;
		$active_arr = $this->Charge->active_arr;
		$account = $this->Account->findByUserId($user_id);
		$this->set(compact('account_type_arr','account','active_arr'));

		if($this->request->isPost()){
			$post_data = $this->request->data;
			$post_data['Charge']['user_id'] = $user_id;
			$post_data['Charge']['charge_type'] = 'charge';
			$post_data['Charge']['active'] = 1;
			
			$db = $this->Charge->getDataSource();
			$db->begin();
			try {
				//记录
				if(!$this->Charge->save($post_data,false)){
					throw new Exception("提交失败", 1);
				}	
				//操作账户
				$account_type = $post_data['Charge']['account_type'];
				$account = $this->Account->findByUserId($user_id);
				$new_account = $account['Account'][$account_type.'_balance']+$post_data['Charge']['amount'];
				if($new_account < 0){
					throw new Exception("余额不足无法扣除!", 2);
				}

				$this->Account->id = $account['Account']['id'];
				if(!$this->Account->saveField($account_type.'_balance',$new_account,false)){
					throw new Exception("操作失败!", 3);
				}

				$db->commit();
				$this->succ("操作成功!");
				$this->redirect($this->referer());
			} catch (Exception $e) {
				$db->rollback();
				$this->error($e->getMessage());
			}
		}
	}
}
