<?php
/**
 * PurseAddressFixture
 *
 */
class PurseAddressFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'account_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 250, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => '分配给谁'),
		'created' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'comment' => '是否使用'),
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
			'account_type' => 'Lorem ipsum dolor sit amet',
			'key' => 'Lorem ipsum dolor sit amet',
			'user_id' => 1,
			'created' => 1,
			'active' => 1
		),
	);

}
