<?php

// this file accepts all the AJAX requests and processes them through the enigma classes

namespace Enigma;

include 'autoloader.php';

//\g($GLOBALS);

if (empty($_POST)) {
	http_response_code(404);
	exit;
}

if (filter_var($_POST['settings'], FILTER_VALIDATE_BOOLEAN)) {
	$E = new Machine();
	$response = $E->getMACHINES($_POST['machine']);

	respond($response);
}

if ( ! filter_var($_POST['strict'], FILTER_VALIDATE_BOOLEAN)) {
	unset($_POST['group']);
}

$response = array( );

$E = new Machine($_POST);

// TODO: do something here

function respond($response) {
	header('Content-Type: application/json');
	echo json_encode($response);
	exit;
}

