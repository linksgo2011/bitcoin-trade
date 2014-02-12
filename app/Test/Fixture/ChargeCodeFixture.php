<?php
/**
 * ChargeCodeFixture
 *
 */
class ChargeCodeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'account_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '12,6', 'comment' => '金额'),
		'used_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'comment' => '使用者ID'),
		'code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 250, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'integer', 'null' => false, 'default' => null),
		'finished' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => '完成时间'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'comment' => '状态 0 为正常那个 1为使用'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'account_type' => 'Lorem ipsum dolor sit amet',
			'amount' => 1,
			'used_id' => 1,
			'code' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'created' => 1,
			'finished' => 1,
			'active' => 1
		),
	);

}
