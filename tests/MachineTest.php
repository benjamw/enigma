<?php

// call these by using
// $> vendor\bin\phpunit --coverage-html .\report tests\MachineTest.php
// or
// $> vendor\bin\phpunit UnitTest tests\MachineTest.php
// from the enigma directory

namespace Tests;

use \Enigma\Machine;

class MachineTest extends Base
{

	public $Machine;
	public $Other;

	public function setUp( ) {
		$this->Machine = new Machine( );
		$this->Other = new Machine( );
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

	public function setupDonitzMachine( ) {
		$settings = array(
			'stecker' => array(
				'AE',
				'BF',
				'CM',
				'DQ',
				'HU',
				'JN',
				'LX',
				'PR',
				'SZ',
				'VW',
			),
			'rings' => array(
				array(
					'type' => 'VIII',
					'ring' => 'L',
					'ground' => 'M',
				),
				array(
					'type' => 'VI',
					'ring' => 'E',
					'ground' => 'E',
				),
				array(
					'type' => 'V',
					'ring' => 'P',
					'ground' => 'A',
				),
				array(
					'type' => 'Beta',
					'ring' => 'E',
					'ground' => 'N'
				),
				array(
					'type' => 'Ct',
				),
			),
			'strict' => true,
			'group' => 4,
		);
		$this->Machine->processSettings($settings);
	}

	public function setupBCompatibilityCompare( ) {
		// this is an M3 machine
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
					'type' => 'VI',
					'ring' => 'M',
					'ground' => 'H',
				),
				array(
					'type' => 'II',
					'ring' => 'O',
					'ground' => 'D',
				),
				array(
					'type' => 'VIII',
					'ring' => 'S',
					'ground' => 'W',
				),
				// this reflector should be equivalent to the Bt-Beta combination
				array(
					'type' => 'B',
				),
			),
			'strict' => true,
			'group' => 5,
		);
		$this->Machine->processSettings($settings);

		// this is an M4 machine
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
					'type' => 'VI',
					'ring' => 'M',
					'ground' => 'H',
				),
				array(
					'type' => 'II',
					'ring' => 'O',
					'ground' => 'D',
				),
				array(
					'type' => 'VIII',
					'ring' => 'S',
					'ground' => 'W',
				),
				// this Bt-Beta combination should be equivalent to the B reflector
				array(
					'type' => 'Beta',
					'ground' => 'A',
				),
				array(
					'type' => 'Bt',
				),
			),
			'strict' => true,
			'group' => 5,
		);
		$this->Other->processSettings($settings);
	}

	public function setupCCompatibilityCompare( ) {
		// this is an M3 machine
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
					'type' => 'VI',
					'ring' => 'M',
					'ground' => 'H',
				),
				array(
					'type' => 'II',
					'ring' => 'O',
					'ground' => 'D',
				),
				array(
					'type' => 'VIII',
					'ring' => 'S',
					'ground' => 'W',
				),
				// this reflector should be equivalent to the Ct-Gamma combination
				array(
					'type' => 'C',
				),
			),
			'strict' => true,
			'group' => 5,
		);
		$this->Machine->processSettings($settings);

		// this is an M4 machine
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
					'type' => 'VI',
					'ring' => 'M',
					'ground' => 'H',
				),
				array(
					'type' => 'II',
					'ring' => 'O',
					'ground' => 'D',
				),
				array(
					'type' => 'VIII',
					'ring' => 'S',
					'ground' => 'W',
				),
				// this Ct-Gamma combination should be equivalent to the C reflector
				array(
					'type' => 'Gamma',
					'ground' => 'A',
				),
				array(
					'type' => 'Ct',
				),
			),
			'strict' => true,
			'group' => 5,
		);
		$this->Other->processSettings($settings);
	}

	public function test_encode( ) {
		$this->setupMachine( );
		$encoded = $this->Machine->encode('hello world');
		$this->assertEquals('SAVGU JIAPL', $encoded);
	}

	public function test_encode_single( ) {
		$this->setupMachine( );
		$this->Machine->clearMessage( );

		$this->Machine->encode('h');
		$this->Machine->encode('e');
		$this->Machine->encode('l');
		$this->Machine->encode('l');
		$this->Machine->encode('o');

		$this->Machine->encode('w');
		$this->Machine->encode('o');
		$this->Machine->encode('r');
		$this->Machine->encode('l');
		$this->Machine->encode('d');

		$this->assertEquals('SAVGU JIAPL', $this->Machine->output);
	}

	public function testDonitzKey( ) {
		$this->setupDonitzMachine( );
		$encoded = $this->Machine->encode('QEOB');
		$this->assertEquals('CDSZ', $encoded);
	}

	public function test_B_compatibility( ) {
		$this->setupBCompatibilityCompare( );
		$encoded_m3 = $this->Machine->encode('SAVGU JIAPL');
		$encoded_m4 = $this->Other->encode('SAVGU JIAPL');
		$this->assertEquals($encoded_m3, $encoded_m4);
	}

	public function test_C_compatibility( ) {
		$this->setupCCompatibilityCompare( );
		$encoded_m3 = $this->Machine->encode('SAVGU JIAPL');
		$encoded_m4 = $this->Other->encode('SAVGU JIAPL');
		$this->assertEquals($encoded_m3, $encoded_m4);
	}

/* TODO: write tests for:
	double stepping as own test
	stepping slow ring without middle ring double step
	adding option to make FAST ring static, and bump MIDDLE to FAST
	using the settings for strict mode and enforcing them
	throwing exception for __construct
	throwing exception for processSettings
		- strict mode group size
	throwing exception for dupRingTest
	throwing exception for setStecker
	throwing exception for steckerTest
		- non-two char stecker string
		- duplicate entries
	setting default reflector for makeRings both M3 and M4
	running dupRingTest without strict mode
	running ringTest without strict mode
	passing empty stecker to setStecker
	encoding longish strings using multiple machines and setups
		- compare with other emulators
*/
}
