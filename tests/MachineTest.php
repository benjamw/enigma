<?php

// call these by using
// $> vendor\bin\phpunit --coverage-html .\report tests\MachineTest.php
// or
// $> vendor\bin\phpunit UnitTest tests\MachineTest.php
// from the enigma directory

use \Enigma\Machine;

class MachineTest extends \Tests\Base
{

	public $Machine;

	public function setUp( ) {
		$this->Machine = new Machine( );
	}

	public function setupMachine( ) {
		$settings = array(
			'stecker' => array(
				2 => 23,
				4 => 5,
				6 => 26,
				8 => 24,
				9 => 19,
				14 => 20,
				18 => 21,
			),
			'rings' => array(
				array(
					'type' => 'IV',
					'ring' => 'M',
					'ground' => 'H',
				),
				array(
					'type' => 'II',
					'ring' => 'O',
					'ground' => 'D',
				),
				array(
					'type' => 'III',
					'ring' => 'S',
					'ground' => 'W',
				),
				array(
					'type' => 'B',
				),
			),
			'strict' => true,
			'group' => 5,
		);
		$this->Machine->processSettings($settings);
	}

	public function test_encode( ) {
		$this->setupMachine( );
		$encoded = $this->Machine->encode('hello world');
		$this->assertEquals('SAVGU JIAPL', $encoded);
	}

}
