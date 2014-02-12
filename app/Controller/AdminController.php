<?php
App::uses('AppController', 'Controller');
/**
 * Admin Controller
 *
 * @property Admin $Admin
 * @property PaginatorComponent $Paginator
 */
class AdminController extends AppController {

	public $layout = 'admin';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$user = $this->UserAuth->getUser();
		if($user['User']['role'] != 'admin'){
			$this->error("没有权限进入!");
			$this->redirect("/Users/login");
		}
	}

	/**
	 * 管理员首页
	 */
	public function index()
	{
		$this->loadModel('User');
		$this->loadModel("Order");
		$this->loadModel("Account");
		$this->loadModel("Trade");
		$this->loadModel("ChargeCode");

		$last_users = $this->User->getLast(5);
		$last_orders = $this->Order->getLast(null,null,5);
		$sys_user_id = $this->User->getSys();
		$sys_user_account = $this->Account->findByUserId($sys_user_id);

		$charge_code_stat = $this->ChargeCode->stat();
		$account_stat = $this->Account->stat();

		//系统成交分组统计
		$statistic = $this->Trade->getStatis();
		$this->set(compact(
			'last_users',
			'last_orders',
			'order_type_arr',
			'sys_user_account',
			'statistic',
			'charge_code_stat',
			'account_stat'
		));
	}
		
	public function test_ga()
	{
		App::uses('Google2FA', 'vendors');
		$this->loadModel("User");
		$ga = new Google2FA();
		if ($_GET['start']) {
			$init_key = $ga->generate_secret_key();
			echo $init_key;exit();
		}
		if($_GET['password'] && $_GET['key']){
			$result = Google2FA::verify_key($_GET['key'],$_GET['password']);
			var_dump($result);
			exit();
		}

	}
}
