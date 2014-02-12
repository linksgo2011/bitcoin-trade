<?php
App::uses('PurseAddress', 'Model');

/**
 * PurseAddress Test Case
 *
 */
class PurseAddressTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.purse_address'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PurseAddress = ClassRegistry::init('PurseAddress');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PurseAddress);

		parent::tearDown();
	}

}
