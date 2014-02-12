<?php
/**
 * AccountFixture
 *
 */
class AccountFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'account';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'btc_balance' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '12,6', 'comment' => '?????'),
		'btc_balance_freeze' => array('type' => 'float', 'null' => true, 'default' => '0.000000', 'length' => '12,6'),
		'ltc_balance' => array('type' => 'float', 'null' => false, 'default' => '0.0000', 'length' => '12,4', 'comment' => '?????'),
		'ltc_balance_freeze' => array('type' => 'float', 'null' => true, 'default' => '0.0000', 'length' => '12,4'),
		'ars_balance' => array('type' => 'float', 'null' => false, 'default' => '0.0000', 'length' => '12,4', 'comment' => '?????'),
		'ars_balance_freeze' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '12,4'),
		'usd_balance' => array('type' => 'float', 'null' => false, 'default' => '0.0000', 'length' => '12,4'),
		'usd_balance_freeze' => array('type' => 'float', 'null' => true, 'default' => '0.0000', 'length' => '12,4'),
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
			'btc_balance' => 1,
			'btc_balance_freeze' => 1,
			'ltc_balance' => 1,
			'ltc_balance_freeze' => 1,
			'ars_balance' => 1,
			'ars_balance_freeze' => 1,
			'usd_balance' => 1,
			'usd_balance_freeze' => 1
		),
	);

}
