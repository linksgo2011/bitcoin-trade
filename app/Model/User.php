<?php
App::uses('AppModel', 'Model');
App::import('Model', 'Merchant');
/**
 * User Model
 *
 * @property Charge $Charge
 * @property Order $Order
 */
class User extends AppModel {


    public $active_arr = array('0'=>'正常','-1'=>'半锁定','-2'=>'完全锁定');
    
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


    public function getLast($limit = 5)
    {
        return $this->find('all',array(
            'limit'=>5,
            'recursive'=>0,
            'order'=>'User.id desc'
        ));
    }

    /**
     * 手续费率
     * 12.07 变更为使用order_type 计算手续费率
     * @param $user_id
     * @return 手续率
     */
    public function feeRate($user_id,$order_type)
    {
        $order_type  = strtolower($order_type);
        $this->recursive = 0;
        $user = $this->read(null,$user_id);
        $user_fee_rate = @$user['User']["{$order_type}_rate"];
        if($user_fee_rate){
            return $user_fee_rate/100;
        }
        $this->Merchant = new Merchant();
        $merchant = $this->Merchant->find('first');
        $default_fee_rate = $merchant['Merchant']["{$order_type}_rate"];
        return $default_fee_rate/100;
    }

    /**
     * 获取系统收款User_ID
     */
    public function getSys()
    {
        $this->Merchant = new Merchant();
        $merchant = $this->Merchant->find('first');
        return $merchant['Merchant']['user_id'];
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
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => '检查是否格式正确!',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				// 'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => '已经存在!',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '不能为空',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'custom' => array(
                'rule' => array('custom','/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,30}$/'),
                'message' => '正确的用户名为6-30个常用字符',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '不能为空',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'custom' => array(
                'rule' => array('custom','/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,30}$/'),
                'message' => '正确的密码为6-30个常用字符',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
		),
		'pay_password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '不能为空',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'custom' => array(
                'rule' => array('custom','/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{6,30}$/'),
                'message' => '正确的密码为6-30个常用字符',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
		),
        'qq'=>array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => '必须是数字',
                'allowEmpty' => true,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
    function RegisterValidate() {
        $this->validate = array(
            'email' => array(
                'mustNotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '必填',
                    'last' => true),
                'mustBeEmail' => array(
                    'rule' => array('email'),
                    'message' => '请输入有效邮箱地址',
                    'last' => true),
                'mustUnique' => array(
                    'rule' => 'isUnique',
                    'message' => '邮箱已经存在',
                )
            ),
            'password' => array(
                'mustNotEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '必填',
                    'on' => 'create',
                    'last' => true),
                'mustBeLonger' => array(
                    'rule' => array('minLength', 6),
                    'message' => '密码最少6位数',
                    'on' => 'create',
                    'last' => true)
            ),
        );
        return $this->validates();
    }

/**
 * hasMany associations
 * @var array
 */
	public $hasMany = array(
		'Charge' => array(
			'className' => 'Charge',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
        'PurseAddress' => array(
            'className' => 'PurseAddress',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
	);
    
}
