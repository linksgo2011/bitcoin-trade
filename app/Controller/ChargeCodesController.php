<?php
App::uses('AppController', 'Controller');
/**
 * ChargeCodes Controller
 *
 * @property ChargeCode $ChargeCode
 * @property PaginatorComponent $Paginator
 */
class ChargeCodesController extends AppController {

	public $layout = 'user';

	public function index() {
		$user_id = $this->UserAuth->getUserId();

		$this->ChargeCode->recursive = 0;
		$this->paginate = array(
			'conditions'=>array(
				'user_id'=>$user_id
			),
			'limit'=>20,
			'order'=>'ChargeCode.id desc'
		);
		$data = $this->paginate();
		$active_arr = $this->ChargeCode->active_arr;
		$this->set(compact('data','active_arr'));
	}

	/**
	 * 从自己账户生成充值码
	 * 1\扣除账户金额
	 * 2\生成充值码
	 */
	public function gen() {
		$this->loadModel("Account");
		$user_id = $this->UserAuth->getUserId();
		$account_type_arr = $this->Account->account_type_arr;
		$this->set(compact('account_type_arr'));

		if ($this->request->is('post')) {
			$post_data = $this->request->data;

			$db = $this->ChargeCode->getDataSource();
			$db->begin();
			try {
				$account_type = $post_data['ChargeCode']['account_type'];
				$amount = abs($post_data['ChargeCode']['amount']);
				$password = $post_data['ChargeCode']['password'];
				if (!preg_match('/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,30}$/',$password)) {
					$this->error("操作失败");
					$this->ChargeCode->validationErrors['password'] = '正确的密码为6-30个常用字符';
					return ;
				}
				$code  = $this->ChargeCode->gen();	
				$created = time();

				//扣钱
				list($result,$msg) = $this->Account->reduceBlance($user_id,$account_type,$amount);
				if(!$result){
					throw new Exception($msg, 1);
				}
				$this->ChargeCode->create(compact(
					'user_id',
					'account_type',
					'amount',
					'code',
					'password',
					'created'
				));
				if(!$this->ChargeCode->save()){
					throw new Exception("生成失败", 2);
				}

				$db->commit();
				$this->succ("操作成功!");
				$this->redirect(array('action'=>'index'));
			} catch (Exception $e) {
				$db->rollback();
				$this->error($e->getMessage());
				return ;
			}
		}
	}

	public function view($code=null)
	{
		$this->layout = false;
		$this->set('data',$this->ChargeCode->findByCode($code));
	}

	/**
	 * 使用充值码
	 * 增加自己账户钱
	 * 标记完成充值码
	 */
	public function use_code()
	{
		$this->loadModel("Account");
		$user_id = $this->UserAuth->getUserId();

		if($this->request->isPost()){
			$post_data = $this->request->data;
			$charge_code = $this->ChargeCode->findByCode($post_data['ChargeCode']['code']);
			if(!$charge_code){
				$this->error("没有此充值码");
				return ;
			}
			if($charge_code['ChargeCode']['active'] != 0){
				$this->error("已经使用本充值码,无法再次使用!");
				return ;
			}
			if($charge_code['ChargeCode']['password'] != $post_data['ChargeCode']['password']){
				$this->error("密码错误");
				return ;
			}
			
			$account_type = $charge_code['ChargeCode']['account_type'];
			$amount = $charge_code['ChargeCode']['amount'];
			$used_id = $user_id;
			$db = $this->ChargeCode->getDataSource();
			$db->begin();

			try {
				list($result,$msg) = $this->Account->creamBlance($user_id,$account_type,$amount);
				if(!$result){
					throw new Exception($msg, 1);
				}
				$this->ChargeCode->id = $charge_code['ChargeCode']['id'];
				$this->ChargeCode->set('used_id',$used_id);
				$this->ChargeCode->set('finished',time());
				$this->ChargeCode->set('active',1);

				if(!$this->ChargeCode->save()){
					throw new Exception("保存失败!", 1);
				}

				$db->commit();
				$this->succ("操作成功!");
				$this->redirect(array('action' => 'index'));
			} catch (Exception $e) {
				$db->rollback();
				$this->error($e->getMessage());
			}
		}
	}


