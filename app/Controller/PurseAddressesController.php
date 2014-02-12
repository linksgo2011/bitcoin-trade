<?php
App::uses('AppController', 'Controller');
/**
 * PurseAddresses Controller
 *
 * @property PurseAddress $PurseAddress
 * @propertComponent
 */
class PurseAddressesController extends AppController {

	public $layout = 'admin';

	public function index() {
		$this->loadModel('Account');
		$active_arr = $this->PurseAddress->active_arr;
		$account_type_arr = array('btc'=>'BTC','ltc'=>'LTC');
		$conditions = array();
		
		$query  = $this->request->query;
		$this->request->data['PurseAddress'] = $query;
		if ($query['account_type']) {
			$conditions['PurseAddress.account_type'] = $query['account_type'];
		}
		if (isset($query['active']) && $query['active'] != '') {
			$conditions['PurseAddress.active'] = $query['active'];
		}
		if ($query['key']) {
			$conditions['PurseAddress.key like'] = '%'.$query['key'].'%';
		}

		$this->PurseAddress->recursive = 0;
		$this->paginate = array(
			'conditions'=>$conditions,
			'limit'=>20,
			'order'=>'PurseAddress.id desc'
		);
		$this->set('data', $this->paginate());
		$this->set(compact('active_arr','account_type_arr'));
	}

	public function add() {
		$this->loadModel("Account");
		$account_type_arr = array('btc'=>'BTC','ltc'=>'LTC');

		$this->set(compact('account_type_arr'));
		if ($this->request->is('post')) {
			$post_data = $this->request->data;
			$keys = '';
			if($post_data['PurseAddress']['add_type'] == 'keys'){
				$keys = $post_data['PurseAddress']['keys'];
			}else{
				$keys = file_get_contents($post_data['PurseAddress']['keys_file']['tmp_name']);
			}	
			if(!$keys){
				$this->error("没有输入!");
				return ;
			}
			$key_arr = explode("\r\n",$keys);
			array_walk($key_arr,'trim');
			$errors = '';
			foreach ($key_arr as $key) {
				if(!$key){
					continue;
				}
				$has_any = $this->PurseAddress->hasAny(array('key'=>$key,'account_type'=>$post_data['PurseAddress']['account_type']));
				if($has_any){
					$errors .= $key.'已经存在无法导入<br />;';
					continue ;
				}
				$data = array(
					'account_type'=>$post_data['PurseAddress']['account_type'],
					'key'=>$key,
					'created'=>time()
				);		
				$this->PurseAddress->create();
				if(!$this->PurseAddress->save($data)){
					$errors .= $key.'导入失败;';
				}
			}
			if($errors){
				$this->error($errors);
			}else{
				$this->succ('导入成功');
			}
			$this->redirect('/PurseAddresses/');
		}

	}

	public function reset($account_type)
	{
		$user_id = $this->UserAuth->getUserId();
		$new = $this->PurseAddress->reset($user_id,$account_type);
		$this->redirect($this->referer());
	}

	public function delete($id = null) {
		$this->PurseAddress->id = $id;
		if (!$this->PurseAddress->exists()) {
			throw new NotFoundException(__('无效请求'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PurseAddress->delete()) {
			$this->succ(__('删除成功!'));
		} else {
			$this->error(__('删除失败!'));
		}
		return $this->redirect(array('action' => 'index'));
	}

}
