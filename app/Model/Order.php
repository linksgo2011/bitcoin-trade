<?php
App::uses('AppModel', 'Model');
/**
 * Order Model
 *
 * @property User $User
 */
class Order extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'user_id';

	/**
	 * 移除 'USD_ARS'
	 */
	//public $order_type_arr = array('ARS_BTC','ARS_LTC','USD_LTC','USD_BTC'); //暂时关闭
	public $order_type_arr = array('ARS_BTC','ARS_LTC'); //交易方式(市场)

	public $active_arr = array('-1'=>'取消','0'=>'正常','1'=>'完成');

	public $trade_type = array('buy'=>'买单','sell'=>'卖单');

	/**
	 * 最近订单
	 */
	public function getLast($order_type,$trade_type,$limit,$user_id=0)
	{
		$conditions = array(
        		'Order.active'=>'0'
        );
       	if ($order_type) {
        	$conditions['Order.order_type'] = $order_type;
       	}
       	if ($trade_type) {
        	$conditions['Order.trade_type'] = $trade_type;
       	}
        if($user_id){
        	$conditions['Order.user_id'] = $user_id;
        }
       	$limit = $limit?$limit:9999;
        return $this->find('all',array(
        	'conditions'=>$conditions,
            'limit'=>$limit,
            'recursive'=>0,
            'order'=>'Order.id desc'
        ));
	}

	/**
	 * 最近订单合并
	 */
	public function getLastCombine($order_type,$trade_type,$limit,$user_id=0,$index=null)
	{
		$conditions = array(
        		'Order.active'=>'0'
        );
       	if ($order_type) {
        	$conditions['Order.order_type'] = $order_type;
       	}
       	if ($trade_type) {
        	$conditions['Order.trade_type'] = $trade_type;
       	}
        if($user_id){
        	$conditions['Order.user_id'] = $user_id;
        }
       	$limit = $limit?$limit:9999;
        $data = $this->find('all',array(
        	'fields'=>array(
        		'Order.price',
        		'SUM(Order.number) as number',
        		'SUM(Order.amount_price) as amount_price'
        	),
        	'conditions'=>$conditions,
            'limit'=>$limit,
            'recursive'=>0,
            'order'=>$index?$index:'Order.id desc',
            'group'=>'price'
        ));

       	$out = array();
       	if($data){
       		foreach ($data as $key => $one) {
       			$one[0]['price'] = $one["Order"]['price'];
       			$out[]['Order'] = $one[0];
       		}
       	}
       	return $out;
	}

	/**
	 * 获取冻结操作账户类型
	 * @param 订单类型
	 * @param 交易类型  buy or sell
	 */
	public function getAccountType($order_type,$trade_type)
	{
		$arr = split('_', $order_type);
		if($trade_type == 'buy'){
			return strtolower($arr[0]);
		}elseif($trade_type == 'sell'){
			return strtolower($arr[1]);
		}
		return null;
	}

	/**
	 * @统计订单
	 */
	public function statis($user_id)
	{
		$this->recursive = -1;
		$data = $this->find('all',array(
			'fields'=>array(
				'count(Order.id) as count',
				'sum(Order.number) as number_sum',
				'sum(Order.amount_price) as amount_sum',
				'Order.trade_type',
			),
			'conditions'=>array('Order.user_id'=>$user_id),
			'group'=>array('Order.trade_type')
		));
		$out = array();
		if ($data) {
			foreach ($data as $key => $one) {
				$out[$one['Order']['trade_type']] = $one[0];
			}
		}
		return $out;
	}

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
		'trade_type' => array(
			'inList' => array(
				'rule' => array('inList',array('sell','buy')),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'order_type' => array(
			'inList' => array(
				'rule' => array('inList',array('USD_ARS','ARS_LTC','ARS_BTC','USD_LTC','USD_BTC')),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'number' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'min'=>array(
				'rule' => array('min',0),
				'message' => '必须大于0',
			)
		),
		'price' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'min'=>array(
				'rule' => array('min',0),
				'message' => '必须大于0',
			)
		),
		'amount_price' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'active' => array(
			'inList' => array(
				'rule' => array('inList',array('-1','0','1',false)),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
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
