<?php

// call these by using
// $> vendor\bin\phpunit --coverage-html .\report tests\RingTest.php
// or
// $> vendor\bin\phpunit UnitTest tests\RingTest.php
// from the enigma directory

use \Enigma\Ring;

class RingTest extends Tests\Base
{

	public $Ring;

	// SETUP --------------------------------------

	public function setUp( ) {
		$this->Ring = new Ring( );
	}

	protected function setupRing($do_pos = false) {
		$this->Ring->setRing('V');

		if ($do_pos) {
			$this->Ring->setRingPosition('T', 'R');
		}
	}

	protected function setupDualRing($do_pos = false) {
		$this->Ring->setRing('VII');

		if ($do_pos) {
			$this->Ring->setRingPosition('F', 'I');
		}
	}

	protected function setupStatorRing($do_pos = false) {
		$this->Ring->setRing('ETW-Q');

		if ($do_pos) {
			$this->Ring->setRingPosition('T', 'R');
		}
	}

	protected function setupReflectorRing($do_pos = false) {
		$this->Ring->setRing('Bt');

		if ($do_pos) {
			$this->Ring->setRingPosition('T', 'R');
		}
	}

	// TESTS --------------------------------------

	public function test_norm_same( ) {
		$this->assertEquals(3, Ring::normalize(13, 3, 3));
	}

	public function test_norm_switch( ) {
		$this->assertEquals(7, Ring::normalize(13, 9, 3));
	}

	public function test_norm_norm( ) {
		$this->assertEquals(7, Ring::normalize(13, 3, 9));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_set_ring_invalid_exception( ) {
		$this->Ring->setRing('POOP');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_set_ring_empty_exception( ) {
		$this->Ring->setRing('');
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage No ring to set the position of
	 */
	public function test_set_ring_position_empty_ring_exception( ) {
		$this->Ring->setRingPosition(1, 1);
	}

	public function test_set_ring_string( ) {
		$this->setupRing( );
		$this->assertEquals('VZBRGITYUPSDNHLXAWMJQOFECK', $this->Ring->getRing( ));
	}

	public function test_set_ring_step_pos( ) {
		$this->setupRing( );
		$this->assertEquals(25, $this->Ring->getStepPos( ));
	}

	public function test_set_ring_settings_pos( ) {
		$this->setupRing(true);
		$this->assertEquals(24, $this->Ring->getPos( ));
	}

	public function test_set_ring_settings_step_pos( ) {
		$this->setupRing(true);
		$this->assertEquals(6, $this->Ring->getStepPos( ));
	}

	public function test_set_dual_ring_string( ) {
		$this->setupDualRing( );
		$this->assertEquals('NZJHGRCXMYSWBOUFAIVLPEKQDT', $this->Ring->getRing( ));
	}

	public function test_set_dual_ring_step_pos( ) {
		$this->setupDualRing( );
		$this->assertEquals(array(25, 12), $this->Ring->getStepPos( ));
	}

	public function test_set_dual_ring_settings_pos( ) {
		$this->setupDualRing(true);
		$this->assertEquals(3, $this->Ring->getPos( )); // TODO: this is pulled from the test results, so may not be correct
	}

	public function test_set_dual_ring_settings_step_pos( ) {
		$this->setupDualRing(true);
		$this->assertEquals(array(20, 7), $this->Ring->getStepPos( )); // TODO: this is pulled from the test results, so may not be correct
	}

	public function test_set_stator_ring_string( ) {
		$this->setupStatorRing( );
		$this->assertEquals('QWERTZUIOASDFGHJKPYXCVBNML', $this->Ring->getRing( ));
	}

	public function test_set_stator_ring_stator( ) {
		$this->setupStatorRing( );
		$this->assertTrue($this->Ring->is_stator);
	}

	public function test_stator_ring_static_true( ) {
		$this->assertTrue(Ring::isStator('ETW-Q'));
	}

	public function test_stator_ring_static_false( ) {
		$this->assertFalse(Ring::isStator('II'));
	}

	public function test_set_stator_ring_pos( ) {
		$this->setupStatorRing( );
		$this->assertEquals(0, $this->Ring->getPos( ));
	}

	public function test_set_stator_ring_pos_edit( ) {
		$this->setupStatorRing(true);
		$this->assertEquals(0, $this->Ring->getPos( ));
	}

	public function test_set_reflector_ring_string( ) {
		$this->setupReflectorRing( );
		$this->assertEquals('ENKQAUYWJICOPBLMDXZVFTHRGS', $this->Ring->getRing( ));
	}

	public function test_set_reflector_ring_stator( ) {
		$this->setupReflectorRing( );
		$this->assertTrue($this->Ring->is_reflector);
	}

	public function test_reflector_ring_static_true( ) {
		$this->assertTrue(Ring::isReflector('Bt'));
	}

	public function test_reflector_ring_static_false( ) {
		$this->assertFalse(Ring::isReflector('ETW-Q'));
	}

	public function test_set_reflector_ring_pos( ) {
		$this->setupReflectorRing( );
		$this->assertEquals(0, $this->Ring->getPos( ));
	}

	public function test_set_reflector_ring_pos_edit( ) {
		$this->setupReflectorRing(true);
		$this->assertEquals(17, $this->Ring->getPos( ));
	}

/* TODO: write tests for:
	stepping
	not stepping when not supposed to
	not stepping next when not supposed to
	setting manual rings for each type
	using manual rings for each type
	getting the type value from the rings
	throwing exceptions for __construct
	Ring::isExtra
	Ring::toIndex for arrays
*/

}
