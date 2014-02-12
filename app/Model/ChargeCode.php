<?php
App::uses('AppModel', 'Model');
/**
 * ChargeCode Model
 *
 */
class ChargeCode extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'code';

	public $active_arr = array('0'=>'正常','1'=>'已经使用!');
	
    public function gen() {
        $code = rand( 1000, 9999 ) . rand( 1000, 9999 ) . rand( 1000, 9999 ) . rand( 1000, 9999 );
        if ($this->findByCode( $code ) ) {
        	$code = $this->gen();
        }else{
 			return $code;
        }        
    }

    public function stat()
    {
    	$data = $this->find('all',array(
    		'fields'=>array(
    			'ChargeCode.account_type',
    			'sum(ChargeCode.amount) as sum'
    		),
    		'conditions'=>array(
    			'ChargeCode.active'=>0
    		),
    		'group'=>'ChargeCode.account_type',
    		'recursive'=>-1
    	));
    	$out = array();
    	foreach ($data as $key => $one) {
    		$out[$one['ChargeCode']['account_type']] = $one['0']['sum'];
    	}
    	return $out;
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
		'amount' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Yourdecimal custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'comparison'=>array(
				'rule'=>array('comparison','isgreater',0),
				'message' => '必须大于0',
			)
		)
	);

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Used' => array(
			'className' => 'User',
			'foreignKey' => 'used_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
