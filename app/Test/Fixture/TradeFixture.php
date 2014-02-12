<?php
/**
 * TradeFixture
 *
 */
class TradeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'sell_order_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'buy_order_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'number' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '12,6'),
		'price' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '12,6'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '12,6'),
		'buy_fee' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '12,6', 'comment' => '买家手续费'),
		'sell_fee' => array('type' => 'float', 'null' => false, 'default' => '0.000000', 'length' => '12,6', 'comment' => '卖家手续费'),
		'created' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => '交易时间'),
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
			'sell_order_id' => 1,
			'buy_order_id' => 1,
			'number' => 1,
			'price' => 1,
			'amount' => 1,
			'buy_fee' => 1,
			'sell_fee' => 1,
			'created' => 1
		),
	);

}
