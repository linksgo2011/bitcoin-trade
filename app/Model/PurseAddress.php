<?php
App::uses('AppModel', 'Model');
/**
 * PurseAddress Model
 *
 */
class PurseAddress extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'key';

	public $active_arr = array('-1'=>'作废','0'=>'正常');

	/**
	 * 分配地址
	 */
	public function setUser($user_id,$account_type)
	{
		$has_any = $this->hasAny(
			array(
				'PurseAddress.active'=>0,
				'PurseAddress.user_id'=>$user_id,
				'PurseAddress.account_type'=>$key
		));
		if (!$has_any) {
			$wait = $this->find('first',array(
				'conditions'=>array(
					'PurseAddress.active'=>0,
					'PurseAddress.user_id'=>0,
					'PurseAddress.account_type'=>$account_type
				),
				'order'=>'RAND()'
			));
			if($wait){
				$this->id = $wait['PurseAddress']['id'];
				$result = $this->saveField('user_id',$user_id);
				return array(1,$wait);
			}else{
				return array(0,'没有可用的地址!');
			}
		}else{
			return array(0,'已经存在可用地址!');
		}
		
	}

	public function getUserAddress($user_id,$account_type)
	{
		$data = $this->find('first',array(
			'conditions'=>array(
				'PurseAddress.active'=>0,
				'PurseAddress.user_id'=>$user_id,
				'PurseAddress.account_type'=>$account_type
			)
		));
		if ($data) {
			return $data['PurseAddress']['key'];
		}
	}
		
	/**
	 * 重新获取
	 */
	public function reset($user_id,$account_type)
	{
		list($result,$new) = $this->setUser($user_id,$account_type);
		if($result && $new){

			$this->updateAll(array(
				'PurseAddress.user_id'=>0
			),
			array(
					'PurseAddress.id !='=>$new['PurseAddress']['id'],
					'PurseAddress.user_id'=>$user_id,
					'PurseAddress.account_type'=>$account_type,
					'PurseAddress.active'=>0
				)
			);
			$this->User->updateCounter();
			$this->updateCounter();
			return $new['PurseAddress']['key'];
		}else{
			return false;
		}
	}	

	/**
	 * 作废用户当前地址并重新分配
	 */
	public function finish($user_id,$account_type)
	{
		$data = $this->find('first',array(
			'conditions'=>array(
				'PurseAddress.user_id'=>$user_id,
				'PurseAddress.account_type'=>$account_type
			)
		));
		if($data){
			$this->id = $data['PurseAddress']['id'];
			$this->saveField('active','-1');

			$this->setUser($user_id,$account_type);
		}
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
		'account_type' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
		'active' => array(
			'inList' => array(
				'rule' => array('inList',array('-1','0')),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

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