	/**
	 * 把钱返回去
	 */
	// public function delete($id = null) {
	// 	$this->loadModel("Account");
	// 	$user_id = $this->UserAuth->getUserId();

	// 	$this->ChargeCode->id = $id;
	// 	$charge_code = $this->ChargeCode->find('first',array(
	// 		'conditions'=>array(
	// 			'user_id'=>$user_id,
	// 			'id'=> $id
	// 		)
	// 	));
	// 	if (!$charge_code) {
	// 		throw new NotFoundException(__('无效请求'));
	// 	}
	// 	$this->request->onlyAllow('post', 'delete');

	// 	extract($charge_code['ChargeCode']);
	// 	$db = $this->ChargeCode->getDataSource();
	// 	$db->begin();
	// 	try {
	// 		if($active == 1){
	// 			throw new Exception("已经使用!", 1);
	// 		}
	// 		//加钱
	// 		list($result,$msg) = $this->Account->creamBlance($user_id,$account_type,$amount);
	// 		if(!$result){
	// 			throw new Exception($msg, 1);
	// 		}
	// 		if(!$this->ChargeCode->delete($id)){
	// 			throw new Exception("删除失败", 1);
	// 		}

	// 		$db->commit();
	// 		$this->succ("操作成功");
	// 	} catch (Exception $e) {
	// 		$db->rollback();
	// 		$this->error($e->getMessage());
	// 	}
	// 	$this->redirect(array('action' => 'index'));
	// }

	/**
	 * 充值码后台管理
	 */
	public function admin_index()
	{
		$this->loadModel("Account");

		$conditions = array();
		$query = $this->request->query;
		$this->request->data['ChargeCode'] = $query;
		if(isset($query['active']) && $query['active'] != ''){
			$conditions['ChargeCode.active'] = $query['active'];
		}
		if($query['account_type']){
			$conditions['ChargeCode.account_type'] = $query['account_type'];
		}
		if($query['code']){
			$conditions['ChargeCode.code like'] = '%'.$query['code'].'%';
		}

		$this->paginate = array(
			'conditions'=>$conditions,
			'limit'=>20,
			'order'=>'ChargeCode.active asc,ChargeCode.id desc'
		);
		$this->ChargeCode->recursive = 0;
		$data = $this->paginate();
		$active_arr = $this->ChargeCode->active_arr;
		$account_type_arr = $this->Account->account_type_arr;
		$this->set(compact('data','active_arr','account_type_arr'));	
	}

	// public function admin_delete($id=null)
	// {	
	// 	$this->loadModel("Account");

	// 	$this->ChargeCode->id = $id;
	// 	$charge_code = $this->ChargeCode->find('first',array(
	// 		'conditions'=>array(
	// 			'ChargeCode.id'=> $id
	// 		)
	// 	));
	// 	if (!$charge_code) {
	// 		throw new NotFoundException(__('无效请求'));
	// 	}
	// 	$this->request->onlyAllow('post', 'delete');

	// 	extract($charge_code['ChargeCode']);
	// 	$db = $this->ChargeCode->getDataSource();
	// 	$db->begin();
	// 	try {
	// 		if($active == 1){
	// 			throw new Exception("已经使用!", 1);
	// 		}
	// 		//加钱
	// 		list($result,$msg) = $this->Account->creamBlance($user_id,$account_type,$amount);
	// 		if(!$result){
	// 			throw new Exception($msg, 1);
	// 		}
	// 		if(!$this->ChargeCode->delete($id)){
	// 			throw new Exception("删除失败", 1);
	// 		}
			
	// 		$db->commit();
	// 		$this->succ("操作成功");
	// 	} catch (Exception $e) {
	// 		$db->rollback();
	// 		$this->error($e->getMessage());
	// 	}
	// 	$this->redirect(array('action' => 'index'));
	// }
}
