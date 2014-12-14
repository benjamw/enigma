<?php

	namespace Enigma;

	require_once '/lib/Machine.php';
	require_once '/lib/Ring.php';

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

	$E = new Machine( );

	$E->processSettings($settings);
	$E->clearMessage( );

	$E->encode('S');
	$E->encode('A');
	$E->encode('V');
	$E->encode('G');
	$E->encode('U');
	$E->encode('J');
	$E->encode('I');
	$E->encode('A');
	$E->encode('P');
	$E->encode('L');

	echo $E->output;
