<?php
App::uses('AppModel', 'Model');
/**
 * Account Model
 *
 * @property User $User
 */
class Account extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'account';

	public $account_type_arr = array('btc'=>'BTC','ltc'=>'LTC','ars'=>'ARS');

	public $v_account_type_arr = array('btc'=>'BTC','ltc'=>'LTC');
	
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'user_id';

	public function init($user_id)
	{
	    $this->create();
	    return $this->save( array( 
	    	'user_id' => $user_id,
	    ));
	}

	/**
	 * 冻结账户
	 *@param $user_id
	 *@param $account_type
	 *@param $amount 
	 */
	public function freeze($user_id,$account_type,$amount)
	{
		$account = $this->findByUserId($user_id);
		if(!$account){
			return array(0,'账户不存在!');
		}

		$new_blance = $account['Account'][$account_type.'_balance'] - $amount;
		$new_blance_freeze = $account['Account'][$account_type.'_balance_freeze'] + $amount;
			
		if($new_blance < 0){
			return array(0,'余额不足!');
		}
		$this->id = $account['Account']['id'];
		$this->set($account_type.'_balance',$new_blance);
		$this->set($account_type.'_balance_freeze',$new_blance_freeze);
		if($this->save()){
			return array(1,null);
		}else{
			return array(0,'冻结失败!');
		}
	}

	/**
	 * 反向冻结
	 *@param $user_id
	 *@param $account_type
	 *@param $amount 
	 */
	public function unFreeze($user_id,$account_type,$amount)
	{
		$account = $this->findByUserId($user_id);
		if(!$account){
			return array(0,'账户不存在!');
		}

		$new_blance_freeze = $account['Account'][$account_type.'_balance_freeze'] - $amount;
		$new_blance = $account['Account'][$account_type.'_balance'] + $amount;
			
		if($new_blance_freeze < 0){
			return array(0,'冻结余额不足!');
		}
		$this->id = $account['Account']['id'];
		$this->set($account_type.'_balance',$new_blance);
		$this->set($account_type.'_balance_freeze',$new_blance_freeze);
		if($this->save()){
			return array(1,null);
		}else{
			return array(0,'取消冻结失败!');
		}
	}

	/**
	 * 扣钱
	 */
	public function reduceBlance($user_id,$account_type,$amount)
	{
		$account_type = strtolower($account_type);
		$account = $this->findByUserId($user_id);
		if(!$account){
			return array(0,'账户不存在');
		}
		if(($account['Account'][$account_type.'_balance']-$amount) < 0){
			return array(0,'余额不足');
		}
		$yue = $account['Account'][$account_type.'_balance']-$amount;

		$this->id = $account['Account']['id'];
		$result = $this->saveField($account_type.'_balance',$yue);
		return array($result,'');
	}

	/**
	 * 充钱
	 */
	public function creamBlance($user_id,$account_type,$amount)
	{
		$account_type = strtolower($account_type);
		$account = $this->findByUserId($user_id);
		if(!$account){
			return array(0,'账户不存在');
		}
		$yue = $account['Account'][$account_type.'_balance']+$amount;

		$this->id = $account['Account']['id'];
		$result = $this->saveField($account_type.'_balance',$yue);
		return array($result,'');
	}
	

	public function stat()
	{
		$data  = $this->find('first',array(
    		'fields'=>array(
    			'sum(Account.btc_balance) as btc_balance',
    			'sum(Account.ltc_balance) as ltc_balance',
    			'sum(Account.ars_balance) as ars_balance',
    			'sum(Account.btc_balance_freeze) as btc_balance_freeze',
    			'sum(Account.ltc_balance_freeze) as ltc_balance_freeze',
    			'sum(Account.ars_balance_freeze) as ars_balance_freeze',
    		),
    		'recursive'=>-1
		));
		return $data[0];
	}
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'btc_balance' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'btc_balance_freeze' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ltc_balance' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ltc_balance_freeze' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ars_balance' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ars_balance_freeze' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'usd_balance' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'usd_balance_freeze' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
