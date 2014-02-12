<?php
App::uses('AppController', 'Controller');
/**
 * Charges Controller
 *
 * @property Charge $Charge
 * @property PaginatorComponent $Paginator
 */
class ChargesController extends AppController {

	public $components = array('Paginator');

   	public $layout = 'user';
   	public $uses = array('Charge','Account');

   	/**
   	 * 充值列表
   	 */
	public function admin_charge_index() {
		$this->Charge->recursive = 0;
		$conditions = array(
				'Charge.charge_type'=>'charge'
		);
		$query = $this->request->query;
		$this->request->data['Charge'] = $query;

		if($query['active'] || $query['active'] !== ''){
			$conditions['Charge.active']=$query['active'];
		}
		if($query['account_type']){
			$conditions['Charge.account_type']=$query['account_type'];
		}
		if($query['email']){
			$conditions['User.email like '] = "%".$query['email']."%";
		}

		$this->paginate = array(
			'conditions'=>$conditions,
			'order'=>'Charge.id desc',
		);
		$data = $this->paginate();
		$active_arr = $this->Charge->active_arr;
		$account_type_arr = $this->Account->account_type_arr;
		$this->set(compact('account_type_arr','active_arr','data'));
	}

	/**
	 * 处理充值
	 *  @ln
	 * 1\标记完成
	 * 2\修改账户
	 */
	public function admin_charge_deal($id=null)
	{
		if(!$this->Charge->exists($id)){
			throw new NotFoundException("没有找到记录");
		}
		$data = $this->Charge->findById($id);
		if($data['Charge']['active'] != 0){
			throw new NotFoundException("错误请求");
		}
		$this->set(compact('data'));
		if ($this->request->isPost() || $this->request->isPut()) {
			$post_data = $this->request->data;
			$db = $this->Charge->getDataSource();
			$db->begin();
			try {
				if($post_data['Charge']['active'] == 1){ //完成
					$account = $this->Account->findByUserId($data['Charge']['user_id']);
					$account_type = $data['Charge']['account_type'];
					$amount = $data['Charge']['amount'];
					$new_amount = $account['Account'][$account_type.'_balance'] + $amount;
					if($new_amount < 0){
						throw new Exception("输入金额有误!", 1);
					}
					$this->Account->id = $account['Account']['id'];
					if(!$this->Account->saveField($account_type.'_balance',$new_amount)){
						throw new Exception("充值有误!", 2);
					}

					//标记
					$this->Charge->id = $data['Charge']['id'];
					$this->Charge->set('active',$post_data['Charge']['active']);
					$this->Charge->set('error_mark',$post_data['Charge']['error_mark']);
					if(!$this->Charge->save()){
						throw new Exception("记录有误!", 2);
					}
				}else{
					//标记
					$this->Charge->id = $data['Charge']['id'];
					if(!$this->Charge->save($post_data)){
						throw new Exception("记录有误!", 2);
					}
				}
				$db->commit();
				$this->succ("处理成功");
				$this->redirect('/admin/Charges/charge_index?active='.$post_data['Charge']['active']);
			} catch (Exception $e) {
				$db->rollback();
				$this->error(addslashes($e->getMessage()));
			}	
		}
	}

	/**
	 * 提现列表
	 */
	public function admin_takeout_index()
	{
		$this->Charge->recursive = 0;
		$conditions = array(
				'Charge.charge_type'=>'takeout'
		);
		$query = $this->request->query;
		$this->request->data['Charge'] = $query;

		if($query['active'] || $query['active'] !== ''){
			$conditions['Charge.active']=$query['active'];
		}
		if($query['account_type']){
			$conditions['Charge.account_type']=$query['account_type'];
		}
		if($query['email']){
			$conditions['User.email like '] = "%".$query['email']."%";
		}

		$this->paginate = array(
			'conditions'=>$conditions,
			'order'=>'Charge.id desc',
		);
		$data = $this->paginate();
		$active_arr = $this->Charge->active_arr;
		$account_type_arr = $this->Account->account_type_arr;
		$this->set(compact('account_type_arr','active_arr','data'));
	}

	/**
	 * 处理提现
	 *  @ln
	 * 1\标记完成
	 * 2\修改账户
	 */
	public function admin_takeout_deal($id=null)
	{
		if(!$this->Charge->exists($id)){
			throw new NotFoundException("没有找到记录");
		}
		$data = $this->Charge->findById($id);
		if($data['Charge']['active'] != 0){
			throw new NotFoundException("错误请求");
		}
		$this->set(compact('data'));
		if ($this->request->isPost() || $this->request->isPut()) {
			$post_data = $this->request->data;
			$db = $this->Charge->getDataSource();
			$db->begin();
			try {
				if($post_data['Charge']['active'] == 1){ //完成
					// $account = $this->Account->findByUserId($data['Charge']['user_id']);
					// $account_type = $data['Charge']['account_type'];
					// $amount = $data['Charge']['amount'];
					// $new_amount = $account['Account'][$account_type.'_balance'] - $amount;
					// if($new_amount < 0){
					// 	throw new Exception("余额不足,无法提现!", 1);
					// }
					// $this->Account->id = $account['Account']['id'];
					// if(!$this->Account->saveField($account_type.'_balance',$new_amount)){
					// 	throw new Exception("提现有误!", 2);
					// }

					//标记
					$this->Charge->id = $data['Charge']['id'];
					$this->Charge->set('active',$post_data['Charge']['active']);
					$this->Charge->set('error_mark',$post_data['Charge']['error_mark']);
					if(!$this->Charge->save()){
						throw new Exception("记录有误!", 2);
					}
				}else{
					//标记
					$this->Charge->id = $data['Charge']['id'];
					//把钱充回去
					$amount = $data['Charge']['amount'];
					$account_type = $data['Charge']['account_type'];

					list($result,$msg) = $this->Account->creamBlance($data['Charge']['user_id'],$account_type,$amount);
					if (!$result) {
						throw new Exception($msg, 1);
					}
					if(!$this->Charge->save($post_data)){
						throw new Exception("记录有误!", 2);
					}
				}
				$db->commit();
				$this->succ("处理成功");
				$this->redirect('/admin/Charges/takeout_index?active='.$post_data['Charge']['active']);
			} catch (Exception $e) {
				$db->rollback();
				$this->error(addslashes($e->getMessage()));
			}	
		}
	}
}
