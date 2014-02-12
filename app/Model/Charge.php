<?php
App::uses('AppModel', 'Model');
/**
 * Charge Model
 *
 * @property User $User
 */
class Charge extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'charge';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'amount';

	public $active_arr = array('-1'=>'作废','0'=>'申请中','1'=>'操作成功');
	
	public $charge_type = array('charge'=>'充值','takeout'=>'提现');

	public function getLast($limit,$user_id=null)
	{	
		$conditions = array();
		if($user_id){
			$conditions['Charge.user_id'] = $user_id;
		}
		return $this->find('all',array(
			'conditions'=>$conditions,
			'limit'=>$limit,
			'order'=>'Charge.id desc',
			'recursive'=>-1
		));
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'uuid' => array(
				'rule' => array('uuid'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'charge_type' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'amount' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				'message' => '必须是数字！',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'comparison'=>array(
				'rule'=>array('comparison','isgreater',0),
				'message' => '必须大于0',
			),
			'maxLength'=>array(
				'rule'=>array('maxLength',50),
				'message' => '最长50字符'
			)
		),
		'mark' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active' => array(
			'inList' => array(
				'rule' => array('inList',array('-1','0','1'),false),
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
