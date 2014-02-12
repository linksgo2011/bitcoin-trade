<?php
App::uses('ChargeCode', 'Model');

/**
 * ChargeCode Test Case
 *
 */
class ChargeCodeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.charge_code'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ChargeCode = ClassRegistry::init('ChargeCode');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ChargeCode);

		parent::tearDown();
	}

}
